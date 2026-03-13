<x-filament-panels::page>

    {{-- Queue Status Bar --}}
    <div
        style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; margin-bottom:16px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="font-size:15px; font-weight:700; color:#111827;">{{ auth()->user()->business->name }}</span>
            @if ($this->business->queue_status === 'open')
                <span
                    style="padding:5px 14px; border-radius:999px; font-size:12px; font-weight:700; background:#16a34a; color:#fff;">●
                    OPEN</span>
            @elseif($this->business->queue_status === 'paused')
                <span
                    style="padding:5px 14px; border-radius:999px; font-size:12px; font-weight:700; background:#eab308; color:#fff;">⏸
                    PAUSED</span>
            @else
                <span
                    style="padding:5px 14px; border-radius:999px; font-size:12px; font-weight:700; background:#dc2626; color:#fff;">✕
                    CLOSED</span>
            @endif
            <span style="font-size:13px; color:#9ca3af;">{{ $this->business->entries_today }} /
                {{ $this->business->daily_limit }} today</span>
        </div>
        <div style="display:flex; gap:8px;">
            @if ($this->business->queue_status !== 'open')
                <button wire:click="openQueue"
                    style="padding:8px 16px; font-size:13px; font-weight:600; border-radius:8px; background:#16a34a; color:#fff; border:none; cursor:pointer;">▶
                    Open</button>
            @endif
            @if ($this->business->queue_status === 'open')
                <button wire:click="pauseQueue"
                    style="padding:8px 16px; font-size:13px; font-weight:600; border-radius:8px; background:#eab308; color:#fff; border:none; cursor:pointer;">⏸
                    Pause</button>
            @endif
            @if ($this->business->queue_status !== 'closed')
                <button wire:click="closeQueue"
                    style="padding:8px 16px; font-size:13px; font-weight:600; border-radius:8px; background:#dc2626; color:#fff; border:none; cursor:pointer;">✕
                    Close</button>
            @endif
        </div>
    </div>

    {{-- Stats Row --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:16px;">
        <div style="padding:20px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; text-align:center;">
            <div style="font-size:2.5rem; font-weight:800; color:#111827;">{{ $waitingCount }}</div>
            <div style="font-size:13px; color:#6b7280; margin-top:4px;">Waiting</div>
        </div>
        <div style="padding:20px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; text-align:center;">
            <div style="font-size:2.5rem; font-weight:800; color:#111827;">{{ $this->business->entries_today }}</div>
            <div style="font-size:13px; color:#6b7280; margin-top:4px;">Served Today</div>
        </div>
        <div style="padding:20px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; text-align:center;">
            <div style="font-size:2.5rem; font-weight:800; color:#111827;">{{ $this->business->avgServiceMinutes() }}
                min</div>
            <div style="font-size:13px; color:#6b7280; margin-top:4px;">Avg Service Time</div>
        </div>
    </div>

    {{-- Current Ticket + Actions --}}
    <div style="padding:24px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; margin-bottom:16px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <h2 style="font-size:17px; font-weight:700; color:#111827;">Now Serving</h2>
            <button wire:click="addManual"
                style="padding:8px 16px; font-size:13px; font-weight:600; border-radius:8px; background:#f3f4f6; color:#374151; border:none; cursor:pointer;">+
                Add to Queue</button>
        </div>

        @if ($currentEntry)
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:5rem; font-weight:900; color:#16a34a; line-height:1;">
                        {{ $currentEntry->ticket_code }}</div>
                    <div style="font-size:13px; color:#9ca3af; margin-top:8px;">
                        {{ $currentEntry->wa_id ?? 'Anonymous' }}
                        &bull;
                        {{ ucfirst($currentEntry->status) }}
                        &bull;
                        Called {{ $currentEntry->called_at?->diffForHumans() }}
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <button wire:click="markDone"
                        style="padding:12px 24px; font-size:14px; font-weight:700; border-radius:12px; background:#16a34a; color:#fff; border:none; cursor:pointer;">✓
                        Done</button>
                    <button wire:click="skipCurrent"
                        style="padding:12px 24px; font-size:14px; font-weight:700; border-radius:12px; background:#eab308; color:#fff; border:none; cursor:pointer;">→
                        Skip</button>
                </div>
            </div>
        @else
            <div style="text-align:center; padding:32px 0; color:#9ca3af;">
                <div style="font-size:3rem; margin-bottom:8px;">—</div>
                <div style="font-size:14px;">No one being served</div>
            </div>
        @endif

        <div style="margin-top:16px; padding-top:16px; border-top:1px solid #f3f4f6;">
            <button wire:click="callNext"
                style="width:100%; padding:14px; font-size:15px; font-weight:700; border-radius:12px; background:#2563eb; color:#fff; border:none; cursor:pointer;">Call
                Next →</button>
        </div>
    </div>

    {{-- Waiting List --}}
    <div style="padding:24px; background:#fff; border-radius:12px; border:1px solid #e5e7eb; margin-bottom:16px;">
        <h2 style="font-size:17px; font-weight:700; color:#111827; margin-bottom:16px;">Waiting ({{ $waitingCount }})
        </h2>
        @forelse($waitingEntries as $entry)
            <div
                style="display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f3f4f6;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <span
                        style="font-size:13px; font-weight:700; color:#9ca3af; width:24px;">{{ $entry->position }}</span>
                    <span
                        style="font-family:monospace; font-size:16px; font-weight:700; color:#111827;">{{ $entry->ticket_code }}</span>
                    <span style="font-size:13px; color:#9ca3af;">{{ $entry->wa_id ?? 'Anonymous' }}</span>
                </div>
                <span style="font-size:12px; color:#9ca3af;">{{ $entry->joined_at->diffForHumans() }}</span>
            </div>
        @empty
            <div style="text-align:center; padding:16px 0; color:#9ca3af; font-size:14px;">Queue is empty</div>
        @endforelse
    </div>

    {{-- TV Display Link --}}
    <div style="padding:16px 20px; background:#fff; border-radius:12px; border:1px solid #e5e7eb;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div>
                <p style="font-size:14px; font-weight:600; color:#374151;">TV Display</p>
                <p style="font-size:12px; color:#9ca3af; margin-top:2px;">Share this link with staff or display on a
                    screen</p>
            </div>
            <a href="{{ route('public.tv', auth()->user()->business->slug) }}" target="_blank"
                style="padding:8px 16px; font-size:13px; font-weight:600; border-radius:8px; background:#111827; color:#fff; text-decoration:none;">
                Open TV Display →
            </a>
        </div>
        <div style="padding:10px 12px; background:#f9fafb; border-radius:8px;">
            <p style="font-size:12px; font-family:monospace; color:#6b7280; word-break:break-all;">
                {{ route('public.tv', auth()->user()->business->slug) }}
            </p>
        </div>
    </div>

</x-filament-panels::page>
