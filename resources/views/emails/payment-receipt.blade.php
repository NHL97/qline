<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; font-family: Arial, sans-serif; padding: 40px 16px; }
        .container { max-width: 560px; margin: 0 auto; }
        .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #0d9488, #065f52); padding: 40px; text-align: center; }
        .logo { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -0.03em; margin-bottom: 8px; }
        .logo em { font-style: normal; color: rgba(255,255,255,0.5); }
        .body { padding: 40px; }
        .greeting { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .text { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }
        .receipt-box { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
        .receipt-header { background: #f8fafc; padding: 14px 20px; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #0f172a; }
        .total-row { display: flex; justify-content: space-between; padding: 16px 20px; background: #f0fdf4; font-size: 15px; font-weight: 700; color: #0f172a; }
        .total-amount { color: #14B8A6; font-size: 18px; }
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
            </div>
            <div class="body">
                <div class="greeting">Payment Receipt 🧾</div>
                <p class="text">Thank you, {{ $user->name }}! Your payment has been received and your subscription is now active.</p>

                <div class="receipt-box">
                    <div class="receipt-header">Receipt Details</div>
                    <div class="info-row">
                        <span class="info-label">Business</span>
                        <span class="info-value">{{ $business->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Plan</span>
                        <span class="info-value">{{ ucfirst($subscription->type) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Valid Until</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($subscription->expires_at)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method</span>
                        <span class="info-value">{{ strtoupper($payment->method ?? 'FPX') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reference</span>
                        <span class="info-value" style="font-family:monospace; font-size:12px;">{{ $payment->reference }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Total Paid</span>
                        <span class="total-amount">RM {{ number_format($payment->amount, 2) }}</span>
                    </div>
                </div>

                <a href="{{ url('/business') }}" class="btn">Go to Dashboard →</a>
            </div>
            <div class="footer">
                <p>Keep this email as your payment record.<br>© 2026 QLine · <a href="{{ url('/') }}">qline.my</a> · Built in Malaysia 🇲🇾</p>
            </div>
        </div>
    </div>
</body>
</html>