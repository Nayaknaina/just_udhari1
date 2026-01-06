@extends('ecomm.site')
@section('title', "Myu Cart")
@section('content')
@php
    @$$activemenu = 'active';
@endphp

<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2 " >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">Scheme Enquiry</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->


<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none  dashboard_lg_control">
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9">
            <div class="table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>ENTRY</th>
                            <th>SCHEME <li class="fa fa-link"></li></th>
                            <th>MESSAGE</th>
                            <th>RESPONSE</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @if($data->count()>0)
                            @foreach($data as $dk=>$enquiry)
                                <tr class="{{ ($enquiry->status==01)?'table-info':(($enquiry->status==11)?'table-success':(($enquiry->status==10)?'table-danger':'')) }}">
                                    <td>{{ date('d-M-Y h:i:a',strtotime($enquiry->created_at)) }}</td>
                                    <td>
                                    <a href="{{ url("{$ecommbaseurl}scheme/{$enquiry->scheme->url_part}") }}" target="_blank">
                                        {{ $enquiry->scheme->scheme_head }}
                                        @if($enquiry->scheme->scheme_sub_head!="")
                                        <hr class="m-1">
                                        {{ $enquiry->scheme->scheme_sub_head }}
                                        @endif
                                    </a>
                                    </td>
                                    <td>{{ $enquiry->message }}</td>
                                    <td>
                                    <b id="status">
                                        @switch($enquiry->status)
                                        @case(01)
                                            READ
                                            @break
                                        @case(11)
                                            ENROLLED
                                            @break
                                        @case(10)
                                            REJECTED
                                            @break
                                        @default
                                            PENDING
                                            @break
                                        @endswitch
                                    </b>
                                    <hr class="m-1">
                                    {{ date('d-M-Y h:i:a',strtotime($enquiry->updated_at)) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr><td colspan="4" class="text-danger text-center">No Scheme Enquiry !</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection
