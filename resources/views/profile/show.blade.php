@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <h4>{{ Auth::user()->name }}</h4>
                                <p class="text-muted">
                                    @if(Auth::user()->hasRole('mcmc'))
                                        MCMC Administrator
                                    @elseif(Auth::user()->hasRole('agency'))
                                        Agency Representative
                                    @else
                                        Public User
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Name') }}</label>
                                <p class="form-control-static">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <p class="form-control-static">{{ Auth::user()->email }}</p>
                            </div>
                            @if(Auth::user()->hasRole('agency') && Auth::user()->agency)
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Agency') }}</label>
                                    <p class="form-control-static">{{ Auth::user()->agency->name }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">{{ __('Member Since') }}</label>
                                <p class="form-control-static">{{ Auth::user()->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="d-flex">
                                <a href="{{ route('profile.edit') }}" class="btn 
                                    @if(Auth::user()->hasRole('mcmc'))
                                        btn-mcmc
                                    @elseif(Auth::user()->hasRole('agency'))
                                        btn-agency
                                    @else
                                        btn-public
                                    @endif
                                    me-2">{{ __('Edit Profile') }}</a>
                                <a href="{{ route('profile.edit.password') }}" class="btn btn-secondary">{{ __('Change Password') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection