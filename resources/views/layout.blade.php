<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Homecare Center for Children')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>
    <style>
        :root {
            --primary-color: #7AE2CF;
            --secondary-color: #4AC7B0;
            --accent-color: #2A8C7D;
            --text-color: #1A3A34;
            --light-bg: #F0F9F7;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        .top-bar {
            background-color:#7AE2CF;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo-img {
            height: 50px;
            object-fit: contain;
        }

        .center-title {
            font-size: 1.3rem;
            font-weight: 300;
            color: black;
            margin: 0;
            white-space: nowrap;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
            font-family: 'Arial', sans-serif;
        }

        .notification-badge {
            position: relative;
            cursor: pointer;
            color: var(--text-color);
            font-size: 1.2rem;
            padding: 4px;
            &::before,
            &::after {
                content: none !important;
                display: none !important;
            }
        }

        .notification-badge i {
            color: var(--text-color) !important;
            font-size: 1.2rem !important;
        }

        .btn-logout {
            background-color: transparent;
            border: 1px solid var(--accent-color);
            color: var(--text-color);
            padding: 0.4rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-logout i {
            color: var(--text-color) !important;
            font-size: 0.9rem !important;
        }

        .btn-logout:hover {
            background-color: var(--accent-color);
            color: white;
        }

        .sidebar {
            width: 220px;
            background-color: white;
            height: calc(100vh - 80px);
            position: fixed;
            left: 0;
            top: 80px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 1rem 0;
            overflow-y: auto;
        }

        .sidebar-button {
            width: 100%;
            padding: 0.75rem 1rem;
            border: none;
            background: none;
            text-align: left;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .sidebar-button:hover {
            background-color: var(--primary-color);
            color: var(--text-color);
        }

        .sidebar-button.active {
            background-color: var(--primary-color);
            color: var(--text-color);
            font-weight: bold;
        }

        .sidebar-button i {
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: calc(80vh - 100px);
            background-color: var(--light-bg);
            margin-top: 80px;
            padding-bottom: 80px;
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 1rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 250px;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 2rem;
            align-items: center;
            z-index: 1000;
        }

        .footer img {
            height: 40px;
            object-fit: contain;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white;
        }

        .card-header {
            background-color: var(--primary-color);
            color: var(--text-color);
            border-bottom: none;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .table thead th {
            background-color: var(--primary-color);
            color: var(--text-color);
            border-bottom: 2px solid var(--secondary-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(122, 226, 207, 0.25);
        }
    </style>
    @yield('styles')
</head>
<body>
    <header class="top-bar">
        <div class="d-flex align-items-center gap-1 text-center">
            <img src="{{ asset('images/logo2.png') }}" alt="logo2 logo"
                 style="max-height: 70px; max-width: 90px; object-fit: contain; margin-left: -20px;">
            <h1 class="center-title display-6 fw-bold m-0">HOMECARE CENTER FOR CHILDREN</h1>
            <img src="{{ asset('images/DSWD.png') }}" alt="DSWD logo"
                 style="max-height: 90px; max-width: 100px; object-fit: contain; margin-left: -15px;">
        </div>
        <div class="d-flex align-items-center gap-3">
            @include('components.notification-dropdown')
            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0 p-0">
                @csrf
                <button type="submit" class="btn-logout d-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <div class="sidebar">
        @cannot('It')
        <a href="{{ route('dashboard') }}" class="sidebar-button {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="{{ route('clients.view') }}" class="sidebar-button {{ request()->is('clients*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Clients
        </a>
        <a href="{{ route('calendar.index') }}" class="sidebar-button {{ (request()->is('calendar*') || request()->is('hearings*')) ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Hearing
        </a>
        <a href="{{ route('events.index') }}" class="sidebar-button {{ (request()->routeIs('events.*') || request()->routeIs('incidents.*')) ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Events
        </a>
        @else
        
        <a href="{{ route('admin.access') }}" class="sidebar-button {{ request()->routeIs('admin.access') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Access
        </a>
        @endcannot

        @can('isAdmin')
        <a href="{{ route('admin.logs') }}" class="sidebar-button {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Logs
        </a>
        <a href="{{ route('admin.report') }}" class="sidebar-button {{ request()->routeIs('admin.report') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
        @endcan
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer (3-column layout: mission, 500 years logo, vision) -->
    <footer class="py-3" style="background: #7AE2CF; border-top: 1px solid #e0e0e0; margin-left:220px;">
        <div class="container">
            <div class="row align-items-center" style="min-height: 1px;">
                <div class="col-md-4 text-center text-md-left">
                    <h6 class="fw-bold mb-2">Mission</h6>
                    <p class="mb-0 small">
                        Homecare Center for Children shall strive to empower admitted clients by providing immediate custodial care, collaborative intervention process ready for his reintegration to his family and community as well.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="{{ asset('images/500_years.png') }}" alt="500 Years Logo" style="height: 130px; width: auto;">
                </div>
                <div class="col-md-4 text-center text-md-right">
                    <h6 class="fw-bold mb-2">Vision</h6>
                    <p class="mb-0 small">
                        Active collaboration is transparently achieved through cooperation and networking to enhance total development and transfonnation of clients.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('styles')
    @stack('scripts')
</body>
</html>



