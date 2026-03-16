<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap" rel="stylesheet">
    <title>Get Started — QLine</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0fdfa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 4px 32px rgba(20, 184, 166, 0.1), 0 1px 4px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(20, 184, 166, 0.12);
        }

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-mark {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .logo-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 2rem;
        }

        .logo-name em {
            font-style: normal;
            color: #14B8A6;
        }

        .logo-tagline {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Heading */
        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
        }

        .page-sub {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 24px;
        }

        /* Alert */
        .alert-error {
            background: rgba(239, 68, 68, 0.06);
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 0.5px solid rgba(239, 68, 68, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Section label */
        .section-label {
            font-size: 10px;
            font-weight: 700;
            color: #14B8A6;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 0.5px;
            background: rgba(20, 184, 166, 0.15);
        }

        /* Fields */
        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .field input {
            width: 100%;
            padding: 10px 13px;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            border-radius: 9px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #fff;
        }

        .field input:focus {
            border-color: #14B8A6;
            box-shadow: 0 0 0 2.5px rgba(20, 184, 166, 0.15);
        }

        .field input::placeholder {
            color: #d1d5db;
        }

        .field .error {
            font-size: 11px;
            color: #b91c1c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .divider {
            height: 0.5px;
            background: rgba(0, 0, 0, 0.06);
            margin: 20px 0;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 14px;
            background: #14B8A6;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s, transform 0.1s;
            letter-spacing: 0.01em;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .btn:hover {
            background: #0f9e8e;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #9ca3af;
        }

        .login-link a {
            color: #14B8A6;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="logo">
            <div class="logo-mark">
                <span class="logo-name">Q<em>line</em></span>
            </div>
            <div class="logo-tagline">WhatsApp Queue Management</div>
        </div>

        <div class="page-title">Get started</div>
        <div class="page-sub">Create your business account</div>

        @if ($errors->any())
            <div class="alert-error">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="#b91c1c" stroke-width="2"
                    stroke-linecap="round">
                    <circle cx="8" cy="8" r="6" />
                    <path d="M8 5v3M8 11v.5" />
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="section-label">Your business</div>

            <div class="field">
                <label>Business name</label>
                <input type="text" name="business_name" value="{{ old('business_name') }}"
                    placeholder="e.g. Klinik Ahmad" required />
                @error('business_name')
                    <div class="error">
                        <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <circle cx="8" cy="8" r="6" />
                            <path d="M8 5v3M8 11v.5" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="field">
                <label>Business Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0123456789" />
            </div>

            <div class="grid">
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

            <div class="section-label">Your account</div>

            <div class="field">
                <label>Full name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ahmad bin Ali" required />
                @error('name')
                    <div class="error">
                        <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <circle cx="8" cy="8" r="6" />
                            <path d="M8 5v3M8 11v.5" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ahmad@example.com"
                    required />
                @error('email')
                    <div class="error">
                        <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <circle cx="8" cy="8" r="6" />
                            <path d="M8 5v3M8 11v.5" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min 8 characters" required />
                    @error('password')
                        <div class="error">
                            <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <circle cx="8" cy="8" r="6" />
                                <path d="M8 5v3M8 11v.5" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="field">
                    <label>Confirm password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password" required />
                </div>
            </div>

            <button type="submit" class="btn">
                Create my business
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="white"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 8h8M9 5l3 3-3 3" />
                </svg>
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="/business">Sign in</a>
        </div>

    </div>

</body>

</html>
