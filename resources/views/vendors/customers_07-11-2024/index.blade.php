@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    $data = component_array('breadcrumb' , 'Customers List' ,[['title' => 'Customers']] ) ;

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
    <a class = "btn btn-outline-primary" href = "{{route('customers.create')}}"><i class="fa fa-plus"></i>  Add New </a>
    </div>

    <div class="card-body">

    <form action="">

    <div class="row">

    <div class="col-6 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>

    <div class="col-6 col-lg-4  form-group">
      <label for=""> Customers Name </label>
    <input type="text" id = "customer_name" class = " form-control" placeholder = "Search Customer Name"  onchange="changeEntries()" >
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
      console.log($("#customer_name").val()) ;
    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
            "customer_name": $("#customer_name").val(),

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

