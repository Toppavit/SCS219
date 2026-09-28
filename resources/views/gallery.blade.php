<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | SCS219</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('about-me') }}">SCS219 / Gallery</a>
            <div class="d-flex gap-3">
                <a class="nav-link text-white" href="{{ route('about-me') }}">About Me</a>
                @auth
                    <a class="nav-link text-white" href="{{ route('weights.index') }}">Weights</a>
                @else
                    <a class="btn btn-sm btn-light" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- EP02 Hero --}}
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
                <img src="https://picsum.photos/seed/scs219-hero/700/500" class="d-block mx-lg-auto img-fluid rounded-3 shadow" alt="Hero image" width="700" height="500" loading="lazy">
            </div>
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">แกลเลอรีผลงาน</h1>
                <p class="lead">รวมภาพและผลงานจากรายวิชา SCS219 การพัฒนาโปรแกรมประยุกต์บนเว็บสำหรับองค์กร</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="#gallery" class="btn btn-primary btn-lg px-4 me-md-2">ดูแกลเลอรี</a>
                    <a href="{{ route('about-me') }}" class="btn btn-outline-secondary btn-lg px-4">About Me</a>
                </div>
            </div>
        </div>
    </div>

    <div id="gallery" class="bg-body-tertiary py-5">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @foreach (range(1, 6) as $i)
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="https://picsum.photos/seed/scs219-{{ $i }}/600/400" class="card-img-top" alt="Gallery image {{ $i }}" width="600" height="400" loading="lazy">
                            <div class="card-body">
                                <p class="card-text mb-0">ภาพที่ {{ $i }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
