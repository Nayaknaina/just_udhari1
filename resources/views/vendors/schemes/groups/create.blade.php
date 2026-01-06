  @extends('layouts.vendors.app')

  @section('content')

  @php

  //$data = component_array('breadcrumb' , 'Scheme Group',[['title' => 'Schemes']] ) ;

  @endphp

  {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('group.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Scheme Groups"=>route('group.index')];
$data = new_component_array('newbreadcrumb',"New Scheme Group",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">

            <!--<div class="card-header">
            <h3 class="card-title"><x-back-button /> Create </h3>
            </div>-->

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('group.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">

                <div class="col-lg-12 form-group ">
                    <label for=""> Schemes </label>
                    <select name="scheme_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($schemes as $scheme )

                            <option value="{{ $scheme->id }}" {{ ($scheme->ss_status=='0')?"disabled":"" }}>{{ $scheme->scheme_head }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-12 form-group ">
                    <label for=""> Group Name </label>
                    <input type = "text" name="group_name" id = "group_name" class="form-control form-group" placeholder="Enter Group Name"  >
                </div>
<!-- 
                <div class="col-lg-6 form-group ">
                    <label for=""> Start Date </label>
                    <input type = "date" name="start_date" id = "start_date" class="form-control form-group" >
                </div>

                <div class="col-lg-6 form-group ">
                    <label for=""> End Date </label>
                    <input type = "date" name="end_date" id = "end_date" class="form-control form-group" >
                </div> -->

                <div class="col-lg-12 form-group ">
                    <label for=""> Group Limit </label>
                    <input type = "number" name="group_limit" id = "group_limit" class="form-control form-group" min = '1'>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center my-3 ">
                    <button type = "submit" class="btn btn-danger"> Submit </button>
                </div>
            </div>

            </form>

            </div>
            </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
  </section>

  @endsection

  @section('javascript')

  <script>

    $(document).ready(function() {
        $('#submitForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formAction = $(this).attr('action') ;
            var formData = new FormData(this) ;

            // Send AJAX request

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                // $('.btn').prop("disabled", true);
                // $('#loader').removeClass('hidden');
                },
                success: function(response) {
                // Handle successful update
                success_sweettoatr(response.success);
                window.open("{{route('group.index')}}", '_self');
                },
                error: function(response) {
                if (response.status === 422) {
                    var errors = response.responseJSON.errors;
                    $('input').removeClass('is-invalid');
                    $('.btn-outline-danger').prop("disabled", false);
                    $('.btn').prop("disabled", false);
                    $('#loader').addClass('hidden');
                    $.each(errors, function(field, messages) {
                    var $field = $('[name="' + field + '"]');
                    toastr.error(messages[0]) ;
                    $field.addClass('is-invalid') ;
                    });
                } else {
                    console.log(response.responseText);
                }
                }
            });
        });
    });

</script>

@endsection

