@extends('layouts.vendors.app')

@section('content')

    @include('layouts.theme.css.datatable')

<style>
#emi_value.disabled{
    opacity:0.3;
    cursor:not-allowed;
    pointer-events: none;
}
sup.text-danger{
    font-weight:bold!important;
}
.type_label{
    border-radius:20px;
    position: relative;
}
.type_label>input{
    display:none;
}
label.gold.checked{
    background-image: linear-gradient(to right,white,#ffdcb7,orange);
}
label.cash.checked{
    background-image: linear-gradient(to right,white,lightgreen,green);
}
.type_label.checked:before,.emi_check.checked:before{
    content:"\2714";
}
.emi{
    display:none;
}
.emi_no.checked{
    color:red;
}
.emi_yes.checked{
    color:green;
}
.edit_form{
    border: 1px dashed;
    box-shadow: 1px 2px 3px 4px gray;
}
</style>
@php 
$anchor = ['<a href="'.route('anjuman.dashboard').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-home"></i> Dashboard</a>'];
$data = new_component_array('newbreadcrumb',"Anjuman New Scheme") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center" id="scheme_block">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-4" id="new_scheme">
                                    <div class="card border-primary" style="border-radius:20px;overflow:hidden;">
                                        <div class="card-header p-2" style="background:#ffcaad;">
                                            <h5 class="card-title" id="form_title">New Scheme</h5>
                                        </div>
                                        <div class="card-body p-2">
                                            <form action="{{ route('anjuman.scheme') }}" method="post" id="scheme_form">
                                                @csrf 
                                                <div class="form-group mb-1 input-group text-center">
                                                    <label for="gold" class="form-control gold type_label checked">
                                                        <input type="radio" name="type" id="gold" value="gold" class="type" > GOLD
                                                    </label>
                                                    <label for="cash" class="form-control cash type_label">
                                                        <input type="radio" name="type" id="cash" value="cash"  class="type"> CASH
                                                    </label>
                                                </div>
                                                <div class="form-group mb-1">
                                                    <label for="title" class="mb-1">Title <sup class="text-danger">*</sup></label>
                                                    <input type="text" class="form-control" id="title" name="title" value="">
                                                </div>
                                                <div class="form-group mb-1">
                                                    <label for="detail" class="mb-1">Detail</label>
                                                    <textarea name="detail" id="detail" class="form-control"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-1">
                                                        <label for="validity" class="mb-1">Validity <sup class="text-danger">*</sup></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" id="validity" name="validity" placeholder="">
                                                            <span class="input-group-text p-1"> <b>Month</b></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 mb-1">
                                                        <label for="start" class="mb-1">Start Date <sup class="text-danger">*</sup></label>
                                                        <input type="date" name="start" id="start" value="{{ date('Y-m-d',strtotime('Now')) }}" class="form-control p-1 text-center" >
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-1">
                                                        <label for="" class="mb-1">Emi Fix ? <sup class="text-danger">*</sup></label>
                                                        <div class="input-group text-center">
                                                            <label for="emi_yes"class="form-control emi_yes emi_check" style="border-radius: 20px 0px 0px 20px;">
                                                                <input type="radio" name="emi" value="yes" id="emi_yes" class="emi" > Yes
                                                            </label>
                                                            <label for="emi_no" class="form-control emi_no  emi_check" style="border-radius: 0px 20px 20px 0px;">
                                                                <input type="radio" name="emi" value="no" id="emi_no" class="emi" >  No
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 disabled mb-1" id="emi_value">
                                                        <label for="quant" class="mb-1">Emi Value ? <sup class="text-danger" id="emi_val_req" style="display:none;">*</sup></label>
                                                        <div class="input-group">
                                                            <input type="text" id="quant" name="quant" class="form-control" value="" >
                                                            <span class="input-group-text"> 
                                                                <b id="unit" >Unit</b>
                                                                <b id="gr_unit" style="display:none;">Grm</b>
                                                                <b id="rs_unit" style="display:none;">Rs.</b>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <hr class="col-12 p-0 m-1" style="border-top:1px solid lightgray;">
                                                    <div class="form-group col-md-12 text-center mb-0">
                                                        <button type="submit" name="create" value="scheme" class="btn btn-primary">Create</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Type</th>
                                                    <th>Title</th>
                                                    <th>Start</th>
                                                    <th>EMI</th>
                                                    <th>Validity</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dataarea">
                                                <tr>
                                                    <td colspan="7" class="text-center text-primary">
                                                        <span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="pagingarea" class="col-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')

    @include('layouts.theme.js.datatable')
    @include('layouts.vendors.js.passwork-popup')
	
<script>
    $(document).ready(function(){
		var edit = false;
        $("#scheme_form").trigger('reset');
        var pre_action = $("#scheme_form").attr('action');
        var new_action = "{{ route('anjuman.scheme.change') }}";
        $('.type_label').removeClass('checked');
        $('.emi_check').removeClass('checked');
        function getresult(url) {
            $("#loader").show();
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                },
                success: function (data) {
                $("#loader").hide();
				if ($.fn.DataTable.isDataTable('#CsTable')) {
					$('#CsTable').DataTable().destroy();
				}
                $("#pagingarea").html(data.paging);
                $("#dataarea").html(data.html);
				$('#CsTable').DataTable();
                },
                error: function () {},
            });
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

        $(".type").change(function(){
            let type = $(this).val();
            if(type==""){
                $('#unit').show();
                $('#gr_unit').hide();
                $('#rs_unit').hide();
            }else{
                $('.type_label').removeClass('checked');
                $(this).parent('label').addClass('checked');
                $('#unit').hide();
                if(type=='gold'){
                    $('#gr_unit').show();
                    $('#rs_unit').hide();
                }else{
                    $('#gr_unit').hide();
                    $('#rs_unit').show();
                }
            }
        });

        $('.emi').change(function(){
            let emi_fix_set = $(this).val();
            $('.emi_check').removeClass('checked');
            if(emi_fix_set=='yes'){
                $("#emi_val_req").show();
                $("#emi_value").removeClass('disabled');
            }else{
                $("#emi_val_req").hide();
                $("#quant").val("");
                $("#emi_value").addClass('disabled');
            }
            $(this).parent('label').addClass('checked');
        });

        $("#scheme_form").submit(function(e){
            e.preventDefault();
            var formdata = $(this).serialize();
            var path = $(this).attr('action');
            $.post(path,formdata,function(response){
                if(response.errors){
                    let error = response.errors;
                    $.each(error ,function(ei,ev){
                        $('#'+ei).addClass('is-invalid');
                        toastr.error(ev);
                    });
                }else if(response.status){
                    success_sweettoatr(response.msg);
                    /*window.location.href='{{ route("anjuman.all.scheme") }}';*/
                    if(edit){
                        /*$("#scheme_form").attr('action',pre_action);
                        $('#form_title').text('New Scheme');
                        getresult(url);
                        $('#scheme_form').trigger('reset');
                        $('.type_label').removeClass('checked');
                        $('.emi_check').removeClass('checked');
                        $('[type="submit"]').text('Create');*/
                    }else{
                        //location.reload();
                    }
					location.reload();
                }else{
                    toastr.error(response.msg);
                }
            });
        });
		
		$(document).on('mpinVerified', function(e, response) {
			if(response.op){
				if(response.op=='edit'){
					let data = response.scheme??null;
					if(data && !$.isEmptyObject(data)){
						$("#scheme").val(data.id);
						$("."+data.type).trigger('click');
						$("#title").val(data.title);
						$("#detail").val(data.detail);
						$("#validity").val(data.validity);
						$("#start").val(data.start);
						let emi_arr = ['emi_no','emi_yes'];
						$("."+emi_arr[data.fix_emi]).trigger('click');
						if(data.fix_emi==1){
							let unit_arr = {'gold':'gr_unit','cash':'rs_unit'};
							$('#unit ,#gr_unit, #rs_unit').hide();
							$("#"+unit_arr[data.type]).show();
							$("#quant").val(data.emi_quant);
						}
						$('#form_title').text('Edit Scheme');
						$("#scheme_form").attr('action',new_action+"/"+data.id);
						$("[type='submit']").text('Change');
						$("#new_scheme >div.card").addClass('edit_form');
						$("#title").focus();
						edit = true;
					}else{
						toastr.error('Invalid Scheme !');
					}
				}else if(response.op=='delete'){
					if(response.status){
						success_sweettoatr(response.msg);
					}else{
						toastr.error(response.msg);
					}
				}
			}
			
		});
		
    });
</script>

@endsection