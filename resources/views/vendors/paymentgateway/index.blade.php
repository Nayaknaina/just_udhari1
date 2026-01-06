@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Payment Gaeways' ,[['title' => 'Payment Gaeways']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Payment Gaeways") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    {{--<div class="card-header">
    <a class = "btn btn-outline-primary" href = "{{route('customers.create')}}"><i class="fa fa-plus"></i>  Add New </a>
    </div>--}}

    <div class="card-body p-1">

    <form action="">

    <div class="row">

    
    <div class="col-12 col-lg-6  form-group">
      <label for="gateway"> Gateway Name </label>
    <input type="text" id = "gateway" class = " form-control" placeholder = "Search Gateway Name"  oninput="changeEntries()" >
    </div>
    <div class="col-12 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>

    </div>
        

    </form>

    <div id = "pagination-result"></div>
        
    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>
 
@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>

    var route = "{{ route('customers.index') }}";

    function getresult(url) {
      $("#pagination-result").empty().append('<p class="text-center" style="border:1px dashed gray;padding:5px;color:white;"><span style="background:lightgray;padding:2px;"><i class="fa fa-spinner fa-spin"></i> Loading Content ...</span></p>');
      //console.log($("#customer_name").val()) ;
    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
            "gateway": $("#gateway").val(),
        },
        success: function (data) {
            $("#pagination-result").empty().html(data.html);
        },
        error: function () {},
    });
    }

    getresult(url);

    $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);

        });

    function changeEntries() {

    getresult(url);

    }
    $(document).on('click','.status_btn',function(e){
        e.preventDefault();
        const self = $(this);
        $.get($(this).attr('href'),"",function(response){
          if(response.success){
              var mark = (self.text()=="Offline")?"Online":"Offline";
              self.text(mark);
              self.toggleClass('active');
              toastr.success(response.success);
          }else{
              toastr.error(response.error);
          }
        });
    });
  </script>


  @include('layouts.vendors.js.passwork-popup')

  @endsection

