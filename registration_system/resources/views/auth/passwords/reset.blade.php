<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/university_portal.css') }}">
</head>
<body style="display:flex; flex-direction:column; min-height:100vh;">
    <div class="login-top-bar"></div>
    <div class="login-content">
        <div class="login-box">
            <div class="login-logo">
                <img src="{{ asset('images/nobgParsulogo.png') }}" alt="Logo">
            </div>
            <h2 class="login-university-name">New Password</h2>
            <p class="login-location">Set a new secure password for your account.</p>

            <div class="login-form">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="login-field">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
                        @error('email')
                            <span style="color:#ef4444; font-size:0.75rem; display:block; margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="password">New Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" autofocus>
                        @error('password')
                            <span style="color:#ef4444; font-size:0.75rem; display:block; margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="password-confirm">Confirm New Password</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="login-btn">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
