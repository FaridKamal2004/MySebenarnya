<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Http\Requests\AgencyCreationRequest;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
=======
use App\Models\agency;
use Illuminate\Http\Request;
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0

class AgencyController extends Controller
{
    /**
<<<<<<< HEAD
     * Display a listing of the agencies.
     */
    public function index()
    {
        // Only MCMC users can view all agencies
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can manage agencies.');
        }
        
        $agencies = Agency::all();
        return view('mcmc.agencies.index', compact('agencies'));
    }

    /**
     * Show the form for creating a new agency.
     */
    public function create()
    {
        // Only MCMC users can create agencies
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create agencies.');
        }
        
        return view('mcmc.agencies.create');
    }

    /**
     * Store a newly created agency in storage.
     */
    public function store(Request $request)
    {
        // Only MCMC users can store agencies
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create agencies.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:8',
        ]);

        // Create agency
        $agency = Agency::create([
            'name' => $validated['name'],
            'contact' => $validated['contact'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Create user for agency
        $user = User::create([
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
            'password' => Hash::make($validated['user_password']),
            'agency_id' => $agency->id,
        ]);

        // No need to assign role, we're using agency_id to determine permissions

        return redirect()->route('agencies.index')
            ->with('success', 'Agency created successfully.');
    }

    /**
     * Display the specified agency.
     */
    public function show(Agency $agency)
    {
        // Only MCMC users can view agency details
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can view agency details.');
        }
        
        return view('mcmc.agencies.show', compact('agency'));
    }

    /**
     * Show the form for editing the specified agency.
     */
    public function edit(Agency $agency)
    {
        // Only MCMC users can edit agencies
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can edit agencies.');
        }
        
        return view('mcmc.agencies.edit', compact('agency'));
    }

    /**
     * Update the specified agency in storage.
     */
    public function update(Request $request, Agency $agency)
    {
        // Only MCMC users can update agencies
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can update agencies.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $agency->update($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agency updated successfully.');
    }

    /**
     * Remove the specified agency from storage.
     */
    public function destroy(Agency $agency)
    {
        // Only MCMC users can delete agencies
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can delete agencies.');
        }
        
        $agency->delete();

        return redirect()->route('agencies.index')
            ->with('success', 'Agency deleted successfully.');
    }
}
=======
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('agencies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'contact' => 'required'
        ]);

        $request->user()->agency()->create($validated);

        return redirect()->route('agencies.index')->with('success', 'You have created a Agency.');
    }

    /**
     * Display the specified resource.
     */
    public function show(agency $agency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(agency $agency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, agency $agency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(agency $agency)
    {
        //
    }
}
>>>>>>> d86407c6485f806f82db76534c623a599cf91bb0
