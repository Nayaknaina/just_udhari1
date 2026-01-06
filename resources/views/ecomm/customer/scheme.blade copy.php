@extends('ecomm.site')
@section('title', "My Dashboard")
@section('content')
@php 
    @$$activemenu = 'active';
    //dd($enrollcusto);
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
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
                    <thead >    
                        <tr class="bg-secondary">
                            <th>SCHEME</th>
                            <th class="text-center">VALIDITY</th>
                            <th class="text-center">START</th>
                            <th class="text-center">END</th>
                            <th class="text-center">GROUP</th>
                            <th class="text-center">PAYABLE</th>
                            <th class="text-right">PAID</th>
                            <th class="text-right">REMAINS</th>
                            <th class="text-center"><li class="fa fa-eye"></li></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($enrollcusto))
                            @foreach($enrollcusto as $ei=>$enroll)
                            <tr >
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
                                <td class="text-center">
                                    {{ $enroll->groups->count('id') }}
                                </td>
                                @php 
                                    $payable = $enroll->sum('emi_amnt')*$enroll->schemes->scheme_validity;
                                    $paid = $enroll->emipaid->sum('emi_amnt')  
                                @endphp
                                <td class="text-right text-info">
                                    {{ $payable }} Rs.
                                </td>
                                <td class="text-right text-success">
                                    {{ $paid }} Rs.
                                </td>
                                <td class="text-right text-danger">
                                    {{ $payable-$paid }} Rs.
                                </td>
                                @php 
                                    $target_id =  str_replace(" ","_",$enroll->schemes->scheme_head);
                                @endphp
                                <td>
                                    <a href="#{{ $target_id }}" class="show_group btn btn-sm btn-outline-info">
                                        <li class="fa fa-angle-down"></li>
                                    </a>
                                </td>
                            </tr>
                            <tr id="{{ $target_id }}" style="display:none;" class="table-separated">
                                <td colspan="9" class="p-0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>GROUP</th>
                                                <th class="text-right">PAYABLE</th>
                                                <th class="text-right">PAID</th>
                                                <th class="text-right"> REMAINS</th>
                                                <th class="text-center"> More</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($enroll->allgroups as $gi=>$group)
                                            <tr>
                                                <th>
                                                    {{ $group->group_name }}
                                                </th>
                                                
                                                <td class="text-right text-info">
                                                    {{ $group->group_name }}
                                                </td>
                                                <td class="text-right text-success">
                                                        {{ $group->emipaid->sum('emi_amnt') }}
                                                </td>
                                                <td class="text-right text-danger">
                                                    {{ $group->group_name }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="">
                                                        TXNs
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center text-danger" colspan="8">No Schemes !</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
tr.table-separated > td,tr.table-separated > th{
    border-bottom:2px dotted lightgray!important;
}
</style>

@endsection