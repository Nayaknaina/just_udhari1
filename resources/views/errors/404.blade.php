@extends('layouts.website.app')

@section('content')

<div class="error-area">
    <div class="d-table">
    <div class="d-table-cell">
    <div class="error-content">
    <img src="{{ url('assets/images/404-error.jpg') }}" alt="Image">
    <h3>Oops! Page Not Found</h3>
    <a href = "{{ url('/') }}" class="default-btn btn-bg-two">
    Return To Home Page
    </a>
    </div>
    </div>
    </div>
</div>

@endsection
