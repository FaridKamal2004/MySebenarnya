@extends('layouts.sidebar')

@section('title', 'Public Dashboard')

@section('heading', 'Dashboard')

@section('heading_buttons')
<a href="{{ route('public.inquiries.create') }}" class="btn btn-public">
    <i class="fas fa-plus-circle"></i> Submit New Inquiry
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-primary fw-bold">Total Inquiries</h6>
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
                        <h6 class="text-uppercase text-warning fw-bold">Pending</h6>
                        <h2 class="mb-0">{{ $pendingInquiries }}</h2>
                    </div>
                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-info fw-bold">Assigned</h6>
                        <h2 class="mb-0">{{ $assignedInquiries }}</h2>
                    </div>
                    <i class="fas fa-tasks fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-success fw-bold">Resolved</h6>
                        <h2 class="mb-0">{{ $resolvedInquiries }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Recent Inquiries</span>
                    <a href="{{ route('public.inquiries.index') }}" class="btn btn-sm btn-public">View All</a>
                </div>
            </div>
            <div class="card-body">
                @if($recentInquiries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
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
                                            <a href="{{ route('public.inquiries.show', $inquiry) }}" class="btn btn-sm btn-public">View</a>
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
                        <a href="{{ route('public.inquiries.create') }}" class="btn btn-public mt-3">Submit Your First Inquiry</a>
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
    .text-gray-300 {
        color: #dddfeb !important;
    }
</style>
@endsection