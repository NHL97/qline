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

        body.flash {
            background: #1e3a3a;
            transition: background 0.2s ease;
        }

        .hdr {
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .hdr-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hdr-biz {
            font-size: 20px;
            font-weight: 700;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pill-open {
            background: rgba(20, 184, 166, .15);
            color: #14B8A6;
        }

        .dot-open {
            background: #14B8A6;
        }

        .pill-paused {
            background: rgba(234, 179, 8, .15);
            color: #fbbf24;
        }

        .dot-paused {
            background: #fbbf24;
        }

        .pill-closed {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        .dot-closed {
            background: #ef4444;
        }

        .hdr-time {
            font-size: 28px;
            font-weight: 700;
            text-align: right;
        }

        .hdr-date {
            font-size: 12px;
            color: rgba(255, 255, 255, .4);
            text-align: right;
        }

        .main {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .now-serving {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid rgba(255, 255, 255, .06);
        }

        .ns-label {
            font-size: 11px;
            letter-spacing: 5px;
            color: rgba(255, 255, 255, .3);
            margin-bottom: 20px;
        }

        .ns-ticket {
            font-size: 140px;
            font-weight: 700;
            color: #14B8A6;
            font-family: monospace;
            transition: all 0.3s ease;
        }

        .ns-empty {
            font-size: 80px;
            color: rgba(255, 255, 255, .1);
        }

        .ns-empty-label {
            color: rgba(255, 255, 255, .2);
            margin-top: 10px;
        }

        .waiting {
            padding: 30px;
            display: flex;
            flex-direction: column;
        }

        .waiting-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .waiting-title {
            font-size: 11px;
            letter-spacing: 5px;
            color: rgba(255, 255, 255, .3);
        }

        .waiting-count {
            color: #14B8A6;
            font-weight: 700;
        }

        .waiting-item {
            display: flex;
            gap: 20px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .waiting-item.is-next {
            background: rgba(20, 184, 166, .05);
            border-radius: 8px;
            padding-left: 10px;
        }

        .waiting-code {
            font-size: 30px;
            font-family: monospace;
        }

        .next-badge {
            font-size: 10px;
            color: #14B8A6;
        }

        .footer {
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, .05);
        }

        #audio-status {
            font-size: 11px;
            color: rgba(255, 255, 255, .3);
        }

        .footer b {
            font-size: 14px;
            font-weight: 700;
            color: #14B8A6;
            font-family: 'Syne', sans-serif;
        }

    </style>
</head>

<body>

    <div class="hdr">
        <div class="hdr-left">
            <span class="hdr-biz">{{ $business->name }}</span>

            <span id="status-badge" class="status-pill pill-{{ $business->queue_status }}">
                <span class="pill-dot dot-{{ $business->queue_status }}"></span>
                {{ strtoupper($business->queue_status) }}
                @if($business->queue_status === 'paused' && $business->pause_reason)
                    — {{ $business->pause_reason }}
                @endif
            </span>
        </div>

        <div>
            <div id="clock" class="hdr-time"></div>
            <div id="date" class="hdr-date"></div>
        </div>
    </div>

    <div class="main">

        <div class="now-serving">
            <div class="ns-label">NOW SERVING</div>

            @if ($current)
                <div id="current-ticket" class="ns-ticket">{{ $current->ticket_code }}</div>
                <div id="current-label"></div>
            @else
                <div id="current-ticket" class="ns-empty">—</div>
                <div id="current-label" class="ns-empty-label">No one is being served</div>
            @endif
        </div>

        <div class="waiting">

            <div class="waiting-header">
                <div class="waiting-title">WAITING</div>
                <div id="waiting-count" class="waiting-count">{{ count($next) }}</div>
            </div>

            <div id="waiting-list">
                @forelse($next as $index => $entry)
                    <div class="waiting-item {{ $index === 0 ? 'is-next' : '' }}">
                        <span>{{ $index + 1 }}</span>
                        <span class="waiting-code">{{ $entry->ticket_code }}</span>
                        @if ($index === 0)
                            <span class="next-badge">Next</span>
                        @endif
                    </div>
                @empty
                    <div>Queue is empty</div>
                @endforelse
            </div>

        </div>

    </div>

    <div class="footer">
        <div><span id="served-count">{{ $business->entries_today }}</span> served today</div>
        <div id="audio-status">🔇 Click anywhere to enable sound</div>
        <div>Powered by <b>Q<em>line.my</em></b></div>
    </div>

    <audio id="ding-sound" src="/ding.mp3" preload="auto"></audio>

    <script>
        // ─── CLOCK ───────────────────────────────────────────────────────────────
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

        // ─── AUDIO UNLOCK ─────────────────────────────────────────────────────────
        // Browsers block autoplay until user interacts with the page.
        // One click anywhere unlocks audio for the rest of the session.
        document.addEventListener('click', () => {
            const audio = document.getElementById('ding-sound');
            if (audio) {
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    document.getElementById('audio-status').textContent = '🔊 Sound enabled';
                }).catch(() => {
                    document.getElementById('audio-status').textContent = '🔇 Sound unavailable';
                });
            }
        }, { once: true });

        function playSound() {
            const audio = document.getElementById('ding-sound');
            if (audio) audio.play().catch(() => {});
        }

        // ─── LAST TICKET (seeded from server to avoid false ding on first update) ─
        let lastTicket = @json($current?->ticket_code);

        // ─── UPDATE UI (called on every Echo event) ───────────────────────────────
        function updateUI(data) {
            // NOW SERVING
            const ticketEl = document.getElementById('current-ticket');
            const labelEl  = document.getElementById('current-label');

            if (data.current_ticket) {
                if (data.current_ticket !== lastTicket) {
                    playSound();
                    document.body.classList.add('flash');
                    setTimeout(() => document.body.classList.remove('flash'), 200);
                    lastTicket = data.current_ticket;
                }
                ticketEl.textContent = data.current_ticket;
                ticketEl.className   = 'ns-ticket';
                if (labelEl) labelEl.textContent = '';
            } else {
                ticketEl.textContent = '—';
                ticketEl.className   = 'ns-empty';
                if (labelEl) {
                    labelEl.textContent = 'No one is being served';
                    labelEl.className   = 'ns-empty-label';
                }
            }

            // STATUS BADGE (including pause reason)
            const badge = document.getElementById('status-badge');
            const pillMap  = { open: 'pill-open',   paused: 'pill-paused',   closed: 'pill-closed'  };
            const dotMap   = { open: 'dot-open',    paused: 'dot-paused',    closed: 'dot-closed'   };
            const labelMap = { open: 'OPEN',        paused: 'PAUSED',        closed: 'CLOSED'       };

            if (badge && data.queue_status) {
                let text = labelMap[data.queue_status] || data.queue_status.toUpperCase();
                if (data.queue_status === 'paused' && data.pause_reason) {
                    text += ' — ' + data.pause_reason;
                }
                badge.className = 'status-pill ' + (pillMap[data.queue_status] || 'pill-closed');
                badge.innerHTML = `<span class="pill-dot ${dotMap[data.queue_status] || 'dot-closed'}"></span> ${text}`;
            }

            // SERVED COUNT
            const servedEl = document.getElementById('served-count');
            if (servedEl && data.entries_today !== undefined) {
                servedEl.textContent = data.entries_today;
            }
        }

        // ─── REFRESH WAITING LIST (dedicated JSON endpoint) ───────────────────────
        function refreshWaitingList() {
            fetch('/q/{{ $business->slug }}/waiting')
                .then(r => r.json())
                .then(data => {
                    const list    = document.getElementById('waiting-list');
                    const countEl = document.getElementById('waiting-count');

                    if (data.waiting.length === 0) {
                        list.innerHTML = '<div style="color: rgba(255,255,255,.3)">Queue is empty</div>';
                    } else {
                        list.innerHTML = data.waiting.map((entry, i) => `
                            <div class="waiting-item ${i === 0 ? 'is-next' : ''}">
                                <span>${i + 1}</span>
                                <span class="waiting-code">${entry.ticket_code}</span>
                                ${i === 0 ? '<span class="next-badge">Next</span>' : ''}
                            </div>
                        `).join('');
                    }

                    if (countEl) countEl.textContent = data.count;
                })
                .catch(err => console.error('Failed to refresh waiting list:', err));
        }

        // ─── ECHO ─────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const channel = window.Echo.channel('queue.{{ $business->slug }}');

            channel.listen('.queue.updated', (data) => {
                updateUI(data);
                refreshWaitingList();
            });

            // Dim screen when connection is lost
            window.addEventListener('offline', () => {
                document.body.style.opacity = '0.5';
            });
            window.addEventListener('online', () => {
                document.body.style.opacity = '1';
            });
        });
    </script>

</body>

</html>