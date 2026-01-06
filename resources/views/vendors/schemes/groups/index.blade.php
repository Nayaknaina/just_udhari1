@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        //$data = component_array('breadcrumb' , 'Scheme Group 4',[['title' => 'Schemes']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a class = "btn btn-sm btn-outline-primary" href = "'.route('group.create').'"><i class="fa fa-plus"></i> New </a>'];
$data = new_component_array('newbreadcrumb',"Scheme Groups") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    {{--<div class="card-header">
    <a class = "btn btn-outline-primary" href = "{{route('group.create')}}"><i class="fa fa-plus"></i>  Add New </a>
    </div> --}}

    <div class="card-body p-2">

    <form action="">

    <div class="row">

    
    <div class="col-12 col-lg-6 form-group">
      <label for="">Schemes</label>
      <input type="text" name="scheme" id="scheme" class="form-control" placeholder="Scheme Name" oninput="changeEntries()">
    </div>
    <div class="col-12 col-lg-4 form-group">
      <label for="">Group</label>
      <input type="text" name="group" id="group" class="form-control" placeholder="Group Name" oninput="changeEntries()">
    </div>
	<div class="col-12 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>
    <!-- <div class="col-6 col-lg-4  form-group">
      <label for=""> Branches Name </label>
    <input type="text" name = "scheme_name" class = "vin_no form-control" placeholder = "Search Schemes Name"  onchange="changeEntries()" >
    </div> -->

    </div>

    </form>

    <div  class="text-center col-12" id="loader"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></div>
    <div id = "pagination-result"> </div>
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

    function getresult(url) {
        
      $("#loader").show();
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "scheme": $("#scheme").val(),
                "group": $("#group").val(),
            },
            success: function (data) {
                $("#loader").hide();
                $("#pagination-result").html(data.html);
            },
            error: function () {},
        });
    }

    getresult(url) ;

    $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);

        });

    function changeEntries() {
        getresult(url);

    }

  </script>

  

@include('layouts.vendors.js.passwork-popup')

  @endsection
