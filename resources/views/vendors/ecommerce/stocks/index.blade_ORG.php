@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    $data = component_array('breadcrumb' , 'Stocks List',[['title' => 'Stocks']] ) ;

    @endphp

    <x-page-component :data=$data />

    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    <div class="card-header">

    </div>

    <div class="card-body">

    <form action=""> 

    <div class="row">

    <div class="col-6 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>

    <div class="col-6 col-lg-4  form-group">
      <label for=""> Ecom Stocks Name </label>
    <input type="text" id = "supplier_name" class = "vin_no form-control" placeholder = "Search Supplier Name"  onchange="changeEntries()" >
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

    var route = "{{ route('ecomstocks.index') }}";

    function getresult(url) {
      console.log($("#supplier_name").val()) ;
    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
            "supplier_name": $("#supplier_name").val(),
           
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