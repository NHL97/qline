<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --teal: #14B8A6;
            --teal-dark: #0d9488;
            --teal-light: #ccfbf1;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #94a3b8;
            --text-label: #475569;
            --input-bg: #f8fafc;
            --divider: #f1f5f9;
            --error-bg: #fff1f2;
            --error-text: #be123c;
            --shadow: 0 20px 60px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0a0f1e;
                --card: #111827;
                --border: #1e293b;
                --text: #f1f5f9;
                --text-muted: #475569;
                --text-label: #94a3b8;
                --input-bg: #0f172a;
                --divider: #1e293b;
                --error-bg: #1c0a0e;
                --error-text: #fb7185;
                --shadow: 0 20px 60px rgba(0,0,0,0.4), 0 4px 16px rgba(0,0,0,0.3);
            }
        }

        body {
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient background glow */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse, rgba(20, 184, 166, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            display: flex;
            width: 100%;
            max-width: 960px;
            gap: 0;
            position: relative;
            z-index: 1;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        /* Left panel */
        .left-panel {
            width: 320px;
            flex-shrink: 0;
            background: linear-gradient(160deg, #0d9488 0%, #0f766e 40%, #065f52 100%);
            padding: 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .brand {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -0.03em;
            color: #fff;
            margin-bottom: 8px;
        }

        .brand-logo em {
            font-style: normal;
            color: rgba(255,255,255,0.5);
        }

        .brand-tagline {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .features {
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.12);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }

        .feature-text strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
        }

        .feature-text span {
            font-size: 12px;
            color: rgba(255,255,255,0.55);
            line-height: 1.4;
        }

        .left-footer {
            position: relative;
            z-index: 1;
            font-size: 11px;
            color: rgba(255,255,255,0.35);
        }

        /* Right panel (form) */
        .right-panel {
            flex: 1;
            background: var(--card);
            padding: 48px 44px;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .form-header p {
            font-size: 14px;
            color: var(--text-muted);
        }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--teal);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-label);
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        .field input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--input-bg);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field input::placeholder {
            color: var(--text-muted);
            font-size: 13px;
        }

        .field input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(20,184,166,0.1);
            background: var(--card);
        }

        .field .error {
            font-size: 11.5px;
            color: var(--error-text);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .divider {
            height: 1px;
            background: var(--divider);
            margin: 24px 0;
        }

        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(190,18,60,0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 13px 20px;
            background: var(--teal);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.01em;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(20,184,166,0.3);
        }

        .btn-submit:hover {
            background: var(--teal-dark);
            box-shadow: 0 6px 20px rgba(20,184,166,0.4);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        .signin-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .signin-link a {
            color: var(--teal);
            font-weight: 600;
            text-decoration: none;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 680px) {
            .left-panel { display: none; }
            .right-panel { padding: 36px 24px; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="wrapper">

        <!-- Left panel -->
        <div class="left-panel">
            <div class="brand">
                <div class="brand-logo">Q<em>line</em></div>
                <div class="brand-tagline">WhatsApp Queue Management</div>
            </div>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">📱</div>
                    <div class="feature-text">
                        <strong>WhatsApp-first</strong>
                        <span>Customers join via WhatsApp — no app needed</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📺</div>
                    <div class="feature-text">
                        <strong>Live TV display</strong>
                        <span>Show queue status on any screen in real-time</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">⚡</div>
                    <div class="feature-text">
                        <strong>Instant notifications</strong>
                        <span>Customers get notified when their turn is near</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">
                        <strong>Analytics built-in</strong>
                        <span>Track wait times, peak hours, and feedback</span>
                    </div>
                </div>
            </div>

            <div class="left-footer">© 2026 QLine · All rights reserved</div>
        </div>

        <!-- Right panel -->
        <div class="right-panel">

            <div class="form-header">
                <h1>Create your account</h1>
                <p>Set up your business queue in under 2 minutes</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 11v.5"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="section-label">Your Business</div>

                <div class="field">
                    <label>Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="e.g. Klinik Ahmad" required />
                    @error('business_name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Business Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0123456789" />
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="Kuantan" />
                    </div>
                    <div class="field">
                        <label>State</label>
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="Pahang" />
                    </div>
                </div>

                <div class="divider"></div>

                <div class="section-label">Your Account</div>

                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ahmad bin Ali" required />
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="ahmad@example.com" required />
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Min 8 characters" required />
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password" required />
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Create My Business
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8h10M9 4l4 4-4 4"/>
                    </svg>
                </button>
            </form>

            <div class="signin-link">
                Already have an account? <a href="/business">Sign in</a>
            </div>

        </div>

    </div>

</body>
</html>