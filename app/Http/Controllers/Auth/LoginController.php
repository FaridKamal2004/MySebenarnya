<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    
    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->agency_id === null) {
            // MCMC users have agency_id = null
            return '/mcmc/dashboard';
        } elseif ($user->agency_id !== null) {
            // Agency users have agency_id set
            return '/agency/dashboard';
        } else {
            // Default to public dashboard
            return '/public/dashboard';
        }
    }
    
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $selectedRole = $request->input('role');
        
        // Check if user has the selected role
        if (!$user->hasRole($selectedRole)) {
            // Log the user out
            Auth::logout();
            
            // Redirect back with error
            return redirect()->route('login')
                ->withInput($request->only('email', 'remember', 'role'))
                ->withErrors([
                    'role' => 'You do not have access to login as ' . ucfirst($selectedRole) . '.'
                ]);
        }
        
        // Redirect based on role
        if ($selectedRole === 'mcmc') {
            return redirect()->route('mcmc.dashboard');
        } elseif ($selectedRole === 'agency') {
            return redirect()->route('agency.dashboard');
        } else {
            return redirect()->route('public.dashboard');
        }
    }
}
