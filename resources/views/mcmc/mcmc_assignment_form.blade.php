@extends('layouts.sidebar')

@section('title', 'Assign Inquiry')

@section('heading', 'Assign Inquiry')

@section('heading_buttons')
<div>
    <a href="{{ route('mcmc.inquiries.show', $inquiry) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Inquiry
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
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assignment Form</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('mcmc.assignments.store', $inquiry) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="agency_id" class="form-label">Select Agency</label>
                        <select class="form-select @error('agency_id') is-invalid @enderror" id="agency_id" name="agency_id" required>
                            <option value="">-- Select Agency --</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('agency_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes for Agency</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="5">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Provide any additional information or instructions for the agency.</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-mcmc">Assign Inquiry</button>
                        <a href="{{ route('mcmc.inquiries.show', $inquiry) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection