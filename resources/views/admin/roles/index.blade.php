@extends('layouts.admin.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Roles List</h1>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href = "{{ route('dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active">Roles</li>
                  </ol>
              </div>
          </div>
      </div><!-- /.container-fluid -->
  </section>

  <section class="content">
  <div class="container-fluid">
  <div class="row">
  <!-- left column -->
  <div class="col-md-12">
  <!-- general form elements -->
  <div class="card ">
  <div class="card-header">
  
  @can('Role create')
  <a class = "btn btn-outline-primary" href = "{{route('roles.create')}}"><i class="fa fa-plus"></i>  Add New </a>
  @endcan 

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
    <input type="text" id = "name" class = "vin_no form-control" placeholder = "Search Role Name"  onchange="changeEntries()" >
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