<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js'])
    <title>Queue Status — {{ $business->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0fdf4;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .business {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .ticket {
            font-size: 5rem;
            font-weight: 900;
            color: #16a34a;
            line-height: 1;
            margin: 16px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .status-waiting {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-called {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-serving {
            background: #dcfce7;
            color: #166534;
        }

        .status-done {
            background: #f0fdf4;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-skipped {
            background: #f3f4f6;
            color: #6b7280;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.85rem;
            color: #9ca3af;
        }

        .info-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #111827;
        }

        .message {
            margin-top: 24px;
            padding: 16px;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .message-serving {
            background: #dcfce7;
            color: #166534;
        }

        .message-done {
            background: #f0fdf4;
            color: #166534;
        }

        .message-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .cancel-btn {
            margin-top: 24px;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            background: #fee2e2;
            color: #991b1b;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background: #fecaca;
        }

        .powered {
            margin-top: 24px;
            font-size: 0.75rem;
            color: #d1d5db;
        }

        .flash {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="card">

        @if (session('message'))
            <div class="flash">{{ session('message') }}</div>
        @endif

        <div class="business">{{ $business->name }}</div>
        <div class="ticket">{{ $entry->ticket_code }}</div>

        <span class="status-badge status-{{ $entry->status }}">
            {{ ucfirst($entry->status) }}
        </span>

        @if ($entry->isActive())
            <div style="margin-bottom: 20px;">
                <div class="info-row">
                    <span class="info-label">Position</span>
                    <span class="info-value">#{{ $positionInfo['position'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">People Ahead</span>
                    <span class="info-value">{{ $positionInfo['ahead'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estimated Wait</span>
                    <span class="info-value">~{{ $positionInfo['estimated_wait'] }} min</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Joined At</span>
                    <span class="info-value">{{ $entry->joined_at->format('H:i') }}</span>
                </div>
            </div>
        @endif

        @if (in_array($entry->status, ['called', 'serving']))
            <div class="message message-serving">
                🔔 Please proceed to the counter now!
            </div>
        @endif

        @if ($entry->isDone())
            <div class="message message-done">
                ✅ Your visit is complete. Thank you!
            </div>
        @endif

        @if ($entry->status === 'cancelled' || $entry->status === 'skipped')
            <div class="message message-cancelled">
                Your queue spot is no longer active.
            </div>
        @endif

        @if ($entry->isWaiting() && $canCancel)
            <form method="POST" action="{{ route('public.cancel', [$business->slug, $entry->id]) }}"
                onsubmit="return confirm('Are you sure you want to cancel your spot?')">
                @csrf
                <input type="hidden" name="token" value="{{ $entry->cancel_token }}">
                <button type="submit" class="cancel-btn">Cancel My Spot</button>
            </form>
        @endif

        <div class="powered">Powered by QLine</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const interval = setInterval(function() {
                if (window.Echo) {
                    clearInterval(interval);

                    window.Echo.channel('queue.{{ $business->slug }}')
                        .listen('.queue.updated', (data) => {
                            // Reload the page to get fresh status
                            window.location.reload();
                        });
                }
            }, 100);
        });
    </script>

</body>

</html>
