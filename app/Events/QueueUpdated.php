<?php

namespace App\Events;

use App\Models\Business;
use App\Models\QueueEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $businessId,
        public string $slug,
        public ?string $currentTicket,
        public int $waitingCount,
        public string $queueStatus,
        public int $entriesToday,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("queue.{$this->slug}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'current_ticket' => $this->currentTicket,
            'waiting_count'  => $this->waitingCount,
            'queue_status'   => $this->queueStatus,
            'entries_today'  => $this->entriesToday,
        ];
    }
}