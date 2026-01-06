@extends('layouts.admin.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Payment Gateway' ,[['title' => 'Payment Gateway']] ) ;

@endphp

<x-page-component :data=$data />

  <section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"> <x-back-button /> Create Tamplate</h3>
                    </div>

                    <div class="card-body">
                        <form  id = "submitForm" method="POST" action="{{ route('paymentgateway.store')}}" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="icon"> Logo / Icon</label>
                                            <input type="file" name="icon" class="form-control" id="icon" value="" placeholder="Gateway name"  accept="image/*">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" class="form-control" value="" placeholder="gateway name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="prod">Production URL</label>
										<input type="url" name="prod" id="prod" class="form-control" value="" placeholder="Live payment URL(EX: https://www.xyz.go)">
                                    </div>
                                    <div class="form-group">
                                        <label for="test">Test URL</label>
										<input type="url" name="test" id="test" class="form-control" value="" placeholder="Test payment URL(EX: https://www.xyz.go)">
                                    </div>
                                    <div class="form-group">
                                        <label for="params">Parameters Name List  <small class="text-danger">['<b style="font-size:20px;">,</b>' Separated Values(names) ]</small> </label>
                                        <input type="text" name="params" id="params" class="form-control" value="" placeholder="Parameter List (Ex: alfa,beeta,gamaa,theta,....)">
                                    </div>
                                </div>
                                <hr class="col-12">
                                <div class="col-12 text-center">
                                    <button type="submit" name="add" value="tamplate" class="btn btn-danger"> Add</button>
                                </div>
                            <div>
                        </form>
                    </div>

                </div>
            </div>

        </div> <!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
  </section>
<style>
#add_field{
    border:1px dashed blue;
    padding:0 5px;
}
#add_field:hover{
    border:1px solid blue;
    background:lightgray;
}
</style>
  @endsection


@section('javascript')

<script>

    $(document).ready(function() {

        $('#submitForm').submit(function(e) {

            e.preventDefault() ;
            var formAction = $(this).attr('action') ;
            var formData = new FormData(this) ;

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                // Handle successful update
                toastr.success(response.success);
                window.open("{{route('paymentgateway.index')}}", '_self') ;
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
                    if(response.status === 425){
                        toastr.error(response.errors);
                    }else{
                        toastr.error(response.errors);
                    }
                }
                }
            }) ;
        });

    });

</script>

@endsection
