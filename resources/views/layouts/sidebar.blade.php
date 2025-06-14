<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MySebenarnya') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: 100vh;
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            margin: 0.2rem 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .sidebar-brand {
            padding: 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-brand:hover {
            color: white;
        }
        
        .sidebar-brand i {
            font-size: 1.5rem;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0.5rem 1rem;
        }
        
        .sidebar-user {
            padding: 1rem;
            color: white;
            display: flex;
            align-items: center;
        }
        
        .sidebar-user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.75rem;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-user-info {
            flex: 1;
        }
        
        .sidebar-user-name {
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .sidebar-user-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            transition: all 0.3s;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
            
            .sidebar-toggle {
                display: block !important;
            }
        }
        
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 999;
            background-color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        
        /* Role-specific colors */
        .bg-mcmc {
            background-color: #e74a3b !important;
        }
        
        .bg-agency {
            background-color: #1cc88a !important;
        }
        
        .bg-public {
            background-color: #4e73df !important;
        }
        
        .btn-mcmc {
            background-color: #e74a3b;
            border-color: #e74a3b;
            color: white;
        }
        
        .btn-mcmc:hover {
            background-color: #c23321;
            border-color: #c23321;
            color: white;
        }
        
        .btn-agency {
            background-color: #1cc88a;
            border-color: #1cc88a;
            color: white;
        }
        
        .btn-agency:hover {
            background-color: #169a6b;
            border-color: #169a6b;
            color: white;
        }
        
        .btn-public {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
        }
        
        .btn-public:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
            color: white;
        }
        
        /* Card styles */
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Table styles */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            font-weight: 700;
            color: #5a5c69;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }
        
        /* Status badges */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 0.25rem;
        }
        
        .badge-pending {
            background-color: #f6c23e;
            color: #fff;
        }
        
        .badge-validated {
            background-color: #4e73df;
            color: #fff;
        }
        
        .badge-assigned {
            background-color: #36b9cc;
            color: #fff;
        }
        
        .badge-resolved {
            background-color: #1cc88a;
            color: #fff;
        }
        
        .badge-rejected {
            background-color: #e74a3b;
            color: #fff;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div id="app">
        <!-- Sidebar Toggle Button (Mobile) -->
        <div class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </div>
        
        <!-- Sidebar -->
        <nav class="sidebar 
            @if(Auth::user()->hasRole('mcmc'))
                bg-mcmc
            @elseif(Auth::user()->hasRole('agency'))
                bg-agency
            @else
                bg-public
            @endif
        ">
            <div class="sidebar-sticky">
                <!-- Sidebar - Brand -->
                <a href="{{ url('/') }}" class="sidebar-brand">
                    <span>MySebenarnya</span>
                    <i class="fas fa-check-circle"></i>
                </a>
                
                <!-- Divider -->
                <hr class="sidebar-divider">
                
                <!-- User Info -->
                <div class="sidebar-user">
                    <div class="sidebar-user-img">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="sidebar-user-info">
                        <p class="sidebar-user-name">{{ Auth::user()->name }}</p>
                        <p class="sidebar-user-role">
                            @if(Auth::user()->hasRole('mcmc'))
                                MCMC Admin
                            @elseif(Auth::user()->hasRole('agency'))
                                Agency User
                            @else
                                Public User
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Divider -->
                <hr class="sidebar-divider">
                
                <!-- Nav Items -->
                @if(Auth::user()->hasRole('mcmc'))
                    <!-- MCMC Navigation -->
                    <div class="sidebar-heading">Main</div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mcmc.dashboard') ? 'active' : '' }}" href="{{ route('mcmc.dashboard') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mcmc.inquiries.*') ? 'active' : '' }}" href="{{ route('mcmc.inquiries.index') }}">
                                <i class="fas fa-fw fa-question-circle"></i>
                                Inquiries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mcmc.assignments.*') ? 'active' : '' }}" href="{{ route('mcmc.assignments.index') }}">
                                <i class="fas fa-fw fa-tasks"></i>
                                Assignments
                            </a>
                        </li>
                    </ul>
                    
                    <div class="sidebar-heading">Management</div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-fw fa-users"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('agencies.*') ? 'active' : '' }}" href="{{ route('agencies.index') }}">
                                <i class="fas fa-fw fa-building"></i>
                                Agencies
                            </a>
                        </li>
                    </ul>
                    
                    <div class="sidebar-heading">Reports</div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-fw fa-file-alt"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('charts.*') ? 'active' : '' }}" href="{{ route('charts.inquiries') }}">
                                <i class="fas fa-fw fa-chart-bar"></i>
                                Charts
                            </a>
                        </li>
                    </ul>
                @elseif(Auth::user()->hasRole('agency'))
                    <!-- Agency Navigation -->
                    <div class="sidebar-heading">Main</div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('agency.dashboard') ? 'active' : '' }}" href="{{ route('agency.dashboard') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('agency.assignments.*') ? 'active' : '' }}" href="{{ route('agency.assignments.index') }}">
                                <i class="fas fa-fw fa-tasks"></i>
                                Assignments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('agency.inquiries.*') ? 'active' : '' }}" href="{{ route('agency.inquiries.index') }}">
                                <i class="fas fa-fw fa-question-circle"></i>
                                Inquiries
                            </a>
                        </li>
                    </ul>
                @else
                    <!-- Public User Navigation -->
                    <div class="sidebar-heading">Main</div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.dashboard') ? 'active' : '' }}" href="{{ route('public.dashboard') }}">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.inquiries.index') ? 'active' : '' }}" href="{{ route('public.inquiries.index') }}">
                                <i class="fas fa-fw fa-list"></i>
                                My Inquiries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('public.inquiries.create') ? 'active' : '' }}" href="{{ route('public.inquiries.create') }}">
                                <i class="fas fa-fw fa-plus-circle"></i>
                                Submit Inquiry
                            </a>
                        </li>
                    </ul>
                @endif
                
                <div class="sidebar-heading">Account</div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                            <i class="fas fa-fw fa-user-circle"></i>
                            Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-fw fa-sign-out-alt"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Heading -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">@yield('heading', 'Dashboard')</h1>
                @yield('heading_buttons')
            </div>
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
    </script>
    
    @yield('scripts')
</body>
</html>