<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DoQueue' }} | DoQueue</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">D</span>
                <span>DoQueue</span>
            </a>
            <nav class="main-nav" aria-label="Main navigation">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">My Tasks</a>
                <a class="{{ request()->routeIs('progress-board') ? 'active' : '' }}" href="{{ route('progress-board') }}">Progress Board</a>
                <a class="notification-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                    <span class="bell-icon" aria-hidden="true">!</span> Notifications
                    @if($unreadNotifications > 0)<span class="notification-count">{{ $unreadNotifications }}</span>@endif
                </a>
            </nav>
            <a class="button button-primary add-button" href="{{ route('tasks.create') }}"><span aria-hidden="true">+</span> Add Task</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-button" type="submit">Log out</button></form>
        </div>
    </header>

    <main class="container page-content">
        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="flash flash-error">
                <strong>Please check the form.</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
