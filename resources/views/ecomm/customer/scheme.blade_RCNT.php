@extends('ecomm.site')
@section('title', "My Dashboard")
@section('content')
@php
@$$activemenu = 'active';
//dd($enrollschemes);
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2">
        <div class="customer_page_head col-md-4 row text-center px-1">
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase mb-3 " style="margin:auto;">My Schemes</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 col-12 customer_info_block">
            <div class="table-responsive">
                <table class="table table-bordered table-default">
                    <thead>
                        <tr class="bg-secondary">
                            <th>SN.</th>
                            <th>SCHEME</th>
                            <th class="text-center">VALIDITY</th>
                            <th class="text-center">START</th>
                            <th class="text-center">END</th>
                            <th class="text-center">
                                <li class="fa fa-eye"></li>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($enrollschemes))
                        @foreach($enrollschemes as $ei=>$enroll)
                        <tr>
                            <td>{{ $ei+1 }}</td>
                            <td>
                                <b>{{ $enroll->schemes->scheme_head }}</b>
                                <hr class="m-1 p-0">
                                {{ $enroll->schemes->scheme_sub_head }}
                            </td>
                            <td>
                                {{ $enroll->schemes->scheme_validity }} Month
                            </td>
                            <td>
                                {{date('d-m-Y',strtotime($enroll->schemes->scheme_date)) }}
                            </td>
                            <td>
                                {{ date('d-m-Y',strtotime("{$enroll->schemes->scheme_date}+{$enroll->schemes->scheme_validity} Month")) }}
                            </td>
                            @php
                            $target_id = str_replace(" ","_",$enroll->schemes->scheme_head);
                            @endphp
                            <td>
                                <a href="{{ url("{$ecommbaseurl}schemedetail/{$enroll->schemes->id}") }}" data-target="{{ $target_id }}" class="show_group btn btn-sm btn-outline-info">
                                    <li class="fa fa-angle-down"></li>
                                </a>
                            </td>
                        </tr>
                        <tr id="{{ $target_id }}" style="display:none;" class="table-separated">

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center text-danger" colspan="6">No Schemes !</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    tr.table-separated>td,
    tr.table-separated>th {
        border-bottom: 2px solid lightgray !important;
    }
</style>

@endsection
@section('javascript')
<script>
    $('.show_group').click(function(e) {
        e.preventDefault();
        const path = $(this).attr('href');
        const target = $(this).data('target');
        $("#" + target).empty().append('<td class="text-center text-danger" colspan="6" style="background:#00000045;"><span style="color:white;"><li class="fa fa-spinner fa-spin"> </li> Loding Content..</span></td>');
        var get_data = ($("#" + target).css('display') == 'none') ? true : false;
        $("#" + target).toggle();
        if (get_data) {
            $.get(path, "", function(response) {
                var res = JSON.parse(response);
                $("#" + target).empty().append(res.html);

            });
        }
    });
</script>
@endsection
