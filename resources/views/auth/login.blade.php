<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Log in | DoQueue</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page"><main class="auth-card"><a class="brand auth-brand" href="{{ route('landing') }}"><span class="brand-mark">D</span><span>DoQueue</span></a><p class="eyebrow">Student workspace</p><h1>Welcome back.</h1><p class="muted">Log in to keep your schoolwork moving.</p>@if($errors->any())<div class="flash flash-error">{{ $errors->first() }}</div>@endif<form class="auth-form" method="POST" action="{{ route('login.store') }}">@csrf<label class="field">Email<input type="email" name="email" value="{{ old('email') }}" required autofocus></label><label class="field">Password<div class="password-field"><input type="password" name="password" required><button type="button" class="password-toggle" data-password-toggle aria-label="Show password"><span class="eye-icon" aria-hidden="true"></span></button></div></label><label class="check-field"><input type="checkbox" name="remember"> Remember me</label><button class="button button-primary" type="submit">Log in</button></form><p class="auth-footer">New to DoQueue? <a href="{{ route('register') }}">Create an account</a></p></main></body>
</html>
