
@php  $sub_arr = [] ; @endphp

@section('css')

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/dist/css/timer.css')}}">

@endsection

@foreach (Auth::user()->subscriptions as $subscription )

    @php

        array_push($sub_arr, ['title' => $subscription->product->title, 'expiry_date' => $subscription->expiry_date , 'id' => $subscription->id]) ;

    @endphp

@endforeach

<div class = "row" id = "clock_div" ></div>

@section('javascript')

    @include('layouts.vendors.js.coundown')

@endsection
