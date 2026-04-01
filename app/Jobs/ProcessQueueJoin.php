<?php

namespace App\Jobs;

use App\Models\Business;
use App\Services\QueueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessQueueJoin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $waId,
        public string $joinCode,
        public array $rawPayload = [],
    ) {}

    public function handle(QueueService $queueService): void
{
    $business = Business::where('join_code', strtoupper(trim($this->joinCode)))
        ->where('is_active', true)
        ->first();

    if (!$business) {
        Log::warning('ProcessQueueJoin: business not found', ['join_code' => $this->joinCode]);
        return;
    }

    // ── Rate limiting — max 3 joins per wa_id per day ─────────────
    $todayJoins = \App\Models\QueueEntry::where('wa_id', $this->waId)
        ->where('business_id', $business->id)
        ->whereDate('joined_at', today())
        ->count();

    if ($todayJoins >= 3) {
        Log::warning('ProcessQueueJoin: rate limit hit', ['wa_id' => $this->waId]);
        return;
    }

    // ── Max 1 active entry per wa_id per business ─────────────────
    $activeEntry = \App\Models\QueueEntry::where('wa_id', $this->waId)
        ->where('business_id', $business->id)
        ->whereIn('status', ['waiting', 'called', 'serving'])
        ->first();

    if ($activeEntry) {
        Log::info('ProcessQueueJoin: already in queue', ['wa_id' => $this->waId]);
        // Send reminder with their existing ticket
        SendWhatsAppMessage::dispatch(
            $this->waId,
            'queue_joined',
            [
                $activeEntry->ticket_code,
                $business->name,
                $activeEntry->position - 1,
                ($activeEntry->position - 1) * $business->avgServiceMinutes(),
                route('public.status', [
                    'slug'    => $business->slug,
                    'entryId' => $activeEntry->id,
                    'token'   => $activeEntry->cancel_token,
                ]),
            ],
            $business->id,
            $activeEntry->id,
        );
        return;
    }

    try {
        $entry = $queueService->join($business, $this->waId);

        $statusUrl    = route('public.status', [
            'slug'    => $business->slug,
            'entryId' => $entry->id,
            'token'   => $entry->cancel_token,
        ]);
        $positionInfo = $queueService->getPositionInfo($entry);

        SendWhatsAppMessage::dispatch(
            $this->waId,
            'queue_joined',
            [
                $entry->ticket_code,
                $business->name,
                $positionInfo['ahead'],
                $positionInfo['estimated_wait'],
                $statusUrl,
            ],
            $business->id,
            $entry->id,
        );

    } catch (\RuntimeException $e) {
        $this->handleError($e->getMessage(), $business);
    }
}

    private function handleError(string $reason, Business $business): void
    {
        $messages = [
            'subscription_inactive' => 'Sorry, this business queue is currently unavailable.',
            'queue_closed' => "Sorry, {$business->name}'s queue is currently closed.",
            'queue_paused' => "Sorry, {$business->name}'s queue is currently paused. Please try again later.",
            'queue_full' => "Sorry, {$business->name}'s queue is full for today.",
            'already_in_queue' => 'You are already in the queue. Check your previous message for your ticket.',
            'too_many_cancels' => 'You have cancelled too many times today. Please visit us directly.',
        ];

        $message = $messages[$reason] ?? 'Sorry, something went wrong. Please try again.';

        Log::info('ProcessQueueJoin error reply', ['reason' => $reason, 'wa_id' => $this->waId]);

        // Send plain text reply for errors (not template)
        // TODO: wire to plain text WA send when Meta is configured
    }
}
