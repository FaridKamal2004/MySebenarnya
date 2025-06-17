<?php

use App\Http\Controllers\AssignmentManagementController;
use App\Http\Controllers\InquiryManagementController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Test route for debugging
Route::get('/test-auth', function () {
    $publicUsers = \App\Models\PublicUser::all();
    $mcmcUsers = \App\Models\McmcUser::all();
    $agencyUsers = \App\Models\AgencyUser::all();
    $roles = \App\Models\RoleUser::all();
    
    return [
        'public_users' => $publicUsers,
        'mcmc_users' => $mcmcUsers,
        'agency_users' => $agencyUsers,
        'roles' => $roles,
    ];
});

// Authentication routes
Route::get('/login', [UserManagementController::class, 'showLogin'])->name('login');
Route::post('/login', [UserManagementController::class, 'login'])->name('login.submit');
Route::get('/register', [UserManagementController::class, 'showRegister'])->name('register');
Route::post('/register', [UserManagementController::class, 'register'])->name('register.submit');
Route::post('/logout', [UserManagementController::class, 'logout'])->name('logout');
Route::get('/verify-email/{token}', [UserManagementController::class, 'verifyEmail'])->name('verify.email');
Route::post('/password/email', [UserManagementController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/first-login-reset', function() {
    return view('auth.first_login_reset');
})->name('first.login.reset');
Route::post('/first-login-reset', [UserManagementController::class, 'enforceFirstLoginReset'])->name('first.login.reset.submit');

// Public user routes
Route::middleware(['IsPublicUser'])->prefix('public')->group(function () {
    Route::get('/dashboard', function() {
        return view('public.dashboard');
    })->name('public.dashboard');
    
    // Inquiry routes for public users
    Route::get('/inquiries', function() {
        return view('public.inquiries.index');
    })->name('public.inquiries.index');
    Route::get('/inquiries/create', function() {
        return view('public.inquiries.create');
    })->name('public.inquiries.create');
    Route::post('/inquiries', [InquiryManagementController::class, 'submitInquiry'])->name('public.inquiries.store');
    Route::get('/inquiries/{inquiry}', function($inquiry) {
        return view('public.inquiries.show', ['inquiry' => $inquiry]);
    })->name('public.inquiries.show');
    
    // Profile routes
    Route::get('/profile', function() {
        return view('profile.show');
    })->name('profile.show');
    Route::get('/profile/edit', function() {
        return view('profile.edit');
    })->name('profile.edit');
    Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');
});

// Agency routes
Route::middleware(['IsAgency'])->prefix('agency')->group(function () {
    Route::get('/dashboard', function() {
        return view('agency.dashboard');
    })->name('agency.dashboard');
    
    // Inquiry routes for agencies
    Route::get('/inquiries', function() {
        return view('agency.inquiries.index');
    })->name('agency.inquiries.index');
    Route::get('/inquiries/{inquiry}', function($inquiry) {
        return view('agency.inquiries.show', ['inquiry' => $inquiry]);
    })->name('agency.inquiries.show');
    
    // Assignment routes for agencies
    Route::get('/assignments', function() {
        return view('agency.assignments.index');
    })->name('agency.assignments.index');
    Route::get('/assignments/{assignment}', function($assignment) {
        return view('agency.assignments.show', ['assignment' => $assignment]);
    })->name('agency.assignments.show');
    Route::post('/assignments/{assignment}/respond', function($assignment) {
        return redirect()->back()->with('success', 'Response submitted successfully!');
    })->name('agency.assignments.respond');
    
    // Profile routes
    Route::get('/profile', function() {
        return view('profile.show');
    })->name('agency.profile.show');
    Route::get('/profile/edit', function() {
        return view('profile.edit');
    })->name('agency.profile.edit');
    Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('agency.profile.update');
});

// MCMC routes
Route::middleware(['IsMcmc'])->prefix('mcmc')->group(function () {
    Route::get('/dashboard', function() {
        return view('mcmc.dashboard');
    })->name('mcmc.dashboard');
    
    // User management
    Route::get('/users/report', [UserManagementController::class, 'generateReport'])->name('users.report');
    
    // Agency management
    Route::get('/agencies/create', function() {
        return view('mcmc.agencies.create');
    })->name('agencies.create');
    Route::post('/agencies', [UserManagementController::class, 'registerAgency'])->name('agencies.store');
    Route::get('/agencies', function() {
        return view('mcmc.agencies.index');
    })->name('agencies.index');
    
    // Inquiry management
    Route::get('/inquiries', [InquiryManagementController::class, 'filterInquiries'])->name('mcmc.inquiries.index');
    Route::get('/inquiries/filter', [InquiryManagementController::class, 'filterInquiries'])->name('mcmc.inquiries.filter');
    Route::get('/inquiries/report', [InquiryManagementController::class, 'generateReport'])->name('mcmc.inquiries.report');
    Route::get('/inquiries/export/{format}', [InquiryManagementController::class, 'exportReport'])->name('mcmc.inquiries.export');
    Route::get('/inquiries/{inquiry}', function($inquiry) {
        return view('mcmc.inquiries.show', ['inquiry' => $inquiry]);
    })->name('mcmc.inquiries.show');
    
    // Assignment management
    Route::post('/inquiries/{inquiry}/assign', [AssignmentManagementController::class, 'assignAgency'])->name('mcmc.inquiries.assign');
    Route::post('/inquiries/{inquiry}/reject', [AssignmentManagementController::class, 'rejectInquiry'])->name('mcmc.inquiries.reject');
    Route::get('/jurisdiction', [AssignmentManagementController::class, 'getJurisdiction'])->name('mcmc.jurisdiction');
    Route::get('/assignments/report', [AssignmentManagementController::class, 'generateAssignmentReport'])->name('mcmc.assignments.report');
    
    // Profile routes
    Route::get('/profile', function() {
        return view('profile.show');
    })->name('mcmc.profile.show');
    Route::get('/profile/edit', function() {
        return view('profile.edit');
    })->name('mcmc.profile.edit');
    Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('mcmc.profile.update');
});
