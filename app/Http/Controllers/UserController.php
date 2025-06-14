<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Only MCMC users can view all users
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can manage users.');
        }
        
        $users = User::all();
        return view('mcmc.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only MCMC users can create users
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create users.');
        }
        
        return view('mcmc.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Only MCMC users can store users
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:public,agency,mcmc',
        ]);

        // Set agency_id based on role
        $agency_id = null;
        if ($validated['role'] === 'agency') {
            // For agency users, we need to set an agency_id
            // Since we don't have agency selection in this form, we'll need to handle this differently
            // For now, we'll abort with a message
            return redirect()->back()->with('error', 'Agency users must be created through the agency management interface.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'agency_id' => $agency_id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Only MCMC users can view user details
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can view user details.');
        }

        return view('mcmc.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Only MCMC users can edit users
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can edit users.');
        }

        return view('mcmc.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only MCMC users can update users
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can update users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:public,agency,mcmc',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update agency_id based on role
        if ($validated['role'] === 'mcmc' || $validated['role'] === 'public') {
            $user->update(['agency_id' => null]);
        } else if ($validated['role'] === 'agency' && $user->agency_id === null) {
            // For agency users, we need to set an agency_id
            // Since we don't have agency selection in this form, we'll need to handle this differently
            return redirect()->back()->with('error', 'Agency users must be updated through the agency management interface.');
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Only MCMC users can delete users
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can delete users.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}