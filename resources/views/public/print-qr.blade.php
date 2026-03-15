<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap" rel="stylesheet">
    <title>QR Code — {{ $business->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #e5e7eb;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px;
        }

        .f-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
        }

        .f-logo em {
            font-style: normal;
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

        .card {
            width: 400px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(20, 184, 166, 0.15);
        }

        .header {
            background: linear-gradient(135deg, #14B8A6 0%, #0d9488 60%, #0f766e 100%);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 32px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
        }

        .powered {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 4px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .biz-name {
            font-size: 30px;
            font-weight: 700;
            color: #fff;
        }

        .location {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
        }

        .qr-section {
            padding: 28px 24px 20px;
            text-align: center;
        }

        .scan-label {
            font-size: 10px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .qr-wrap {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .qr-frame {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px;
            border-radius: 16px;
            border: 1.5px solid rgba(20, 184, 166, 0.25);
            background: #f0fdfa;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            position: relative;
        }

        .qr-frame img {
            width: 190px;
            height: 190px;
            display: block;
            border-radius: 4px;
        }

        .qr-corner {
            position: absolute;
            width: 17px;
            height: 17px;
            border-color: #14B8A6;
            border-style: solid;
        }

        .qr-corner.tl {
            top: 6px;
            left: 6px;
            border-width: 2px 0 0 2px;
            border-radius: 3px 0 0 0;
        }

        .qr-corner.tr {
            top: 6px;
            right: 6px;
            border-width: 2px 2px 0 0;
            border-radius: 0 3px 0 0;
        }

        .qr-corner.bl {
            bottom: 6px;
            left: 6px;
            border-width: 0 0 2px 2px;
            border-radius: 0 0 0 3px;
        }

        .qr-corner.br {
            bottom: 6px;
            right: 6px;
            border-width: 0 2px 2px 0;
            border-radius: 0 0 3px 0;
        }

        .via {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: #64748b;
        }

        .via-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #25D366;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            flex-shrink: 0;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(20, 184, 166, 0.18), transparent);
            margin: 0 24px;
        }

        .instructions {
            padding: 18px 24px 22px;
        }

        .how-box {
            background: #f0fdfa;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-radius: 12px;
            padding: 15px 17px;
            border: 1px solid rgba(20, 184, 166, 0.12);
        }

        .how-title {
            font-size: 9px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 9px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-num {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #14B8A6;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .step-text {
            font-size: 12.5px;
            color: #374151;
            line-height: 1.5;
        }

        .footer {
            background: #f8fffe;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-top: 1px solid rgba(20, 184, 166, 0.1);
            padding: 12px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .join-code {
            font-size: 11px;
            color: #64748b;
        }

        .join-code span {
            font-family: monospace;
            font-weight: 700;
            color: #0f766e;
            background: rgba(20, 184, 166, 0.1);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 2px 7px;
            border-radius: 5px;
        }

        .brand {
            font-size: 11px;
            color: #14B8A6;
            font-weight: 600;
        }

        /* ✅ Card stays fixed size, centered on the printed page */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                background: #fff !important;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .card {
                width: 190mm !important;
                /* big but not full A4 (A4 is 210mm wide) */
                border-radius: 16px !important;
                box-shadow: none !important;
                border: 1px solid rgba(20, 184, 166, 0.2) !important;
            }

            .qr-frame img {
                width: 120mm !important;
                height: 120mm !important;
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

    <div class="card">
        <div class="header">
            <div class="powered">Powered by <span class="f-logo">Q<em>Line</em></span></div>
            <div class="biz-name">{{ $business->name }}</div>
            @if ($business->city)
                <div class="location">{{ $business->city }}, {{ $business->state }}</div>
            @endif
        </div>

        <div class="qr-section">
            <div class="scan-label">Scan to join the queue</div>
            <div class="qr-wrap">
                <div class="qr-frame">
                    <div class="qr-corner tl"></div>
                    <div class="qr-corner tr"></div>
                    <div class="qr-corner bl"></div>
                    <div class="qr-corner br"></div>
                    <img src="{{ $qrImageUrl }}" alt="QR Code" />
                </div>
                <div class="via">
                    <div class="via-dot"></div>
                    via WhatsApp
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="instructions">
            <div class="how-box">
                <div class="how-title">How to join</div>
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text">Open your WhatsApp camera and scan the QR code above</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">Send the message — your spot will be reserved automatically</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text">Wait for your WhatsApp notification when it's your turn</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="join-code">Join Code: <span>{{ $business->join_code }}</span></div>
            <div class="brand">qline.my</div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>

</body>

</html>
