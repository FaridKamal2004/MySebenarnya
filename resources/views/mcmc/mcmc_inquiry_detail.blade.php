@extends('layouts.sidebar')

@section('title', 'Inquiry Details')

@section('heading', 'Inquiry Details')

@section('heading_buttons')
<div>
    <a href="{{ route('mcmc.inquiries.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Inquiries
    </a>
    @if($inquiry->status === 'pending')
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#validateModal">
        <i class="fas fa-check"></i> Validate
    </button>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
        <i class="fas fa-times"></i> Reject
    </button>
    @elseif($inquiry->status === 'validated')
    <a href="{{ route('mcmc.assignments.create', $inquiry) }}" class="btn btn-mcmc">
        <i class="fas fa-paper-plane"></i> Assign to Agency
    </a>
    @endif
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Inquiry Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>{{ $inquiry->title }}</h4>
                        <span class="badge badge-{{ 
                            $inquiry->status === 'pending' ? 'pending' : 
                            ($inquiry->status === 'validated' ? 'validated' : 
                            ($inquiry->status === 'assigned' ? 'assigned' : 
                            ($inquiry->status === 'resolved' ? 'resolved' : 'secondary'))) 
                        }}">
                            {{ ucfirst($inquiry->status) }}
                        </span>
                    </div>
                    <div class="text-muted mb-3">
                        <span><i class="fas fa-user me-1"></i> {{ $inquiry->user->name }}</span>
                        <span class="ms-3"><i class="fas fa-calendar me-1"></i> {{ $inquiry->created_at->format('d M Y, h:i A') }}</span>
                        <span class="ms-3"><i class="fas fa-folder me-1"></i> {{ $inquiry->category->name }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Description</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $inquiry->description }}
                    </div>
                </div>

                @if($inquiry->source_url)
                <div class="mb-4">
                    <h6 class="fw-bold">Source URL</h6>
                    <div class="p-3 bg-light rounded">
                        <a href="{{ $inquiry->source_url }}" target="_blank">{{ $inquiry->source_url }}</a>
                    </div>
                </div>
                @endif

                @if($inquiry->attachment_path)
                <div class="mb-4">
                    <h6 class="fw-bold">Attachment</h6>
                    <div class="p-3 bg-light rounded">
                        <a href="{{ asset('storage/' . $inquiry->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download Attachment
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Status Updates</h5>
            </div>
            <div class="card-body">
                @if($inquiry->statusUpdates->count() > 0)
                    <div class="timeline">
                        @foreach($inquiry->statusUpdates as $update)
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

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Submitter Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Name</h6>
                    <p>{{ $inquiry->user->name }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Email</h6>
                    <p>{{ $inquiry->user->email }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Joined</h6>
                    <p>{{ $inquiry->user->created_at->format('d M Y') }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Total Inquiries</h6>
                    <p>{{ $inquiry->user->inquiries->count() }}</p>
                </div>
            </div>
        </div>

        @if($inquiry->status === 'assigned' || $inquiry->status === 'resolved')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Assignment Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Assigned To</h6>
                    <p>{{ $assignment->agency->name }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Assigned By</h6>
                    <p>{{ $assignment->assignedBy->name }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Assigned On</h6>
                    <p>{{ $assignment->assigned_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Status</h6>
                    <span class="badge badge-{{ 
                        $assignment->status === 'pending' ? 'pending' : 
                        ($assignment->status === 'accepted' ? 'resolved' : 
                        ($assignment->status === 'rejected' ? 'rejected' : 'secondary')) 
                    }}">
                        {{ ucfirst($assignment->status) }}
                    </span>
                </div>
                @if($assignment->responded_at)
                <div class="mb-3">
                    <h6 class="fw-bold">Response</h6>
                    <p>{{ $assignment->feedback }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Responded On</h6>
                    <p>{{ $assignment->responded_at->format('d M Y, h:i A') }}</p>
                </div>
                @endif
                <div class="d-grid">
                    <a href="{{ route('mcmc.assignments.show', $assignment) }}" class="btn btn-mcmc">
                        <i class="fas fa-eye me-1"></i> View Assignment Details
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add Status Update</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('status.store', $inquiry) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-mcmc">Add Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Validate Modal -->
<div class="modal fade" id="validateModal" tabindex="-1" aria-labelledby="validateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validateModalLabel">Validate Inquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('mcmc.inquiries.validate', $inquiry) }}">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to validate this inquiry?</p>
                    <div class="mb-3">
                        <label for="validate_comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="validate_comment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Validate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Inquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('mcmc.inquiries.reject', $inquiry) }}">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject this inquiry?</p>
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label">Reason for Rejection</label>
                        <select class="form-select mb-3" id="reject_reason" name="reason" required>
                            <option value="">-- Select Reason --</option>
                            <option value="duplicate">Duplicate Inquiry</option>
                            <option value="insufficient">Insufficient Information</option>
                            <option value="inappropriate">Inappropriate Content</option>
                            <option value="outside_scope">Outside Scope</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reject_comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="reject_comment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
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