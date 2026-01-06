@extends('layouts.admin.app')

@section('content')
@php 
    $schemes_ids = $user->schemes->pluck('scheme_id')->toArray();
@endphp 
  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Shops</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<section class = "content">
  <div class = "container-fluid">
    <div class = "row justify-content-center">
          <!-- left column -->
        <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">

        <div class="card-header">
        <h3 class="card-title"> <x-back-button /> Edit </h3>
        </div>

        <div class="card-body">

        <form id = "submitForm" method="POST" action="{{ route('schemes.assign')}}" class = "myForm" enctype="multipart/form-data">

        @csrf

        @method('post')

        <div class="row justify-content-center">

            <div class="col-lg-6 text-center">
                <h3 class="text-info text-bold"> {{ $user->shop->shop_name }} </h3> 
            </div>

            <div class="col-lg-12">
                <label for="">Software Scheme</label>
                <div class="row">
                    @foreach($schemes as $scheme)
						@php 
                            $selected = (in_array($scheme->id,$schemes_ids))?'checked':''
                        @endphp
                        <div class="col-lg-3 mb-3">
                            <div class="card card-body h-100 text-center">
                                <div class="flex flex-col">
                                    <label class="inline-flex items-center mt-3">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="schemes[]" value="{{ $scheme->id }}"  {{ $selected }} >
                                        <span class="ml-2 text-gray-700">{{ $scheme->scheme_head }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

    </div>

        <div class="row">
            <div class="col-12 text-center my-3 ">
                <button type = "submit" class="btn btn-danger"> Submit </button>
                <input type = "hidden" name = "shop_id" value="{{ $user->shop_id }}">
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
          e.preventDefault() ; // Prevent default form submission

              var formAction = $(this).attr('action');
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
                      $('.btn').prop("disabled", true) ;
                      $('#loader').removeClass('hidden') ;
                  },
                  success: function(response) {
                      // Handle successful update
                      toastr.success(response.success);
                      window.open("{{route('users.index')}}", '_self');
                  },
                  error: function(response) {

                      $('input').removeClass('is-invalid');
                      $('.btn-outline-danger').prop("disabled", false);
                      $('.btn').prop("disabled", false);
                      $('#loader').addClass('hidden');

                  var errors = response.responseJSON.errors ;

                      if (response.status === 422) {

                          $.each(errors, function(field, messages) {
                          var $field = $('[name="' + field + '"]');
                          toastr.error(messages) ;
                          $field.addClass('is-invalid') ;
                          }) ;

                      } else {

                          toastr.error(errors) ;

                      }
                  }
              });
          });
      });

</script>

@endsection

