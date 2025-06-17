@extends('layouts.sidebar')

@section('title', 'Assignment Details')

@section('heading', 'Assignment Details')

@section('heading_buttons')
<div>
    <a href="{{ route('mcmc.inquiries.show', $assignment->inquiry) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Inquiry
    </a>
    @if($assignment->status === 'rejected')
    <a href="#" class="btn btn-mcmc" data-bs-toggle="modal" data-bs-target="#reassignModal">
        <i class="fas fa-exchange-alt"></i> Reassign
    </a>
    @endif
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Assignment Information</h5>
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
                        <h6 class="fw-bold">Assignment Details</h6>
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

                @if($assignment->notes)
                <div class="mb-4">
                    <h6 class="fw-bold">Assignment Notes</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $assignment->notes }}
                    </div>
                </div>
                @endif

                @if($assignment->responded_at)
                <div class="mb-4">
                    <h6 class="fw-bold">Agency Response</h6>
                    <div class="alert alert-{{ $assignment->status === 'accepted' ? 'success' : 'danger' }}">
                        <h6 class="alert-heading">{{ $assignment->status === 'accepted' ? 'Assignment Accepted' : 'Assignment Rejected' }}</h6>
                        <p class="mb-0">{{ $assignment->feedback }}</p>
                        <div class="mt-2 text-muted small">
                            Responded on: {{ $assignment->responded_at->format('d M Y, h:i A') }}
                        </div>
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

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Agency Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Name</h6>
                    <p>{{ $assignment->agency->name }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Email</h6>
                    <p>{{ $assignment->agency->email }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Phone</h6>
                    <p>{{ $assignment->agency->phone }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Address</h6>
                    <p>{{ $assignment->agency->address }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Contact Person</h6>
                    <p>{{ $assignment->agency->contact_person }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add Status Update</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('status.store', $assignment->inquiry) }}">
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

<!-- Reassign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Reassign Inquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('mcmc.assignments.reassign', $assignment) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="agency_id" class="form-label">Select New Agency</label>
                        <select class="form-select" id="agency_id" name="agency_id" required>
                            <option value="">-- Select Agency --</option>
                            @foreach($agencies as $agency)
                                @if($agency->id != $assignment->agency_id)
                                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        <div class="form-text">Provide any additional information for the new agency.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-mcmc">Reassign</button>
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