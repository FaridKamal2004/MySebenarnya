<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Assignment;
use App\Models\Inquiry;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index(Request $request)
    {
        $path = $request->path();
        $user = Auth::user();
        
        if (str_starts_with($path, 'mcmc')) {
            // MCMC users have agency_id = null
            if ($user->agency_id !== null) {
                abort(403, 'You do not have permission to access the MCMC dashboard.');
            }
            return $this->mcmcDashboard();
        } elseif (str_starts_with($path, 'agency')) {
            // Agency users have agency_id set
            if ($user->agency_id === null) {
                abort(403, 'You do not have permission to access the Agency dashboard.');
            }
            return $this->agencyDashboard();
        } else {
            // Public dashboard is accessible to all users for now
            return $this->publicDashboard();
        }
    }

    /**
     * Show the MCMC dashboard.
     */
    private function mcmcDashboard()
    {
        $totalUsers = User::count();
        $totalInquiries = Inquiry::count();
        $totalAgencies = Agency::count();
        $totalAssignments = Assignment::count();

        $recentInquiries = Inquiry::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $pendingInquiries = Inquiry::where('status', 'pending')->count();
        $validatedInquiries = Inquiry::where('status', 'validated')->count();
        $assignedInquiries = Inquiry::where('status', 'assigned')->count();
        $resolvedInquiries = Inquiry::where('status', 'resolved')->count();

        $pendingAssignments = Assignment::where('status', 'pending')->count();
        $acceptedAssignments = Assignment::where('status', 'accepted')->count();
        $rejectedAssignments = Assignment::where('status', 'rejected')->count();

        return view('mcmc.dashboard', compact(
            'totalUsers',
            'totalInquiries',
            'totalAgencies',
            'totalAssignments',
            'recentInquiries',
            'pendingInquiries',
            'validatedInquiries',
            'assignedInquiries',
            'resolvedInquiries',
            'pendingAssignments',
            'acceptedAssignments',
            'rejectedAssignments'
        ));
    }

    /**
     * Show the Agency dashboard.
     */
    private function agencyDashboard()
    {
        $agencyId = Auth::user()->agency_id;

        $totalAssignments = Assignment::where('agency_id', $agencyId)->count();
        $pendingAssignments = Assignment::where('agency_id', $agencyId)
            ->where('status', 'pending')
            ->count();
        $acceptedAssignments = Assignment::where('agency_id', $agencyId)
            ->where('status', 'accepted')
            ->count();
        $rejectedAssignments = Assignment::where('agency_id', $agencyId)
            ->where('status', 'rejected')
            ->count();

        $recentAssignments = Assignment::where('agency_id', $agencyId)
            ->with(['inquiry.user', 'inquiry.category'])
            ->latest()
            ->take(5)
            ->get();

        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('agency.dashboard', compact(
            'totalAssignments',
            'pendingAssignments',
            'acceptedAssignments',
            'rejectedAssignments',
            'recentAssignments',
            'notifications'
        ));
    }

    /**
     * Show the Public dashboard.
     */
    private function publicDashboard()
    {
        $totalInquiries = Inquiry::where('user_id', Auth::id())->count();
        $pendingInquiries = Inquiry::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();
        $validatedInquiries = Inquiry::where('user_id', Auth::id())
            ->where('status', 'validated')
            ->count();
        $assignedInquiries = Inquiry::where('user_id', Auth::id())
            ->where('status', 'assigned')
            ->count();
        $resolvedInquiries = Inquiry::where('user_id', Auth::id())
            ->where('status', 'resolved')
            ->count();

        $recentInquiries = Inquiry::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('public.dashboard', compact(
            'totalInquiries',
            'pendingInquiries',
            'validatedInquiries',
            'assignedInquiries',
            'resolvedInquiries',
            'recentInquiries'
        ));
    }
}