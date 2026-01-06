@extends('layouts.vendors.app')
   
@section('content')
@include('layouts.theme.css.datatable')
@php 
	$anchor = ['<a href="'.route('billing',['sale']).'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
	$data = new_component_array('newbreadcrumb',"All ".ucfirst($type)." Bills") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <style>
        .form-control.no-border.is-invalid{
            border:1px solid red!important;
        }
    </style>
	<style>
    .delete_question{
        position: absolute;
        display:none;
        top:0;
        left:0;
        right:0;
        bottom:0;
        background-color: #00000054;
    }
    .delete_question ul{
        list-style:none;
        display:inline-flex;
        padding: 0;
        margin: 0;
    }
    .delete_question_content{
        width: fit-content;
        margin: 20vh auto;
        border: 1px solid gray;
        background: white;
        padding: 5px;;
    }
	tbody>tr.soft-delete > td{
        color:orangered;
    }
    tbody>tr.hard-delete > td{
        color:maroon;
    }
</style>
  <section class = "content">
    <div class = "container-fluid">
        <div class = "row">
            <!-- left column -->
            <div class="col-md-12 p-0">
            <!-- general form elements -->
            <div class="card card-primary">

            <div class="card-body p-1">
                

            {{--<div class="row mb-2 " >
                <div class="col-md-9 col-12">
                    <div class="row">
                        <div class="form-group col-md-4 mb-2">
                            <div class="input-group" id="bill_type_select">
                                
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <div class="input-group" id="bill_type_select">
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8 mb-2">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12 ">
                    <div class="row px-3">
                        <div class="form-group text-center  col-4 p-0">
                            <label class="input-group-text">Bill No.</label>
                            <hr  class="m-0">
                                <label class="form-control h-auto px-0">{{ @$bill->bill_number }}</label>
                        </div>
                        <div class="form-group text-center col-4 p-0">
                            <label class="input-group-text">Bill DATE</label>
                            <hr  class="m-0">
                                <label class="form-control h-auto px-0">{{ @date('d-m-Y',strtotime($bill->bill_date)) }}</label>
                        </div>
                        <div class="form-group text-center col-4 p-0">
                            <label class="input-group-text">DUE DATE</label>
                            <hr  class="m-0">
                                <label class="form-control h-auto px-0">{{ @date('d-m-Y',strtotime($bill->due_date)) }}</label>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div class="row">
                <div class="col-12 table-responsive">
                  <table id="CsTable" class="table table-bordered table_theme ">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>DATE</th>
                            <th>BILL No.</th>
                            <th>TYPE</th>
                            <th>CUSTOMER</th>
                            <th>TOTAL</th>
                            <th>PAYMENT</th>
                            <th>BALANCE</th>
                            <th>ENTRY</th>
                            <th>&#8942;</th>
                            {{--<th><i class="fa fa-cog"></i></th>--}}
                        </tr>
                    </thead>

                  </table>
				  <p class="col-12 text-center text-primary" id="data_loader"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></p>
                </div>
            </div>
			<div class="col-12">
				<div  id="paging"></div>
			</div>
            </form>
            </div>
            </div>
            </div>
            <!-- <ul id="item_list"style="display:none;">
                
            </ul> -->
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
		
		<div class="delete_question" id="delete_question">
			<div class="delete_question_content">
				<form role="form" name="delete_question_form" id="delete_question_form" action="">
					<h6 class="w-100">Delete Bill ? <button type="button" style="float:right;" class="btn btn-sm btn-danger p-0 px-1" onclick="$('#delete_question_form').trigger('reset');$('#delete_question').hide();">&cross;</button></h6>
					<hr class="p-0 my-2">
					@csrf
					<ul>
						<li>
							<label  class="form-control text-danger">
								<input type="radio" name="delete_bill" value="return" class="delete_bill_option" id="delete_bill_option" > Item Return !
							</label>
						</li>
						<li>
							<label  class="form-control text-primary" >
								<input type="radio" name="delete_bill" value="only" class="delete_bill_option" id="delete_bill_option" > Delete Only !
							</label>
						</li>
					</ul>
					<div class="text-center">
					<button type="submit" name="delete_bill_done" id="delete_bill_done" class="btn btn-sm btn-danger "  data-redirect="false" data-mpin-check="true">Done !</button>
					</div>
				</form>
			</div>
		</div>
		
  </section>
    @include('vendors.commonpages.newcustomerwithcategory')
  @endsection

  @section('javascript')

@include('layouts.theme.js.datatable') 
  <script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
    <script>
        $('select.my_select').myselect96();
        function getresult(url) {
            $("#data_loader").show();
            //$(document).find('.data_area').remove();
			$('#CsTable').DataTable().destroy();
			$("#paging").empty() ;
            //$(document).find("table .data_area").remove();
			$('#CsTable').find('tbody').remove();  // remove old tbody
			$('#CsTable').find('tfoot').remove();  // 
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    "entries": $(".entries").val(),
                    "customer": $("#customer").val(),
                    "bill": $("#bill").val(),
                    "date_rage":$("#reportrange").val()
                },
                success: function (data) {
                    $("#data_loader").hide();
                    $(data.html).insertAfter("thead");
                    $("#paging").html(data.paging) ;
					$('#CsTable').DataTable();
                },
                error: function () {},
            });
        }

        getresult(url) ;

        $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                var pageUrl = $(this).attr('href');
                getresult(pageUrl);

        });

        function changeEntries() {

            getresult(url) ;

        }
		@if(auth()->user()->shop_id== 35 && auth()->user()->branch_id == 37)
		$(document).on('click','#bill_delete',function(e){
            e.preventDefault();
            $("#delete_question_form").attr('action',$(this).attr('href'));
            $("#delete_question").toggle();
        });

        $('.delete_bill_option').change(function(){
            let action = $('#delete_question_form').attr('action');
            if($(this).is(':checked')){
                action =action.replace('done',$(this).val());
                action =action.replace('return',$(this).val());
                action =action.replace('only',$(this).val());
            }
            $('#delete_question_form').attr('action',action);
        });

        $(document).on('mpinVerified', function(e, response) {
            if(response.op=='delete'){
                if(response.status){
                        success_sweettoatr(response.msg);
                    }else{
                        toastr.error(response.msg);
                    }
            } 
        });
		@else 
			$(document).on('click','#bill_delete',function(e){
				e.preventDefault();
				alert('Show Only !');
			});
		@endif
    </script>
    @include('layouts.theme.js.datatable')
    @include('layouts.vendors.js.passwork-popup')
    @endsection

