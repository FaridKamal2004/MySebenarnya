<?php

namespace App\Http\Controllers;

use App\Models\AgencyUser;
use App\Models\Assignment;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentManagementController extends Controller
{
    /**
     * Assign an inquiry to an agency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignAgency(Request $request)
    {
        $request->validate([
            'inquiry_id' => 'required|exists:inquiries,InquiryId',
            'agency_id' => 'required|exists:agency_users,id',
        ]);

        $inquiryId = $request->inquiry_id;
        $agencyId = $request->agency_id;

        // Get the inquiry
        $inquiry = Inquiry::where('InquiryId', $inquiryId)->first();
        if (!$inquiry) {
            return redirect()->back()->withErrors(['inquiry_id' => 'Inquiry not found.']);
        }

        // Check if the inquiry is already assigned
        if ($inquiry->status !== 'pending') {
            return redirect()->back()->withErrors(['inquiry_id' => 'Inquiry is already assigned or processed.']);
        }

        // Assign the inquiry to the agency
        $inquiry->assignToAgency($agencyId);
        $inquiry->updateStatus('assigned');

        // Create the assignment record
        Assignment::createAssignment([
            'Inquiry_Id' => $inquiryId,
            'Agency_Id' => $agencyId,
            'Assigned_by' => Auth::guard('mcmc')->id(),
            'Assigned_at' => now(),
        ]);

        return redirect()->route('mcmc.inquiries.index')
            ->with('success', 'Inquiry assigned to agency successfully!');
    }

    /**
     * Reject an inquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectInquiry(Request $request)
    {
        $request->validate([
            'inquiry_id' => 'required|exists:inquiries,InquiryId',
            'reason' => 'required|string',
        ]);

        $inquiryId = $request->inquiry_id;

        // Get the inquiry
        $inquiry = Inquiry::where('InquiryId', $inquiryId)->first();
        if (!$inquiry) {
            return redirect()->back()->withErrors(['inquiry_id' => 'Inquiry not found.']);
        }

        // Check if the inquiry is already processed
        if ($inquiry->status !== 'pending') {
            return redirect()->back()->withErrors(['inquiry_id' => 'Inquiry is already assigned or processed.']);
        }

        // Update the inquiry status to rejected
        $inquiry->updateStatus('rejected');

        // Add a progress record with the rejection reason
        $inquiry->progress()->create([
            'ProgressResult' => 'rejected',
            'ProgressDescription' => $request->reason,
            'MCMCID' => Auth::guard('mcmc')->id(),
        ]);

        return redirect()->route('mcmc.inquiries.index')
            ->with('success', 'Inquiry rejected successfully!');
    }

    /**
     * Get the jurisdiction information for agencies.
     *
     * @return \Illuminate\View\View
     */
    public function getJurisdiction()
    {
        // Get all agencies
        $agencies = AgencyUser::all();

        return view('jurisdiction_check', [
            'agencies' => $agencies,
        ]);
    }

    /**
     * Generate a report of assignments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function generateAssignmentReport(Request $request)
    {
        $request->validate([
            'agency_id' => 'nullable|exists:agency_users,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|in:assigned,in_progress,completed,rejected',
        ]);

        $query = Assignment::query();

        // Apply filters
        if ($request->filled('agency_id')) {
            $query->where('Agency_Id', $request->agency_id);
        }

        if ($request->filled('date_from')) {
            $query->where('Assigned_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('Assigned_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->whereHas('inquiry', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Get the assignments for the report
        $assignments = $query->with(['inquiry', 'agency', 'assignedBy'])
            ->orderBy('Assigned_at', 'desc')
            ->get();

        return view('assignment_report', [
            'assignments' => $assignments,
            'filters' => $request->only(['agency_id', 'date_from', 'date_to', 'status']),
        ]);
    }
}