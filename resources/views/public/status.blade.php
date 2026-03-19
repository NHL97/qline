<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js'])
    <title>Queue Status — {{ $business->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #f0fdfa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            padding: 32px 28px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 32px rgba(20,184,166,0.1), 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid rgba(20,184,166,0.12);
        }

        .flash {
            background: rgba(20,184,166,0.08);
            color: #0f766e;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 0.5px solid rgba(20,184,166,0.2);
            display: flex; align-items: center; gap: 8px;
            text-align: left;
        }

        .biz-name {
            font-size: 12px; font-weight: 500; color: #9ca3af;
            letter-spacing: 0.04em; text-transform: uppercase;
            margin-bottom: 16px;
        }

        .ticket-num {
            font-size: 72px; font-weight: 700; color: #14B8A6;
            line-height: 1; font-family: monospace; letter-spacing: -3px;
            margin-bottom: 14px;
        }

        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 16px; border-radius: 999px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.04em;
            margin-bottom: 24px;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

        .status-waiting  { background: rgba(234,179,8,0.1);   color: #92400e; }
        .dot-waiting     { background: #eab308; }
        .status-called   { background: rgba(59,130,246,0.1);  color: #1e40af; }
        .dot-called      { background: #3b82f6; }
        .status-serving  { background: rgba(20,184,166,0.1);  color: #0f766e; }
        .dot-serving     { background: #14B8A6; }
        .status-done     { background: rgba(20,184,166,0.08); color: #0f766e; }
        .dot-done        { background: #14B8A6; }
        .status-cancelled { background: rgba(239,68,68,0.08); color: #b91c1c; }
        .dot-cancelled   { background: #ef4444; }
        .status-skipped  { background: rgba(0,0,0,0.05);      color: #6b7280; }
        .dot-skipped     { background: #9ca3af; }

        .info-box {
            background: #f8fffe;
            border-radius: 14px;
            border: 1px solid rgba(20,184,166,0.1);
            margin-bottom: 20px;
            overflow: hidden;
            text-align: left;
        }
        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 11px 16px;
            border-bottom: 0.5px solid rgba(20,184,166,0.07);
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-size: 13px; color: #9ca3af; }
        .info-value { font-size: 13px; font-weight: 600; color: #111827; }
        .info-value-teal { color: #14B8A6; }

        .message {
            padding: 14px 16px; border-radius: 12px;
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px; text-align: left;
        }
        .message-icon {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .message-serving  { background: rgba(20,184,166,0.08); color: #0f766e; border: 0.5px solid rgba(20,184,166,0.2); }
        .icon-serving     { background: rgba(20,184,166,0.15); }
        .message-done     { background: rgba(20,184,166,0.06); color: #0f766e; border: 0.5px solid rgba(20,184,166,0.15); }
        .icon-done        { background: rgba(20,184,166,0.1); }
        .message-cancelled { background: rgba(239,68,68,0.06); color: #b91c1c; border: 0.5px solid rgba(239,68,68,0.15); }
        .icon-cancelled   { background: rgba(239,68,68,0.1); }

        .cancel-btn {
            width: 100%; padding: 13px; border-radius: 12px;
            background: rgba(239,68,68,0.08); color: #b91c1c;
            font-weight: 600; font-size: 14px;
            border: 1px solid rgba(239,68,68,0.15);
            cursor: pointer; margin-bottom: 16px;
            transition: background 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .cancel-btn:hover { background: rgba(239,68,68,0.13); }

        .powered { font-size: 12px; color: #d1d5db; }
        .powered a { color: #14B8A6; font-weight: 600; }
    </style>
</head>
<body>

    <div class="card">

        @if(session('message'))
            <div class="flash">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8l4 4 8-8"/></svg>
                {{ session('message') }}
            </div>
        @endif

        <div class="biz-name">{{ $business->name }}</div>
        <div class="ticket-num">{{ $entry->ticket_code }}</div>

        @php
            $dotClass = match($entry->status) {
                'waiting'   => 'dot-waiting',
                'called'    => 'dot-called',
                'serving'   => 'dot-serving',
                'done'      => 'dot-done',
                'cancelled' => 'dot-cancelled',
                'skipped'   => 'dot-skipped',
                default     => 'dot-skipped',
            };
        @endphp

        <span class="status-badge status-{{ $entry->status }}">
            <span class="badge-dot {{ $dotClass }}"></span>
            {{ ucfirst($entry->status) }}
        </span>

        @if($entry->isActive())
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Position</span>
                    <span class="info-value info-value-teal">#{{ $positionInfo['position'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">People ahead</span>
                    <span class="info-value">{{ $positionInfo['ahead'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estimated wait</span>
                    <span class="info-value">~{{ $positionInfo['estimated_wait'] }} min</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Joined at</span>
                    <span class="info-value">{{ $entry->joined_at->format('H:i') }}</span>
                </div>
            </div>
        @endif

        @if(in_array($entry->status, ['called', 'serving']))
            <div class="message message-serving">
                <div class="message-icon icon-serving">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round"><path d="M8 2a6 6 0 110 12A6 6 0 018 2z"/><path d="M8 6v3M8 11v.5"/></svg>
                </div>
                <span>Please proceed to the counter now!</span>
            </div>
        @endif

        @if($entry->isDone())
            <div class="message message-done">
                <div class="message-icon icon-done">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8l4 4 8-8"/></svg>
                </div>
                <span>Your visit is complete. Thank you!</span>
            </div>
        @endif

        @if($entry->status === 'cancelled' || $entry->status === 'skipped')
            <div class="message message-cancelled">
                <div class="message-icon icon-cancelled">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round"><path d="M3 3l10 10M13 3L3 13"/></svg>
                </div>
                <span>Your queue spot is no longer active.</span>
            </div>
        @endif

        @if($entry->isWaiting() && $canCancel)
            <form method="POST"
                action="{{ route('public.cancel', [$business->slug, $entry->id]) }}"
                onsubmit="return confirm('Are you sure you want to cancel your spot?')">
                @csrf
                <input type="hidden" name="token" value="{{ $entry->cancel_token }}">
                <button type="submit" class="cancel-btn">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 3l10 10M13 3L3 13"/></svg>
                    Cancel my spot
                </button>
            </form>
        @endif

        <div class="powered">Powered by <a href="{{ route('home') }}" style="text-decoration: none;">Qline</a></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const interval = setInterval(function () {
                if (window.Echo) {
                    clearInterval(interval);
                    window.Echo.channel('queue.{{ $business->slug }}')
                        .listen('.queue.updated', () => {
                            window.location.reload();
                        });
                }
            }, 100);
        });
    </script>

</body>
</html>