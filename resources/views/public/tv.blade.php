<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap" rel="stylesheet">
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
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header */
        .hdr {
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }

        .hdr-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hdr-biz {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pill-open {
            background: rgba(20, 184, 166, 0.15);
            color: #14B8A6;
        }

        .dot-open {
            background: #14B8A6;
        }

        .pill-paused {
            background: rgba(234, 179, 8, 0.15);
            color: #fbbf24;
        }

        .dot-paused {
            background: #fbbf24;
        }

        .pill-closed {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .dot-closed {
            background: #ef4444;
        }

        .hdr-right {
            text-align: right;
        }

        .hdr-time {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            font-variant-numeric: tabular-nums;
            letter-spacing: -1px;
            line-height: 1;
        }

        .hdr-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 3px;
        }

        /* Main grid */
        .main {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        /* Now serving */
        .now-serving {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            position: relative;
            overflow: hidden;
        }

        .now-serving::before {
            content: '';
            position: absolute;
            top: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(20, 184, 166, 0.04);
        }

        .now-serving::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(20, 184, 166, 0.03);
        }

        .ns-label {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .ns-ticket {
            font-size: 140px;
            font-weight: 700;
            color: #14B8A6;
            line-height: 1;
            font-family: monospace;
            letter-spacing: -6px;
        }

        .ns-empty {
            font-size: 80px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.08);
            font-family: monospace;
        }

        .ns-empty-label {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.2);
            margin-top: 12px;
        }

        /* Waiting list */
        .waiting {
            padding: 32px 36px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .waiting-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .waiting-title {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .waiting-count {
            font-size: 12px;
            font-weight: 700;
            color: #14B8A6;
            background: rgba(20, 184, 166, 0.1);
            padding: 3px 10px;
            border-radius: 999px;
        }

        .waiting-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 14px 0;
            border-bottom: 0.5px solid rgba(255, 255, 255, 0.05);
        }

        .waiting-item:last-child {
            border-bottom: none;
        }

        .waiting-pos {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            width: 24px;
            flex-shrink: 0;
        }

        .waiting-code {
            font-size: 32px;
            font-weight: 700;
            color: #e2e8f0;
            font-family: monospace;
            letter-spacing: -1px;
            line-height: 1;
        }

        .waiting-item.is-next .waiting-code {
            color: #14B8A6;
        }

        .next-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            background: rgba(20, 184, 166, 0.15);
            color: #14B8A6;
            margin-left: 8px;
        }

        .waiting-empty {
            color: rgba(255, 255, 255, 0.2);
            font-size: 14px;
            margin-top: 8px;
        }

        /* Footer */
        .footer {
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .footer-left {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .footer-served {
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
        }

        .footer-brand {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.2);
            
        }

        .footer-brand span {
            color: #14B8A6;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1rem;
        }
        .footer-brand span em {
            font-style: normal;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <div class="hdr">
        <div class="hdr-left">
            <span class="hdr-biz">{{ $business->name }}</span>

            @php
                $pillClass = match ($business->queue_status) {
                    'open' => 'pill-open',
                    'paused' => 'pill-paused',
                    default => 'pill-closed',
                };
                $dotClass = match ($business->queue_status) {
                    'open' => 'dot-open',
                    'paused' => 'dot-paused',
                    default => 'dot-closed',
                };
                $statusLabel = match ($business->queue_status) {
                    'open' => 'OPEN',
                    'paused' => 'PAUSED',
                    default => 'CLOSED',
                };
            @endphp

            <span id="status-badge" class="status-pill {{ $pillClass }}">
                <span class="pill-dot {{ $dotClass }}"></span>
                {{ $statusLabel }}
            </span>
        </div>
        <div class="hdr-right">
            <div class="hdr-time" id="clock"></div>
            <div class="hdr-date" id="date"></div>
        </div>
    </div>

    <div class="main">
        <div class="now-serving">
            <div class="ns-label">Now serving</div>
            @if ($current)
                <div class="ns-ticket" id="current-ticket">{{ $current->ticket_code }}</div>
            @else
                <div class="ns-empty" id="current-ticket">—</div>
                <div class="ns-empty-label" id="current-label">No one is being served</div>
            @endif
        </div>

        <div id="waiting-list">
            @forelse($next as $index => $entry)
                <div class="waiting-item {{ $index === 0 ? 'is-next' : '' }}">
                    <span class="waiting-pos">{{ $index + 1 }}</span>
                    <span class="waiting-code">{{ $entry->ticket_code }}</span>
                    @if ($index === 0)
                        <span class="next-badge">Next</span>
                    @endif
                </div>
            @empty
                <div class="waiting-empty">Queue is empty</div>
            @endforelse
        </div>
    </div>
    </div>

    <div class="footer">
        <div class="footer-left">
            <span class="footer-served" id="served-count">{{ $business->entries_today }}</span>
            served today
        </div>
        <div class="footer-brand">Powered by <span>Q<em>line.my</em></span></div>
    </div>

    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const interval = setInterval(function() {
                if (window.Echo) {
                    clearInterval(interval);

                    window.Echo.channel('queue.{{ $business->slug }}')
                        .listen('.queue.updated', (data) => {

                            // Update now serving
                            const ticketEl = document.getElementById('current-ticket');
                            const labelEl = document.getElementById('current-label');

                            if (data.current_ticket) {
                                ticketEl.textContent = data.current_ticket;
                                ticketEl.className = 'ns-ticket';

                                labelEl.textContent = '';
                            } else {
                                ticketEl.textContent = '—';
                                ticketEl.className = 'ns-empty';

                                labelEl.textContent = 'No one is being served';
                            }

                            // Update status badge
                            const badge = document.getElementById('status-badge');
                            const pillMap = {
                                open: 'pill-open',
                                paused: 'pill-paused',
                                closed: 'pill-closed'
                            };
                            const dotMap = {
                                open: 'dot-open',
                                paused: 'dot-paused',
                                closed: 'dot-closed'
                            };
                            const labelMap = {
                                open: 'OPEN',
                                paused: 'PAUSED',
                                closed: 'CLOSED'
                            };
                            if (badge && data.queue_status) {
                                badge.className = 'status-pill ' + (pillMap[data.queue_status] ||
                                    'pill-closed');
                                badge.innerHTML =
                                    `<span class="pill-dot ${dotMap[data.queue_status] || 'dot-closed'}"></span> ${labelMap[data.queue_status] || data.queue_status}`;
                            }

                            // Update served count
                            const servedEl = document.getElementById('served-count');
                            if (servedEl && data.entries_today !== undefined) {
                                servedEl.textContent = data.entries_today;
                            }

                            // Fetch fresh waiting list
                            fetch(window.location.href)
                                .then(r => r.text())
                                .then(html => {
                                    const doc = new DOMParser().parseFromString(html, 'text/html');
                                    document.getElementById('waiting-list').innerHTML =
                                        doc.getElementById('waiting-list').innerHTML;
                                    document.getElementById('waiting-count').textContent =
                                        doc.getElementById('waiting-count').textContent;
                                });
                        });
                }
            }, 100);
        });
    </script>

</body>

</html>
