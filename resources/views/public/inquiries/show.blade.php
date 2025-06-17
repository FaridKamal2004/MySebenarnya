@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Inquiry Details') }}</span>
                    <a href="{{ route('public.inquiries.index') }}" class="btn btn-secondary btn-sm">{{ __('Back to Inquiries') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Inquiry ID') }}:</div>
                        <div class="col-md-9">{{ $inquiry->InquiryId }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Title') }}:</div>
                        <div class="col-md-9">{{ $inquiry->Title }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Description') }}:</div>
                        <div class="col-md-9">{{ $inquiry->Description }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Source URL') }}:</div>
                        <div class="col-md-9">
                            @if ($inquiry->sourceURL)
                                <a href="{{ $inquiry->sourceURL }}" target="_blank">{{ $inquiry->sourceURL }}</a>
                            @else
                                <span class="text-muted">{{ __('Not provided') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Attachment') }}:</div>
                        <div class="col-md-9">
                            @if ($inquiry->attachment)
                                <a href="{{ asset('storage/' . $inquiry->attachment) }}" target="_blank">{{ __('View Attachment') }}</a>
                            @else
                                <span class="text-muted">{{ __('No attachment') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Status') }}:</div>
                        <div class="col-md-9">
                            @switch($inquiry->status)
                                @case('pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                    @break
                                @case('assigned')
                                    <span class="badge bg-info">{{ __('Assigned') }}</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-primary">{{ __('In Progress') }}</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">{{ __('Completed') }}</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $inquiry->status }}</span>
                            @endswitch
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">{{ __('Submitted At') }}:</div>
                        <div class="col-md-9">{{ $inquiry->submitted_at->format('d M Y, h:i A') }}</div>
                    </div>

                    @if ($inquiry->agency)
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">{{ __('Assigned To') }}:</div>
                            <div class="col-md-9">{{ $inquiry->agency->AgencyUserName }}</div>
                        </div>
                    @endif

                    <hr>

                    <h5 class="mt-4 mb-3">{{ __('Progress Updates') }}</h5>

                    @if (count($inquiry->progress ?? []) > 0)
                        <div class="timeline">
                            @foreach ($inquiry->progress as $progress)
                                <div class="timeline-item mb-4">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <strong>{{ $progress->created_at->format('d M Y, h:i A') }}</strong>
                                            @if ($progress->ProgressResult)
                                                <span class="badge bg-info ms-2">{{ $progress->ProgressResult }}</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <p>{{ $progress->ProgressDescription }}</p>
                                            
                                            @if ($progress->ProgressEvidence)
                                                <div class="mt-2">
                                                    <strong>{{ __('Evidence') }}:</strong>
                                                    <p>{{ $progress->ProgressEvidence }}</p>
                                                </div>
                                            @endif
                                            
                                            @if ($progress->ProgressReferences)
                                                <div class="mt-2">
                                                    <strong>{{ __('References') }}:</strong>
                                                    <p>{{ $progress->ProgressReferences }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="text-muted mt-2">
                                                @if ($progress->agency)
                                                    <small>{{ __('Updated by') }}: {{ $progress->agency->AgencyUserName }}</small>
                                                @elseif ($progress->mcmcUser)
                                                    <small>{{ __('Updated by') }}: {{ $progress->mcmcUser->MCMCUserName }} (MCMC)</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ __('No progress updates available yet.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection