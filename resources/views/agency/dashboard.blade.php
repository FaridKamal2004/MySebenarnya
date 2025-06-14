@extends('layouts.sidebar')

@section('title', 'Agency Dashboard')

@section('heading', 'Agency Dashboard')

@section('heading_buttons')
<a href="{{ route('agency.assignments.index') }}" class="btn btn-agency">
    <i class="fas fa-tasks"></i> View All Assignments
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-primary fw-bold">Total Assignments</h6>
                        <h2 class="mb-0">{{ $totalAssignments }}</h2>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-warning fw-bold">Pending</h6>
                        <h2 class="mb-0">{{ $pendingAssignments }}</h2>
                    </div>
                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-success fw-bold">Accepted</h6>
                        <h2 class="mb-0">{{ $acceptedAssignments }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-danger fw-bold">Rejected</h6>
                        <h2 class="mb-0">{{ $rejectedAssignments }}</h2>
                    </div>
                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Recent Assignments</span>
                    <a href="{{ route('agency.assignments.index') }}" class="btn btn-sm btn-agency">View All</a>
                </div>
            </div>
            <div class="card-body">
                @if($recentAssignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Inquiry</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Assigned</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAssignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->inquiry->title }}</td>
                                        <td>{{ $assignment->inquiry->category->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $assignment->status === 'pending' ? 'pending' : 
                                                ($assignment->status === 'accepted' ? 'resolved' : 
                                                ($assignment->status === 'rejected' ? 'rejected' : 'secondary')) 
                                            }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $assignment->assigned_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('agency.assignments.show', $assignment) }}" class="btn btn-sm btn-agency">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                        <p class="mb-0">No assignments found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">Recent Notifications</div>
            <div class="card-body">
                @if($notifications->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <li class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($notification->message, 100) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                        <p class="mb-0">No notifications.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
</style>
@endsection