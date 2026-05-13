<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QAMS - @yield('title', 'Quiz & Assignment Management System')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Modern Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            background-color: #f4f7f6;
            font-family: 'Inter', sans-serif;
            color: #334155;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 15px;
            z-index: 1040;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar .sidebar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            padding: 10px 15px 22px;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        .sidebar a {
            color: #94a3b8;
            text-decoration: none;
            display: block;
            padding: 11px 18px;
            border-radius: 10px;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.92rem;
            transition: all 0.25s ease;
            white-space: nowrap;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar hr {
            border-color: rgba(255,255,255,0.1);
            margin: 18px 0;
        }
        .logout-btn {
            color: #f87171 !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            padding: 11px 18px;
            font-size: 0.92rem;
        }
        .logout-btn:hover {
            background: rgba(248,113,113,0.12);
            color: #ef4444 !important;
        }

        /* ── Sidebar Overlay (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main Content ── */
        .main-content {
            margin-left: 260px;
            padding: 24px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ── Top Bar ── */
        .top-bar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            flex-wrap: wrap;
            gap: 10px;
        }
        .top-bar strong {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .user-pill {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 999px;
            padding: 5px 14px 5px 8px;
            border: 1px solid #e2e8f0;
            gap: 8px;
        }
        .user-pill .fw-semibold { font-size: 0.88rem; }

        /* ── Hamburger Toggle ── */
        .sidebar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            color: #334155;
            font-size: 1.1rem;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .sidebar-toggle:hover { background: #e2e8f0; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        /* ── Alerts ── */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        }
        .badge-late { background: #ef4444; }

        /* ── Tooltip ── */
        .tooltip .tooltip-inner {
            background-color: #1a1a2e;
            color: aliceblue;
            font-family: 'Inter', sans-serif;
            font-size: 0.77rem;
            font-weight: 700;
            padding: 6px 13px;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
            letter-spacing: 0.4px;
        }
        .tooltip.bs-tooltip-top .tooltip-arrow::before    { border-top-color:    #1a1a2e; }
        .tooltip.bs-tooltip-bottom .tooltip-arrow::before { border-bottom-color: #1a1a2e; }
        .tooltip.bs-tooltip-start .tooltip-arrow::before  { border-left-color:   #1a1a2e; }
        .tooltip.bs-tooltip-end .tooltip-arrow::before    { border-right-color:  #1a1a2e; }

        /* ── Table Improvements ── */
        .table-responsive { border-radius: 14px; overflow: hidden; }
        .table { margin-bottom: 0; font-size: 0.9rem; }
        .table th { font-weight: 600; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.4px; }

        /* ── Responsive Breakpoints ── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                padding: 16px;
            }
            .sidebar-toggle {
                display: flex;
            }
            .top-bar strong {
                font-size: 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .main-content { padding: 12px; }
            .top-bar { padding: 10px 14px; border-radius: 10px; }
            .top-bar strong { font-size: 0.9rem; }
            .user-pill .fw-semibold { display: none; }
            .top-bar-right .btn-sm { padding: 4px 8px; font-size: 0.78rem; }
        }

        /* ── Form Responsive Fixes ── */
        .form-card-wrap { width: 100%; max-width: 700px; }
        .form-card-wrap-sm { width: 100%; max-width: 520px; }

        @media (max-width: 767.98px) {
            .form-card-wrap, .form-card-wrap-sm { max-width: 100%; }
            .d-flex.gap-4 { gap: 12px !important; }
        }

        /* ── Stats Card ── */
        .stat-card h2 { font-size: 1.8rem; }
        @media (max-width: 575.98px) {
            .stat-card h2 { font-size: 1.4rem; }
            .stat-card p  { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-mortarboard-fill me-2 text-primary"></i>QAMS
    </div>

    @yield('sidebar')

    <hr>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-link logout-btn w-100 text-start p-0">
            <i class="bi bi-box-arrow-left me-2"></i>Logout
        </button>
    </form>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="d-flex align-items-center gap-2">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" aria-label="Toggle Menu">
                <i class="bi bi-list"></i>
            </button>
            <strong>@yield('page-title')</strong>
        </div>

        <div class="top-bar-right">
            <a href="{{ route('password.change') }}"
               class="btn btn-sm btn-light border"
               data-bs-toggle="tooltip" title="Change Password"
               style="border-radius: 8px; font-weight: 500;">
                <i class="bi bi-lock me-1"></i><span class="d-none d-sm-inline">Password</span>
            </a>

            <div class="user-pill">
                @if(auth()->user()->student && auth()->user()->student->picture)
                    <img src="{{ Storage::url(auth()->user()->student->picture) }}"
                         class="rounded-circle"
                         style="width:28px; height:28px; object-fit:cover;">
                @else
                    <i class="bi bi-person-circle text-primary" style="font-size:1.3rem;"></i>
                @endif
                <span class="fw-semibold">{{ auth()->user()->name }}</span>
                <span class="badge bg-primary rounded-pill" style="font-size:0.72rem;">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3">
            <i class="bi bi-check-circle-fill me-2 fs-5 flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start mb-3">
            <i class="bi bi-x-circle-fill me-2 fs-5 mt-1 flex-shrink-0"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    function toggleSidebar() {
        var sidebar  = document.getElementById('sidebar');
        var overlay  = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // Close sidebar on resize to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });

    // Tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el, { trigger: 'hover', delay: { show: 200, hide: 100 } });
        });
    });
</script>
@yield('scripts')
</body>
</html>
