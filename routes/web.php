<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('mcmc')) {
            return redirect()->route('mcmc.dashboard');
        } elseif (Auth::user()->hasRole('agency')) {
            return redirect()->route('agency.dashboard');
        } else {
            return redirect()->route('public.dashboard');
        }
    }
    return view('welcome');
});

Auth::routes();

// Redirect to dashboard based on role
Route::get('/home', function() {
    if (Auth::check()) {
        if (Auth::user()->hasRole('mcmc')) {
            return redirect()->route('mcmc.dashboard');
        } elseif (Auth::user()->hasRole('agency')) {
            return redirect()->route('agency.dashboard');
        } else {
            return redirect()->route('public.dashboard');
        }
    }
    return redirect('/');
})->name('home');

// Profile routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
});

// Public user routes
Route::middleware(['auth', 'role:public'])->prefix('public')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('public.dashboard');
    
    // Inquiry routes for public users
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('public.inquiries.index');
    Route::get('/inquiries/create', [InquiryController::class, 'create'])->name('public.inquiries.create');
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('public.inquiries.store');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('public.inquiries.show');
});

// Agency routes
Route::middleware(['auth', 'role:agency'])->prefix('agency')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('agency.dashboard');
    
    // Inquiry routes for agencies
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('agency.inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('agency.inquiries.show');
    
    // Assignment routes for agencies
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('agency.assignments.index');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('agency.assignments.show');
    Route::post('/assignments/{assignment}/respond', [AssignmentController::class, 'respond'])->name('agency.assignments.respond');
});

// MCMC routes
Route::middleware(['auth', 'role:mcmc'])->prefix('mcmc')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('mcmc.dashboard');
    
    // User management
    Route::resource('users', UserController::class);
    
    // Agency management
    Route::resource('agencies', AgencyController::class);
    
    // Inquiry management
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('mcmc.inquiries.index');
    Route::get('/inquiries/filter', [InquiryController::class, 'filter'])->name('mcmc.inquiries.filter');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('mcmc.inquiries.show');
    Route::post('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('mcmc.inquiries.status');
    
    // Assignment management
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('mcmc.assignments.index');
    Route::get('/inquiries/{inquiry}/assignments/create', [AssignmentController::class, 'create'])->name('mcmc.assignments.create');
    Route::post('/inquiries/{inquiry}/assignments', [AssignmentController::class, 'store'])->name('mcmc.assignments.store');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('mcmc.assignments.show');
    Route::post('/assignments/{assignment}/reassign', [AssignmentController::class, 'reassign'])->name('mcmc.assignments.reassign');
    
    // Report generation
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/users', [ReportController::class, 'generateUserReport'])->name('reports.users');
    Route::post('/reports/inquiries', [ReportController::class, 'generateInquiryReport'])->name('reports.inquiries');
    Route::post('/reports/assignments', [ReportController::class, 'generateAssignmentReport'])->name('reports.assignments');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    
    // Charts
    Route::get('/charts/inquiries', [ReportController::class, 'inquiryCharts'])->name('charts.inquiries');
    Route::get('/charts/assignments', [ReportController::class, 'assignmentCharts'])->name('charts.assignments');
});

// Common routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
    // Status updates - restrict to appropriate roles
    Route::middleware('role_or_permission:mcmc|agency|public')->group(function () {
        Route::get('/inquiries/{inquiry}/status', [StatusController::class, 'index'])->name('status.index');
    });
    
    // Only MCMC and Agency can add status updates
    Route::middleware('role_or_permission:mcmc|agency')->group(function () {
        Route::post('/inquiries/{inquiry}/status', [StatusController::class, 'store'])->name('status.store');
    });
    
    // Debug route to check user roles
    Route::get('/check-role', function() {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'has_mcmc_role' => $user->hasRole('mcmc'),
            'has_agency_role' => $user->hasRole('agency'),
            'has_public_role' => $user->hasRole('public')
        ]);
    });
});