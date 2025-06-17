@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Filter Inquiries') }}</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('mcmc.inquiries.filter') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>{{ __('Assigned') }}</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="date_from" class="form-label">{{ __('Date From') }}</label>
                                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="date_to" class="form-label">{{ __('Date To') }}</label>
                                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="agency_id" class="form-label">{{ __('Agency') }}</label>
                                <select id="agency_id" name="agency_id" class="form-control">
                                    <option value="">{{ __('All Agencies') }}</option>
                                    @foreach ($agencies ?? [] as $agency)
                                        <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>
                                            {{ $agency->AgencyUserName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                                <a href="{{ route('mcmc.inquiries.index') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Submitted By') }}</th>
                                    <th>{{ __('Assigned To') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Submitted At') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inquiries ?? [] as $inquiry)
                                    <tr>
                                        <td>{{ $inquiry->InquiryId }}</td>
                                        <td>{{ $inquiry->Title }}</td>
                                        <td>{{ $inquiry->submitter->PublicName ?? 'N/A' }}</td>
                                        <td>{{ $inquiry->agency->AgencyUserName ?? 'Not Assigned' }}</td>
                                        <td>
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
                                        </td>
                                        <td>{{ $inquiry->submitted_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('mcmc.inquiries.show', $inquiry->InquiryId) }}" class="btn btn-sm btn-info">{{ __('View') }}</a>
                                            
                                            @if ($inquiry->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $inquiry->InquiryId }}">
                                                    {{ __('Assign') }}
                                                </button>
                                                
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $inquiry->InquiryId }}">
                                                    {{ __('Reject') }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('No inquiries found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $inquiries->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Modals -->
@foreach ($inquiries ?? [] as $inquiry)
    @if ($inquiry->status === 'pending')
        <div class="modal fade" id="assignModal{{ $inquiry->InquiryId }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $inquiry->InquiryId }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('mcmc.inquiries.assign', $inquiry->InquiryId) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignModalLabel{{ $inquiry->InquiryId }}">{{ __('Assign Inquiry to Agency') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="inquiry_id" value="{{ $inquiry->InquiryId }}">
                            
                            <div class="mb-3">
                                <label for="agency_id" class="form-label">{{ __('Select Agency') }}</label>
                                <select id="agency_id" name="agency_id" class="form-control" required>
                                    <option value="">{{ __('-- Select Agency --') }}</option>
                                    @foreach ($agencies ?? [] as $agency)
                                        <option value="{{ $agency->id }}">{{ $agency->AgencyUserName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Assign') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="rejectModal{{ $inquiry->InquiryId }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $inquiry->InquiryId }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('mcmc.inquiries.reject', $inquiry->InquiryId) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $inquiry->InquiryId }}">{{ __('Reject Inquiry') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="inquiry_id" value="{{ $inquiry->InquiryId }}">
                            
                            <div class="mb-3">
                                <label for="reason" class="form-label">{{ __('Reason for Rejection') }}</label>
                                <textarea id="reason" name="reason" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-danger">{{ __('Reject') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection