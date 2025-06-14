<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        // Check if user has the required role based on agency_id
        $user = $request->user();
        $hasAccess = false;
        
        foreach ($roles as $role) {
            if ($role === 'mcmc' && $user->agency_id === null) {
                $hasAccess = true;
                break;
            } elseif ($role === 'agency' && $user->agency_id !== null) {
                $hasAccess = true;
                break;
            } elseif ($role === 'public') {
                $hasAccess = true;
                break;
            }
        }
        
        if (!$hasAccess) {
            abort(403, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}