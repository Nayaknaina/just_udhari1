@extends('layouts.admin.app')

@section('content')

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

        <form id = "submitForm" method="POST" action="{{ route('users.update',$user->id)}}" class = "myForm" enctype="multipart/form-data">

        @csrf

        @method('put')

        <div class="row">

            <div class="col-lg-4">
                <label for=""> Shop Name <span class = "text-danger"> * </span> </label>
                <input type="text" name="name" class="form-control form-group" placeholder="Enter Shop Name" value = "{{ @$user->shop->shop_name }}" readonly  >
            </div>

            <div class="col-lg-4">
                <label for=""> Owner Name <span class = "text-danger"> * </span> </label>
                <input type="text" name="name" class="form-control form-group" placeholder="Enter Name" value="{{ $user->name }}" readonly >
            </div>

            <div class="col-lg-4">
                <label for=""> Mobile No  <span class = "text-danger"> * </span>  </label>
                <input type="number" name="mobile_no" class="form-control form-group" placeholder="Enter Mobile No" value="{{ $user->mobile_no }}" readonly >
            </div>

            <div class="col-lg-12">
                <label for="">Software Product</label>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-3 mb-3">
                            <div class="card card-body h-100 text-center">
                                <div class="flex flex-col">
                                    <label class="inline-flex items-center mt-3">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="products[]" value="{{ $product->id }}" @if(isset($subscribedProducts[$product->id])) checked @endif>
                                        <span class="ml-2 text-gray-700">{{ $product->title }}</span>
                                    </label>
                                </div>
                                <label class="inline-flex items-center mt-3" style="font-weight:500">Subscription Ended Date</label>
                                <input type="date" class="form-control" name="expiry_date[{{ $product->id }}]" value="{{ $subscribedProducts[$product->id]->expiry_date ?? '' }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

    </div>

        <div class="row">
            <div class="col-12 text-center my-3 ">
                <button type = "submit" class="btn btn-danger"> Submit </button>
                <input type="hidden" name="shop_id" value="{{ $user->shop_id }}">
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

