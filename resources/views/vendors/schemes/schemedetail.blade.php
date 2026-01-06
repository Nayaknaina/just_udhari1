@if($view_type)

@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')
    

@endsection

@section('content')

    @php

        //$data = component_array('breadcrumb' , 'Schemes Details',[['title' => 'Schemes']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Schemes Detail") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
    <div class="container-fluid">

@endif
<style>
        ul.scheme_attr{
            /* display:inline; */
            list-style:none;
        }
        ul.scheme_attr  > li > strong.attr_title,ul.scheme_attr  > li > span.attr_value{
            width:50%;
        }
        ul.scheme_attr  > li > span.attr_value{
            text-align:right;
        }
        ul.scheme_attr > li{
            display:inherit;
        }
    </style>
<div class="card card-primary">
    @php 
        $today = date("Y-m-d",strtotime('now'));
        $end_date = ($schemedetail->scheme_date_fix=='1')?(($schemedetail->scheme_date!="")?date("Y-m-d",strtotime("{$schemedetail->scheme_date}+{$schemedetail->scheme_validity} Month")):false):"Dependent";
        $initiated = ($schemedetail->scheme_date!="")?(($schemedetail->scheme_date<=$today)?true:false):null;
    @endphp
    <div class="card-header p-2">
        <h3>{{ $schemedetail->scheme_head }} 
            @if($view_type)
                @if($end_date && $end_date <$today)
                    <a href="{{route('shopscheme.edit', $schemedetail->id)}}" class="btn btn-warning float-right"><li class="fa fa-edit"></li></a>
                @endif
            @endif
        </h3>
        <hr class="m-1">
        <h4 class="card-title col-12">{{ $schemedetail->scheme_sub_head }}</h4>
    </div>
    <div class="card-body p-2">
        <div class="row">
        <div class="col-md-3">
            <label for="scheme_image" class="form-control text-center p-0 bg-gray" style="height:200px;" id="scheme_image_label">
                <img src="{{ asset($schemedetail->scheme_img) }}" id="ascheme_prev" class="img-responsive" style="height:inherit;width:inherit;">
            </label>
        </div>
        <div class="col-md-9">
            <ul class="scheme_attr row p-0">
                <li class="col-md-4"><strong class="attr_title">Validity</strong><span class="attr_value">{{ $schemedetail->scheme_validity }} Month</span></li>
                <li class="col-md-4"><strong class="attr_title">Scheme Amount</strong><span class="attr_value">{{ $schemedetail->total_amt }} Rs.</span></li>
                <li class="col-md-4"><strong class="attr_title">EMI</strong><span class="attr_value">{{ ($schemedetail->emi_range_start != $schemedetail->emi_range_end)?"{$schemedetail->emi_range_start} - {$schemedetail->emi_range_end}":"{$schemedetail->emi_range_start}" }} Rs.</span></li>
                <li class="col-md-4"><strong class="attr_title">EMI Date</strong><span class="attr_value">{{ ($schemedetail->emi_date!="")?$schemedetail->emi_date:"dd"; }}</span></li>
                
                @if($schemedetail->scheme_interest == "Yes")
                    @php 
                        $interest = ($schemedetail->interest_type=='per')?$schemedetail->interest_rate." %":$schemedetail->interest_amt." Rs.";
                    @endphp
                    <li class="col-md-4"><strong class="attr_title">Interest</strong><span class="attr_value">{{ $interest }}</span></li>
                @endif
            </ul>
            <ul class="scheme_attr row p-0">
                <li class="col-md-4"><strong class="attr_title">Start Date</strong><span class="attr_value">{{ ($schemedetail->scheme_date_fix=='1')?(($schemedetail->scheme_date!="")?$schemedetail->scheme_date:"dd-mm-yyyy"):"Enrolled"; }}</span></li>
                
                <li class="col-md-4"><strong class="attr_title">End Date</strong><span class="attr_value">{{  $end_date }}</span></li>
                
                
                <li class="col-md-4"><strong class="attr_title">Launch Date</strong><span class="attr_value">{{ ($schemedetail->launch_date!="")?$schemedetail->launch_date:"dd-mm-yyyy"; }}</span></li>
            </ul>
        </div>
        <div class="col-md-12 ">
            {!! $schemedetail->scheme_detail_one !!}
        </div>
		<div class="col-md-12 ">
            @if(!empty($schemedetail->scheme_rules))
                <h2>Rules</h2>
                {!! $schemedetail->scheme_rules !!}
            @endif
        </div>
    </div>
    </div>
</div>

@if($view_type)

        </div>
    </section>

    <script>
        function editRow(rowId) {

            // Show input fields and buttons for the selected row
            let inputs = document.querySelectorAll(`input[id^='input-${rowId}-']`);
            let texts = document.querySelectorAll(`span[id^='text-${rowId}-']`);
            inputs.forEach(input => input.style.display = 'block');
            texts.forEach(text => text.style.display = 'none');

            document.getElementById(`save-${rowId}`).style.display = 'inline-block';
            document.getElementById(`cancel-${rowId}`).style.display = 'inline-block';

        }
        
        function saveRow(rowId) {
            // Handle saving logic here, such as submitting a form via AJAX or a regular form submission
            alert("Save logic for row " + rowId);
        
            // Hide the inputs and show the original text after saving
            cancelEdit(rowId);
        }

        function cancelEdit(rowId) {
           
            // Show input fields and buttons for the selected row
            let inputs = document.querySelectorAll(`input[id^='input-${rowId}-']`);
            let texts = document.querySelectorAll(`span[id^='text-${rowId}-']`);
            inputs.forEach(input => input.style.display = 'none');
            texts.forEach(text => text.style.display = 'block');

            document.getElementById(`save-${rowId}`).style.display = 'none';
            document.getElementById(`cancel-${rowId}`).style.display = 'none';
        
        }

        </script>
@endsection

@else
    @php exit(); @endphp
@endif


