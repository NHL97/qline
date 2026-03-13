<div>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
</div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->name }} — Queue Display</title>
    @vite(['resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0f172a;
            color: #fff;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #166534;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 800;
        }

        .header .time {
            font-size: 1.2rem;
            color: #86efac;
        }

        .header .date {
            font-size: 1rem;
            color: #86efac;
        }

        .main {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .now-serving {
            background: #14532d;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .now-serving .label {
            font-size: 1.2rem;
            color: #86efac;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .now-serving .ticket {
            font-size: 10rem;
            font-weight: 900;
            color: #4ade80;
            line-height: 1;
        }

        .now-serving .empty {
            font-size: 3rem;
            color: #166534;
        }

        .waiting-list {
            background: #1e293b;
            padding: 40px;
        }

        .waiting-list h2 {
            font-size: 1rem;
            color: #64748b;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .waiting-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 16px 0;
            border-bottom: 1px solid #334155;
        }

        .waiting-item .pos {
            font-size: 1.2rem;
            color: #64748b;
            width: 30px;
        }

        .waiting-item .code {
            font-size: 2rem;
            font-weight: 800;
            color: #e2e8f0;
        }

        .waiting-item.next .code {
            color: #4ade80;
        }

        .footer {
            background: #0f172a;
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #1e293b;
        }

        .footer span {
            font-size: 0.8rem;
            color: #475569;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="display:flex; align-items:center; gap:16px;">
            <h1>{{ $business->name }}</h1>
            @if ($business->queue_status === 'open')
                <span id="status-badge"
                    style="padding:5px 14px; border-radius:999px; font-size:14px; font-weight:700; background:#4ade80; color:#14532d;">●
                    OPEN</span>
            @elseif($business->queue_status === 'paused')
                <span id="status-badge"
                    style="padding:5px 14px; border-radius:999px; font-size:14px; font-weight:700; background:#eab308; color:#fff;">⏸
                    PAUSED</span>
            @else
                <span id="status-badge"
                    style="padding:5px 14px; border-radius:999px; font-size:14px; font-weight:700; background:#dc2626; color:#fff;">✕
                    CLOSED</span>
            @endif
        </div>
        <div class="time" id="clock"></div>
        <div class="date" id="date"></div>
    </div>

    <div class="main">
        <div class="now-serving">
            <div class="label">Now Serving</div>
            @if ($current)
                <div class="ticket">{{ $current->ticket_code }}</div>
            @else
                <div class="empty">—</div>
            @endif
        </div>

        <div class="waiting-list">
            <h2>Up Next</h2>
            @forelse($next as $index => $entry)
                <div class="waiting-item {{ $index === 0 ? 'next' : '' }}">
                    <div class="pos">{{ $entry->position }}</div>
                    <div class="code">{{ $entry->ticket_code }}</div>
                </div>
            @empty
                <p style="color: #475569; margin-top: 20px;">Queue is empty</p>
            @endforelse
        </div>
    </div>

    <div class="footer">
        <span>{{ $business->entries_today }} served today</span>
        <span>Powered by QLine</span>
    </div>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('en-MY', {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('date').textContent = now.toLocaleDateString('en-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Wait for Echo to be ready
        document.addEventListener('DOMContentLoaded', function() {
            const interval = setInterval(function() {
                if (window.Echo) {
                    clearInterval(interval);

                    window.Echo.channel('queue.{{ $business->slug }}')
                        .listen('.queue.updated', (data) => {
                            // Update now serving
                            if (data.current_ticket) {
                                document.querySelector('.now-serving').innerHTML =
                                    '<div class="label">Now Serving</div><div class="ticket">' + data
                                    .current_ticket + '</div>';
                            } else {
                                document.querySelector('.now-serving').innerHTML =
                                    '<div class="label">Now Serving</div><div class="empty">—</div>';
                            }

                            // Update status badge
                            const statusMap = {
                                open: '● OPEN',
                                paused: '⏸ PAUSED',
                                closed: '✕ CLOSED'
                            };
                            const colorMap = {
                                open: '#16a34a',
                                paused: '#eab308',
                                closed: '#dc2626'
                            };
                            const badge = document.getElementById('status-badge');
                            if (badge) {
                                badge.textContent = statusMap[data.queue_status] || data.queue_status;
                                badge.style.background = colorMap[data.queue_status] || '#6b7280';
                            }

                            // Update footer
                            const spans = document.querySelectorAll('.footer span');
                            if (spans.length >= 2) spans[1].textContent = data.entries_today +
                                ' served today';

                            // Fetch fresh waiting list from server
                            fetch(window.location.href)
                                .then(r => r.text())
                                .then(html => {
                                    const doc = new DOMParser().parseFromString(html, 'text/html');
                                    document.querySelector('.waiting-list').innerHTML =
                                        doc.querySelector('.waiting-list').innerHTML;
                                });
                        });
                }
            }, 100);
        });
    </script>

</body>

</html>
