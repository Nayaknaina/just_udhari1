@extends('layouts.vendors.app')

@section('content')

@include('layouts.theme.css.datatable')



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
</style>
<style>
    #pay_block{
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
    #custo_info{
        font-size:110%;
    }
    .title_value>li{
        width:100%;
    }
    .title_value>li > span{
        float:right;
    }
    label.action > input{
        display:none;
    }
    label.action{
        border-radius:20px;
        position:relative;
        color:gray;
        cursor:pointer;
        font-weight:normal;
    }
    label.success-checked:hover{
        color:green;
    }
    label.danger-checked:hover{
        color:red;
    }
    .action:hover,.action.checked{
        text-shadow:1px 2px 3px gray;
    }
    .action:before{
        content:'\2714';
    }
    .success-checked.checked{
        border:1px solid green;
        color:green;
    }
    .danger-checked.checked{
        border:1px solid red;
        color:red;
    }
    .action.checked{
        box-shadow:1px -2px 3px gray;
    }
    .curr_value{
        font-weight:normal;
    }
    tr.text-success > td{
        color:green!important;
    }
    tr.text-danger > td{
        color:maroon!important;
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
	.txn_type{
        border-radius:20px;
    }
	.txn_type>.old_block{
		display:block;
	}
	.txn_type>.old_block >.old_value{
		float:right;
	}
    .txn_field{
        border:none;
        border:1px dashed gray;
        text-align:center;
        padding-left:45px;
    }
    .new_content{
        position:relative;
        align-content: center;
    }
    .new_content:after{
        content:"NEW";
        position:absolute;
        z-index: 3;
        left:0;
        top: 0;
        bottom: 0;
        height: 100%;
        align-content: center;
        background:#d3d3d33b;   
        border-radius:15px 0 0 15px;
        padding:0 5px;
		border:1px solid;
    }
	#form_title{
        display:none;
    }
    .edit_form{
        border: 1px dashed;
        box-shadow: 1px 2px 3px 4px gray;
    }
    #pay_block.edit_form > #form_title{
        display:block;
    }
	#missing_emi_check > li >label{
        cursor:pointer;
        color:gray;
    }
    #missing_emi_check > li >label:hover{
        color:white;
    }
    #missing_emi_check > li >label:before{
        content:"\2713";
    }
    #missing_emi_check > li.checked{
        font-weight:bold;
        box-shadow:1px 2px 3px 4px lightgray;
    }
    #missing_emi_check > li.checked > label{
        color:blue;
    }
    #custo_block.block{
        pointer-events:none;
    }
	
