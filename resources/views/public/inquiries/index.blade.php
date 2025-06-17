@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('My Inquiries') }}</span>
                    <a href="{{ route('public.inquiries.create') }}" class="btn btn-primary btn-sm">{{ __('Submit New Inquiry') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($inquiries ?? []) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Submitted At') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inquiries as $inquiry)
                                        <tr>
                                            <td>{{ $inquiry->InquiryId }}</td>
                                            <td>{{ $inquiry->Title }}</td>
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
                                            <td>{{ $inquiry->submitted_at->format('d M Y, h:i A') }}</td>
                                            <td>
                                                <a href="{{ route('public.inquiries.show', $inquiry->InquiryId) }}" class="btn btn-sm btn-info">{{ __('View') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $inquiries->links() }}
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            {{ __('You have not submitted any inquiries yet.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection