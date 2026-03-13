<?php

namespace App\Filament\Business;

use App\Models\Business;
use App\Models\QueueEntry;
use App\Services\QueueService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class QueueDashboard extends Page
{
    protected string $view = 'filament.business.queue-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Queue Dashboard';

    protected static ?int $navigationSort = 1;

    public Business $business;

    public ?QueueEntry $currentEntry = null;

    public function mount(): void
    {
        $this->business = Business::where('id', Auth::user()->business_id)->firstOrFail();
        $this->loadCurrent();
    }

    public int $waitingCount = 0;

    public $waitingEntries = [];

    public function loadCurrent(): void
    {
        $this->currentEntry = QueueEntry::where('business_id', $this->business->id)
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        $this->waitingCount = QueueEntry::where('business_id', $this->business->id)
            ->where('status', 'waiting')
            ->count();

        $this->waitingEntries = QueueEntry::where('business_id', $this->business->id)
            ->where('status', 'waiting')
            ->orderBy('position')
            ->take(10)
            ->get();

        $this->business->refresh();
    }

    // ── Queue Status Actions ──────────────────────────────────────
    public function openQueue(): void
    {
        app(QueueService::class)->openQueue($this->business);
        $this->business->refresh();
        Notification::make()->title('Queue opened')->success()->send();
    }

    public function pauseQueue(): void
    {
        app(QueueService::class)->pauseQueue($this->business);
        $this->business->refresh();
        Notification::make()->title('Queue paused')->warning()->send();
    }

    public function closeQueue(): void
    {
        app(QueueService::class)->closeQueue($this->business);
        $this->business->refresh();
        Notification::make()->title('Queue closed')->danger()->send();
    }

    // ── Queue Operations ──────────────────────────────────────────
    public function callNext(): void
    {
        $entry = app(QueueService::class)->callNext($this->business);

        if (! $entry) {
            Notification::make()->title('No one waiting in queue')->warning()->send();

            return;
        }

        $this->loadCurrent();
        Notification::make()->title("Calling {$entry->ticket_code}")->success()->send();
    }

    public function markDone(): void
    {
        if (! $this->currentEntry) {
            return;
        }

        app(QueueService::class)->markDone($this->currentEntry);
        $this->loadCurrent();
        Notification::make()->title('Marked as done')->success()->send();
    }

    public function skipCurrent(): void
    {
        if (! $this->currentEntry) {
            return;
        }

        app(QueueService::class)->skip($this->currentEntry);
        $this->loadCurrent();
        Notification::make()->title('Entry skipped')->warning()->send();
    }

    public function addManual(): void
    {
        try {
            $entry = app(QueueService::class)->addManual($this->business);
            $this->loadCurrent();
            Notification::make()->title("Added {$entry->ticket_code} (anonymous)")->success()->send();
        } catch (\RuntimeException $e) {
            Notification::make()->title('Cannot add entry: '.$e->getMessage())->danger()->send();
        }
    }

    // ── Computed Properties ───────────────────────────────────────
    public function getWaitingCountProperty(): int
    {
        return QueueEntry::where('business_id', $this->business->id)
            ->where('status', 'waiting')
            ->count();
    }

    public function getWaitingEntriesProperty()
    {
        return QueueEntry::where('business_id', $this->business->id)
            ->where('status', 'waiting')
            ->orderBy('position')
            ->take(10)
            ->get();
    }

    protected function getListeners(): array
    {
        return [
            'queue-updated' => 'loadCurrent',
        ];
    }
}
