@extends('layouts.sidebar')

@section('title', 'Inquiry Status Tracker')

@section('heading', 'Inquiry Status Tracker')

@section('heading_buttons')
<div>
    <a href="{{ route('public.inquiries.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Inquiries
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
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
                        <span><i class="fas fa-calendar me-1"></i> Submitted on: {{ $inquiry->created_at->format('d M Y, h:i A') }}</span>
                        <span class="ms-3"><i class="fas fa-folder me-1"></i> Category: {{ $inquiry->category->name }}</span>
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
    </div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status Timeline</h5>
            </div>
            <div class="card-body">
                <div class="status-tracker">
                    <div class="status-step {{ $inquiry->status == 'pending' || $inquiry->status == 'validated' || $inquiry->status == 'assigned' || $inquiry->status == 'resolved' ? 'active' : '' }}">
                        <div class="status-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="status-content">
                            <h6>Submitted</h6>
                            <p>Your inquiry has been submitted and is awaiting review.</p>
                            <div class="status-date">{{ $inquiry->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                    
                    <div class="status-step {{ $inquiry->status == 'validated' || $inquiry->status == 'assigned' || $inquiry->status == 'resolved' ? 'active' : '' }}">
                        <div class="status-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="status-content">
                            <h6>Validated</h6>
                            <p>Your inquiry has been reviewed and validated by MCMC.</p>
                            <div class="status-date">
                                @if($inquiry->status == 'validated' || $inquiry->status == 'assigned' || $inquiry->status == 'resolved')
                                    {{ $inquiry->statusUpdates->where('status', 'validated')->first()->created_at->format('d M Y, h:i A') }}
                                @else
                                    Pending
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="status-step {{ $inquiry->status == 'assigned' || $inquiry->status == 'resolved' ? 'active' : '' }}">
                        <div class="status-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="status-content">
                            <h6>Assigned</h6>
                            <p>Your inquiry has been assigned to the appropriate agency for action.</p>
                            <div class="status-date">
                                @if($inquiry->status == 'assigned' || $inquiry->status == 'resolved')
                                    {{ $inquiry->statusUpdates->where('status', 'assigned')->first()->created_at->format('d M Y, h:i A') }}
                                @else
                                    Pending
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="status-step {{ $inquiry->status == 'resolved' ? 'active' : '' }}">
                        <div class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="status-content">
                            <h6>Resolved</h6>
                            <p>Your inquiry has been resolved by the assigned agency.</p>
                            <div class="status-date">
                                @if($inquiry->status == 'resolved')
                                    {{ $inquiry->statusUpdates->where('status', 'resolved')->first()->created_at->format('d M Y, h:i A') }}
                                @else
                                    Pending
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
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
    .status-tracker {
        position: relative;
        padding: 20px 0;
    }
    
    .status-step {
        display: flex;
        margin-bottom: 30px;
        position: relative;
        opacity: 0.5;
    }
    
    .status-step.active {
        opacity: 1;
    }
    
    .status-step:last-child {
        margin-bottom: 0;
    }
    
    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e3e6f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 20px;
        position: relative;
        z-index: 2;
    }
    
    .status-step.active .status-icon {
        background-color: #4e73df;
        color: white;
    }
    
    .status-content {
        flex: 1;
    }
    
    .status-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .status-content p {
        margin-bottom: 5px;
        color: #6c757d;
    }
    
    .status-date {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .status-tracker:before {
        content: '';
        position: absolute;
        top: 45px;
        left: 25px;
        height: calc(100% - 90px);
        width: 2px;
        background-color: #e3e6f0;
        z-index: 1;
    }
    
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