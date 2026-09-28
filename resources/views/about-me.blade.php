<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me | SCS219</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f5f7fb; color: #17263c; font-family: system-ui, sans-serif; line-height: 1.6; }
        a { color: #145ac6; text-underline-offset: 4px; }
        a:focus-visible { outline: 3px solid #145ac6; outline-offset: 5px; }
        .navigation { background: #0d6efd; color: white; }
        .nav-inner { max-width: 960px; margin: auto; padding: 20px 24px; display: flex; justify-content: space-between; gap: 24px; align-items: center; }
        .navigation a { color: white; }
        .brand { font-weight: 700; letter-spacing: .04em; }
        main { max-width: 960px; margin: 64px auto; padding: 0 24px; }
        .eyebrow { color: #466286; font-size: .8rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 8px 0; font-size: clamp(2rem, 6vw, 3.25rem); line-height: 1.2; }
        .intro { margin: 12px 0 32px; color: #53647c; }
        .profile { display: grid; grid-template-columns: 240px 1fr; gap: 40px; padding: 40px; background: white; border: 1px solid #e0e6ef; border-radius: 20px; box-shadow: 0 12px 36px #17263c08; }
        .portrait, .photo-placeholder { width: 100%; aspect-ratio: 1; border-radius: 16px; }
        .portrait { object-fit: cover; }
        .photo-placeholder { display: grid; place-content: center; gap: 12px; text-align: center; background: #edf3fc; color: #526b90; border: 1px dashed #b3c5df; }
        .photo-placeholder svg { width: 72px; height: 72px; margin: auto; }
        h2 { margin: 0 0 24px; font-size: 1.4rem; }
        dl { margin: 0; }
        dt { font-size: .85rem; color: #53647c; margin-top: 20px; }
        dd { margin: 4px 0 0; font-size: 1.2rem; overflow-wrap: anywhere; }
        .pending { color: #6a7586; }
        .works { margin-top: 40px; }
        .works ul { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
        .works a { display: block; padding: 16px 20px; background: white; border: 1px solid #e0e6ef; border-radius: 12px; text-decoration: none; }
        .works a:hover { border-color: #145ac6; }
        .works small { display: block; color: #64748b; }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links button { background: none; border: 1px solid white; border-radius: 6px; color: white; padding: 4px 12px; font: inherit; cursor: pointer; }
        footer { margin-top: 28px; color: #64748b; font-size: .85rem; }
        @media (max-width: 640px) {
            main { margin-top: 36px; }
            .profile { grid-template-columns: 1fr; padding: 24px; gap: 28px; }
            .portrait, .photo-placeholder { max-width: 240px; margin: auto; }
        }
    </style>
</head>
<body>
    <nav class="navigation" aria-label="เมนูหลัก">
        <div class="nav-inner">
            <span class="brand">SCS219 / PROJECT</span>
            <div class="nav-links">
                <a href="{{ route('weights.index') }}">กลับหน้าหลัก</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </nav>
    <main>
        <div class="eyebrow">Student profile</div>
        <h1>เกี่ยวกับฉัน <span lang="en">/ About Me</span></h1>
        <p class="intro">ข้อมูลส่วนตัวสำหรับรายวิชา SCS219 การพัฒนาโปรแกรมประยุกต์บนเว็บสำหรับองค์กร</p>
        <section class="profile" aria-labelledby="profile-heading">
            @if (config('profile.photo'))
                <img class="portrait" src="{{ asset(config('profile.photo')) }}" alt="รูปภาพของ {{ config('profile.name') }}" width="240" height="240">
            @else
                <div class="photo-placeholder" role="img" aria-label="ยังไม่ได้เพิ่มรูปภาพนักศึกษา">
                    <svg viewBox="0 0 80 80" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                        <circle cx="40" cy="26" r="13" />
                        <path d="M15 68v-5a25 25 0 0 1 50 0v5" />
                    </svg>
                    <span>รอเพิ่มรูปภาพ</span>
                </div>
            @endif
            <div>
                <h2 id="profile-heading">ข้อมูลนักศึกษา</h2>
                <dl>
                    <dt>ชื่อ–นามสกุล / Full name</dt>
                    <dd @class(['pending' => ! config('profile.name')])>{{ config('profile.name') ?: 'รอเพิ่มชื่อ–นามสกุล' }}</dd>
                    <dt>รหัสนักศึกษา / Student ID</dt>
                    <dd @class(['pending' => ! config('profile.student_id')])>{{ config('profile.student_id') ?: 'รอเพิ่มรหัสนักศึกษา' }}</dd>
                    <dt>รายวิชา / Course</dt>
                    <dd>SCS219</dd>
                </dl>
            </div>
        </section>
        <section class="works" aria-labelledby="works-heading">
            <h2 id="works-heading">ผลงานที่ผ่านมา <span lang="en">/ Previous work</span></h2>
            <ul>
                <li><a href="{{ route('gallery') }}">Gallery<small>EP02 Hero section</small></a></li>
                <li><a href="{{ route('index') }}">Active Bootstrap<small>EP03 active menu</small></a></li>
                <li><a href="{{ route('weights.index') }}">Weight Tracker<small>ต้องเข้าสู่ระบบ</small></a></li>
                <li><a href="{{ route('barchart') }}">Bar Chart<small>Google Charts</small></a></li>
                <li><a href="{{ url('query/orm') }}">Query Test<small>SQL / Builder / Eloquent</small></a></li>
            </ul>
        </section>
        <footer>SCS219 · About Me · <a href="{{ route('welcome') }}">Laravel Welcome</a></footer>
    </main>
</body>
</html>
