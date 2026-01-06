@extends('layouts.vendors.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"> Stock </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-6">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title">Create </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('stockcategories.update',$stockcategory->id)}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')

          <div class="row">

              <div class="col-lg-12">
                  <label for="">Category Parent </label>
                   <select name ="parent" class = "form-control select2 ">
                      <option value="">Select</option>
                      @foreach ($categories as $category )
                          <option value="{{ $category->id }}" @if ($stockcategory->parent==$category->id)
                              selected
                          @endif > {{ $category->name }} </option>
                      @endforeach
                   </select>
              </div>

              <div class="col-lg-12">
                  <label for="">Category Name</label>
                  <input type="text" name="category_name" class="form-control form-group" placeholder="Enter Category Name" value = "{{ $stockcategory->name }}">
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
      toastr.success(response.success);
      window.open("{{route('stockcategories.index')}}", '_self');
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

