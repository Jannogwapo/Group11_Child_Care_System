<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Homecare Center for Children')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .header {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-color);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar {
            width: 250px;
            background-color: white;
            height: calc(100vh - 60px);
            position: fixed;
            left: 0;
            top: 60px;
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
            min-height: calc(100vh - 120px);
            background-color: var(--light-bg);
            margin-top: 60px;
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

        .notification-badge {
            position: relative;
            cursor: pointer;
            color: var(--text-color);
            font-size: 1.2rem;
            padding: 4px;
        }

        .notification-badge i {
            font-size: 1.2rem;
        }

        .notification-badge::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background-color: var(--accent-color);
            border-radius: 50%;
        }

        .btn-logout {
            background-color: transparent;
            border: 1px solid var(--accent-color);
            color: var(--text-color);
            padding: 0.2rem 0.75rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
            top: 7px;
        }

        .btn-logout i {
            font-size: 0.9rem;
        }

        .btn-logout:hover {
            background-color: var(--accent-color);
            color: white;
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

        /* Button styling */
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* Table styling */
        .table thead th {
            background-color: var(--primary-color);
            color: var(--text-color);
            border-bottom: 2px solid var(--secondary-color);
        }

        /* Form styling */
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(122, 226, 207, 0.25);
        }
    </style>
    @yield('styles')
</head>
<body>
    <header class="header">
        <div class="header-title">HOMECARE CENTER FOR CHILDREN</div>
        <div class="header-actions">
            @include('components.notification-dropdown')
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn-logout">
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
                   <a href="{{ route('admin.report') }}" class="sidebar-button {{ request()->routeIs('admin.report') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
        <a href="{{ route('admin.access') }}" class="sidebar-button {{ request()->routeIs('admin.access') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Access
        </a>
        @endcannot
        @can('isAdmin')
        <a href="{{ route('admin.logs') }}" class="sidebar-button {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Logs
        </a>
        @endcan
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        <img src="{{ asset('images/city-logo.png') }}" alt="City Logo">
        <img src="{{ asset('images/500-years.png') }}" alt="500 Years Logo">
        <img src="{{ asset('images/organization-logo.png') }}" alt="Organization Logo">
    </footer>
    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('styles')
    @stack('scripts')
</body>
</html>