@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Scheme EMI',[['title' => 'Schemes EMI']] ) ;
print_r($enrollcusto->toArray());
@endphp

<x-page-component :data=$data />

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-8">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title"><x-back-button /> Pay EMI </h3>
          </div>

          <div class="card-body">
          <ul class="scheme_ul">
            <li >{{ $enrollcusto->schemes->scheme_head }}</li>
            <li>{{ $enrollcusto->groups->group_name }}</li>
            <li>{{ $enrollcusto->info->custo_full_name }}</li>
          </ul>
          <div class="row">
            <div class="col-md-4 text-center">
              <img src="{{  url($enrollcusto->info->custo_img) }}" class="img-responsive">
            </div>
            <div class="col-md-8">
              <ul class="custo_ul">
                <li><strong>NAME<strong><span>{{ $enrollcusto->info->custo_full_name }}</span></li>
                <li><strong>MOBILE<strong><span>{{ $enrollcusto->info->custo_fone }}</span></li>
                <li><strong>EMAIL<strong><span>{{ $enrollcusto->info->custo_mail }}</span></li>
              </ul>
            </div>
          </div>
          <form id = "submitForm" method="POST" action="{{ route("shopscheme.emipay",27) }}" class = "myForm" enctype="multipart/form-data">

          @csrf
          @method('post')
          <div class="table-responsive">
            <table class="table tabel-bordered emi-pay-table">
              <thead>
                <tr>
                  <th>S.N.</th>
                  <th>EMI</th>
                  <th>ADD-ON</th>
                  <th>REMARK</th>
                </tr>
              </thead>
              <tbody>
                @if($enrollcusto->token_amt!="")
                  <tr>
                    <td class="text-center"> 0 </td>
                    <td> {{ $enrollcusto->token_amt }}  </td>
                    <td> 0 </td>
                    <td>Token Amount </td>
                  </tr>
                @endif
                @php 
                  
                  $bonus = ($enrollcusto->schemes->scheme_interest=='Yes')?(($enrollcusto->schemes->interest_type=='amt')?$enrollcusto->schemes->interest_amt:(($enrollcusto->schemes->emi_amt*$enrollcusto->schemes->interest_rate)/100)):0;

                @endphp 
                @for($i=1;$i<=$enrollcusto->schemes->scheme_validity;$i++)
                  @if($i<=count($enrollcusto->emipaid))
                    <tr>
                      <td class="text-center"> {{ $i }} </td>
                      <td> {{ $enrollcusto->schemes->emi_amt }} </td>
                      <td> {{ $bonus }} </td>
                      <td> {{ $enrollcusto->emipaid->remark??"Bonus AddOn" }} </td>
                      <td>Token Amount </td>
                    </tr>
                  @else
                    <tr>
                      <td class="text-center"> {{ $i }} </td>
                      <td> <input type="number" class="form-control" id="emi_amnt" name="emi_amnt" value="{{ $enrollcusto->schemes->emi_amt }}"> </td>
                      <td> {{ $bonus }} </td>
                      <td>Token Amount </td>
                    </tr>
                  @endif
                    
                @endfor
              </tbody>
            </table>
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

