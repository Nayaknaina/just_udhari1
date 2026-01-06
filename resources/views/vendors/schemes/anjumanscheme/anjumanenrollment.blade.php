@extends('layouts.vendors.app')

@section('content')

@include('layouts.theme.css.datatable')
<style>
    #enroll_block{
        border: 1px solid orange;
        border-radius: 20px;
        overflow: hidden;
    }
    #scheme >li{
        align-content: center;
    }
    #scheme >li:first-child{
        background-color: #ffcaad;
    }
    #scheme >li:last-child{
         background-color: black;
    }
    #scheme > li>#scheme_type{       
        color:white;
    }
    .scheme_info{
        display:none;
    }
    #scheme_info_show{
        position:relative;
        z-index: 1;
    }
     #scheme_info_show > button{
        position:relative;
        z-index: 2;
    }
    #scheme_info_show:before{
        content:"";
        position:absolute;
        width:100%;
        border-top:1px dashed orange;
        /* bottom: 0; */
        left:0;
        top:50%;
        z-index: 0;
    }
</style>
<style>
    sup.text-danger{
        font-weight:bold!important;
    }
    #customerlist {
        list-style-type: none;
        padding: 0;
        margin: 0;
        background-color:#efefef;
        border: 1px solid #ccc;
        width: 200px;
        display: none;
        position: absolute;
        z-index: 100;
        max-height:50vh;
        height:auto;
        min-width:auto;
        overflow-x:scroll;
        box-shadow: 1px 2px 3px gray;
        border-radius:10px;
    }
    #customerlist.active{
        display:block;
    }
    #customerlist li {
        padding: 10px;
        cursor: pointer;
        text-wrap:wrap;
    }

    #customerlist li.hover,#customerlist li:hover {
        background-color: #ddd;
    }
	#form_title{
        display:none;
    }
	.edit_form{
        border: 1px dashed;
        box-shadow: 1px 2px 3px 4px gray;
    }
    #enroll_block.edit_form > #form_title{
        display:block;
    }
</style>

@php 
$anchor = ['<a href="'.route('anjuman.dashboard').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-home"></i> Dashboard</a>','<a href="'.route('anjuman.payment',$id).'" class="btn btn-sm btn-outline-primary"> &plus; Payment</a>','<a href="'. route('anjuman.due',$id).'" class="btn btn-sm btn-outline-info">&#9776; Month DUE</a>'];
$data = new_component_array('newbreadcrumb',"Anjuman Scheme Enroll") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-2">
                            <div class="row">
                                <div class="col-md-4 p-0" id="enroll_block">
									<h5 id="form_title" class="text-center">New Enroll</h5>
                                    <ul class="row p-0 m-0" style="list-style:none;" id="scheme">
                                        <li  class="col-md-8 text-center p-0 py-1">
                                            <h6 id="scheme_name" class="m-0">Scheme Name</h6>
                                        </li>
                                        <li  class="col-md-4 text-center p-0 py-1">
                                            <h5 id="scheme_type"  class="m-0">Type</h5>
                                        </li>
                                    </ul>
                                    <div class="col-12 p-0 p-2" >
                                        <div class="card-body py-1">
                                            <form role="form" id="enroll_form" action="{{ route('anjuman.enroll')}}"> 
                                                @csrf
                                                <div class="row">
													<input type="hidden" id="enroll" name="enroll" value="">
                                                    <input type="hidden" name="scheme" value="" id="scheme_id">
                                                    <div class="form-group col-md-12 px-1 mb-1">
                                                        <label for="custo" class="mb-1">Customer <sup class="text-danger">*</sup></label>
                                                        <div class="input-group m-0" id="custo_parent_div">
                                                            <input type="text" name="custo" id="custo" class="form-control" value="" placeholder="Find Name/Mobile">
                                                            <input type="hidden" name="custo_id" id="custo_id" value="">
															<input type="hidden" name="custo_type" id="custo_type" value="">
                                                            <div class="input-group-append">

                                                                <button class="btn btn-secondary m-0" type="button"  data-toggle="modal" data-target="#custo_modal"  style="border-radius:0 15px 15px 0">
                                                                    <span class="fa fa-plus"></span>
                                                                </button>
                                                            </div>
                                                            <ul id="customerlist" class="w-100"></ul>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-8  px-1">
                                                        <label for="date" class="mb-1">Enroll Date<sup class="text-danger">*</sup></label>
                                                        <input type="date" name="date" id="date" class="form-control text-center" value="{{ date('d/m/Y',strtotime('now')) }}">
                                                    </div>
                                                    <div class="form-group col-md-4 px-1">
                                                        <label for="times" class="mb-1">Multi Enroll <sup class="text-danger">*</sup></label>
                                                        <input type="number" name="times" id="times" class="form-control text-center" value="1" placeholder="Num" min="1" style="font-weight:bold;">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="row p-1" style="border:1px dashed gray;box-shadow:1px 2px 3px 3px  lightgray inset;border-radius: 0px 0px 20px 20px;" id="multienroll">
                                                            <div class="form-group col-md-12 px-1">
                                                                <label for="" class=" mb-1">Enrolled Name 1 <sup class="text-danger">*</sup></label>
                                                                <input type="text" name="name[]" id="name" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 px-1">
                                                        <label for="remark" class="mb-1">Remark</label>
                                                        <input type="text" name="remark" id="remark"class="form-control" placeholder="Remark">
                                                    </div>
                                                    <hr class="col-12 p-0 mb-1 mt-2" style="border-top:1px solid lightgray;">
                                                    <div class="form-group col-md-12 text-center mb-0">
                                                        <button type="submit" name="create" value="scheme" class="btn btn-primary">Create</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
									<div class="row">
                                        <div class="col-12">
                                            <div class="form-inline">
                                                <div class="form-group col-md-4 p-0 mb-1">
                                                    <input type="text" name="scheme" id="scheme_filter" class="form-control w-100  " value="" placeholder="Find Scheme Name" >
                                                </div>
                                                <div class="form-group col-md-4 p-0 mb-1">
                                                    <input type="text" name="custo" id="custo_filter" class="form-control w-100" value="" placeholder="Find Customer Name/Mobile" >
                                                </div>
                                                <div class="input-group col-md-4 p-0  w-100 mb-1">
                                                    <button type = "button" class = "form-control float-right  h-auto" id = "daterange-btn" >
                                                    <i class="far fa-calendar-alt" style="float:left;"></i>
                                                    <span  id="daterange-text" >Start Date - End Date</span>
                                                    <i class="fas fa-caret-down" style="float:right;"></i>
                                                    </button>
                                                    <input type="hidden" class="form-control"  id ="reportrange" value = ""  readonly >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>NAME</th>
                                                    <th>SCHEME</th>
                                                    <th>DATE</th>
                                                    <th>REMARK</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dataarea">
                                                <tr class="bg-light">
                                                    <td colspan="7" class="text-center text-danger">
                                                        <span><i class="fa fa-spinner fa-spin"></i>Loading Content...</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="pagingarea">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--@include('vendors.commonpages.newcustomerfull')--}}
	
    @include('vendors.commonpages.newcustomerwithcategory')
