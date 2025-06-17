@extends('layouts.sidebar')

@section('title', 'Inquiry Details')

@section('heading', 'Inquiry Details')

@section('heading_buttons')
<div>
    <a href="{{ route('agency.inquiries.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Inquiries
    </a>
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
                <h5 class="mb-0">Assignment Information</h5>
            </div>
            <div class="card-body">
                @if($assignment)
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
                    <div class="mb-3">
                        <h6 class="fw-bold">Assigned By</h6>
                        <p>{{ $assignment->assignedBy->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Assigned On</h6>
                        <p>{{ $assignment->assigned_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if($assignment->notes)
                    <div class="mb-3">
                        <h6 class="fw-bold">Notes</h6>
                        <p>{{ $assignment->notes }}</p>
                    </div>
                    @endif
                    @if($assignment->status === 'pending')
                        <div class="d-grid gap-2">
                            <a href="{{ route('agency.assignments.respond', $assignment) }}" class="btn btn-agency">
                                <i class="fas fa-reply me-1"></i> Respond to Assignment
                            </a>
                        </div>
                    @else
                        <div class="mb-3">
                            <h6 class="fw-bold">Response</h6>
                            <p>{{ $assignment->feedback }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Responded On</h6>
                            <p>{{ $assignment->responded_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                        <p class="mb-0">No assignment information available.</p>
                    </div>
                @endif
            </div>
        </div>

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
                        <button type="submit" class="btn btn-agency">Add Update</button>
                    </div>
                </form>
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