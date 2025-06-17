<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MySebenarnya - MCMC Inquiry Management System</title>
    
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
            color: #333;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        
        .hero-title {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-weight: 300;
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        
        .feature-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .feature-text {
            color: #6c757d;
        }
        
        .cta-section {
            background-color: #f1f5fe;
            padding: 80px 0;
            margin: 50px 0;
        }
        
        .btn-public {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 5px;
        }
        
        .btn-public:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
            color: white;
        }
        
        .btn-mcmc {
            background-color: #e74a3b;
            border-color: #e74a3b;
            color: white;
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 5px;
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
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 5px;
        }
        
        .btn-agency:hover {
            background-color: #169a6b;
            border-color: #169a6b;
            color: white;
        }
        
        .footer {
            background-color: #222;
            color: #f8f9fa;
            padding: 50px 0 20px;
        }
        
        .footer-title {
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-icons {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #adb5bd;
            transition: color 0.3s;
        }
        
        .social-icons:hover {
            color: white;
        }
        
        .copyright {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #444;
            color: #adb5bd;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-public">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <strong>MySebenarnya</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->hasRole('mcmc'))
                                <li class="nav-item">
                                    <a href="{{ route('mcmc.dashboard') }}" class="nav-link">Dashboard</a>
                                </li>
                            @elseif(Auth::user()->hasRole('agency'))
                                <li class="nav-item">
                                    <a href="{{ route('agency.dashboard') }}" class="nav-link">Dashboard</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('public.dashboard') }}" class="nav-link">Dashboard</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="hero-title">Welcome to MySebenarnya</h1>
            <p class="hero-subtitle">The official MCMC Inquiry Management System</p>
            <div class="d-flex justify-content-center gap-3">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->hasRole('mcmc'))
                            <a href="{{ route('mcmc.dashboard') }}" class="btn btn-light btn-lg">Go to Dashboard</a>
                        @elseif(Auth::user()->hasRole('agency'))
                            <a href="{{ route('agency.dashboard') }}" class="btn btn-light btn-lg">Go to Dashboard</a>
                        @else
                            <a href="{{ route('public.dashboard') }}" class="btn btn-light btn-lg">Go to Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </section>

    <!-- User Roles Section -->
    <section class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">User Roles</h2>
            <p class="text-muted">Different access levels for different stakeholders</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title">Public Users</h4>
                        <p class="card-text">Submit inquiries about online content and track their status. Public users can register directly through the platform.</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mt-3">Register as Public User</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="fas fa-building fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title">Agency Users</h4>
                        <p class="card-text">Government agencies that respond to assigned inquiries. Agency accounts are created by MCMC administrators.</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-success mt-3">Login as Agency</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="icon-box mb-3">
                            <i class="fas fa-user-shield fa-3x text-danger"></i>
                        </div>
                        <h4 class="card-title">MCMC Administrators</h4>
                        <p class="card-text">Manage the entire system, validate inquiries, assign them to agencies, and generate reports.</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-danger mt-3">Login as MCMC</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Platform Features</h2>
            <p class="text-muted">Streamlining the inquiry process for better communication and resolution</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-public">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <h4 class="feature-title">Submit Inquiries</h4>
                        <p class="feature-text">Easily submit your inquiries and track their progress through our intuitive interface.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-agency">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4 class="feature-title">Agency Collaboration</h4>
                        <p class="feature-text">Agencies can efficiently manage and respond to assigned inquiries.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-mcmc">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="feature-title">MCMC Oversight</h4>
                        <p class="feature-text">Comprehensive management and reporting tools for MCMC administrators.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Ready to Get Started?</h2>
            <p class="mb-4">Join our platform today and experience a streamlined inquiry management process.</p>
            <div class="d-flex justify-content-center gap-3">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->hasRole('mcmc'))
                            <a href="{{ route('mcmc.dashboard') }}" class="btn btn-public btn-lg">Go to Dashboard</a>
                        @elseif(Auth::user()->hasRole('agency'))
                            <a href="{{ route('agency.dashboard') }}" class="btn btn-public btn-lg">Go to Dashboard</a>
                        @else
                            <a href="{{ route('public.dashboard') }}" class="btn btn-public btn-lg">Go to Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-public btn-lg">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">MySebenarnya</h5>
                    <p>The official MCMC Inquiry Management System designed to streamline communication between the public, agencies, and MCMC.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icons"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-icons"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icons"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icons"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="footer-title">Contact</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> MCMC Headquarters, Cyberjaya</li>
                        <li><i class="fas fa-phone me-2"></i> +60 3-8688 8000</li>
                        <li><i class="fas fa-envelope me-2"></i> info@mysebenarnya.gov.my</li>
                    </ul>
                </div>
            </div>
            
            <div class="text-center copyright">
                <p>&copy; {{ date('Y') }} MySebenarnya. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