@endsection

@section('javascript')

	<script src = "https://onetaperp.com/plugins/moment/moment.min.js"></script>
    <script src = "{{ asset('main/assets/js/onetaperp_daterangepicker.js')}}"></script>
    <script>
        $(document).ready(function(){
			var per_page = 20;
			var edit = false;
            var pre_action = $("#enroll_form").attr('action');
            var new_action = "{{ route('anjuman.schemeenroll.change') }}";
            $("#enroll_form").trigger('reset');
            $.get("","scheme=true",function(response){
                if(response.scheme){
                    $("#scheme_id").val(response.scheme.id);
                    $("#scheme_name").html(response.scheme.title);
                    $("#scheme_type").html(response.scheme.type.toUpperCase());
                }else{
                    let div = `<div class="alert alert-danger text-center">Invalid Scheme !</div>`;
                    $("#enroll_block").html(div);
                }
            });
            function getresult(url) {
                $("#loader").show();
                $.ajax({
                    url: url , // Updated route URL
                    type: "GET",
                    data: {
						'entries':per_page,
						"scheme_name":$("#scheme_filter").val()??'',
                        "custo":$("#custo_filter").val()??'',
                        "daterange":$("#reportrange").val()??''
                    },
                    success: function (data) {
						$("#loader").hide();
						if ($.fn.DataTable.isDataTable('#CsTable')) {
							$('#CsTable').DataTable().destroy();
						}
						$("#dataarea").html(data.html);
						$("#pagingarea").html(data.paging);
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
				//$("#CsTable").datatable().destroy();
                //$("#CsTable").datatable();
            });

            function changeEntries() {
                getresult(url);
            }

			 $('#scheme_filter ,#custo_filter ,#reportrange').on('input change',function(){
                changeEntries();
            });

            $('#daterange-btn').daterangepicker({},
                function (start, end) {
                    $('#reportrange').val(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
                    $('#daterange-text').html(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
                    $('#reportrange').trigger('change');
                    //changeEntries() ;
            });
            cleardate(changeEntries);

            function getcustomer(self){
            const title = self.val();
            if(title!=""){
                $.get("{{ route('global.customers.search') }}","keyword="+title+"&mode=default",function(response){
                    if(response){
                        var li = '';
                        if(response.length > 0){
                            $.each(response,function(i,v){
                                let stream = v.id+'~'+v.name+'~'+v.mobile+'~'+v.type;
                                li+='<li><a href="javascript:void(null);" class="select_customer" data-target="'+stream+'">'+v.name+' - ('+v.mobile+') </a></li>';  
                            });
                        }else{
                           li = '<li>No Record</li>';
                        }
                    }
                    $("#customerlist").empty().append(li);
                    $("#customerlist").addClass('active');
                    positionmenu('#customerlist','#name');
                });
            }else{
                $('[name="custo_id"]').val('');
                $('[name="custo_type"]').val('');
                $('[name="udhar_id"]').val('');
                $("#udhar_form").trigger('reset');
                $("#customerlist").removeClass('active');
                $("#bottom_block").hide();
            }
        }

        function positionmenu(container,input){
            const $menu = $(container);
            const $input = $(input);
            const input_height = $input.outerHeight();//Use To Specify Position From Top/Bottom
            const menu_height = $menu.outerHeight();
            const page_height = $(document).height();
            const from_top = $input.offset().top; 
            const from_bottom = $(document).height()-(from_top+input_height);
            switch(menu_height){
                case (menu_height/4<from_bottom):
                    $menu.css({
                        top: input_height + 'px',
                    });
                    break;
                case (menu_height/4 < from_top):
                    $menu.css({
                        bottom: input_height + 'px',
                    });
                    break;
                default :
                    $menu.css({
                        top: input_height + 'px',
                    });
                    break;
            }
        }
        $("#times").on('input',function(e){
            var val = ($(this).val()>0)?$(this).val():1;
            $("#multienroll").empty();
            for(var i=1;i<=val;i++){
                var name = $('#custo').val()??'';
                if(name!=""){
                    name = $(name.split('-'))[0].trim();
                }
                var div = `<div class="form-group col-md-12 px-1">
                                <label form="type">Enrolled Name `+i+` <sup class="text-danger">*</sup></label>
                                <input type="text" name="name[]" id="name" class="form-control" value="`+name+`">
                            </div>`;
                $("#multienroll").append(div);
            }
        })
        $("#times").on('blur',function(){
            if($(this).val()==""){
                $(this).val(1);
            }
        })
        $("#custo").keyup(function(e){
            $("#custi_id").val("");
			$("#custi_type").val("");
            $("#customerlist").removeClass('active');
            getcustomer($(this));
            positionmenu("#customerlist",'#custo_parent_div');
        });

        $(document).on('click','.select_customer',function(e){
            var target_data = $(this).data('target');
            var data = target_data.split('~');
            $("#custo_id").val(data[0]);
			 $("#custo_type").val(data[3]);
            $("#custo").val(data[1]+' - ('+data[2]+')');
            var enrol_count = $("#times").val()??1;
            for(var i=1;i<=enrol_count;i++){
                $('input[name="name[]"]').val(data[1]);
            }
            $("#customerlist").removeClass('active');
        });
		
		$("#enroll_form").submit(function(e){
			e.preventDefault();
			const path = $(this).attr('action');
			const data = $(this).serialize();
			$.post(path,data,function(response){
				if(response.errors){
					$.each(response.errors,function(i,v){
						$("input[name='"+i+"']").addClass('is-invalid');
						toastr.error(v);
					});
				}else if(response.status){
					success_sweettoatr(response.msg);
					/*if(edit){

					}else{
						location.reload();
					}*/
					location.reload();
				}else{
					toastr.error(response.msg)
				}
			});
		})
			
		$(document).on('mpinVerified', function(e, response) {
			if(response.op){
				if(response.op=='edit'){
					let data = response.enroll??null;
					if(data && !$.isEmptyObject(data)){
						$("#times").prop('readonly',true);
						$("#enroll").val(data.id);
						$("#custo_id").val(data.custo_id);
						$("#custo").val(data.customer.custo_full_name+" - ("+data.customer.custo_fone+")");
						$('[name="name[]"]').eq(0).val(data.custo_name);
						$("#date").val(data.enroll_date);
						$("#remark").val(data.remark);
						$('#form_title').text('Edit Enroll');
						$("#enroll_form").attr('action',new_action+"/"+data.id);
						$("[type='submit']").text('Change');
						$("#enroll_block").addClass('edit_form');
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
			
		$(document).on('customerformsubmit',function(e){
            let data  = e.originalEvent.detail;
            $("#custo_plus_form").find("button[type='submit']").prop('disabled',false);
            $("#process_wait").hide();
            if(data.errors){
                errors = data.errors;
                $.each(errors,function(i,v){
                    let err = '';
                    $.each(v,function(ei,ev){
                        if(err!=''){
                            err+='\n';
                        }
                        err+=ev;
                    });
                    $("#"+i).addClass('is-invalid');
                    $("#"+i+"_error").html(err);
                    toastr.error(err)
                });
            }else if(data.error){
                toastr.error(data.error)
            }else{				
				let custo = data.custo;
                $("#custo_id").val(custo.id);
                $("#custo_type").val(data.type);
                let name = custo.name;
                let mob = custo.contact;
                $('#custo').val(name+' -( '+mob+' )');
                var enrol_count = $("#times").val()??1;
                for(var i=1;i<=enrol_count;i++){
                    $('input[name="name[]"]').val(name);
                }
                success_sweettoatr("Customer succesfully Added !");
                $("#custo_modal").modal('hide');
                resetcustoform(true);
            }
        });
			
        });
    </script>
	
@include('layouts.theme.js.datatable')
@include('layouts.vendors.js.passwork-popup')
@endsection