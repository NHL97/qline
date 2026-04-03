<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Activated</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; font-family: Arial, sans-serif; padding: 40px 16px; }
        .container { max-width: 560px; margin: 0 auto; }
        .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #0d9488, #065f52); padding: 40px; text-align: center; }
        .logo { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -0.03em; margin-bottom: 8px; }
        .logo em { font-style: normal; color: rgba(255,255,255,0.5); }
        .check-circle { width: 64px; height: 64px; border-radius: 50%; background: rgba(255,255,255,0.15); margin: 16px auto 0; display: flex; align-items: center; justify-content: center; font-size: 28px; }
        .body { padding: 40px; }
        .greeting { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .text { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }
        .info-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
        .info-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid #dcfce7; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #0f172a; }
        .btn { display: block; text-align: center; padding: 14px 24px; background: #14B8A6; color: #fff; font-size: 15px; font-weight: 700; border-radius: 10px; text-decoration: none; margin-bottom: 24px; }
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
                <div class="check-circle">✅</div>
            </div>
            <div class="body">
                <div class="greeting">Your queue is now live!</div>
                <p class="text">Your <strong>{{ ucfirst($subscription->type) }} Plan</strong> for <strong>{{ $business->name }}</strong> has been activated. Customers can now join your queue.</p>

                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Plan</span>
                        <span class="info-value">{{ ucfirst($subscription->type) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Valid Until</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Daily Limit</span>
                        <span class="info-value">{{ number_format($business->daily_limit) }} customers/day</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Join Code</span>
                        <span class="info-value" style="font-family:monospace;">{{ $business->join_code }}</span>
                    </div>
                </div>

                <a href="{{ url('/business') }}" class="btn">Open My Dashboard →</a>

                <p class="text" style="font-size:13px;">Your queue will be automatically paused when your plan expires. Renew before <strong>{{ \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y') }}</strong> to avoid interruption.</p>
            </div>
            <div class="footer">
                <p>© 2026 Qline · <a href="{{ url('/') }}">qline.my</a> · Built in Malaysia 🇲🇾</p>
            </div>
        </div>
    </div>
</body>
</html>