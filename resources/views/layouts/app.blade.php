<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Issue Tracker')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>
<body>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar p-3" id="appSidebar">
    <div class="mb-4">
        <span class="fs-5 fw-semibold text-dark">Issue Tracker</span>
    </div>

    <nav class="nav nav-pills flex-column gap-1">
        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}"
           href="{{ route('projects.index') }}">Projects</a>

        <a class="nav-link {{ request()->routeIs('issues.*') ? 'active' : '' }}"
           href="{{ route('issues.index') }}">Issues</a>

        <a class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}"
           href="{{ route('tags.index') }}">Tags</a>
    </nav>

    <div class="mt-auto border-top pt-2">
        <div class="d-flex align-items-center justify-content-between px-1 py-2">
            <div class="small text-muted">{{ auth()->user()->name }}</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary-custom">
                    Log out
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="main-shell">
    <header class="bg-white border-bottom px-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="mobile-menu-btn" id="sidebarToggle" aria-label="Open menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M4 6H20M4 12H20M4 18H20"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linecap="round"/>
                </svg>
            </button>

            <h1 class="fs-5 fw-semibold mb-0 text-dark">@yield('header', 'Dashboard')</h1>
        </div>
    </header>

    <main class="p-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');
        const sidebarLinks = document.querySelectorAll('#appSidebar .nav-link');

        function openSidebar() {
            document.body.classList.add('sidebar-open');
        }

        function closeSidebar() {
            document.body.classList.remove('sidebar-open');
        }

        toggle?.addEventListener('click', openSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        sidebarLinks.forEach(link => {
            link.addEventListener('click', closeSidebar);
        });
    });
</script>

@yield('scripts')
</body>

</html>
