<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\StatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /**
     * Display a listing of the status updates for an inquiry.
     */
    public function index(Inquiry $inquiry)
    {
        $user = Auth::user();
        
        // If user is not MCMC (has agency_id = null), check permissions
        if ($user->agency_id !== null) {
            // Agency users can only access inquiries assigned to their agency
            $hasAccess = $inquiry->assignments()->where('agency_id', $user->agency_id)->exists();
            if (!$hasAccess) {
                // If not assigned to their agency, check if it's their own inquiry
                if ($inquiry->user_id !== $user->id) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        $statusUpdates = $inquiry->statusUpdates()->with('user')->latest()->get();

        return view('status.index', compact('inquiry', 'statusUpdates'));
    }

    /**
     * Store a newly created status update in storage.
     */
    public function store(Request $request, Inquiry $inquiry)
    {
        $user = Auth::user();
        
        // If user is not MCMC (has agency_id = null), check permissions
        if ($user->agency_id !== null) {
            // Agency users can only access inquiries assigned to their agency
            $hasAccess = $inquiry->assignments()->where('agency_id', $user->agency_id)->exists();
            if (!$hasAccess) {
                // If not assigned to their agency, check if it's their own inquiry
                if ($inquiry->user_id !== $user->id) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        $validated = $request->validate([
            'status' => 'required|string',
            'comment' => 'required|string',
        ]);

        StatusUpdate::create([
            'inquiry_id' => $inquiry->id,
            'user_id' => Auth::id(),
            'status' => $validated['status'],
            'comment' => $validated['comment'],
        ]);

        // Update inquiry status
        $inquiry->update(['status' => $validated['status']]);

        return redirect()->route('inquiries.show', $inquiry)
            ->with('success', 'Status updated successfully.');
    }
}