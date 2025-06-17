@extends('layouts.sidebar')

@section('title', 'Review Assignment')

@section('heading', 'Review Assignment')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assignment Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Inquiry Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Title</th>
                                <td>{{ $assignment->inquiry->title }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $assignment->inquiry->category->name }}</td>
                            </tr>
                            <tr>
                                <th>Submitted By</th>
                                <td>{{ $assignment->inquiry->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Submitted On</th>
                                <td>{{ $assignment->inquiry->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Assignment Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Assigned To</th>
                                <td>{{ $assignment->agency->name }}</td>
                            </tr>
                            <tr>
                                <th>Assigned By</th>
                                <td>{{ $assignment->assignedBy->name }}</td>
                            </tr>
                            <tr>
                                <th>Assigned On</th>
                                <td>{{ $assignment->assigned_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ 
                                        $assignment->status === 'pending' ? 'pending' : 
                                        ($assignment->status === 'accepted' ? 'resolved' : 
                                        ($assignment->status === 'rejected' ? 'rejected' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Inquiry Description</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $assignment->inquiry->description }}
                    </div>
                </div>

                @if($assignment->inquiry->source_url)
                <div class="mb-4">
                    <h6 class="fw-bold">Source URL</h6>
                    <div class="p-3 bg-light rounded">
                        <a href="{{ $assignment->inquiry->source_url }}" target="_blank">{{ $assignment->inquiry->source_url }}</a>
                    </div>
                </div>
                @endif

                @if($assignment->notes)
                <div class="mb-4">
                    <h6 class="fw-bold">MCMC Notes</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $assignment->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($assignment->status === 'pending')
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Respond to Assignment</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('agency.assignments.respond', $assignment) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Response</label>
                        <div class="d-flex gap-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_accept" value="accepted" required>
                                <label class="form-check-label" for="status_accept">
                                    Accept Assignment
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_reject" value="rejected" required>
                                <label class="form-check-label" for="status_reject">
                                    Reject Assignment
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback</label>
                        <textarea class="form-control @error('feedback') is-invalid @enderror" id="feedback" name="feedback" rows="5" required>{{ old('feedback') }}</textarea>
                        @error('feedback')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Please provide detailed feedback about your decision.</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('agency.assignments.show', $assignment) }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-agency">Submit Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Response</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-{{ $assignment->status === 'accepted' ? 'success' : 'danger' }}">
                    <h6 class="alert-heading">{{ $assignment->status === 'accepted' ? 'Assignment Accepted' : 'Assignment Rejected' }}</h6>
                    <p class="mb-0">{{ $assignment->feedback }}</p>
                    <div class="mt-2 text-muted small">
                        Responded on: {{ $assignment->responded_at->format('d M Y, h:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status Updates</h5>
            </div>
            <div class="card-body">
                @if($assignment->statusUpdates->count() > 0)
                    <div class="timeline">
                        @foreach($assignment->statusUpdates as $update)
                            <div class="timeline-item">
                                <div class="timeline-marker 
                                    @if($update->status === 'pending') bg-warning
                                    @elseif($update->status === 'validated') bg-info
                                    @elseif($update->status === 'assigned') bg-primary
                                    @elseif($update->status === 'accepted') bg-success
                                    @elseif($update->status === 'rejected') bg-danger
                                    @elseif($update->status === 'resolved') bg-success
                                    @else bg-secondary @endif
                                "></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold mb-1">{{ ucfirst($update->status) }}</h6>
                                        <span class="text-muted small">{{ $update->created_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                    <p class="mb-0">{{ $update->comment }}</p>
                                    <div class="text-muted small">By: {{ $update->user->name }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                        <p class="mb-0">No status updates found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    
    .timeline-content {
        position: relative;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .timeline-item:last-child .timeline-content {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        left: -23px;
        height: 100%;
        width: 2px;
        background-color: #e3e6f0;
    }
</style>
@endsection