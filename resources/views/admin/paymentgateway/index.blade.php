@extends('layouts.admin.app')

@section('content')

 

  @php

  $data = component_array('breadcrumb' , 'Gateway Tamplate List' ,[['title' => 'PGateway Tamplate List']] ) ;

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
  

  <a class = "btn btn-outline-primary" href = "{{route('paymentgateway.create')}}"><i class="fa fa-plus"></i>  Add New </a>
  

  </div>
  
  
  <div class="card-body">

    <form action=""> 

    <div class="row">

    <div class="col-6 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>

    <div class="col-6 col-lg-4  form-group">
      <label for=""> Role Name </label>
    <input type="text" id = "name" class = "vin_no form-control" placeholder = "Search Role Name"  oninput="changeEntries()" >
    </div>

    </div>

    </form>

    <div id = "pagination-result">
    
    </div>
    
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
      $("#pagination-result").empty().append('<p class="text-center col-12"><span style="background:lightgray;padding:2px;"><i class="fa fa-spinner fa-spin"></i> Loading Content...</span></p>');
      
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

    $(document).on('click','.',function(e){
      e.preventDefault();
      var url = $(this).attr('href');
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
    })
  </script>



  @endsection