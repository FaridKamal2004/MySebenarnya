@extends('layouts.mcmc')

@section('title', 'Agency Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Agency Details</h1>
        <div>
            <a href="{{ route('agencies.edit', $agency) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Agency
            </a>
            <a href="{{ route('agencies.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Agencies
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Agency Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Agency Name:</strong>
                        <p>{{ $agency->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Contact Person:</strong>
                        <p>{{ $agency->contact }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p>{{ $agency->email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <p>{{ $agency->phone }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Address:</strong>
                        <p>{{ $agency->address }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Agency Users</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agency->users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Assigned Inquiries</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Assigned Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agency->assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->id }}</td>
                            <td>{{ $assignment->inquiry->title }}</td>
                            <td>
                                <span class="badge bg-{{ $assignment->status == 'pending' ? 'warning' : ($assignment->status == 'accepted' ? 'success' : 'danger') }}">
                                    {{ ucfirst($assignment->status) }}
                                </span>
                            </td>
                            <td>{{ $assignment->assigned_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection