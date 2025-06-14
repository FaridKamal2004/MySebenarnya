@extends('layouts.sidebar')

@section('title', 'Inquiry Details')

@section('heading', 'Inquiry Details')

@section('heading_buttons')
<a href="{{ route('public.inquiries.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Back to Inquiries
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $inquiry->title }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span class="badge badge-{{ 
                                $inquiry->status === 'pending' ? 'pending' : 
                                ($inquiry->status === 'validated' ? 'validated' : 
                                ($inquiry->status === 'assigned' ? 'assigned' : 
                                ($inquiry->status === 'resolved' ? 'resolved' : 'secondary'))) 
                            }} mb-2">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                            <span class="text-muted ms-2">Submitted on {{ $inquiry->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <span class="badge bg-secondary">{{ $inquiry->category->name }}</span>
                    </div>
                    
                    <div class="inquiry-description p-3 bg-light rounded">
                        {{ $inquiry->description }}
                    </div>
                    
                    @if($inquiry->source_url)
                        <div class="mt-3">
                            <strong>Source URL:</strong> 
                            <a href="{{ $inquiry->source_url }}" target="_blank" rel="noopener noreferrer">
                                {{ $inquiry->source_url }} <i class="fas fa-external-link-alt ms-1 small"></i>
                            </a>
                        </div>
                    @endif
                    
                    @if($inquiry->attachment_path)
                        <div class="mt-3">
                            <strong>Attachment:</strong> 
                            <a href="{{ Storage::url($inquiry->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download Attachment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status Updates</h5>
            </div>
            <div class="card-body">
                @if($inquiry->statusUpdates->count() > 0)
                    <div class="timeline">
                        @foreach($inquiry->statusUpdates as $update)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ 
                                    $update->status === 'pending' ? 'warning' : 
                                    ($update->status === 'validated' ? 'info' : 
                                    ($update->status === 'assigned' ? 'primary' : 
                                    ($update->status === 'resolved' ? 'success' : 'secondary'))) 
                                }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Status changed to <strong>{{ ucfirst($update->status) }}</strong></h6>
                                        <small class="text-muted">{{ $update->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    <p class="mb-0">{{ $update->comment }}</p>
                                    <small class="text-muted">By: {{ $update->user->name }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center py-3">No status updates yet.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Inquiry Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Inquiry ID</span>
                        <span class="badge bg-secondary">{{ $inquiry->id }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Status</span>
                        <span class="badge badge-{{ 
                            $inquiry->status === 'pending' ? 'pending' : 
                            ($inquiry->status === 'validated' ? 'validated' : 
                            ($inquiry->status === 'assigned' ? 'assigned' : 
                            ($inquiry->status === 'resolved' ? 'resolved' : 'secondary'))) 
                        }}">
                            {{ ucfirst($inquiry->status) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Category</span>
                        <span>{{ $inquiry->category->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Submitted</span>
                        <span>{{ $inquiry->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Last Updated</span>
                        <span>{{ $inquiry->updated_at->format('d M Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        @if($inquiry->assignments->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Assigned Agencies</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($inquiry->assignments as $assignment)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $assignment->agency->name }}</span>
                                    <span class="badge badge-{{ 
                                        $assignment->status === 'pending' ? 'pending' : 
                                        ($assignment->status === 'accepted' ? 'resolved' : 
                                        ($assignment->status === 'rejected' ? 'rejected' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </div>
                                <small class="text-muted">Assigned on {{ $assignment->assigned_at->format('d M Y') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .inquiry-description {
        white-space: pre-line;
    }
    
    .timeline {
        position: relative;
        padding-left: 1.5rem;
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
        left: -1.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
    }
    
    .timeline-content {
        position: relative;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .timeline-item:last-child .timeline-content {
        border-bottom: none;
        padding-bottom: 0;
    }
</style>
@endsection