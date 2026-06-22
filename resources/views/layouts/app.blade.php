<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Issue Tracker')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>
<body>
<div class="d-flex">


    <aside class="sidebar p-3">
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
    </aside>

    {{-- Main content --}}
    <div class="flex-grow-1">
        <header class="bg-white border-bottom px-4 py-3">
            <h1 class="fs-5 fw-semibold mb-0 text-dark">@yield('header', 'Dashboard')</h1>
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

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
