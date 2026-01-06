@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Schemes List',[['title' => 'Schemes']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"All Schemes") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    <!-- <div class="card-header">
    <a class = "btn btn-outline-primary" href = "{{route('schemes.create')}}"><i class="fa fa-plus"></i>  Add New </a>
    </div> -->

    <div class="card-body p-2">

    <form action="">

    <div class="row">

      

      <div class="col-12 col-md-5  form-group">
        <label for=""> Scheme Name </label>
        <input type="text" name = "name" id="name" class = "form-control" placeholder = "Search Schemes Name"  oninput="changeEntries()" >
      </div>

      <div class="col-md-5">
        <div class="row">
          <div class="col-12">
            <label for="">
                <select name="date" id="date" class="bg-white form-control p-0 h-auto" style="border:1px dashed lightgray;font-weight:bold;" onchange="changeEntries()" >
                <option value="">Date of</option>
                <option value="start">Start</option>
                <option value="launch">Launch</option>
                </select>
            </label>
          </div>
          <div class="col-12 col-md-6  form-group">
            <input type="date" name = "start" id="start" class = "form-control date_range text-center" placeholder = "start Date"  onchange="changeEntries()" disabled>
          </div>
        
          <div class="col-12 col-md-6  form-group">
            <input type="date" name = "end" id="end" class = "form-control date_range  text-center" placeholder = "Launch Date"  onchange="changeEntries()" disabled>
          </div>
      </div>
      </div>
	  <div class="col-12 col-md-2 form-group">
        
        <label for="">Show entries</label>
        @include('layouts.theme.datatable.entry')
      </div>
    </div>

    </form>
    <div  class="text-center col-12" id="loader"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></div>
   
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
    function check_date_range(){
      var valid = true;
      if($("#date").val()!=""){
        if($("#start").val()!="" && $("#end").val()!=""){
          if($('#end') < $("#start")){
            valid = false;
          }
        }else{
          valid = false;
        }
      }
      return valid;
    }
    $('#date').change(function(e){
      if($(this).val()!=""){
        $('.date_range').attr('disabled',false);
        $('.date_range').attr('required',true);
      }else{
        $('.date_range').attr('disabled',true);
        $('.date_range').attr('required',false);
        $('.date_range').val('');
      }
    });
    var route = "{{ route('shopbranches.index') }}";

    function getresult(url) {
      if(check_date_range()){
      $("#loader").show();
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "name": $("#name").val(),
                "date": $("#date").val(),
                "start": $("#start").val()??'',
                "end": $("#end").val()??'',
            },
            success: function (data) {
              $("#loader").hide();
              $("#pagination-result").html(data.html);
            },
            error: function () {},
        });
      }else{
        toastr.error("Please recheck the Date Range");
      }
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
