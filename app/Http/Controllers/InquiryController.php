<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\StatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InquiryController extends Controller
{
    /**
     * Display a listing of the inquiries.
     */
    public function index(Request $request)
    {
        $path = $request->path();
        $user = Auth::user();
        
        if (str_starts_with($path, 'mcmc')) {
            // MCMC users have agency_id = null
            if ($user->agency_id !== null) {
                abort(403, 'You do not have permission to access MCMC inquiries.');
            }
            $inquiries = Inquiry::with(['user', 'category'])->latest()->get();
            $categories = Category::all();
            return view('mcmc.mcmc_inquiry_validation_list', compact('inquiries', 'categories'));
        } elseif (str_starts_with($path, 'agency')) {
            // Agency users have agency_id set
            if ($user->agency_id === null) {
                abort(403, 'You do not have permission to access agency inquiries.');
            }
            $inquiries = Inquiry::whereHas('assignments', function ($query) {
                $query->where('agency_id', Auth::user()->agency_id);
            })->with(['user', 'category'])->latest()->get();
            return view('agency.agency_inquiry_assigned_list', compact('inquiries'));
        } else {
            // Public dashboard is accessible to all for now
            $inquiries = Inquiry::where('user_id', Auth::id())->with('category')->latest()->get();
            return view('public.public_inquiry_list', compact('inquiries'));
        }
    }

    /**
     * Show the form for creating a new inquiry.
     */
    public function create()
    {
        $categories = Category::all();
        return view('public.public_inquiry_create', compact('categories'));
    }

    /**
     * Store a newly created inquiry in storage.
     */
    public function store(InquiryRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $validated['attachment_path'] = $path;
        }

        $inquiry = Inquiry::create($validated);

        // Create initial status update
        StatusUpdate::create([
            'inquiry_id' => $inquiry->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'comment' => 'Inquiry submitted',
        ]);

        return redirect()->route('public.inquiries.show', $inquiry)
            ->with('success', 'Inquiry submitted successfully.');
    }

    /**
     * Display the specified inquiry.
     */
    public function show(Inquiry $inquiry, Request $request)
    {
        $inquiry->load(['user', 'category', 'statusUpdates.user', 'assignments.agency']);
        $path = $request->path();
        $user = Auth::user();

        if (str_starts_with($path, 'mcmc')) {
            // MCMC users have agency_id = null
            if ($user->agency_id !== null) {
                abort(403, 'You do not have permission to access MCMC inquiry details.');
            }
            return view('mcmc.mcmc_inquiry_detail', compact('inquiry'));
        } elseif (str_starts_with($path, 'agency')) {
            // Agency users have agency_id set
            if ($user->agency_id === null) {
                abort(403, 'You do not have permission to access agency inquiry details.');
            }
            
            // Check if this inquiry is assigned to the current agency
            $hasAccess = $inquiry->assignments()->where('agency_id', $user->agency_id)->exists();
            if (!$hasAccess) {
                abort(403, 'This inquiry is not assigned to your agency.');
            }
            
            return view('agency.agency_inquiry_detail', compact('inquiry'));
        } else {
            // Public users can only view their own inquiries
            if ($inquiry->user_id !== $user->id) {
                abort(403, 'You do not have permission to view this inquiry.');
            }
            
            return view('public.public_inquiry_detail', compact('inquiry'));
        }
    }

    /**
     * Update the status of an inquiry (MCMC only).
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        // Ensure only MCMC users can update status
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can update inquiry status.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,validated,assigned,resolved,closed',
            'comment' => 'nullable|string',
        ]);

        $inquiry->update(['status' => $validated['status']]);

        // Create status update
        StatusUpdate::create([
            'inquiry_id' => $inquiry->id,
            'user_id' => Auth::id(),
            'status' => $validated['status'],
            'comment' => $validated['comment'] ?? 'Status updated to ' . $validated['status'],
        ]);

        return redirect()->route('mcmc.inquiries.show', $inquiry)
            ->with('success', 'Inquiry status updated successfully.');
    }

    /**
     * Filter inquiries by category, status, or date (MCMC only).
     */
    public function filter(Request $request)
    {
        // Ensure only MCMC users can filter inquiries
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can access this feature.');
        }
        
        $query = Inquiry::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $inquiries = $query->with(['user', 'category'])->latest()->get();
        $categories = Category::all();

        return view('mcmc.mcmc_inquiry_validation_list', compact('inquiries', 'categories'));
    }
}