<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InquiryManagementController extends Controller
{
    /**
     * Submit a new inquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitInquiry(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'source_url' => 'nullable|url',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('attachments', 'public');
        }

        // Create the inquiry
        $inquiry = Inquiry::createInquiry([
            'Title' => $request->title,
            'Description' => $request->description,
            'sourceURL' => $request->source_url,
            'attachment' => $attachmentPath,
            'submitted_by' => Auth::guard('public')->id(),
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('public.inquiries.index')
            ->with('success', 'Inquiry submitted successfully!');
    }

    /**
     * Filter inquiries based on criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function filterInquiries(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,assigned,in_progress,completed,rejected',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'agency_id' => 'nullable|exists:agency_users,id',
        ]);

        $query = Inquiry::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('submitted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('submitted_at', '<=', $request->date_to);
        }

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        // Get the filtered inquiries
        $inquiries = $query->orderBy('submitted_at', 'desc')->paginate(10);

        return view('filter_inquiries', [
            'inquiries' => $inquiries,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'agency_id']),
        ]);
    }

    /**
     * Generate a report of inquiries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,assigned,in_progress,completed,rejected',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'agency_id' => 'nullable|exists:agency_users,id',
        ]);

        $query = Inquiry::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('submitted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('submitted_at', '<=', $request->date_to);
        }

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        // Get the inquiries for the report
        $inquiries = $query->orderBy('submitted_at', 'desc')->get();

        return view('inquiry_report', [
            'inquiries' => $inquiries,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'agency_id']),
        ]);
    }

    /**
     * Export the inquiry report in the specified format.
     *
     * @param  string  $format
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportReport(string $format)
    {
        // Get the inquiries for the report
        $inquiries = Inquiry::orderBy('submitted_at', 'desc')->get();

        // Export the report based on the format
        switch ($format) {
            case 'pdf':
                // Generate PDF report
                // For simplicity, we'll just return a view
                return view('inquiry_report', ['inquiries' => $inquiries]);
                
            case 'csv':
                // Generate CSV report
                // For simplicity, we'll just return a view
                return view('inquiry_report', ['inquiries' => $inquiries]);
                
            case 'excel':
                // Generate Excel report
                // For simplicity, we'll just return a view
                return view('inquiry_report', ['inquiries' => $inquiries]);
                
            default:
                return redirect()->back()->withErrors(['format' => 'Invalid export format.']);
        }
    }
}