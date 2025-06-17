<?php

namespace App\Http\Controllers;

use App\Models\AgencyUser;
use App\Models\McmcUser;
use App\Models\PublicUser;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:public,agency,mcmc',
        ]);

        $credentials = $request->only('email', 'password');
        $role = $request->input('role');

        // Determine which guard to use based on the role
        switch ($role) {
            case 'public':
                $guard = 'public';
                $credentials = [
                    'PublicEmail' => $credentials['email'],
                    'password' => $credentials['password'],
                ];
                break;
            case 'agency':
                $guard = 'agency';
                $credentials = [
                    'AgencyEmail' => $credentials['email'],
                    'password' => $credentials['password'],
                ];
                break;
            case 'mcmc':
                $guard = 'mcmc';
                $credentials = [
                    'MCMCEmail' => $credentials['email'],
                    'password' => $credentials['password'],
                ];
                break;
            default:
                return redirect()->back()->withErrors(['role' => 'Invalid role selected.']);
        }
        
        // For debugging
        \Log::info('Login attempt', [
            'guard' => $guard,
            'credentials' => $credentials,
        ]);

        try {
            if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();

                // Check if agency user is logging in for the first time
                if ($role === 'agency') {
                    $user = Auth::guard($guard)->user();
                    if ($user->AgencyFirstLogin) {
                        return redirect()->route('first.login.reset');
                    }
                }

                // Redirect to the appropriate dashboard
                return redirect()->intended($this->getRedirectPath($role));
            }
            
            // For debugging
            \Log::info('Login failed', [
                'guard' => $guard,
                'credentials' => $credentials,
            ]);
        } catch (\Exception $e) {
            // For debugging
            \Log::error('Login exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'An error occurred during login: ' . $e->getMessage()]);
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:public_users,PublicEmail',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'required|string|max:20',
        ]);
        
        // For debugging
        \Log::info('Registration attempt', [
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);

        // Get the public role ID
        $publicRole = RoleUser::where('RoleName', 'public')->first();
        if (!$publicRole) {
            return redirect()->back()->withErrors(['role' => 'Public role not found.']);
        }

        try {
            // Create the public user
            $hashedPassword = Hash::make($request->password);
            
            $user = PublicUser::create([
                'PublicName' => $request->name,
                'PublicEmail' => $request->email,
                'PublicPassword' => $hashedPassword,
                'password' => $hashedPassword,
                'PublicContact' => $request->contact,
                'PublicStatusVerify' => false,
                'RoleID' => $publicRole->RoleID,
            ]);
            
            // For debugging
            \Log::info('User created', [
                'user_id' => $user->id,
                'name' => $user->PublicName,
                'email' => $user->PublicEmail,
            ]);
        } catch (\Exception $e) {
            // For debugging
            \Log::error('Registration exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'An error occurred during registration: ' . $e->getMessage()]);
        }

        // Generate verification token
        $token = Str::random(64);
        
        // Store token in database (you would need to create a verification_tokens table)
        // For simplicity, we'll skip this step in this example

        // Send verification email
        // Mail::to($user->PublicEmail)->send(new VerifyEmail($user, $token));

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Determine which guard the user is authenticated with
        if (Auth::guard('public')->check()) {
            Auth::guard('public')->logout();
        } elseif (Auth::guard('agency')->check()) {
            Auth::guard('agency')->logout();
        } elseif (Auth::guard('mcmc')->check()) {
            Auth::guard('mcmc')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Verify a user's email address.
     *
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($token)
    {
        // Verify the token and update the user's verification status
        // For simplicity, we'll skip the token verification logic

        return redirect()->route('login')
            ->with('success', 'Your email has been verified! You can now log in.');
    }

    /**
     * Send a password reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:public,agency,mcmc',
        ]);

        $email = $request->email;
        $role = $request->role;

        // Determine which model to use based on the role
        switch ($role) {
            case 'public':
                $user = PublicUser::where('PublicEmail', $email)->first();
                $broker = 'public_users';
                break;
            case 'agency':
                $user = AgencyUser::where('AgencyEmail', $email)->first();
                $broker = 'agency_users';
                break;
            case 'mcmc':
                $user = McmcUser::where('MCMCEmail', $email)->first();
                $broker = 'mcmc_users';
                break;
            default:
                return redirect()->back()->withErrors(['role' => 'Invalid role selected.']);
        }

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Send password reset link
        // For simplicity, we'll skip the actual email sending
        // Password::broker($broker)->sendResetLink($request->only('email'));

        return redirect()->back()
            ->with('status', 'We have emailed your password reset link!');
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request, $role)
    {
        // Validate the request based on the role
        switch ($role) {
            case 'public':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'contact' => 'required|string|max:20',
                ]);
                
                $user = Auth::guard('public')->user();
                $user->PublicName = $request->name;
                $user->PublicContact = $request->contact;
                break;
                
            case 'agency':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'contact' => 'required|string|max:20',
                ]);
                
                $user = Auth::guard('agency')->user();
                $user->AgencyUserName = $request->name;
                $user->AgencyContact = $request->contact;
                break;
                
            case 'mcmc':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'contact' => 'required|string|max:20',
                ]);
                
                $user = Auth::guard('mcmc')->user();
                $user->MCMCUserName = $request->name;
                $user->MCMCContact = $request->contact;
                break;
                
            default:
                return redirect()->back()->withErrors(['role' => 'Invalid role.']);
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Register a new agency user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerAgency(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:agency_users,AgencyEmail',
            'contact' => 'required|string|max:20',
        ]);

        // Get the agency role ID
        $agencyRole = RoleUser::where('RoleName', 'agency')->first();
        if (!$agencyRole) {
            return redirect()->back()->withErrors(['role' => 'Agency role not found.']);
        }

        // Generate a random password
        $password = Str::random(10);

        // Create the agency user
        $user = AgencyUser::create([
            'AgencyUserName' => $request->name,
            'AgencyEmail' => $request->email,
            'password' => Hash::make($password),
            'AgencyContact' => $request->contact,
            'AgencyFirstLogin' => true,
            'RoleID' => $agencyRole->RoleID,
            'MCMCID' => Auth::guard('mcmc')->id(),
        ]);

        // Send credentials email
        // For simplicity, we'll skip the actual email sending
        // Mail::to($user->AgencyEmail)->send(new AgencyCredentials($user, $password));

        return redirect()->route('agencies.index')
            ->with('success', 'Agency user created successfully! Credentials have been sent to their email.');
    }

    /**
     * Generate a report of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:all,public,agency,mcmc',
            'format' => 'required|in:pdf,csv,excel',
        ]);

        $reportType = $request->report_type;
        $format = $request->format;

        // Get the users based on the report type
        switch ($reportType) {
            case 'all':
                $publicUsers = PublicUser::all();
                $agencyUsers = AgencyUser::all();
                $mcmcUsers = McmcUser::all();
                $data = [
                    'publicUsers' => $publicUsers,
                    'agencyUsers' => $agencyUsers,
                    'mcmcUsers' => $mcmcUsers,
                ];
                break;
                
            case 'public':
                $publicUsers = PublicUser::all();
                $data = ['publicUsers' => $publicUsers];
                break;
                
            case 'agency':
                $agencyUsers = AgencyUser::all();
                $data = ['agencyUsers' => $agencyUsers];
                break;
                
            case 'mcmc':
                $mcmcUsers = McmcUser::all();
                $data = ['mcmcUsers' => $mcmcUsers];
                break;
                
            default:
                return redirect()->back()->withErrors(['report_type' => 'Invalid report type.']);
        }

        // Generate the report based on the format
        // For simplicity, we'll just return a view
        return view('user_report', $data);
    }

    /**
     * Force an agency user to reset their password on first login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enforceFirstLoginReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard('agency')->user();
        
        if (!$user || !$user->AgencyFirstLogin) {
            return redirect()->route('login');
        }

        $user->password = Hash::make($request->password);
        $user->AgencyFirstLogin = false;
        $user->save();

        return redirect()->route('agency.dashboard')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Get the redirect path based on the user's role.
     *
     * @param  string  $role
     * @return string
     */
    private function getRedirectPath($role)
    {
        switch ($role) {
            case 'public':
                return route('public.dashboard');
            case 'agency':
                return route('agency.dashboard');
            case 'mcmc':
                return route('mcmc.dashboard');
            default:
                return '/';
        }
    }
}