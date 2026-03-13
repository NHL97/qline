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
        // Find business by join code
        $business = Business::where('join_code', strtoupper(trim($this->joinCode)))
            ->where('is_active', true)
            ->first();

        if (!$business) {
            Log::warning('ProcessQueueJoin: business not found', ['join_code' => $this->joinCode]);
            // TODO: send WA reply "Business not found"
            return;
        }

        try {
            $entry = $queueService->join($business, $this->waId);

            // Build status page URL
            $statusUrl = route('public.status', [$business->slug, $entry->id]);

            // Calculate position info
            $positionInfo = $queueService->getPositionInfo($entry);

            // Send queue_joined WA message
            SendWhatsAppMessage::dispatch(
                $this->waId,
                'queue_joined',
                [
                    $entry->ticket_code,                    // {{1}} ticket
                    $business->name,                        // {{2}} business
                    $positionInfo['ahead'],                 // {{3}} people ahead
                    $positionInfo['estimated_wait'],        // {{4}} estimated wait
                    $statusUrl,                             // {{5}} status URL
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
            'queue_closed'          => "Sorry, {$business->name}'s queue is currently closed.",
            'queue_paused'          => "Sorry, {$business->name}'s queue is currently paused. Please try again later.",
            'queue_full'            => "Sorry, {$business->name}'s queue is full for today.",
            'already_in_queue'      => "You are already in the queue. Check your previous message for your ticket.",
            'too_many_cancels'      => "You have cancelled too many times today. Please visit us directly.",
        ];

        $message = $messages[$reason] ?? 'Sorry, something went wrong. Please try again.';

        Log::info('ProcessQueueJoin error reply', ['reason' => $reason, 'wa_id' => $this->waId]);

        // Send plain text reply for errors (not template)
        // TODO: wire to plain text WA send when Meta is configured
    }
}