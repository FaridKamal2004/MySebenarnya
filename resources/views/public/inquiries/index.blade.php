@extends('layouts.sidebar')

@section('title', 'My Inquiries')

@section('heading', 'My Inquiries')

@section('heading_buttons')
<a href="{{ route('public.inquiries.create') }}" class="btn btn-public">
    <i class="fas fa-plus-circle"></i> Submit New Inquiry
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if($inquiries->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Last Update</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                            <tr>
                                <td>{{ $inquiry->id }}</td>
                                <td>{{ $inquiry->title }}</td>
                                <td>{{ $inquiry->category->name }}</td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $inquiry->status === 'pending' ? 'pending' : 
                                        ($inquiry->status === 'validated' ? 'validated' : 
                                        ($inquiry->status === 'assigned' ? 'assigned' : 
                                        ($inquiry->status === 'resolved' ? 'resolved' : 'secondary'))) 
                                    }}">
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                </td>
                                <td>{{ $inquiry->created_at->format('d M Y') }}</td>
                                <td>{{ $inquiry->updated_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('public.inquiries.show', $inquiry) }}" class="btn btn-sm btn-public">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-gray-300 mb-3"></i>
                <h4 class="text-muted mb-3">No Inquiries Found</h4>
                <p class="mb-4">You haven't submitted any inquiries yet.</p>
                <a href="{{ route('public.inquiries.create') }}" class="btn btn-public">
                    <i class="fas fa-plus-circle"></i> Submit Your First Inquiry
                </a>
            </div>
        @endif
    </div>
</div>
@endsection