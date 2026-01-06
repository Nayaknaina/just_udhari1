@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

@php

$data = component_array('breadcrumb' , 'Text Message Api',[['title' => 'Text Message Api']] ) ;

@endphp
<style>
    a.socket{
        height:20px!important;
        width:45px;
        border:1px solid lightgray;
        margin:5px 0;
        position:relative;
        display:block;
        margin:auto;
    }
    span.switch:before{
        position:absolute;
        width:50%;
        height:100%;
        font-size:10px;
        text-align:center;
    }
    span.switch.on:before{
        border:1px solid green;
        background:lightgreen;
        color:green;
        content:'On';
        right:0;
    }
    span.switch.off:before{
        border:1px solid red;
        background:pink;
        color:red;
        content:'Off';
        left:0;
    }
    tr.disabled >td{
        position:relative
    }
    tr.disabled >td:before{
        content:"";
        width:100%;
        height:100%;
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        right:0;
        background:black;
        opacity:0.5;
        z-index:1;
    }
</style>
<x-page-component :data=$data />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h6 class="card-title">Text Message Api Setting</h6>
                        </div>
                        <div class="card-body row">
                            @php 
                                $border_arr = ['border-danger','border-success'];
                                $status_arr = ['off','on'];
                            @endphp
                            <div class="card m-auto col-md-6 p-0 {{ isset($apiurl->status)?$border_arr[$apiurl->status]:null }}">
                                <div class="card-header p-1">
                                <h5>
                                    @if(isset($apiurl->url) && $apiurl->url!="")
                                    <a href="{{ route('textmsgeapi.edit',[$apiurl->id,'edit'=>'url']) }}" class="btn btn-sm btn-outline-info editButton pull-right"><li class="fa fa-edit"></li></a>
                                    @else 
                                    <a href="{{ route('textmsgeapi.create',['new'=>'url']) }}" class="btn btn-sm btn-primary pull-right" ><li class="fa fa-plus"></li></a>
                                    @endif
                                    API
                                    @if(isset($apiurl->id))
                                    <label style="float:right;">
                                        <a href="{{ route('textmsgeapi.show',[$apiurl->id,'status'=>'url'])}}" class="socket" id="url_switch"><span class="switch {{ $status_arr[$apiurl->status] }}"></span></a>
                                    </label>
                                    @endif
                                </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group m-auto w-auto p-1 h-auto ">
                                        <label class=""><b>API Url =></b></label>
                                        <label style="font-weight:normal;word-wrap: anywhere;">{{ @$apiurl->url }}</label>
                                    </div>
                                    <div class="form-group m-auto w-auto p-1 h-auto ">
                                        <label class=""><b>API Key =></b></label>
                                        <label style="font-weight:normal;word-wrap: anywhere;">{{ @$apiurl->api_key }}</label>
                                    </div>
                                    <div class="form-group m-auto w-auto p-1 h-auto ">
                                        <label class=""><b>API Route =></b></label>
                                        <label style="font-weight:normal;word-wrap: anywhere;">
											@if(!empty($apiurl))
												 {{ ($apiurl->route=='q')?'Q(Quick Message)':strtoupper($apiurl->route) }}
											@endif
										</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="col-12">
                            <div class="col-12">
                                <h5  class="bg-light p-2" style="border:1px solid lightgray;">
                                    Tamplates
                                    <a href="{{ route('textmsgeapi.create',['new'=>'tamplate']) }}" class="btn btn-sm btn-primary" style="float:right;"><li class="fa fa-plus"></li> Add New</a>
                                </h5>
                                <div class="row">
                                    <div class="col-12 col-lg-8  form-group">
                                        <label for="">Head Search</label>
                                        <input type="text" id = "keyword" class = "form-control" placeholder = "Head Search (Enter Keyword )" oninput="changeEntries()" >
                                    </div>
                                    <div class="col-md-2  form-group">
                                        <label for="">Show entries</label>
                                        @include('layouts.theme.datatable.entry')
                                    </div>
                                </div>
                                <div class="table-responsive">  
                                    <table class="table table-bordered table-stripped" id="CsTable">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>SN.</th>
                                                <th>HEAD</th>
                                                <th>BODY</th>
                                                <th>VARIABLES</th>
                                                <th>DETAIL</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_area">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12" id="paging_area">
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('javascript')
@include('layouts.vendors.js.passwork-popup')

@include('layouts.theme.js.datatable')


    <script>
        var route = "{{ route('textmsgeapi.index') }}";

        function getresult(url) {
            $("#loader").show();
            const loading_tr = '<tr><td colspan="7" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
            $("#data_area").html(loading_tr)
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    "entries": $(".entries").val(),
                    "keyword": $("#keyword").val(),
                },
                success: function (data) {
                    $("#loader").hide();
                    $("#data_area").html(data.html);
                    $("#paging_area").html(data.paging);
                    //$("#pagination-result").html(data.html);
                },
                error: function (data) {
                    $("#loader").hide();
                },
            });
        }
        getresult(url) ;

        $(document).on('click', '.pagination a', function (e) {

                e.preventDefault();
                var pageUrl = $(this).attr('href');
                getresult(pageUrl);

        });

        function changeEntries() {

            getresult(url) ;

        }
        $(document).on('click','a.socket',function(e){
            e.preventDefault();
            var span = $(this).find('span.switch');
            const id = $(this).attr('id')??false;
            const div = $(this).closest('div.card');
            $.get($(this).attr('href'),"",function(response){
                if(response.status){
                    span.toggleClass('on off');
                    if(id=='url_switch'){
                        div.toggleClass('border-success border-danger');
                    }
                    success_sweettoatr(response.msg);
                }else{
                    toastr.error(response.msg);
                }
            });
        });
    </script>


@endsection
