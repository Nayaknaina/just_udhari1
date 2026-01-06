@extends('layouts.vendors.app')
@section('css')
@include('layouts.theme.css.datatable')
@endsection
@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Designation List',[['title' => 'Designations']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a class = "btn btn-sm btn-outline-primary" href = "'.route('designations.create').'"><i class="fa fa-plus"></i> New </a>'];
$data = new_component_array('newbreadcrumb',"Designations") 
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

    <a class = "btn btn-outline-primary" href = "{{route('designations.create')}}"><i class="fa fa-plus"></i>  Add New </a>

  </div>--}}

  <div class="card-body">

    <form action=""> 

    <div class="row">

    

    <div class="col-12 col-lg-6  form-group">
      <label for=""> Designation Name </label>
    <input type="text" id = "name" class = " form-control" placeholder = "Search Role Name"  onchange="changeEntries()" >
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

  @endsection
  

  
  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>

    var route = "{{ route('roles.index') }}";

    function getresult(url) {

    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
            "name": $("#name").val(),
        },
        success: function (data) {
            $("#pagination-result").html(data.html);
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

  </script>



  @endsection