</style>
@php 
$anchor = ['<a href="'.route('anjuman.dashboard').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-home"></i> Dashboard</a>','<a href="'.route('anjuman.enroll',$id).'" class="btn btn-sm btn-outline-primary"> &plus; Enroll</a>','<a href="'. route('anjuman.due',$id).'" class="btn btn-sm btn-outline-info">&#9776; Month DUE</a>'];
$data = new_component_array('newbreadcrumb',"Anjuman Scheme Payment") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/>
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-2">
                            <div class="row">
                                <div class="col-md-4 p-0" id="pay_block">
									<h5 id="form_title" class="text-center">Edit Enroll</h5>
                                    <ul class="row p-0 m-0" style="list-style:none;" id="scheme">
                                        <li  class="col-md-8 text-center p-0 py-1">
                                            <h6 id="scheme_name" class="m-0">Scheme Name</h6>
                                        </li>
                                        <li  class="col-md-4 text-center p-0 py-1">
                                            <h5 id="scheme_type"  class="m-0"> Type</h5>
                                        </li>
                                    </ul>
                                    <div class="col-12 p-0 p-2" >
                                        <form action="{{ route('anjuman.payment') }}" id="txn_form"  > 
                                            @csrf
											<input type="hidden" id="txn" name="txn" value="">
                                            <input type="hidden" name="scheme_id" id="scheme_id" value="">
                                            
                                            <div class="form-group text-center  mb-2" >
                                                <div class="input-group">
                                                    <select name="custo" class="select2" id="custo">
                                                        <option>Loading Customers</option>
                                                    </select>
                                                    <a href="{{ route('anjuman.enroll',$id) }}"   class="btn btn-info m-0 input-group-append" style="align-content:center;position:absolute;right:0;top:0;bottom:0;">
                                                        <i class="fa fa-plus"></i> Enroll
                                                    </a>
                                                </div>
                                            </div>
											<div class="form-group form-control border-warning h-auto" id="missing_emi" style="display:none;">
                                                <label class="text-warning" for="">DUE Emi </label>
                                                <ul class="p-0" style="list-style:none;" id="missing_emi_check">
                                                </ul>
                                            </div>
                                            <div class="form-group col-12 mb-2">
                                                <div class="row">
                                                    <div class="col-md-6 p-0 form-control h-auto text-success txn_type border-success px-2">
                                                        <label class="text-success m-0" for="deposite">Deposite</label>
                                                        <hr class="m-0 border-success">
                                                        <div class="input-group old_block"  style="font-size:80%;">
                                                            <span class="">OLD : </span>
                                                            <span class="w-auto old_value" id="curr_deposite"></span>
                                                        </div>
                                                        <hr class="col-12 p-0 m-1">
                                                        <div class="input-group p0 new_content border-success mb-1">
                                                            <input type="text" class="form-control h-auto  txn_field text-success" id="deposite" name="deposite" placeholder="Deposite">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 p-0 form-control h-auto text-danger txn_type border-danger px-2">
                                                        <label class="text-danger m-0" for="withdraw">Withdraw</label>
                                                        <hr class="m-0 border-danger">
                                                        <div class="input-group old_block" style="font-size:80%;">
                                                            <span class="">OLD : </span>
                                                            <span class="w-auto old_value" id="curr_withdraw"></span>
                                                        </div>
                                                        <hr class="col-12 p-0 m-1">
                                                        <div class="input-group p-0 new_content mb-1">
                                                            <input type="text" class="form-control h-auto  txn_field text-danger" id="withdraw" name="withdraw" placeholder="Withdraw">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-12 mb-2">
                                                <div class="row">
                                                    <div class="col-md-6 p-0">
                                                        <input type="date" name="date" id="date" class="form-control text-center" value="{{ date('Y-m-d',strtotime('now')) }}">
                                                    </div>
                                                    <div class="col-md-6 p-0">
                                                        <input type="text" class="form-control" id="remark" name="remark" placeholder="Remark">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr style="border-top:1px solid gray" class="m-2">
                                            <div class="form-group text-center mb-0">
                                                <button type="submit" name="do" value="txn" class="btn btn-success">Save</button>
												<button type="submit" name="do" value="print" class="btn btn-secondary">Save & Print</button>
                                            </div>
                                        </form>
                                    </div>
                                    <ul class="row p-0 m-0 text-center" style="list-style:none;" id="emi_info">
                                        <li class="col-12 p-0 text-right" id="scheme_info_show">
                                            <button type="button" class="btn btn-sm bg-white text-primary" style="border:1px dashed orange" onclick="$('.scheme_info').toggle('slow');$(this).find('i').toggleClass('fa-angle-down fa-angle-up');">
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                        </li>
                                        <li class="col-6 text-info alert alert-outline-info p-0 m-0">
                                            <b>DUE</b>
                                            <hr class="m-0" style="border-top:1px dotted">
                                            <span id="due_quantity">EMI DUE</span>
                                        </li>
                                        <li class="col-6 text-warning alert alert-outline-warning p-0 m-0">
                                            <b>PAYABLE</b>
                                            <hr class="m-0">
                                            <span id="payable_quantity">EMI SUM</span>
                                        </li>
                                        <li class="col-6  alert alert-outline-dark p-0 m-0 scheme_info">
                                            <b>EMI</b>
                                            <hr class="m-0">
                                            <span id="emi_quantity">EMI</span>
                                        </li>
                                        <li class="col-6 alert alert-outline-dark p-0 m-0 scheme_info">
                                            <b>VALIDITY</b>
                                            <hr class="m-0">
                                            <span id="scheme_valid">UPTO</span>
                                        </li>
                                    </ul>
                                </div>
								
										<style>
											tbody#dataarea >tr.edit>td{
												background-color:#e8e8fd!important;
											}
											tbody#dataarea >tr.delete>td{
												background-color:#ffeee0!important;
											}
											tbody#dataarea >tr.update>td{
												background-color:#e2ffe2!important;
											}
										</style>
                                <div class="col-md-8 table-responsive">
                                    <table id="csTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
                                        <thead>
                                            <tr >
                                                <th>SN</th>
                                                <th>DATE</th>
                                                <th>TXN</th>
                                                <th>MONTH</th>
                                                <th>TYPE</th>
                                                <th>REMARK</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataarea">
                                            <tr class="bg-light">
                                                <td colspan="7" class="text-center text-danger">
                                                    <span><i class="fa fa-info-circle"></i>Select/Find Customer !</span>
                                                </td>
                                            </tr>
                                        </tbody>
										<tfoot>
                                            <tr>
                                                <td colspan="7" class="p-0">
                                                    <table class="w-100">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2" class="p-0">TOTAL</th>
                                                                <th style="color:green;" class="text-right p-0">Deposite :</th>
                                                                <th id="ttl_dp" class="text-success text-left p-0">

                                                                </th>
                                                                <th style="color:maroon;" class="text-right p-0">Withdraw :</th>
                                                                <th id="ttl_wd" class="text-danger text-left p-0">

                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<style>
	</style>
