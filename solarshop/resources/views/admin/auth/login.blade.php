<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | {{ site_name() }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:radial-gradient(1200px 600px at 80% -10%, rgba(254,169,1,.25), transparent 60%), linear-gradient(135deg, #061c3f 0%, #0b2a5b 55%, #0d3470 100%);">
    <div class="form-card" style="width:100%;max-width:420px;padding:2.2rem;">
        <div style="text-align:center;margin-bottom:1.6rem;">
            <img src="{{ asset(setting('logo', 'img/logo-wide.png')) }}" alt="{{ site_name() }} logo" style="max-height:64px;margin:0 auto;">
            <h1 style="font-size:1.15rem;color:var(--a-heading);margin-top:.8rem;">{{ site_name() }} Admin</h1>
            <p class="text-muted" style="font-size:.85rem;">Sign in to manage {{ site_name() }}</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">&#10003; {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">&#10007; {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">&#10007; {{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="flex-direction:row;gap:.5rem;align-items:center;font-weight:400;">
                    <input type="checkbox" name="remember" value="1" style="width:auto;"> Remember me
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="padding:.75rem;">Sign In</button>
        </form>

        <p class="text-muted" style="text-align:center;margin-top:1.2rem;font-size:.8rem;">
            Unauthorized access is prohibited and monitored.<br>
            <a href="{{ route('home') }}">← Back to storefront</a>
        </p>
    </div>
</body>
</html>