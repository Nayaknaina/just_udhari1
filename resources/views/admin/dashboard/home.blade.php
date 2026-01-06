@extends('layouts.admin.app')

@section('content')

    <div class="content-wrapper" style="min-height: 493px;">

        {{ ($superAdmin = Auth::guard('superadmin')->user()->name) }} 

    </div>

@endsection
