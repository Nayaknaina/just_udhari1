  @extends('layouts.vendors.app')

  @section('content')

  @php

  //$data = component_array('breadcrumb' , 'Branches',[['title' => 'Shop Branches']] ) ;

  @endphp

  {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('shopbranches.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Shop Branches"=>route('shopbranches.index')];
$data = new_component_array('newbreadcrumb',"Edit Branch",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/>   
  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">

            <!--<div class="card-header">
            <h3 class="card-title"><x-back-button /> Edit </h3>
            </div>-->

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('shopbranches.update',$shopbranch->id)}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('put')

            <div class="row">

                <div class="col-lg-6">
                    <label for="">Shop Branch Name</label>
                    <input type="text" name="branch_name" class="form-control form-group" placeholder="Enter Supplier Name" value = "{{ $shopbranch->branch_name }}" >
                </div>
                <div class="col-lg-6">
                    <label for="">Incharge Name</label>
                    <input type="text" name="incharge_name" class="form-control form-group" placeholder="Enter Incharge Name" value = "{{ $shopbranch->name }}">
                </div>
                <div class="col-lg-2">
                    <label for="">Mobile No</label>
                    <input type="text" name="mobile_no" class="form-control form-group" placeholder="Enter Mobile No" value = "{{ $shopbranch->mobile_no }}">
                </div>

                <div class="col-5">
                    <div class="form-group">
                        <label for="">State</label>
                    <select name="state" class="form-control select2" id = "state" >
                        <option value="">Select State</option>
                        @foreach (states() as $state )
                            <option value = "{{ $state->code }}" @if($shopbranch->state==$state->code) selected @endif > {{ $state->name }} </option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <div class="col-5">
                    <div class="form-group">   
                    <label for="">District</label>
                    <select name="district" class="form-control" id = "district"  >
                        <option value="">Select District</option>                   
                        @foreach (districts($shopbranch->state) as $district)
                            <option value = "{{ $district->code }}" @if($shopbranch->district==$district->code) selected @endif > {{ $district->name }} </option>
                        @endforeach 
                    </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <label for="">Address</label>
                    <textarea name="address"class="form-control form-group" placeholder="Enter Address">{{ $shopbranch->address }}</textarea>
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

  <script type="text/javascript">

    $(document).ready(function() {
        $('#state').change(function() {
            var state = $(this).val();
            if (state) {
                $.ajax({
                    url: '/get-districts',
                    type: 'GET',
                    data: { state: state },
                    success: function(response) {
                        $('#district').empty();
                        $('#district').append('<option value="">Select District</option>');
                        $.each(response, function(key, district) {
                            $('#district').append('<option value="' + district.code + '">' + district.name + '</option>');
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                $('#district').empty();
                $('#district').append('<option value="">Select District</option>');
            }
        });
    });

</script>

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
        window.open("{{route('shopbranches.index')}}", '_self');
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

