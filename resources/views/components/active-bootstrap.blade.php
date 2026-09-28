@props(['title' => 'Active Bootstrap'])

@php
    $menu = [
        'index' => 'Home',
        'about' => 'About',
        'services' => 'Services',
        'portfolio' => 'Portfolio',
        'team' => 'Team',
        'blog' => 'Blog',
        'contact' => 'Contact',
    ];
@endphp

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | SCS219</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav id="navmenu" class="navbar navbar-expand-lg navbar-dark bg-dark navmenu">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('index') }}">EP03 Active</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#activeNav" aria-controls="activeNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="activeNav">
                <ul class="navbar-nav ms-auto">
                    @foreach ($menu as $name => $label)
                        <li class="nav-item">
                            <a href="{{ route($name) }}" @class(['nav-link', 'active' => request()->routeIs($name)]) @if (request()->routeIs($name)) aria-current="page" @endif>{{ $label }}</a>
                        </li>
                    @endforeach
                    <li class="nav-item"><a class="nav-link" href="{{ route('about-me') }}">About Me</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
