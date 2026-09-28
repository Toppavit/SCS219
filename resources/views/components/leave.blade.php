<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Leave Management' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('leaves.index') }}">
                <i class="fa-solid fa-calendar-check me-2"></i>Leave Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#leaveNav" aria-controls="leaveNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="leaveNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('leaves.index')]) href="{{ route('leaves.index') }}">ใบลาของฉัน</a></li>
                    <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('leaves.create')]) href="{{ route('leaves.create') }}">ยื่นใบลา</a></li>
                    @can('manage-leaves')
                        <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('leaves.approvals')]) href="{{ route('leaves.approvals') }}">อนุมัติใบลา</a></li>
                    @endcan
                    <li class="nav-item"><a class="nav-link" href="{{ route('weights.index') }}">Weights</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about-me') }}">About Me</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-white-50 small">
                        {{ Auth::user()->name }}
                        <span class="badge text-bg-light ms-1">{{ Auth::user()->isManager() ? 'Manager' : 'Employee' }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">
                            <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
