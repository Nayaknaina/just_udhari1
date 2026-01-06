@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        //$data = component_array('breadcrumb' , 'Enroll Customers List',[['title' => 'Schemes']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('enrollcustomer.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> Enroll New</a>'];
$data = new_component_array('newbreadcrumb',"Scheme Enrolled Customers") 
@endphp
<style>
	.withdraw_btn{
		height: 20px;
		width: 50px;
		display: block;
		border: 1px solid gray;
		position: relative;
		border-radius: 5px;
		color:white;
		text-shadow:1px 2px 3px gray;
		overflow:hidden;
	}
	.withdraw_btn:before,.withdraw_btn:after{
	  position: absolute;
	  width:50%;
	  height:100%;
	  text-align:center;
	  line-height: auto;
	}
	.withdraw_btn:before{
		content:'\2717';
		left:0;
		background-color: #e52e2e;
	}
	.withdraw_btn:after{
		content:'\2713';
		right:0;
		background-color: unset;
	}
	.withdraw_btn.active:after{
	  background-color: green;
	}
	.withdraw_btn.active:before{
	  background-color: unset;
	}
	.withdraw_btn:hover:before,.withdraw_btn.active:hover:after{
		color:lightgray;
		background-color: white;
	}
	.withdraw_btn:hover:after{
		color:green;
		background-color: lightgreen;
	}
	.withdraw_btn.active:hover:before{
		color:red;
		background-color: pink;
	}
</style> 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    {{--<div class="card-header">
    <a class = "btn btn-outline-primary" href = "{{route('enrollcustomer.create')}}"><i class="fa fa-plus"></i>  Add New </a><span class="text-secondary" style="font-weight:bold;">Click here to add new customer to scheme !</span>
   
    </div> --}}

    <div class="card-body p-0">
    @if (Session::has('error'))
      <strong class="text-danger alert alert-outline-danger p-1">{!! Session::get('error') !!}</strong>
    @endif
    <div class="col-12 p-2">
    <form action="">

	<div class="container p-0"> 
		<div class="row"> 

			<div class="col-md-6 col-12">
				<label for="" class="mb-1">
				Customer
				<small><label for="winner" class="text-success"><input type="checkbox" name="winner" id="winner" class="" onchange="changeEntries()"> Winner</label></small>
				<small><label for="withdraw" class="text-danger"><input type="checkbox" name="withdraw" id="withdraw" class="" onchange="changeEntries()" > Withdraw</label></small>
				</label>
				<div class="container">
					<div class="row">
						<input type="text" name="custo" id="custo" class="form-control col-md-8" placeholder="Name" oninput="changeEntries()">
						<input type="text" name="mob" id="mob" class="form-control col-md-4" placeholder="Mobile" oninput="changeEntries()">
					</div>
				</div>
			</div>
			<div class=" col-md-2  col-12">
				<label for="" class="mb-1">Assign ID</label>
				<input type="text" name="assign" id="assign" class="form-control" Placeholder="Assigned ID" oninput="changeEntries()">
			</div>
			<div class="col-md-4 col-12">
				<label for="" class="mb-1">EnrollMent</label>
				<div class="input-group">
				<input type="date" name = "start" id="start" class = "form-control date_range text-center input-append" placeholder = "start Date"  onchange="changeEntries()">
				<input type="date" name = "end" id="end" class = "form-control date_range  text-center input-append" placeholder = "Launch Date"  onchange="changeEntries()" style="border-radius:0 5px 5px 0!important;">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-7">
			  <label for="" class="mb-1"> Scheme </label>
			  <select name="scheme" class="form-control" id="scheme" onchange="changeEntries();getgroups();">
				<option value="">Select</option>
				@foreach($schemes as $sk=>$scheme)
				<option value="{{ $scheme->id }}">{{ $scheme->scheme_head }} ( {{ $scheme->scheme_sub_head }} )</option>
				@endforeach
			  </select>
			</div>
			<div class="col-8 col-md-3">
				<label for="" class="mb-1"> Group </label>
				<select name="group" id="group" class="form-control" id="scheme" onchange="changeEntries()">
					<option value="">Select</option>
				</select>
			</div>
			<div class="col-4 col-md-2"> 
				<label for=""  class="mb-1">Entries</label>
				@include('layouts.theme.datatable.entry')
			</div>
		</div>
	</div>

	{{--
    <div class="row">

    <div class="col-12 col-md-9">
      <label for="">Customer</label>
      <div class="row">
        <div class="col-md-7">
          <input type="text" name="custo" id="custo" class="form-control" placeholder="Name" oninput="changeEntries()">
        </div>
        <div class="col-md-5">
          <input type="text" name="mob" id="mob" class="form-control" placeholder="Mobile" oninput="changeEntries()">
        </div>
      </div>
    </div>
	
    <div class="col-12 col-md-3 form-group">
      <label for="">Assign ID</label>
      <input type="text" name="assign" id="assign" class="form-control" Placeholder="Assigned ID" oninput="changeEntries()">
    </div>
	
    <div class="col-12 col-md-5  form-group">
      <label for=""> Scheme </label>
      <select name="scheme" class="form-control" id="scheme" onchange="changeEntries();getgroups();">
        <option value="">Select</option>
        @foreach($schemes as $sk=>$scheme)
        <option value="{{ $scheme->id }}">{{ $scheme->scheme_head }} ( {{ $scheme->scheme_sub_head }} )</option>
        @endforeach
      </select>
    </div>
    <div class="col-12 col-md-2  form-group">
      <label for=""> Group </label>
      <select name="group" id="group" class="form-control" id="scheme" onchange="changeEntries()">
        <option value="">Select</option>
      </select>
    </div>
    <div class="col-12 col-md-5  form-group">
      <div class="row">
        <div class="col-12">
          <label for="">Enrollment</label>
        </div>
        <div class="col-12 col-md-6 ">
          <input type="date" name = "start" id="start" class = "form-control date_range text-center" placeholder = "start Date"  onchange="changeEntries()">
        </div>
      
        <div class="col-12 col-md-6 ">
          <input type="date" name = "end" id="end" class = "form-control date_range  text-center" placeholder = "Launch Date"  onchange="changeEntries()">
        </div>
      </div>
    </div>


    <div class="col-12 col-md-2 form-group">
      <label for="">Show entries</label>
      @include('layouts.theme.datatable.entry')
    </div>
    </div>--}}

    </form>
