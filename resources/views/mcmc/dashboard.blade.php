@extends('layouts.sidebar')

@section('title', 'MCMC Dashboard')

@section('heading', 'MCMC Dashboard')

@section('heading_buttons')
<div>
    <a href="{{ route('mcmc.inquiries.index') }}" class="btn btn-mcmc me-2">
        <i class="fas fa-list"></i> All Inquiries
    </a>
    <a href="{{ route('reports.create') }}" class="btn btn-outline-secondary">
        <i class="fas fa-chart-bar"></i> Generate Report
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-primary fw-bold">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-success fw-bold">Total Agencies</h6>
                        <h2 class="mb-0">{{ $totalAgencies }}</h2>
                    </div>
                    <i class="fas fa-building fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-info fw-bold">Total Inquiries</h6>
                        <h2 class="mb-0">{{ $totalInquiries }}</h2>
                    </div>
                    <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-warning fw-bold">Total Assignments</h6>
                        <h2 class="mb-0">{{ $totalAssignments }}</h2>
                    </div>
                    <i class="fas fa-tasks fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Inquiry Status</span>
                    <a href="{{ route('charts.inquiries') }}" class="btn btn-sm btn-mcmc">View Charts</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-warning fw-bold">Pending</h6>
                                        <h3 class="mb-0">{{ $pendingInquiries }}</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-info h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-info fw-bold">Validated</h6>
                                        <h3 class="mb-0">{{ $validatedInquiries }}</h3>
                                    </div>
                                    <i class="fas fa-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-primary fw-bold">Assigned</h6>
                                        <h3 class="mb-0">{{ $assignedInquiries }}</h3>
                                    </div>
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-success fw-bold">Resolved</h6>
                                        <h3 class="mb-0">{{ $resolvedInquiries }}</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Assignment Status</span>
                    <a href="{{ route('charts.assignments') }}" class="btn btn-sm btn-mcmc">View Charts</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-left-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-warning fw-bold">Pending</h6>
                                        <h3 class="mb-0">{{ $pendingAssignments }}</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-success fw-bold">Accepted</h6>
                                        <h3 class="mb-0">{{ $acceptedAssignments }}</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-danger h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-danger fw-bold">Rejected</h6>
                                        <h3 class="mb-0">{{ $rejectedAssignments }}</h3>
                                    </div>
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Recent Inquiries</span>
                    <a href="{{ route('mcmc.inquiries.index') }}" class="btn btn-sm btn-mcmc">View All</a>
                </div>
            </div>
            <div class="card-body">
                @if($recentInquiries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>User</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInquiries as $inquiry)
                                    <tr>
                                        <td>{{ $inquiry->title }}</td>
                                        <td>{{ $inquiry->user->name }}</td>
                                        <td>{{ $inquiry->category->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $inquiry->status === 'pending' ? 'pending' : 
                                                ($inquiry->status === 'validated' ? 'validated' : 
                                                ($inquiry->status === 'assigned' ? 'assigned' : 
                                                ($inquiry->status === 'resolved' ? 'resolved' : 'secondary'))) 
                                            }}">
                                                {{ ucfirst($inquiry->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $inquiry->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('mcmc.inquiries.show', $inquiry) }}" class="btn btn-sm btn-mcmc">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                        <p class="mb-0">No inquiries found.</p>
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
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
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