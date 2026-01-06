@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        $data = component_array('breadcrumb' , 'Enroll Customers List',[['title' => 'Schemes']] ) ;

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
    <a class = "btn btn-outline-primary" href = "{{route('enrollcustomer.create')}}"><i class="fa fa-plus"></i>  Add New </a>
    @if (Session::has('error'))
      <strong class="text-danger">{!! Session::get('error') !!}</strong>
    @endif
    </div> 

    <div class="card-body">

    <form action="">

    <div class="row">

    <div class="col-6 col-lg-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>

    <!-- <div class="col-6 col-lg-4  form-group">
      <label for=""> Branches Name </label>
    <input type="text" name = "scheme_name" class = "vin_no form-control" placeholder = "Search Schemes Name"  onchange="changeEntries()" >
    </div> -->

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

    var route = "{{ route('shopbranches.index') }}";

    function getresult(url) {

    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
            "vin_no": $(".vin_no").val(),
            "customer_name": $(".customer_name").val(),
            "model_name": $(".model_name").val(),
            "date": $(".date").val(),
            "month": $(".month").val(),
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
  <script>
      let path = "";
      $(document).on('click',".enroll_custo_delete",function(e){
        e.preventDefault();
        path = $(this).attr('href');
        $("#mpinerror").addClass('d-none');
        $('input[type="password"]').val("");
        $("#blockingmodal").modal();
      });
      
      $('#mpincheckform').on('submit', function(e) {
          e.preventDefault();
          var formdata = $(this).serialize();
          var action = "{{ route('shopschemes.mpincheck') }}";
          $.post(action,formdata,function(response){
          var res = JSON.parse(response);
          if(res[0]){
              $("#blockingmodal").modal('hide');
              //form.trigger('submit');
              deleteenrollment(path); 
          }else{
              $("#mpinerror").empty().append("Invalid MPIN !");
              $("#mpinerror").removeClass('d-none');
              toastr.error("Invalid MPIN !") ;
          }
          })
      });
      
      // $(form).on('submit',function(e){
      //   e.preventDefault();
      //   const path = form.attr('action');
      //   var formdata = form.serialize();
      //   alert(path);
      //   alert(formdata);
      // });

      function deleteenrollment(path){
        $.post(path,{_method:'DELETE',_token:"{{ csrf_token() }}"},function(response){
          if(response.status){
            success_sweettoatr(response.msg);
            location.reload();
          }else{
            toastr.error(response.msg);
          }
        });
      }
      // $(document).on('submit',form,function(e){
      //   e.preventDefault();
      //   const path = form.attr('action');
      //   var formdata = form.serialize();
      //   alert(path);
      //   alert(formdata);
      //   $(document).off('submit',form);
      // });
      //function deleteenrollment(path){
        //$.post(path,{_method:'DELETE',_token:""},function(response){

        //});
        // $.ajax({
        //       url: path,
        //       type: 'DELETE',
        //       data: "",
        //       dataType: 'json',
        //       contentType: false,
        //       processData: false,
        //       beforeSend: function() {
        //       // $('.btn').prop("disabled", true);
        //       // $('#loader').removeClass('hidden');
        //       },
        //       success: function(response) {
        //       // Handle successful update
        //       success_sweettoatr(response.success);
        //       window.open("{{route('enrollcustomer.index')}}", '_self');
        //       },
        //       error: function(response) {
        //       if (response.status === 422) {
        //           var errors = response.responseJSON.errors;
        //           $('input').removeClass('is-invalid');
        //           $('.btn-outline-danger').prop("disabled", false);
        //           $('.btn').prop("disabled", false);
        //           $('#loader').addClass('hidden');
        //           $.each(errors, function(field, messages) {
        //           var $field = $('[name="' + field + '"]');
        //           toastr.error(messages[0]) ;
        //           $field.addClass('is-invalid') ;
        //           });
        //       } else {
        //           console.log(response.responseText);
        //       }
        //       }
        //   });
      //}
      
</script>



  @endsection
