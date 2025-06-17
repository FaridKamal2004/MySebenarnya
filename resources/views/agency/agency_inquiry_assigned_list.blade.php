@extends('layouts.sidebar')

@section('title', 'Assigned Inquiries')

@section('heading', 'Assigned Inquiries')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Filter Assignments</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('agency.assignments.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-8">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search by inquiry title or description" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-agency me-2">Apply Filters</button>
                        <a href="{{ route('agency.assignments.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assignments List</h5>
                </div>
            </div>
            <div class="card-body">
                @if($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Inquiry Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Assigned Date</th>
                                    <th>Response Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->id }}</td>
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
                                        <td>{{ $assignment->responded_at ? $assignment->responded_at->format('d M Y') : 'Not responded' }}</td>
                                        <td>
                                            <a href="{{ route('agency.assignments.show', $assignment) }}" class="btn btn-sm btn-agency">View</a>
                                            @if($assignment->status === 'pending')
                                                <a href="{{ route('agency.assignments.respond', $assignment) }}" class="btn btn-sm btn-outline-primary">Respond</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $assignments->appends(request()->query())->links() }}
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
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assignment Status Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-left-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-warning fw-bold">Pending</h6>
                                        <h2 class="mb-0">{{ $pendingCount }}</h2>
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
                                        <h2 class="mb-0">{{ $acceptedCount }}</h2>
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
                                        <h2 class="mb-0">{{ $rejectedCount }}</h2>
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

<style>
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
</style>
@endsection