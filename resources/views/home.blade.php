@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-primary" href="{{ route('agencies.index') }}">Agency Profiel</a>
            </div>
        </div>
    </div>
@endsection