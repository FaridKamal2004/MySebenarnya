<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Models\Agency;
use App\Models\Assignment;
use App\Models\Inquiry;
use App\Models\Notification;
use App\Models\StatusUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments.
     */
    public function index(Request $request)
    {
        $path = $request->path();
        $user = Auth::user();
        
        if (str_starts_with($path, 'mcmc')) {
            // MCMC users have agency_id = null
            if ($user->agency_id !== null) {
                abort(403, 'Only MCMC administrators can view all assignments.');
            }
            $assignments = Assignment::with(['inquiry.user', 'agency'])->latest()->get();
            return view('mcmc.assignments.index', compact('assignments'));
        } elseif (str_starts_with($path, 'agency')) {
            // Agency users have agency_id set
            if ($user->agency_id === null) {
                abort(403, 'Only agency users can view agency assignments.');
            }
            $assignments = Assignment::where('agency_id', $user->agency_id)
                ->with(['inquiry.user'])
                ->latest()
                ->get();
            return view('agency.assignments.index', compact('assignments'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(Inquiry $inquiry)
    {
        // Only MCMC users can create assignments
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create assignments.');
        }

        if ($inquiry->status !== 'validated') {
            return redirect()->route('mcmc.inquiries.show', $inquiry)
                ->with('error', 'Inquiry must be validated before assignment.');
        }

        $agencies = Agency::all();
        return view('mcmc.assignments.create', compact('inquiry', 'agencies'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(AssignmentRequest $request, Inquiry $inquiry)
    {
        // Only MCMC users can store assignments
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create assignments.');
        }

        $validated = $request->validated();
        $validated['inquiry_id'] = $inquiry->id;
        $validated['assigned_by'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['assigned_at'] = now();

        $assignment = Assignment::create($validated);

        // Update inquiry status
        $inquiry->update(['status' => 'assigned']);

        // Create status update
        StatusUpdate::create([
            'inquiry_id' => $inquiry->id,
            'assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'status' => 'assigned',
            'comment' => 'Inquiry assigned to ' . $assignment->agency->name,
        ]);

        // Create notification for agency users
        $agencyUsers = User::where('agency_id', $validated['agency_id'])->get();
        foreach ($agencyUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'New Assignment',
                'message' => 'A new inquiry has been assigned to your agency.',
                'type' => 'assignment',
                'related_id' => $assignment->id,
            ]);
        }

        return redirect()->route('mcmc.inquiries.show', $inquiry)
            ->with('success', 'Inquiry assigned successfully.');
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment, Request $request)
    {
        $assignment->load(['inquiry.user', 'inquiry.category', 'agency', 'statusUpdates.user']);
        $path = $request->path();
        $user = Auth::user();
        
        if (str_starts_with($path, 'mcmc')) {
            // MCMC users have agency_id = null
            if ($user->agency_id !== null) {
                abort(403, 'Only MCMC administrators can view assignment details in this context.');
            }
            return view('mcmc.assignments.show', compact('assignment'));
        } else {
            // Agency users have agency_id set
            if ($user->agency_id === null) {
                abort(403, 'Only agency users can view agency assignment details.');
            }
            
            // Agency users can only view assignments for their own agency
            if ($assignment->agency_id !== $user->agency_id) {
                abort(403, 'You do not have permission to view this assignment.');
            }
            
            return view('agency.assignments.show', compact('assignment'));
        }
    }

    /**
     * Update the assignment status (Agency response).
     */
    public function respond(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        
        // Only agency users can respond to assignments
        if ($user->agency_id === null) {
            abort(403, 'Only agency users can respond to assignments.');
        }
        
        // Agency users can only respond to assignments for their own agency
        if ($assignment->agency_id !== $user->agency_id) {
            abort(403, 'You do not have permission to respond to this assignment.');
        }
        
        // Check if the assignment is already responded to
        if ($assignment->responded_at) {
            return redirect()->route('agency.assignments.show', $assignment)
                ->with('error', 'This assignment has already been responded to.');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'feedback' => 'required|string',
        ]);

        $assignment->update([
            'status' => $validated['status'],
            'feedback' => $validated['feedback'],
            'responded_at' => now(),
        ]);

        // Create status update
        StatusUpdate::create([
            'inquiry_id' => $assignment->inquiry_id,
            'assignment_id' => $assignment->id,
            'user_id' => Auth::id(),
            'status' => $validated['status'],
            'comment' => 'Assignment ' . $validated['status'] . ': ' . $validated['feedback'],
        ]);

        // Create notification for MCMC users
        $mcmcUsers = User::whereNull('agency_id')->get();
        foreach ($mcmcUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Assignment Response',
                'message' => 'Agency has ' . $validated['status'] . ' the assignment.',
                'type' => 'assignment',
                'related_id' => $assignment->id,
            ]);
        }

        return redirect()->route('agency.assignments.show', $assignment)
            ->with('success', 'Response submitted successfully.');
    }

    /**
     * Reassign an inquiry to a different agency (MCMC only).
     */
    public function reassign(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        
        // Only MCMC users can reassign inquiries
        if ($user->agency_id !== null) {
            abort(403, 'Only MCMC administrators can reassign inquiries.');
        }

        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'notes' => 'nullable|string',
        ]);

        // Create a new assignment
        $newAssignment = Assignment::create([
            'inquiry_id' => $assignment->inquiry_id,
            'agency_id' => $validated['agency_id'],
            'assigned_by' => Auth::id(),
            'notes' => $validated['notes'] ?? 'Reassigned from previous agency',
            'status' => 'pending',
            'assigned_at' => now(),
        ]);

        // Update status update
        StatusUpdate::create([
            'inquiry_id' => $assignment->inquiry_id,
            'assignment_id' => $newAssignment->id,
            'user_id' => Auth::id(),
            'status' => 'reassigned',
            'comment' => 'Inquiry reassigned to ' . $newAssignment->agency->name,
        ]);

        // Create notification for new agency users
        $agencyUsers = User::where('agency_id', $validated['agency_id'])->get();
        foreach ($agencyUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'New Assignment',
                'message' => 'A new inquiry has been assigned to your agency.',
                'type' => 'assignment',
                'related_id' => $newAssignment->id,
            ]);
        }

        return redirect()->route('mcmc.inquiries.show', $assignment->inquiry_id)
            ->with('success', 'Inquiry reassigned successfully.');
    }
}