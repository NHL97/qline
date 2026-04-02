<x-filament-panels::page>
    <style>
        .qd {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-top: 8px;
        }

        /* Status bar */
        .status-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: var(--color-background-primary, white);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .status-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .biz-name {
            font-size: 15px;
            font-weight: 500;
            color: var(--color-text-primary, #111827);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .pill-open {
            background: rgba(20, 184, 166, 0.12);
            color: #0f766e;
        }

        .pill-paused {
            background: rgba(234, 179, 8, 0.12);
            color: #92400e;
        }

        .pill-closed {
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
        }

        .pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-open {
            background: #14B8A6;
        }

        .dot-paused {
            background: #eab308;
        }

        .dot-closed {
            background: #ef4444;
        }

        .entries-count {
            font-size: 12px;
            color: #9ca3af;
        }

        .status-actions {
            display: flex;
            gap: 6px;
        }

        .btn-open {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            background: #14B8A6;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-open:hover {
            background: #0f9e8e;
        }

        .btn-pause {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            background: rgba(234, 179, 8, 0.12);
            color: #92400e;
            border: none;
            cursor: pointer;
        }

        .btn-close {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
            border: none;
            cursor: pointer;
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-card {
            background: var(--color-background-primary, white);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 16px 18px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-text-primary, #111827);
            line-height: 1;
        }

        .stat-value-teal {
            color: #14B8A6;
        }

        .stat-sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        /* ✅ Main layout — single column, stacked */
        .main-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Now serving — full width, bigger on its own */
        .now-serving {
            background: var(--color-background-primary, white);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 7px;
            background: var(--color-background-secondary, #f3f4f6);
            color: var(--color-text-secondary, #374151);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        /* ✅ Ticket number bigger now that it has full width */
        .ticket-big {
            font-size: 80px;
            font-weight: 700;
            color: #14B8A6;
            line-height: 1;
            letter-spacing: -3px;
            font-family: monospace;
        }

        .ticket-meta {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .tmeta-dot {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #d1d5db;
        }

        /* ✅ Actions row — horizontal, full width */
        .ticket-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-done {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background: #14B8A6;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-done:hover {
            background: #0f9e8e;
        }

        .btn-skip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background: rgba(234, 179, 8, 0.12);
            color: #92400e;
            border: none;
            cursor: pointer;
        }

        .no-serving {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            gap: 10px;
        }

        .no-serving svg {
            width: 40px;
            height: 40px;
            opacity: 0.2;
        }

        .no-serving span {
            font-size: 14px;
            color: #9ca3af;
        }

        /* ✅ Call next — big tap-friendly button */
        .call-next-btn {
            width: 100%;
            padding: 16px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 12px;
            background: #0f172a;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .call-next-btn:hover {
            background: #1e293b;
        }

        /* Waiting list */
        .waiting-panel {
            background: var(--color-background-primary, white);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px;
        }

        .waiting-count-badge {
            font-size: 12px;
            font-weight: 600;
            color: #14B8A6;
            background: rgba(20, 184, 166, 0.1);
            padding: 2px 9px;
            border-radius: 999px;
        }

        .waiting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 0;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.05);
        }

        .waiting-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .waiting-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .waiting-pos {
            font-size: 12px;
            font-weight: 600;
            color: #d1d5db;
            width: 20px;
            flex-shrink: 0;
        }

        .waiting-ticket {
            font-family: monospace;
            font-size: 16px;
            font-weight: 700;
            color: var(--color-text-primary, #111827);
        }

        .waiting-id {
            font-size: 12px;
            color: #9ca3af;
        }

        .waiting-time {
            font-size: 11px;
            color: #9ca3af;
        }

        .waiting-empty {
            text-align: center;
            padding: 28px 0;
            color: #9ca3af;
            font-size: 13px;
        }

        /* TV card */
        .tv-card {
            background: var(--color-background-primary, white);
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .tv-info {
            flex: 1;
        }

        .tv-info p {
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-primary, #111827);
        }

        .tv-info span {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
            display: block;
        }

        .tv-url {
            margin-top: 10px;
            padding: 8px 12px;
            background: var(--color-background-secondary, #f9fafb);
            border-radius: 8px;
            font-size: 11px;
            font-family: monospace;
            color: #6b7280;
            word-break: break-all;
            border: 0.5px solid rgba(0, 0, 0, 0.06);
        }

        .btn-tv {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            background: #0f172a;
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
        }
    </style>

    <div class="qd">

        {{-- Status Bar --}}
        <div class="status-bar">
            <div class="status-left">
                <span class="biz-name">{{ auth()->user()->business->name }}</span>

                @if ($this->business->queue_status === 'open')
                    <span class="status-pill pill-open"><span class="pill-dot dot-open"></span> OPEN</span>
                @elseif($this->business->queue_status === 'paused')
                    <span class="status-pill pill-paused"><span class="pill-dot dot-paused"></span> PAUSED</span>
                @else
                    <span class="status-pill pill-closed"><span class="pill-dot dot-closed"></span> CLOSED</span>
                @endif

                <span class="entries-count">{{ $this->business->entries_today }} / {{ $this->business->daily_limit }}
                    today</span>
            </div>

            <div class="status-actions">
                @if ($this->business->queue_status !== 'open')
                    <button wire:click="openQueue" class="btn-open">
                        <svg width="11" height="11" viewBox="0 0 16 16" fill="white">
                            <path d="M4 3l9 5-9 5V3z" />
                        </svg>
                        Open
                    </button>
                @endif
                {{-- Pause with reason --}}
                @if ($this->business->queue_status === 'open')
                    <div style="display:flex; gap:8px; align-items:center;">
                        <input wire:model="pauseReason" type="text" placeholder="Pause reason (optional)"
                            style="padding:8px 12px; font-size:13px; color: #111827; border-radius:8px; border:1.5px solid #e5e7eb; outline:none; width:200px;" />
                        <button wire:click="pauseQueue"
                            style="padding:8px 16px; font-size:13px; font-weight:600; border-radius:8px; background:#eab308; color:#fff; border:none; cursor:pointer;">
                            ⏸ Pause
                        </button>
                    </div>
                @endif
                @if ($this->business->queue_status !== 'closed')
                    <button wire:click="closeQueue" class="btn-close">
                        <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round">
                            <path d="M3 3l10 10M13 3L3 13" />
                        </svg>
                        Close
                    </button>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Waiting</div>
                <div class="stat-value stat-value-teal">{{ $waitingCount }}</div>
                <div class="stat-sub">in queue now</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Served today</div>
                <div class="stat-value">{{ $this->business->entries_today }}</div>
                <div class="stat-sub">of {{ $this->business->daily_limit }} limit</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Avg service time</div>
                <div class="stat-value">{{ $this->business->avgServiceMinutes() }}<span
                        style="font-size:16px;font-weight:500;color:#9ca3af;"> min</span></div>
                <div class="stat-sub">per customer</div>
            </div>
        </div>

        {{-- ✅ Now Serving — full width standalone --}}
        <div class="main-grid">
            <div class="now-serving">
                <div class="panel-header">
                    <span class="panel-title">Now serving</span>
                    <button wire:click="addManual" class="btn-add">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M8 2v12M2 8h12" />
                        </svg>
                        Add to queue
                    </button>
                </div>

                @if ($currentEntry)
                    <div class="ticket-big">{{ $currentEntry->ticket_code }}</div>
                    <div class="ticket-meta">
                        <span>{{ $currentEntry->wa_id ?? 'Anonymous' }}</span>
                        <span class="tmeta-dot"></span>
                        <span>{{ ucfirst($currentEntry->status) }}</span>
                        <span class="tmeta-dot"></span>
                        <span>{{ $currentEntry->called_at?->diffForHumans() }}</span>
                    </div>
                    <div class="ticket-actions">
                        <button wire:click="markDone" class="btn-done">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 8l4 4 8-8" />
                            </svg>
                            Done
                        </button>
                        <button wire:click="skipCurrent" class="btn-skip">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 8h8M9 5l3 3-3 3" />
                            </svg>
                            Skip
                        </button>
                    </div>
                @else
                    <div class="no-serving">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 8v4M12 16v.5" />
                        </svg>
                        <span>No one being served</span>
                    </div>
                @endif

                <button wire:click="callNext" class="call-next-btn">
                    Call next
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 8h8M9 5l3 3-3 3" />
                    </svg>
                </button>
            </div>

            {{-- ✅ Waiting list — below now serving --}}
            <div class="waiting-panel">
                <div class="panel-header">
                    <span class="panel-title">Waiting</span>
                    <span class="waiting-count-badge">{{ $waitingCount }}</span>
                </div>

                @forelse($waitingEntries as $entry)
                    <div class="waiting-row">
                        <div class="waiting-left">
                            <span class="waiting-pos">{{ $entry->position }}</span>
                            <span class="waiting-ticket">{{ $entry->ticket_code }}</span>
                            <span class="waiting-id">{{ $entry->wa_id ?? 'Anonymous' }}</span>
                        </div>
                        <span class="waiting-time">{{ $entry->joined_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="waiting-empty">Queue is empty</div>
                @endforelse
            </div>
        </div>

        {{-- TV Display --}}
        <div class="tv-card">
            <div class="tv-info">
                <p>TV display</p>
                <span>Show the live queue on a screen or monitor</span>
                <div class="tv-url">{{ route('public.tv', auth()->user()->business->slug) }}</div>
            </div>
            <a href="{{ route('public.tv', auth()->user()->business->slug) }}" target="_blank" class="btn-tv">
                Open TV
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="white"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 8h8M9 5l3 3-3 3" />
                </svg>
            </a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const interval = setInterval(function() {
                if (window.Echo) {
                    clearInterval(interval);
                    window.Echo.channel('queue.{{ auth()->user()->business->slug }}')
                        .listen('.queue.updated', () => {
                            Livewire.dispatch('queue-updated');
                        });
                }
            }, 100);
        });
    </script>

</x-filament-panels::page>
