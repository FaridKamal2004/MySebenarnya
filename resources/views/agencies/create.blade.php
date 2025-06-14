@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create a New Agency</h2>
    <form class="row" action="{{ route('agencies.store') }}" method="POST">
        @csrf
        <div class="col-8 mt-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="Enter agency name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-8 mt-3">
            <label class="form-label" for="contact">Contact</label>
            <textarea class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact" cols="30" rows="10" placeholder="Enter contact details"></textarea>
            @error('contact')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-8 mt-3">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</div>
@endsection