</div>
    <div  class="text-center col-12 mb-2" id="loader">
		<span class="p-1" style="background:lightgray;">
			<li class="fa fa-spinner fa-spin"></li> Loading Content..
		</span>
	</div>
   
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
    function check_date_range(){
      var valid = true;
        if($("#start").val()!="" && $("#end").val()!=""){
          if($('#end') < $("#start")){
            valid = false;
          }
        }else{
          if(($("#start").val()!="" && $("#end").val()=="") || ($("#start").val()=="" && $("#end").val()!="")){
              valid = false;
          }
        }
      return valid;
    }

    function getgroups(){
      if($("#scheme").val()!=""){
        $("#group").empty().append('<option value="">Loading..</option>');
        $.get("{{ route("shopschemes.getgroup") }}",{ scheme_id: $("#scheme").val() },function(response){
          if(response[0].length > 0){
            $("#group").empty().append('<option value="">Select</option>');
            $.each(response[0], function(index, group){
              $("#group").append('<option value="'+group.id+'">'+group.group_name+'</option>');
            });
          }else{
            $("#group").empty().append('<option value="">No Data !</option>');
          }
        });
      }else{
        $("#group").empty().append('<option value="">Select</option>');
      }
    }
    function getresult(url) {
      if(check_date_range()){
        $("#loader").show();
		const winner = ($("#winner").is(':checked'))?'yes':'no';
		const withdraw = ($("#withdraw").is(':checked'))?'yes':'no';
		
		
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "scheme": $("#scheme").val()??"",
                "group": $("#group").val()??'',
                "start": $("#start").val(),
                "end": $("#end").val(),
                "custo": $("#custo").val(),
                "mob": $("#mob").val(),
                "assign": $("#assign").val(),
				"winner":winner,
				"withdraw":withdraw,
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
      
	  
	  function withdrawcustomer(){
        event.preventDefault();
        const element = event.currentTarget;
        const target = $(element).attr('href');
        $.get(target,"",function(response){
          if(response.status){
            $(element).toggleClass('active');
            success_sweettoatr(response.msg);
          }else{
            toastr.error(response.msg);
          }
        });
      }
	  
</script>



  @endsection
