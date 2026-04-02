<?php

namespace App\Services;

use App\Events\QueueUpdated;
use App\Jobs\SendWhatsAppMessage;
use App\Models\Business;
use App\Models\QueueEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\QLineLogger;

class QueueService
{
    // ── Join Queue (WhatsApp) ─────────────────────────────────────
    public function join(Business $business, string $waId): QueueEntry
    {
        return DB::transaction(function () use ($business, $waId) {

            // Check subscription
            if (! $business->hasActiveSubscription()) {
                throw new \RuntimeException('subscription_inactive');
            }

            // Check queue status
            if ($business->isClosed()) {
                throw new \RuntimeException('queue_closed');
            }

            if ($business->isPaused()) {
                throw new \RuntimeException('queue_paused');
            }

            // Check daily limit
            if ($business->isAtDailyLimit()) {
                throw new \RuntimeException('queue_full');
            }

            // Check duplicate active entry
            $existing = QueueEntry::where('business_id', $business->id)
                ->where('wa_id', $waId)
                ->whereIn('status', ['waiting', 'called', 'serving'])
                ->first();

            if ($existing) {
                throw new \RuntimeException('already_in_queue');
            }

            // Check cancel count today
            $cancelCount = QueueEntry::where('business_id', $business->id)
                ->where('wa_id', $waId)
                ->where('status', 'cancelled')
                ->whereDate('joined_at', today())
                ->count();

            if ($cancelCount >= 3) {
                throw new \RuntimeException('too_many_cancels');
            }

            // Issue ticket
            $ticketNumber = $business->nextTicketNumber();
            $ticketCode = $business->queue_prefix.str_pad($ticketNumber, 3, '0', STR_PAD_LEFT);

            // Get next position
            $position = QueueEntry::where('business_id', $business->id)
                ->whereIn('status', ['waiting', 'called', 'serving'])
                ->max('position') + 1;

            // Create entry
            $entry = QueueEntry::create([
                'business_id' => $business->id,
                'wa_id' => $waId,
                'ticket_number' => $ticketNumber,
                'ticket_code' => $ticketCode,
                'status' => 'waiting',
                'source' => 'whatsapp',
                'position' => $position,
                'joined_at' => now(),
                'cancel_token' => Str::random(32),
            ]);

            QLineLogger::customerJoined($business->id, $waId, $entry->ticket_code, $entry->position);

            // Increment entries today
            $business->increment('entries_today');

            // Auto-pause if daily limit hit
            if ($business->isAtDailyLimit()) {
                $business->update(['queue_status' => 'paused']);
            }

            return $entry;
        });
    }

    // ── Add Anonymous Entry (Manual) ──────────────────────────────
    public function addManual(Business $business): QueueEntry
    {
        return DB::transaction(function () use ($business) {

            if ($business->isClosed()) {
                throw new \RuntimeException('queue_closed');
            }

            // Issue ticket
            $ticketNumber = $business->nextTicketNumber();
            $ticketCode = $business->queue_prefix.str_pad($ticketNumber, 3, '0', STR_PAD_LEFT);

            // Get next position
            $position = QueueEntry::where('business_id', $business->id)
                ->whereIn('status', ['waiting', 'called', 'serving'])
                ->max('position') + 1;

            $entry = QueueEntry::create([
                'business_id' => $business->id,
                'wa_id' => null,
                'ticket_number' => $ticketNumber,
                'ticket_code' => $ticketCode,
                'status' => 'waiting',
                'source' => 'manual',
                'position' => $position,
                'joined_at' => now(),
                'cancel_token' => Str::random(32),
            ]);

            $business->increment('entries_today');

            // Auto-pause if daily limit hit
            if ($business->isAtDailyLimit()) {
                $business->update(['queue_status' => 'paused']);
            }
            $this->broadcastUpdate($business);

            return $entry;
        });
    }

    // ── Call Next ─────────────────────────────────────────────────
    public function callNext(Business $business): ?QueueEntry
    {
        return DB::transaction(function () use ($business) {

            // SELECT FOR UPDATE — prevents two staff calling same ticket
            $next = QueueEntry::where('business_id', $business->id)
                ->where('status', 'waiting')
                ->orderBy('position')
                ->lockForUpdate()
                ->first();

            if (! $next) {
                return null;
            }

            $next->update([
                'status' => 'called',
                'called_at' => now(),
            ]);

            QLineLogger::customerCalled($business->id, $next->ticket_code);

            if ($next->wa_id) {
                SendWhatsAppMessage::dispatch(
                    $next->wa_id,
                    'now_serving',
                    [$next->ticket_code, $business->name],
                    $business->id,
                    $next->id,
                );
            }

            $this->recalculatePositions($business->id);
            $this->checkApproaching($business);
            $this->broadcastUpdate($business);

            return $next->fresh();
        });
    }

    // ── Mark Serving ──────────────────────────────────────────────
    public function markServing(QueueEntry $entry): QueueEntry
    {
        $entry->update([
            'status' => 'serving',
            'served_at' => now(),
        ]);

        return $entry->fresh();
    }

