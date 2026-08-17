<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My Website' }}</title>
</head>
<body>
    <!-- Navbar (ส่วนของ Active Menu จาก Quiz 2 และสไลด์หน้า 100) -->
    <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
            <li><a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'active' : '' }}">Portfolio</a></li>
            <li><a href="{{ route('team') }}" class="{{ request()->routeIs('team') ? 'active' : '' }}">Team</a></li>
            <li><a href="{{ route('blog') }}" class="{{ request()->routeIs('blog') ? 'active' : '' }}">Blog</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
        </ul>
    </nav>

    <!-- Content Slot (ส่วนที่จะถูกเปลี่ยนตามแต่ละหน้า) -->
    <main>
        {{ $slot }}
    </main>
</body>
</html>
