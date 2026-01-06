  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Customer Enroll',[['title' => 'Schemes']] ) ;

  @endphp

  <x-page-component :data=$data />

  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">

            <div class="card-header">
            <h3 class="card-title"><x-back-button /> Create </h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('enrollcustomer.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">
				@if((isset($schemeenquiry->id ) && $schemeenquiry->id!="") && (isset($schemeenquiry->scheme_id ) && $schemeenquiry->scheme_id!="") && (isset($schemeenquiry->custo_id) && $schemeenquiry->custo_id!=""))
                <input type="hidden" name="enroll" value="{{ $schemeenquiry->id }}" >
                @endif
                <div class="col-lg-4 form-group ">
                    <label for="customer_id"> Customer </label><a href="#"  id="custo_plus"><li class="fa fa-plus" ></li></a>
                    <select name="customer_id" id = "customer_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($customers as $customer )
                            <option value="{{ $customer->id }}" data-name = "{{ $customer->custo_full_name }}" {{ (isset($schemeenquiry->custo_id) && $schemeenquiry->custo_id==$customer->id)?'selected':'' }} >{{ $customer->custo_full_name }} <b>( {{ $customer->custo_fone }} )</b></option>
                        @endforeach
                    </select>
                </div>
				
                <div class="col-lg-4 form-group ">
                    <label for="scheme_id"> Schemes </label>
                    <select name="scheme_id" id = "scheme_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($schemes as $scheme )
                            <option value="{{ $scheme->id }}" {{ ($scheme->ss_status=='0')?'disabled':''; }} {{ (isset($schemeenquiry->scheme_id) && $schemeenquiry->scheme_id==$scheme->id)?'selected':'' }} >{{ $scheme->scheme_head }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 form-group ">
                    <label for="group_id"> Group </label>
                    <select name="group_id" id = "group_id" class="form-control select2">
                        <option value = "">Select</option>
                    </select>
                </div>

                <style>
                    a#custo_plus{
                        font-size:15px;
                        border:1px dotted blue;
                        padding:0 0.3rem 0 0.3rem;
                        float:right;
                        color:blue;
                    }
                    a#custo_plus:hover{
                        border:1px solid blue;
                        background:lightblue;
                        color:white;
                    }
                    /* a#custo_plus{
                        color:#0000ff82;
                        float:right;
                    }
                    a#custo_plus:hover{
                        color:blue;
                    } */
                    .custo.invalid{
                        border:1px solid red;
                    }
                </style>
                <div class="col-lg-2 form-group ">
                    <label for="customer_multiple">Multi Enroll</label>
                    <input type ="number" name="customer_multiple" id = "customer_multiple" class="form-control form-group" placeholder="Times(Num)" min = "1" value = "1" >
                </div>

                <div class="col-lg-12 form-group " id="customer_choice_container">
                    <div id="customer_names_container" class="row">
                        <div class="col-lg-4 form-group" id="">
                            <label for="">Customer Name</label>
                            <input type="text" name="customer_name[]" class="form-control form-group customer_name" placeholder="Customer Name in Scheme" required>
                        </div>
                        <div class="col-lg-2 form-group">
                            <label for="">Assign ID</label>
                            <input type="text" name="assign_id[]" class="form-control form-group assign_id" placeholder="Assign ID" required>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="">Token/Advance Amount</label>
							<div class="input-group">
								<input type="text" name="token_amt[]" class="form-control form-group token_amt" placeholder="Token Amount" required value="0">
								<span class="input-group-text" id="basic-addon2"><b>Rs.</b></span>
							</div>
                        </div>
						<div class="col-lg-3 form-group">
							<label for="pai_medium">Pay Medium</label>
							<select class="form-control pay_medium" id="pay_medium" name="pay_medium[]" disabled >
								<option value="">SELECT</option>
								@php 
									$medium_arr = ['Card','Cash','PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay'];
								@endphp
								@foreach($medium_arr as $mk=>$med)
									<option value="{{ $med }}">{{ $med }}</option>  
								@endforeach
							</select>
						</div>
                        <div class="col-lg-3 form-group">
                            <label for="emi_amt" id="emi_amt_label" class="emi_amt_label">EMI </label>
							<div class="input-group">
								<input type="text" id="emi_amt" name="emi_amt[]" class="form-control form-group emi_amt" placeholder="Choosed EMI Amount" required>
								<span class="input-group-text" id="basic-addon2"><b>Rs.</b></span>
							</div>
                        </div>
						<div class="col-lg-3 form-group">
                            <label for="enroll_date" id="enroll_date_label" class="enroll_date_label">Enroll Date</label>
                            <input type="date" id="enroll_date" name="enroll_date[]" class="form-control form-group enroll_date text-center" placeholder="Enter Enroll Date" required value="{{ date('Y-m-d',strtotime("now")); }}">
                        </div>
                    </div>
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
    <div class="modal" tabindex="-1" role="dialog" id="custo_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">New Customer</h6>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form action="{{ route('enrollcustomer/newcustomer') }}" method="post" id="custo_plus_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                        @csrf
                        <div class="form-group">
                            <a href="profile_image_placer" class="btn btn-sm btn-outline-danger" style="position:absolute;right:0;display:none;" id="profile_image_clear">X</a>
                            <label class="form-control h-auto" for="profile_image" style="cursor:pointer;">
                            <img src="{{asset("assets/images/icon/browse.png")}}" class="img-responsive h-auto form-control" id="profile_image_placer"></label>
                            <input type="file" name="image" id="profile_image" style="display:none;" accept="image/*">
                        </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Name<sup class="text-danger">*</sup></label>
                                <input type="text" name="name" id="name" class="form-control custo" placeholder="Customer Name" required>
                                <small class="text-danger help-block" id="name_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="fone">Mobile<sup class="text-danger">*</sup></label>
                                <input type="text" name="fone" id="fone" class="form-control custo" placeholder="Customer Mobile Number" required>
                                <small class="text-danger help-block" id="fone_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="mail">E-Mail</label>
                                <input type="text" name="mail" id="mail" class="form-control custo" placeholder="Customer E-Mail Address">
                                <small class="text-danger help-block" id="mail_error"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="addr">Address<sup class="text-danger">*</sup></label>
                                <textarea name="addr" id="addr" class="form-control custo" rows="2" placeholder="Customer Address" required ></textarea>
                                <small class="text-danger help-block" id="addr_error"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" value="custo" class="btn btn-primary">Add ?</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
            </div>
        </div>
    </div>
  </section>

  @endsection

  @section('javascript')

  <script>

    $(document).ready(function() {
		
		$("#profile_image").change(function(e){
            var file = this.files[0];
            var id = $(this).attr('id');
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#'+id+'_placer').attr('src', e.target.result);
                $("#"+id+"_clear").show();
                $("#"+id+"_upload").show();
            }
            reader.readAsDataURL(this.files[0]);
        });
        $("#profile_image_clear").click(function(e){
            e.preventDefault();
            $("#"+$(this).attr('href')).attr('src',"{{ asset("assets/images/icon/browse.png") }}");
            $(this).hide();
            $('.'+$(this).attr('href')).hide();
        });
		
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
                window.open("{{route('enrollcustomer.index')}}", '_self');
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

    $(document).ready(function() {
        $("#custo_plus").click(function(e){
            e.preventDefault();
            $("#custo_plus_form").trigger('reset');
            $("#custo_modal").modal();
        });

        $("#custo_plus_form").submit(function(e){
            e.preventDefault();
            $('.help-block').empty();
            $('.custo').removeClass('invalid');
            const path = $(this).attr('action');
            const fd = new FormData(this) ;
            //var formData = new FormData(this); 
            $.ajax({
                url: path,
                type: 'POST',
                data: fd,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                // $('.btn').prop("disabled", true);
                // $('#loader').removeClass('hidden');
                },
                success: function(response) {
                    if(response.valid){
                        if(response.status){
                            const custo = response.data;
                            const option = '<option value="'+custo.id+'" data-name = "'+custo.custo_full_name+'">'+custo.custo_full_name+'( '+custo.custo_fone+') </option>';
                            $("#customer_id").append(option);
                            $("#customer_id").val(custo.id);
                            $("#customer_id").trigger('change');
                            $("#custo_modal").modal('hide');
                            success_sweettoatr(response.msg+" & Selected !");
                        }else{
                            toastr.error(response.msg);
                        }
                    }else{
                        $.each(response.errors,function(i,v){
                            $("#"+i).addClass('invalid');
                            $("#"+i).focus();
                            $("#"+i+"_error").text(v);
                        });
                    }
                },
                error: function(response) {
                
                }
            });
            /*$.post(path,formdata,function(response){
                if(response.valid){
                    if(response.status){
                        const custo = response.data;
                        const option = '<option value="'+custo.id+'" data-name = "'+custo.custo_full_name+'">'+custo.custo_full_name+'( '+custo.custo_fone+') </option>';
                        $("#customer_id").append(option);
                        $("#customer_id").val(custo.id);
                        $("#customer_id").trigger('change');
                        $("#custo_modal").modal('hide');
                        success_sweettoatr(response.msg+" & Selected !");
                    }else{
                        toastr.error(response.msg);
                    }
                }else{
                    $.each(response.errors,function(i,v){
                        $("#"+i).addClass('invalid');
                        $("#"+i).focus();
                        $("#"+i+"_error").text(v);
                    });
                }
            });*/
        });

		$(document).on("input change",".token_amt",function(){
            var my_index = $('.token_amt').index(this);
            var sel_ele = $(".pay_medium").eq(my_index);
            if($(this).val()!="" && $(this).val()>0){
                sel_ele.prop('required',true);
                sel_ele.prop('disabled',false);
            }else{
                sel_ele.prop('required',false);
                sel_ele.prop('disabled',true);
                sel_ele.val('');
            }
        });
        
        $('#customer_id').on('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            // Get the data-name attribute value
            var customerName = selectedOption.getAttribute('data-name');

            // Get all elements with the class 'customer_name'
            var customerNameFields = document.getElementsByClassName('customer_name');

            // Loop through all customer_name fields and set the value
            for (var i = 0; i < customerNameFields.length; i++) {
                customerNameFields[i].value = customerName || '';
            }
        });
    });

    $('#customer_multiple').on('input',function(){
        var multiple = $(this).val() || 1;
        $(document).find('.customer_names_container').remove();
        for (var i = 2; i <= multiple; i++) {
            var ele = $('#customer_names_container').clone();
            ele.removeAttr('id').addClass('customer_names_container');
            ele.appendTo("#customer_choice_container");
        }
    })
   

    $(document).ready(function() {
     
    $('#scheme_id').on('change', function() {
        var schemeId = $(this).val(); // Get the selected scheme_id
        
        // Clear previous options from the group_id dropdown
        $('#group_id').empty().append('<option value="">Select</option>');

        if (schemeId) {
            $.ajax({
                url: '{{ route("shopschemes.getgroup") }}',
                type: 'GET',
                data: { scheme_id: schemeId },
                success: function(response) {
                    // Populate the group_id dropdown with the received data
                    $.each(response[0], function(index, group) {
                        $('#group_id').append('<option value="' + group.id + '">' + group.group_name + '</option>');
                    });
                    const scheme = response[1];
                    var emi_sugg = '';
                    if(scheme.emi_range_start == scheme.emi_range_end){
                        $(document).find('.emi_range').remove();
                        $(".emi_amt").attr('readonly',true);
                    }else{
                        emi_sugg = '<small class="emi_range text-danger"><b> ( Range '+scheme.emi_range_start+' to '+scheme.emi_range_end+' )</b></small>';
                        $(".emi_amt_label").append(emi_sugg);
                        $(".emi_amt").attr('readonly',false);
                    }
                    $(".emi_amt").val(scheme.emi_range_start);
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while fetching groups.');
                }
            });
        }
    });
	@if((isset($schemeenquiry->scheme_id) && $schemeenquiry->scheme_id!="") && (isset($schemeenquiry->scheme_id) && $schemeenquiry->scheme_id!=""))
    $("#scheme_id").trigger('change');
    $("#customer_id").trigger('change');
    @endif
});

</script>

@endsection