    // ── Mark Done ─────────────────────────────────────────────────
    public function markDone(QueueEntry $entry): QueueEntry
    {
        return DB::transaction(function () use ($entry) {

            $servedAt = $entry->served_at ?? $entry->called_at ?? now();
            $waitMin = $entry->joined_at ? (int) $entry->joined_at->diffInMinutes($servedAt) : null;
            $serviceMin = (int) $servedAt->diffInMinutes(now());

            $entry->update([
                'status' => 'done',
                'done_at' => now(),
                'wait_minutes' => $waitMin,
                'service_minutes' => $serviceMin,
            ]);

            QLineLogger::customerDone($entry->business_id, $entry->ticket_code, $entry->wait_minutes, $entry->service_minutes);

            if ($entry->wa_id) {
                SendWhatsAppMessage::dispatch(
                    $entry->wa_id,
                    'queue_done',
                    [
                        $entry->ticket_code,    // {{1}} ticket
                        $entry->business->name, // {{2}} business
                    ],
                    $entry->business_id,
                    $entry->id,
                );
            }

            $this->recalculatePositions($entry->business_id);
            $this->broadcastUpdate($entry->business);

            return $entry->fresh();
        });
    }

    // ── Skip ──────────────────────────────────────────────────────
    public function skip(QueueEntry $entry): QueueEntry
    {
        return DB::transaction(function () use ($entry) {

            $entry->update([
                'status' => 'skipped',
                'done_at' => now(),
            ]);

            QLineLogger::customerSkipped($entry->business_id, $entry->ticket_code);

            $this->recalculatePositions($entry->business_id);
            $this->broadcastUpdate($entry->business);
            $this->checkApproaching(Business::findOrFail($entry->business_id));

            return $entry->fresh();
        });
    }

    // ── Cancel (customer initiated) ───────────────────────────────
    public function cancel(QueueEntry $entry): QueueEntry
    {
        return DB::transaction(function () use ($entry) {

            $entry->update([
                'status' => 'cancelled',
                'done_at' => now(),
            ]);

            // Send cancellation WA if customer has WhatsApp
            if ($entry->wa_id) {
                SendWhatsAppMessage::dispatch(
                    $entry->wa_id,
                    'queue_cancelled',
                    [
                        $entry->ticket_code,        // {{1}} ticket
                        $entry->business->name,      // {{2}} business
                    ],
                    $entry->business_id,
                    $entry->id,
                );
            }

            QLineLogger::customerCancelled($entry->business_id, $entry->ticket_code, $entry->wa_id ? 'customer' : 'staff');

            $this->recalculatePositions($entry->business_id);
            $this->broadcastUpdate($entry->business);

            return $entry->fresh();
        });
    }

    // ── Open Queue ────────────────────────────────────────────────
    public function openQueue(Business $business): void
    {
        // Check subscription before allowing open
        if (! $business->hasActiveSubscription()) {
            throw new \RuntimeException('subscription_inactive');
        }

        DB::transaction(function () use ($business) {
            if ($business->needsReset()) {
                $business->resetQueue();
            }

            $business->update(['queue_status' => 'open', 'pause_reason' => null]);

            QLineLogger::queueOpened($business->id, $business->name);
            $this->broadcastUpdate($business);
        });
    }

    // ── Pause Queue ───────────────────────────────────────────────
    public function pauseQueue(Business $business, string $reason = ''): void
    {
        $business->update([
            'queue_status' => 'paused',
            'pause_reason' => $reason ?: null,
        ]);
        QLineLogger::queuePaused($business->id, $business->name);
        $this->broadcastUpdate($business);
    }

    // ── Close Queue ───────────────────────────────────────────────
    public function closeQueue(Business $business): void
    {
        $business->update(['queue_status' => 'closed', 'pause_reason' => null]);

        QLineLogger::queueClosed($business->id, $business->name);
        $this->broadcastUpdate($business);
    }

    // ── Get Position Info (for status page) ───────────────────────
    public function getPositionInfo(QueueEntry $entry): array
    {
        $ahead = QueueEntry::where('business_id', $entry->business_id)
            ->whereIn('status', ['waiting', 'called', 'serving'])
            ->where('position', '<', $entry->position)
            ->count();

        $avg = Business::findOrFail($entry->business_id)->avgServiceMinutes();
        $estimatedWait = max(0, $ahead * $avg);

        return [
            'position' => $entry->position,
            'ahead' => $ahead,
            'estimated_wait' => $estimatedWait,
        ];
    }

    // ── Private Helpers ───────────────────────────────────────────
    private function recalculatePositions(int $businessId): void
    {
        $entries = QueueEntry::where('business_id', $businessId)
            ->whereIn('status', ['waiting', 'called', 'serving'])
            ->orderBy('position')
            ->get();

        foreach ($entries as $index => $entry) {
            $entry->update(['position' => $index + 1]);
        }
    }

    private function checkApproaching(Business $business): void
    {
        $notifyAt = $business->notify_turns_before;

        $entry = QueueEntry::where('business_id', $business->id)
            ->where('status', 'waiting')
            ->where('position', $notifyAt)
            ->whereNotNull('wa_id')
            ->first();

        if ($entry) {
            SendWhatsAppMessage::dispatch(
                $entry->wa_id,
                'turn_approaching',
                [
                    $entry->ticket_code,        // {{1}} ticket
                    $business->name,            // {{2}} business
                    $notifyAt,                  // {{3}} turns away
                ],
                $business->id,
                $entry->id,
            );
        }
    }

    private function broadcastUpdate(Business $business): void
    {
        $current = QueueEntry::where('business_id', $business->id)
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        QueueUpdated::dispatch(
            businessId: $business->id,
            slug: $business->slug,
            currentTicket: $current?->ticket_code,
            waitingCount: QueueEntry::where('business_id', $business->id)
                ->where('status', 'waiting')
                ->count(),
            queueStatus: $business->queue_status,
            entriesToday: $business->entries_today,
            pauseReason: $business->pause_reason,
        );
    }
}
