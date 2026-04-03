<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to QLine</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; font-family: Arial, sans-serif; padding: 40px 16px; }
        .container { max-width: 560px; margin: 0 auto; }
        .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #0d9488, #065f52); padding: 40px 40px 32px; text-align: center; }
        .logo { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -0.03em; margin-bottom: 8px; }
        .logo em { font-style: normal; color: rgba(255,255,255,0.5); }
        .header-sub { font-size: 14px; color: rgba(255,255,255,0.65); }
        .body { padding: 40px; }
        .greeting { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .text { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }
        .info-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
        .info-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid #dcfce7; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #0f172a; font-family: monospace; }
        .btn { display: block; text-align: center; padding: 14px 24px; background: #14B8A6; color: #fff; font-size: 15px; font-weight: 700; border-radius: 10px; text-decoration: none; margin-bottom: 24px; }
        .steps { margin-bottom: 24px; }
        .step { display: flex; gap: 14px; margin-bottom: 16px; align-items: flex-start; }
        .step-num { width: 28px; height: 28px; border-radius: 50%; background: #14B8A6; color: #fff; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; }
        .step-text strong { display: block; font-size: 14px; color: #0f172a; margin-bottom: 2px; }
        .step-text span { font-size: 13px; color: #64748b; }
        .footer { background: #f8fafc; padding: 24px 40px; text-align: center; border-top: 1px solid #f1f5f9; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .footer a { color: #14B8A6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">Q<em>line</em></div>
                <div class="header-sub">WhatsApp Queue Management</div>
            </div>

            <div class="body">
                <div class="greeting">Welcome, {{ $user->name }}! 👋</div>
                <p class="text">Your business <strong>{{ $business->name }}</strong> is now registered on QLine. Here are your details — keep them handy.</p>

                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Business Name</span>
                        <span class="info-value">{{ $business->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Join Code</span>
                        <span class="info-value">{{ $business->join_code }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Dashboard</span>
                        <span class="info-value">qline.my/business</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">TV Display</span>
                        <span class="info-value">qline.my/q/{{ $business->slug }}/tv</span>
                    </div>
                </div>

                <div class="steps">
                    <div class="step">
                        <div class="step-num">1</div>
                        <div class="step-text">
                            <strong>Subscribe to activate your queue</strong>
                            <span>Go to your dashboard and choose a Daily or Monthly plan to start accepting customers.</span>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <strong>Generate and print your QR code</strong>
                            <span>Go to Business Profile → QR Code and print your branded QR card to display at your entrance.</span>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div class="step-text">
                            <strong>Open your queue and start serving</strong>
                            <span>Customers scan the QR, join via WhatsApp, and you manage everything from the dashboard.</span>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/business') }}" class="btn">Go to My Dashboard →</a>

                <p class="text" style="font-size:13px;">Need help? Reply to this email or WhatsApp us at <a href="https://wa.me/{{ config('qline.wa_number') }}" style="color:#14B8A6;">+{{ config('qline.wa_number') }}</a>.</p>
            </div>

            <div class="footer">
                <p>You received this because you registered at <a href="{{ url('/') }}">qline.my</a>.<br>© 2026 QLine · Built in Malaysia 🇲🇾</p>
            </div>
        </div>
    </div>
</body>
</html>