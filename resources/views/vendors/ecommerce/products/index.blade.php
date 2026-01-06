@extends('layouts.vendors.app')

@section('css')
<style>
  .dropdown.sub_drop_over{
    position:absolute;
    top:0;
    right:0;
  }
  .dropdown.sub_drop_over>.dropdown-menu{
    width:auto;
    min-width: unset;
  }
</style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Ecommerce Product List',[['title' => 'Ecommerce Product']] ) ;
$data = new_component_array('newbreadcrumb' , 'Ecommerce Product List' ) ; 

@endphp
<x-new-bread-crumb :data=$data />
{{--<x-page-component :data=$data />--}}

    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    <div class="card-body">

    <form action="">

    <div class="row">

	<div class="col-6 col-lg-4  form-group">
      <label for=""> Product Name </label>
    <input type="text" id = "name" name="name" class = "vin_no form-control" placeholder = "Search Product Name"  oninput="changeEntries()" >
    </div>

    <div class="col-6 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>

    

    </div>

    </form>
<hr class="col-12 m-0 py-2" style="border-top:1px solid lightgray;">
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

    function getresult(url) {
      console.log($("#supplier_name").val()) ;
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

@include('layouts.vendors.js.passwork-popup')

  @endsection