@endsection

@section('javascript')


    @include('layouts.theme.js.datatable')
    @include('layouts.vendors.js.passwork-popup')

    <script>
        $(document).ready(function(){
            var unit = '';
			 var edit = false;
            var pre_action = $("#txn_form").attr('action');
            var new_action = "{{ route('anjuman.payment.change') }}";
            var  payable = 0;
            var  start = false;
            var emi = false;
			var per_page = 20;
            $("#txn_form").trigger('reset');
            $("#scheme_id").val("");
            $("#scheme_name").html("");
            $("#scheme_type").html("");
            $.get("{{ route('anjuman.enroll',$id) }}","scheme=true",function(response){
                if(response.scheme){
                    $("#scheme_id").val(response.scheme.id);
                    $("#scheme_name").html(response.scheme.title);
                    unit  = (response.scheme.type=='gold')?"Gm.":"Rs.";
					start = response.scheme.start;
                    emi = (response.scheme.fix_emi==1)?response.scheme.emi_quant:false;
                    if(emi){
                        $("#deposite").val(emi);
                    }
                    payable = (emi)?emi*response.scheme.validity:false;
                    $('.unit').html(unit);
                    $("#emi_quantity").html(((emi)?emi+" "+unit:'No Fix !'));
                    $("#payable_quantity").html(((payable)?payable+" "+unit:'No Fix !'));
                    $("#scheme_valid").html(response.scheme.validity+" Month");
                    $("#scheme_type").html(response.scheme.type.toUpperCase());
                    $().html();
                }else{
                    let div = `<div class="alert alert-danger text-center">Invalid Scheme !</div>`;
                    $("#enroll_block").html(div)
                }
            });
            

            $.get('{{ route("anjuman.find") }}','key=all&scheme={{ $id }}',function(response){
                var option = '<option value="">No Enroll !</option>';
                if(response.enroll.length){
                    option='<option value="">Select Customer !</option>';
                    $.each(response.enroll,function(i,v){
                        option+='<option value="'+v.id+'">'+v.custo_name+'- ('+v.customer.custo_full_name+' - '+v.customer.custo_fone+')</option>';  
                    });
                }
                $("#custo").html(option);
				@if($custo)
					$("#custo").val("{{ $custo }}");
					$("#custo").trigger('input');
                @endif
            });


            $("#custo").on('input',function(response){
                var enroll = $(this).val()??false;
                if(enroll){
                    gettxns(enroll);
                }else{  
                    $("#missing_emi_check").empty();
                    $("#missing_emi").hide();
                }
                $("#curr_deposite").html('0 /-');
                $("#curr_withdraw").html('0 /-');
                $("#due_quantity").html('EMI DUE');
                $("#dataarea").html('<tr><td class="text-primary text-center" colspan="7"><span><i class="fa fa-info-circle"></i> Select/Find Customer !</span></td></tr>');
                if(enroll){
                    $.get("{{ route('anjuman.payment') }}/"+enroll,"custo=true",function(response){
                        //$("#curr_deposite").html((response.summery.deposite??0)+" "+unit);
                        //$("#curr_withdraw").html((response.summery.withdraw??0)+" "+unit);
						$("#curr_deposite,#ttl_dp").html((response.summery.deposite??0)+" "+unit);
                        $("#curr_withdraw,#ttl_wd").html((response.summery.withdraw??0)+" "+unit);
                        let due = (payable)?+payable - +response.summery.deposite:'No Fix !';
                        $("#due_quantity").html(due+" "+unit??'No Fixed !');
                    });
                }
            });

            function gettxns(enroll){
                $("#loader").show();
                $("#dataarea").html('<tr><td class="text-primary text-center" colspan="7"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></td></tr>');
				var op = {'E':' edit','U':' update','A':' add','D':' delete'};
                $.ajax({
                    url: "{{ route('anjuman.payment') }}/"+enroll , // Updated route URL
                    type: "GET",
                    data: {
						'entries':per_page
                    },
                    success: function (data) {
                    $("#loader").hide();
                    let tr = '<tr class="bg-light"><td colspan="7" class="text-center text-danger">No Txns !</td></tr>';
					
                    if(data.txns.length>0){
                        tr = '';
						const emiSums = {};
						const month = ['Jan','Feb','March','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec'];
                        $.each(data.txns,function(txni,txnv){
                            var show_class = (txnv.txn_status=='1')?'success':'danger';
                            var date_arr = txnv.txn_date.split('-');
                            //var month = ['Jan','Feb','March','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec'];
                            var date = date_arr[2]+"-"+(month[date_arr[1] - 1])+"-"+date_arr[0];
                            tr+=`<tr class='text-`+show_class+op[txnv.txn_action]+`'>
                                    <td class="text-center">`+(txni+1)+`</td>
                                    <td class="text-center">`+date+`</td>
                                    <td class="text-center">`+(txnv.txn_quant+" "+unit)+`</td>`;
									
									const pay_date = new Date(start);
                                    pay_date.setMonth(pay_date.getMonth() + (+txnv.emi_num - 1));
                                    let emi_month = (txnv.txn_status=='1')?pay_date.toLocaleString('default', { month: 'long' }):"NA";
									
                                    tr+=`<td class="text-center"><b>`+emi_month+`</b></td>
									<td class="text-center">`+((txnv.txn_status=='1')?'Deposit':'Withdraw')+`</td>
                                    <td class="text-center">`+txnv.remark+`</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                &#8942;
                                            </button>
                                            <ul class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton" style="min-width:max-content;">`;
											if(txnv.txn_action!='D' ){
                                                
                                                if(txnv.txn_action!='E'){
                                                tr+= `<li class="w-auto p-1">
                                                        <a class="w-100 btn btn-sm btn-outline-info" href="{{ route('anjuman.payment.edit')}}/`+txnv.id+`" data-redirect="false" data-mpin-check='true' >
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                    </li>`;
                                                }
                                                tr+= `<li class="w-auto p-1">
                                                    <a class="w-100 btn btn-sm btn-outline-danger" href="{{ route('anjuman.payment.delete')}}/`+txnv.id+`" data-redirect="false" data-mpin-check='true' >
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </li>`;
                                            }
                                            tr+=`<li class="w-auto p-1">
                                                    <a href="{{ route('anjuman.txn.print') }}/`+txnv.id+`" class="w-100 btn btn-sm btn-outline-secondary" target="_blank"><i class="fa fa-print"></i> Print</a>
                                                </li> 
                                            </ul>
                                        </div>
                                    </td>
                                </tr>`;
                        });
						if(Object.keys(emiSums).length > 0){
							const missing = {};
							var li='';
							console.log(emiSums);
								$.each(emiSums,function($key,$value){
								if($value<emi){
									var start_date = new Date(start);
									let mnth_num = start_date.getMonth() + +$key;
									missing[$key] = +emi - +$value;
									li+=`<input type="hidden" name="missing" value="true">
									<li class="btn btn-sm btn-outline-warning p-0 px-1 miss_emi_check">
									<label class="m-0" for="emi_`+$key+`">
									<input type="radio" name="num" value="`+$key+`" id="emi_`+$key+`" style="display:none;" data-value="`+missing[$key]+`"> `+month[mnth_num-1]+`
									</label>
									</li>`;
								}
							});
							$("#missing_emi_check").html(li);
							$("#missing_emi").show();
							$("#missing_emi_check").find('input[type="radio"]').eq(0).trigger('click');
						}
                        
                    }
                    $("#dataarea").html(tr);
                    },
                    error: function () {},
                });
            }

            $('.txn_field').on('input',function(){
                const target_field = {'deposite':'withdraw','withdraw':'deposite'};
                var pre_val = "";
                if($(this).val()!=""){
					if($(this).attr('id')=='deposite' && $(this).val() > emi){
                        toastr.error("Can;t Exceed to the <b>EMI ("+emi+")</b>");
                        $(this).val(emi);
                    }
                    $("#"+target_field[$(this).attr('id')]).val("");
                }else if($(this).attr('id')=='withdraw'){
                    $("#deposite").val(emi);
                }
                
            });
			
			$(document).on('change','input[type="radio"][name="num"]',function(){
                if($(this).is(':checked')){
                    $('.miss_emi_check').removeClass('checked');
                    $(this).closest('li').addClass('checked');
                    let now = $(this).data('value');
                    $("#deposite").val(now);
                }
            });
			
			var submit_btn = false;
            $('button[type="submit"]').click(function(){
                submit_btn = $(this).val();
            });
            $("#txn_form").submit(function(e){
                e.preventDefault();
                const path = $(this).attr('action');
                const data = $(this).serialize()+"&action="+submit_btn;;
                $.post(path,data,function(response){
                    if(response.errors){
                        $.each(response.errors,function(i,v){
                            $("input[name='"+i+"']").addClass('is-invalid');
                            toastr.error(v);
                        });
                    }else if(response.status){
                        success_sweettoatr(response.msg);
						if(response.url){
                            window.open(response.url, '_blank');
                        }
                        location.reload();
                    }else{
                        toastr.error(response.msg)
                    }
                });
            });
			
			$(document).on('mpinVerified', function(e, response) {
                if(response.op){
                    if(response.op=='edit'){
                        let data = response.txn??null;
                        if(data && !$.isEmptyObject(data)){
                            $("#txn").val(data.id);
                            $("#date").val(data.txn_date);
                            $("#remark").val(data.remark);
                            if(data.txn_action=='A' ||  data.txn_action=='U'){
                                $("#withdraw,#deposite").val("");
                                if(data.txn_status==1){
                                    $("#deposite").val(data.txn_quant);
                                }else if(data.txn_status==0){
                                    $("#withdraw").val(data.txn_quant);
                                }
                            }
                            $('button[type="submit"][name="do"][value="print"]').hide();
                            $('#form_title').text('Edit Payment');
                            $("#txn_form").attr('action',new_action+"/"+data.id);
                            $("[type='submit']").text('Change');
                            $("#pay_block").addClass('edit_form');
                            edit = true;
                        }else{
                            toastr.error('Invalid Payment !');
                        }
                    }else if(response.op=='delete'){
                        if(response.status){
                            success_sweettoatr(response.msg);
							location.reload();
                        }else{
                            toastr.error(response.msg);
                        }
                    }
                } 
            });
			
			
			$('span.select2.select2-container').css('width','inherit');
        });
    </script>
	
@endsection