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
                        <h3 class="card-title"> <x-back-button /> Assign Tamplate</h3>
                    </div>
                    @php 
                    $gateway_ids = $user->gateways->pluck('gateway_id')->toArray()
                    @endphp
                    <div class="card-body">
                        <h3 class="text-primary text-center">{{ @$user->shop->shop_name }}</h1>
                        <p><b><u>Payment Gateway Tamplates</u></b><small class="text-danger"> ( Pre-Assigned were neither Re-Assigned nor Remove ! ) </small></p>
                        <form  id = "submitForm" method="POST" action="{{ route('paymentgateway.assign')}}" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <div class="row">
                                @if($tamplate->count()>0)
                                <input type="hidden" name="user" value="{{ $user->id }}">
                                @foreach($tamplate as $tk=>$tmplt)
                                    @php 
                                        $selected = (in_array($tmplt->id,$gateway_ids))?'checked':''
                                    @endphp
                                <label class="form-control col-md-2 col-sm-6 h-auto text-center p-0 m-2" for="{{ str_replace(" ","_",strtolower($tmplt->name)) }}" style="width:auto;">
                                    <img src="{{ asset("{$tmplt->icon}") }}" class="img-responsive img-thumbnail w-auto" style="height:150px;">
                                    <label class="form-control m-0">
                                    <input type="checkbox" id="{{ str_replace(" ","_",strtolower($tmplt->name)) }}" name="assign[]" value="{{ $tmplt->id }}" {{ @$selected }}> {{ $tmplt->name }}</label>
                                </label>
                                @endforeach
                                <hr class="col-12">
                                <div class="col-12 text-center">
                                    <button type="submit" name="add" value="tamplate" class="btn btn-danger"> Assign</button>
                                </div>
                                @else 
                                    <p class="text-danger text-center">No Payment Gateay Tamplate !</p>
                                @endif
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
                window.open("{{route('users.index')}}", '_self') ;
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
