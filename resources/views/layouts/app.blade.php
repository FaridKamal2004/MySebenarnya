<<<<<<< HEAD
<!DOCTYPE html>
=======
<!doctype html>
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MCMC Inquiry Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .bg-mcmc {
            background-color: #e74a3b !important;
        }
        
        .bg-agency {
            background-color: #1cc88a !important;
        }
        
        .bg-public {
            background-color: #4e73df !important;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
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
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table thead {
            background-color: #f8f9fa;
        }
        
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid #e9ecef;
        }
    </style>

    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark mb-4 
            @if(Auth::check() && Auth::user()->hasRole('mcmc'))
                bg-mcmc
            @elseif(Auth::check() && Auth::user()->hasRole('agency'))
                bg-agency
            @else
                bg-public
            @endif
        ">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'MCMC Inquiry Management System') }}
=======

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
<<<<<<< HEAD
                        @auth
                            @if(Auth::user()->hasRole('mcmc'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('mcmc.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Inquiries
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('mcmc.inquiries.index') }}">All Inquiries</a></li>
                                        <li><a class="dropdown-item" href="{{ route('mcmc.inquiries.filter') }}">Filter Inquiries</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('mcmc.assignments.index') }}">Assignments</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Management
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('users.index') }}">Users</a></li>
                                        <li><a class="dropdown-item" href="{{ route('agencies.index') }}">Agencies</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Reports
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('reports.index') }}">All Reports</a></li>
                                        <li><a class="dropdown-item" href="{{ route('reports.create') }}">Generate Report</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('charts.inquiries') }}">Inquiry Charts</a></li>
                                        <li><a class="dropdown-item" href="{{ route('charts.assignments') }}">Assignment Charts</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->hasRole('agency'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('agency.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('agency.inquiries.index') }}">Inquiries</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('agency.assignments.index') }}">Assignments</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('public.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('public.inquiries.index') }}">My Inquiries</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('public.inquiries.create') }}">Submit Inquiry</a>
                                </li>
                            @endif
                        @endauth
=======

>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
<<<<<<< HEAD
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i> Profile
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
=======
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

<<<<<<< HEAD
        <main class="container py-4">
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

            @yield('content')
        </main>

        <footer class="footer">
            <div class="container text-center">
                <span class="text-muted">© {{ date('Y') }} MCMC Inquiry Management System. All rights reserved.</span>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
=======
        <main class="py-4">
            @yield('content')
        </main>
    </div>
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
</body>
</html>
