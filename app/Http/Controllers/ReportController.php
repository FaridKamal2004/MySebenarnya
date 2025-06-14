<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Assignment;
use App\Models\Inquiry;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index()
    {
        // Only MCMC users can view reports
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can access reports.');
        }
        
        $reports = Report::where('generated_by', Auth::id())->latest()->get();
        return view('mcmc.reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        // Only MCMC users can create reports
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can create reports.');
        }
        
        $agencies = Agency::all();
        return view('mcmc.reports.create', compact('agencies'));
    }

    /**
     * Generate a user management report.
     */
    public function generateUserReport(Request $request)
    {
        // Only MCMC users can generate reports
        if (Auth::user()->agency_id !== null) {
            abort(403, 'Only MCMC administrators can generate reports.');
        }
        
        $validated = $request->validate([
            'role' => 'nullable|in:public,agency,mcmc',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = User::query();

        if ($request->filled('role')) {
            if ($validated['role'] === 'mcmc') {
                $query->whereNull('agency_id');
            } else if ($validated['role'] === 'agency') {
                $query->whereNotNull('agency_id');
            }
            // For 'public' role, we don't have a specific filter yet
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        $users = $query->get();

        // Generate PDF
        $pdf = Pdf::loadView('mcmc.reports.users_pdf', [
            'users' => $users,
            'filters' => $validated,
        ]);

        $filename = 'user_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $path = 'reports/' . $filename;
        Storage::put('public/' . $path, $pdf->output());

        // Create report record
        $report = Report::create([
            'title' => 'User Management Report',
            'type' => 'user',
            'parameters' => $validated,
            'generated_by' => Auth::id(),
            'file_path' => $path,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'User report generated successfully.');
    }

    /**
     * Generate an inquiry report.
     */
    public function generateInquiryReport(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,validated,assigned,resolved,closed',
            'category_id' => 'nullable|exists:categories,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Inquiry::query();

        if ($request->filled('status')) {
            $query->where('status', $validated['status']);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $validated['category_id']);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        $inquiries = $query->with(['user', 'category'])->get();

        // Generate PDF
        $pdf = Pdf::loadView('mcmc.reports.inquiries_pdf', [
            'inquiries' => $inquiries,
            'filters' => $validated,
        ]);

        $filename = 'inquiry_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $path = 'reports/' . $filename;
        Storage::put('public/' . $path, $pdf->output());

        // Create report record
        $report = Report::create([
            'title' => 'Inquiry Report',
            'type' => 'inquiry',
            'parameters' => $validated,
            'generated_by' => Auth::id(),
            'file_path' => $path,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Inquiry report generated successfully.');
    }

    /**
     * Generate an assignment report.
     */
    public function generateAssignmentReport(Request $request)
    {
        $validated = $request->validate([
            'agency_id' => 'nullable|exists:agencies,id',
            'status' => 'nullable|in:pending,accepted,rejected',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Assignment::query();

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $validated['agency_id']);
        }

        if ($request->filled('status')) {
            $query->where('status', $validated['status']);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        $assignments = $query->with(['inquiry.user', 'agency'])->get();

        // Generate PDF
        $pdf = Pdf::loadView('mcmc.reports.assignments_pdf', [
            'assignments' => $assignments,
            'filters' => $validated,
        ]);

        $filename = 'assignment_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $path = 'reports/' . $filename;
        Storage::put('public/' . $path, $pdf->output());

        // Create report record
        $report = Report::create([
            'title' => 'Assignment Report',
            'type' => 'assignment',
            'parameters' => $validated,
            'generated_by' => Auth::id(),
            'file_path' => $path,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Assignment report generated successfully.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        return view('mcmc.reports.show', compact('report'));
    }

    /**
     * Download the report file.
     */
    public function download(Report $report)
    {
        return Storage::download('public/' . $report->file_path);
    }

    /**
     * Display charts for inquiry statistics.
     */
    public function inquiryCharts()
    {

        // Status distribution
        $statusCounts = Inquiry::select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->get();

        // Category distribution
        $categoryCounts = Inquiry::select('category_id')
            ->selectRaw('count(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Monthly trend
        $monthlyTrend = Inquiry::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('mcmc.reports.inquiry_charts', compact('statusCounts', 'categoryCounts', 'monthlyTrend'));
    }

    /**
     * Display charts for assignment statistics.
     */
    public function assignmentCharts()
    {

        // Agency distribution
        $agencyCounts = Assignment::select('agency_id')
            ->selectRaw('count(*) as count')
            ->groupBy('agency_id')
            ->with('agency')
            ->get();

        // Status distribution
        $statusCounts = Assignment::select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->get();

        // Response time
        $responseTimes = Assignment::whereNotNull('responded_at')
            ->select('agency_id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, assigned_at, responded_at)) as avg_response_time')
            ->groupBy('agency_id')
            ->with('agency')
            ->get();

        return view('mcmc.reports.assignment_charts', compact('agencyCounts', 'statusCounts', 'responseTimes'));
    }
}