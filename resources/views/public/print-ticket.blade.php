<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap" rel="stylesheet">
    <title>Ticket {{ $entry->ticket_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0fdfa;
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .f-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
        }

        .f-logo em {
            font-style: normal;
            color: #14B8A6;
        }

        .no-print {
            margin-bottom: 16px;
        }

        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 22px;
            background: #14B8A6;
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-btn:hover {
            background: #0f9e8e;
        }

        .ticket {
            background: #fff;
            border-radius: 20px;
            width: 300px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(20, 184, 166, 0.15);
            text-align: center;
        }

        /* Header */
        .ticket-header {
            background: linear-gradient(135deg, #14B8A6 0%, #0f766e 100%);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 20px;
        }

        .ticket-powered {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 4px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .ticket-biz {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
        }

        .ticket-loc {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.65);
            margin-top: 3px;
        }

        /* Body */
        .ticket-body {
            padding: 28px 20px 20px;
        }

        .ticket-label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }

        .ticket-number {
            font-size: 80px;
            font-weight: 700;
            color: #14B8A6;
            line-height: 1;
            font-family: monospace;
            letter-spacing: -3px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ticket-badge {
            display: inline-block;
            margin-top: 12px;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(20, 184, 166, 0.1);
            color: #0f766e;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Dashed cut line */
        .ticket-cut {
            border: none;
            border-top: 1.5px dashed #e5e7eb;
            margin: 0 20px;
        }

        /* Info rows */
        .ticket-info {
            padding: 14px 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            border-bottom: 0.5px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            color: #9ca3af;
        }

        .info-value {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        /* Footer */
        .ticket-footer {
            background: #f8fffe;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-top: 0.5px solid rgba(20, 184, 166, 0.1);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-note {
            font-size: 10px;
            color: #9ca3af;
        }

        .footer-brand {
            font-size: 10px;
            font-weight: 600;
            color: #14B8A6;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                background: #fff !important;
                padding: 0 !important;
                display: block;
            }

            .no-print {
                display: none !important;
            }

            .ticket {
                width: 100% !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="1" width="10" height="5" rx="1" />
                <path d="M3 6H2a1 1 0 00-1 1v5a1 1 0 001 1h1v-3h10v3h1a1 1 0 001-1V7a1 1 0 00-1-1h-1" />
                <rect x="3" y="10" width="10" height="5" rx="1" />
            </svg>
            Print
        </button>
    </div>

    <div class="ticket">

        <div class="ticket-header">
            <div class="powered">Powered by <span class="f-logo">Q<em>line</em></span></div>
            <div class="ticket-biz">{{ $entry->business->name }}</div>
            @if ($entry->business->city)
                <div class="ticket-loc">{{ $entry->business->city }}, {{ $entry->business->state }}</div>
            @endif
        </div>

        <div class="ticket-body">
            <div class="ticket-label">Your ticket number</div>
            <div class="ticket-number">{{ $entry->ticket_code }}</div>
        </div>

        <hr class="ticket-cut">

        <div class="ticket-info">
            <div class="info-row">
                <span class="info-label">Date</span>
                <span class="info-value">{{ $entry->joined_at->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Time</span>
                <span class="info-value">{{ $entry->joined_at->format('H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Position</span>
                <span class="info-value">#{{ $positionInfo['position'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">People ahead</span>
                <span class="info-value">{{ $positionInfo['ahead'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Estimated wait</span>
                <span class="info-value">~{{ $positionInfo['estimated_wait'] }} min</span>
            </div>
        </div>

        <div class="ticket-footer">
            <span class="footer-note">Please wait for your number to be called</span>
            <span class="footer-brand">qline.my</span>
        </div>

    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>

</body>

</html>
