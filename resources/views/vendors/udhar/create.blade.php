@extends('layouts.vendors.app')

@section('css')

<style>
    .udhar_block  div.form-group>label{
        font-size:80%;
        margin-bottom:1px;
    }
    .udhar_block div.form-group{
        margin-bottom:2px;
    }
    .udhar_block div.form-group>input,
    .udhar_block div.form-group div.input-group>span>input{
        padding:2px 15px;
        height:auto!important;
    }
    .udar_block_head{
        border-bottom:1px dashed gray;
    }
    #bhav_cut_table > thead > tr >th,#bhav_cut_table > tbody > tr >td{
        padding:0;
        border:0;
    }
    #bhav_cut_table > thead > tr >th>input,#bhav_cut_table > tbody > tr >td>input{
        padding:2px 25px 2px 5px;
        height:auto!important;
    }
    #bhav_cut_table > tbody > tr >td>div.input-group >input {
        padding:2px 10px;
        height:auto!important;
    }
    #bhav_cut_table > tbody > tr >td>div.input-group >div> select{
        padding:2px 2px;
        height:auto!important;
    }
    tr>td.equal_sign{
        position:relative;
        padding:0 10px;
        width:15%;
    }
    tr>td.equal_sign:after{
        position:absolute;
        content:":";
        right:0;
    }
</style>
<style>
    #customerlist {
        list-style-type: none;
        padding: 0;
        margin: 0;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        width: 200px;
        display: none;
        position: absolute;
        z-index: 100;
        max-height:50vh;
        height:auto;
        min-width:auto;
        overflow-x:scroll;
        box-shadow: 1px 2px 3px gray
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
    .unit{
        position:relative;
    }
    .unit:after{
        position:absolute;
        right:0;
        bottom:2px;
        padding:0 2px;
        /* color:gray; */
        /* top: 0; */
        align-content: center;
    }
    .unit.gm_unit:after{
        content:"gm";
    }
    .unit.per_gm_unit:after{
        content:"/gm";
    }
    .unit.rs_unit:after{
        content:"₹";
        padding-right:15px;
        font-size:120%;
        right:2px;
    }
    #loader{
        position:absolute;
        width:100%;
        height:100%;
        top:0;
        left:0;
        background: #0009;
        z-index:3;
        color: orange;
        font-size: 150%;
        padding:40% 0;
        display:none;
    }
    .metal.gold,.metal.silver{
        display:none;
    }
    .block_at_bhav_cut.active{
        position: relative;
    }
    .block_at_bhav_cut.active:after{
        content:" ";
        width:100%;
        height:100%;
        position: absolute;
        top:0;
        left:0;
        background:#00000014;
        z-index:25;
    }
    input:focus,select:focus{
        box-shadow: -2px 5px 5px gray!important;
    }
    #bhav_cut_table > tbody > tr >td{
        min-width:100px;
        width:auto;
    }
	label#bhaw_cut_button{
        position:absolute;
        right:10px;
        z-index:1;
    }
    @media only screen and (max-width: 768px){
        label#bhaw_cut_button{
            position:inherit;
            margin:auto!important;
        }
    }
</style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'New Udhari',[['title' => 'New Udhari']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('udhar.index').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> List</a>','<a href="'.route('udhar.ledger').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Ledger</a>'];
$data = new_component_array('newbreadcrumb',"New Udhar") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    
    <section class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary" style="border-top:1px dashed #ff2300;;">
                        <!-- <div class="card-header">
                            <h6 class="card-title col-12 p-0">New Udhari  <a href="#" class="btn btn-sm btn-primary" style="float:right;"><li class="fa fa-list-ol"></li></a></h6>
                        </div> -->
                        <form id="udhar_form" action="{{ route('udhar.store') }}" role="form" autocomplete="off">
                            @csrf
                            <div class="card-body pt-2" id="form_container">
                                <div class="row block_at_bhav_cut">
                                    <div class="col-md-6 col-10 p-2">
                                        <div class="row">
                                            <div class="input-group col-md-12">
                                                <div class="input-group">
                                                    <input type="text" name="name" id="name" class="form-control myselect placeholdertolabel h-auto" placeholder="CustomerNo./Name/Mobile" oninput="getcustomer($(this))">
                                                    <input type="hidden" name="custo_id" value="">
                                                    <input type="hidden" name="udhar_id" value="">
                                                    <ul id="customerlist" class="w-100">
                                                    </ul>
                                                    <div class="input-group-append">
                                                        <!-- <button class="btn btn-outline-secondary m-0" type="button"><i class="fa fa-caret-down"></i></button> -->
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#custo_modal">
                                                        <i class="fa fa-plus"></i>
                                                        </button>

                                                        <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#custo_modal"  class="btn btn-warning m-0" type="button"><i class="fa fa-plus" style="vertical-align: bottom;"></i></a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2 text-center p-2">
                                        <a href="#" class="btn btn-primary form-control w-auto h-auto" id="custo_txn_jump" style="line-height: normal;">
											<i class="fa fa-book" style="vertical-align:bottom;" ></i>
										</a>
										{{-- <a href="{{ route('udhar.index') }}" class="btn btn-primary form-control w-auto mb-3"><i class="fa fa-list" style="vertical-align:bottom;"></i></a>--}}
                                    </div>
									<div class="form-group offset-md-2 col-md-2 p-2">
                                        <input type="date" class="form-control text-center text-secondary" id="date" name="date" value="{{ date('Y-m-d',strtotime('now')) }}" style="border:1px solid  #f95600;font-weight:bold;">
                                    </div>
                                </div>
                                <div class="row block_at_bhav_cut" >
                                    <div class="col-md-5 col-12 m-0">
                                        <div class="card border-dark">
                                            <div class="card-header text-center p-1 udar_block_head">
                                                <h3 class="card-title col-12 text-dark">Cash 
                                                    <img src="{{ asset('main/assets/cstm_imgs/game-icons_take-my-money.svg') }}" alt="Money Icon" width="30" height="30">
                                                </h3>
                                            </div>
                                            <div class="card-body udhar_block">
                                                <div class="row">
                                                    <div class="form-group unit rs_unit col-12">
                                                        <label for="old_cash" >Old</label>
                                                        <input type="text" class="form-control cash_block" name="cash_old" placeholder="" readonly>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <label for="old_cash" class="text-danger col-12">Udhar/Out(-)</label>
                                                            <div class="input-group">
                                                                <span class="col-6   unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-danger  text-danger udhar_input_field" name="cash_out_off"  data-target="cash"  placeholder="Cash">
                                                                </span>
                                                                <span class="col-6  unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-danger  text-danger udhar_input_field" name="cash_out_on"  data-target="cash"  placeholder="Online">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                        <label for="old_cash" class="text-success col-12">Jama/In(+)</label>
                                                            <div class="input-group">
                                                                <span class="col-6   unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-success  text-success udhar_input_field" name="cash_in_off"   data-target="cash"   placeholder="Cash">
                                                                </span>
                                                                <span class="col-6  unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-success  text-success udhar_input_field" name="cash_in_on"   data-target="cash"   placeholder="Online">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group unit rs_unit col-12">
                                                        <label for="old_cash">Final</label>
                                                        <input type="text" class="form-control cash_block" name="cash_final" placeholder="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-6 col-12 mb-3">
                                                <div class="card border-dark">
                                                    <div class="card-header text-center p-1 udar_block_head">
                                                        <h3 class="card-title col-12 text-dark">
                                                            Gold
                                                            <!-- <i style="color:#fa9933;font-size: 150%">&#10022;</i> -->
                                                            <i style="color:#fa9933;font-size: 150%;">&#10023;</i>
                                                        </h3>
                                                    </div>
                                                    <div class="card-body udhar_block">
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" >Old</label>
                                                            <input type="text" class="form-control gold_block" name="gold_old" placeholder="" readonly>
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-danger">Udhar/Out(-)</label>
                                                            <input type="text" class="form-control gold_block border-danger  text-danger udhar_input_field" name="gold_out_off"  data-target="gold"  placeholder="">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-success">Jama/In(+)</label>
                                                            <input type="text" class="form-control gold_block border-success  text-success udhar_input_field" name="gold_in_off" data-target="gold" placeholder="">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash">Final</label>
                                                            <input type="text" class="form-control gold_block" name="gold_final" placeholder="" readonly>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="col-md-6 col-12 mb-3">
                                                <div class="card border-dark">
                                                    <div class="card-header text-center p-1 udar_block_head">
                                                        <h3 class="card-title col-12 text-dark">
                                                            Silver
                                                            <!-- <i style="color:#cdcdcd;font-size: 150%">&#10022;</i> -->
                                                            <i style="color:#cdcdcd;font-size: 150%">&#10023;</i>
                                                        </h3>
                                                    </div>
                                                    <div class="card-body udhar_block">
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" >Old</label>
                                                            <input type="text" class="form-control silver_block" name="silver_old" placeholder="" readonly>
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-danger">Udhar/Out(-)</label>
                                                            <input type="text" class="form-control silver_block border-danger text-danger udhar_input_field" name="silver_out_off" data-target="silver" placeholder="">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-success">Jama/In(+)</label>
                                                            <input type="text" class="form-control silver_block border-success  text-success udhar_input_field" name="silver_in_off" data-target="silver" placeholder="">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash">Final</label>
                                                            <input type="text" class="form-control silver_block" name="silver_final" placeholder="" readonly>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"  id="bottom_block" style="display:;">
									<label class="btn btn-secondary toggle_button m-2"  for="chav_cut_check" style="border-radius:15px;" id="bhaw_cut_button">
										Bhav Cut <i class="fa fa-caret-down"></i>
										<input type="checkbox" name="bhav_cut" value="yes" id="chav_cut_check" style="display:none;" onchange="$('#bhav_cut').toggle('fade');$(this).closest('label').toggleClass('btn-secondary btn-outline-secondary');">
									</label>
                                    <div class="col-md-8 text-center p-2 offset-md-2"  id="bhav_cut" style="border:1px solid gray;display:none;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive border-dark py-3">
                                                    <table class="table table-bordered m-0" id="bhav_cut_table">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th colspan="2">
                                                                    <label class="form-control border-secondary m-0" style="border-radius:15px;">CASH</label>
                                                                </th>
                                                                <th></th>
                                                                <th colspan="2">
                                                                    <div class="dropdown show">
                                                                        <select name="conver_into" class="form-control border-secondary text-center m-0" style="font-weight:bold;">
                                                                            <option value="">GOLD/SILVER</option>
                                                                            <option value="gold">Gold</option>
                                                                            <option value="silver">Silver</option>
                                                                        </select>
                                                                    </div>
                                                                </th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="equal_sign">Final </td>
                                                                <td></td>
                                                                <td class="unit rs_unit">
                                                                <input type="text" class="form-control bhav_final_udhar  text-center" name="bhav_final_udhar"  id="bhav_final_udhar" placeholder="" readonly>
                                                                </td>
                                                                <td></td>
                                                                <td class="unit gm_unit">
                                                                    <input type="text" class="form-control bhav_final_cnvrt  text-center" id="bhav_final_cnvrt" name="bhav_final_cnvrt" placeholder="" readonly>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="equal_sign" >Exchange</td>
                                                                <td class="text-right">
                                                                    <label class="btn btn-sm btn-outline-warning m-0 bhav_cnvrt_btn" id="bhav_cnvrt_left" for="direction_amount">
                                                                        <
                                                                        <input type="radio" id="direction_amount" name="direction" value="amount" style="display:none;">
                                                                    </label>
                                                                </td>
                                                                <td class="unit rs_unit">
                                                                    <input type="text" class="form-control bhav_cnvrt_from  text-center convert_source" name="bhav_cnvrt_from"  id="bhav_cnvrt_from" placeholder="" style="color:blue;" >
                                                                </td>
                                                                <td style="min-width:150px;">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control bhav_cnvrt_rate text-info text-center" name="bhav_cnvrt_rate"  id="bhav_cnvrt_rate" placeholder="" >
                                                                        <div class="input-group-append ">
                                                                            <select name="cnvrt_unit" class="form-control text-right" style="font-size: 80%;font-weight: bold;"> 
                                                                                <option value="" class="metal">Unit</option>
                                                                                <option value="1" class="gold metal gold_1">/1gm </option>
                                                                                <option value="10" class="gold metal gold_10">/10gm</option>
                                                                                <option value="1" class="silver metal silver_1">/1kg</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="unit gm_unit">
                                                                    <input type="text" class="form-control bhav_cnvrt_to  text-center convert_source" name="bhav_cnvrt_to"  id="bhav_cnvrt_to" placeholder="" style="color:blue;" >
                                                                </td>
                                                                <td class="text-left">
                                                                    <label class="btn btn-sm btn-outline-warning m-0 bhav_cnvrt_btn" id="bhav_cnvrt_right" for="direction_metal"> 
                                                                        <input type="radio" id="direction_metal" name="direction" value="metal" style="display:none;" >
                                                                        >
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="unit rs_unit">
                                                                <input type="text" class="form-control bhav_udhar_money  text-center" name="bhav_udhar_money"  id="bhav_udhar_money" placeholder="" readonly>
                                                                </td>
                                                                <td></td>
                                                                <td class="unit gm_unit">
                                                                    <input type="text" class="form-control bhav_udhar_metal text-center" name="bhav_udhar_metal"  id="bhav_udhar_metal" placeholder="" readonly>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center pt-2 mt-2" style="border-top:1px solid lightgray;">
										<div class="col-12 p-0">
											<label><input type="text" class="form-control" id="remark" name="remark" value="" placeholder="Remark here"></label>
										</div>
										
                                        <!--<label class="btn btn-secondary toggle_button m-2"  for="chav_cut_check" style="border-radius:15px;">
                                            Bhav Cut <i class="fa fa-caret-down"></i>
                                            <input type="checkbox" name="bhav_cut" value="yes" id="chav_cut_check" style="display:none;" onchange="$('#bhav_cut').toggle('fade');$(this).closest('label').toggleClass('btn-secondary btn-outline-secondary');">
                                        </label>-->
                                        <button type="submit" name="action" value="print" class="btn btn-outline-warning m-2">Print <i class="fa fa-print"></i></button>
                                        <button type="submit" name="action" value="done" class="btn btn-success m-2">Save <i class="fa fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="text-center" id="loader">
                            <span class=""><i class="fa fa-spinner fa-spin"></i>Please Wait...</span>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
    @php $from = 4 @endphp
    @include('vendors.commonpages.newcustomerwithcategory')

@endsection
@section('javascript')
    @include('layouts.common.placeholdertolabel')
    <script>
        //const price = {'gold':1000,'silver':800};
        const price = @json($rate);
        function getcustomer(self){
            const title = self.val();
            if(title!=""){
                $.get("{{ route('global.customers.search') }}","keyword="+title,function(response){
                    var li = '';
                    if(response){
                        /*$(response).each(function(i,v){
                            var name = v.name??"NA";
                            var num = v.num??'NA';
                            var mob = v.mobile??'NA';
                            var id=v.id??0;
                            var stream = num+"/"+name+" - "+mob;
                            li+='<li><a href="{{ url('vendors/customer/udhardata') }}/'+id+'" data-target="'+stream+'" class="select_customer">'+stream+'</a></li>';
                        });*/
                    }
                    $("#customerlist").empty().append(response);
                    $("#customerlist").addClass('active');
                    positionmenu('#customerlist','#name');
                });
            }else{
                $('[name="custo_id"]').val('');
                $('[name="udhar_id"]').val('');
                $("#udhar_form").trigger('reset');
                $("#customerlist").removeClass('active');
                //$("#bottom_block").hide();
            }
        }
		
		/*$("#name").focusout(function(){
			setTimeout(function() {
			if (!$(document).find(':focus').hasClass('select_customer')) {
					$("#customerlist").hide();
				}
			}, 5); 
        });*/
		
        /*$("#name").focusin(function(){
            const customer_list = $("#customerlist");
            //alert(customer_list.find('li').length);
            if(customer_list.find('li').length > 0){
                customer_list.show();
            }
        });*/
		
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

        $(document).on('click','.select_customer',function(e){
            e.preventDefault();
            $("#customerlist").removeClass('active');
            $("#name").empty().val($(this).data('target'));
            var trgt_path = $(this).attr('href');
            var match_path = "{{ url('vendors/customer/udhardata') }}";
            if(trgt_path.includes(match_path)){
                $.get($(this).attr('href'),"",function(response){
                    $("#custo_txn_jump").attr('href',"javascript:void(null);");
                    const custo_id = response.custo_id??0;
                    const udhar_id = response.id??0;
                    $('[name="custo_id"]').empty().val(custo_id);
                    $('[name="udhar_id"]').empty().val(udhar_id);
                    if(udhar_id!=0){
                        $("#custo_txn_jump").attr('href',"{{ url('vendors/udhar/transactions') }}/"+udhar_id);
                    }else{
                        $("#custo_txn_jump").attr('onclick',"toastr.error('No Udhar Account Yet !')" );
                    }
                    var amount = response.custo_amount??0;
                    if(amount !=0){
                        amount = (response.custo_amount_status==1)?'+'+amount:'-'+amount;
                        var color = (response.custo_amount_status==1)?'green':'red';
                        $('[name="cash_old"],[name="cash_final"]').css('color',color);
                    }
                    $("#bhav_final_udhar").val(amount);
                    $('[name="cash_old"],[name="cash_final"]').empty().val(amount);
                    
                    var gold = response.custo_gold??0;
                    if(gold!=0){
                        gold = (response.custo_gold_status==1)?'+'+gold:'-'+gold;
                        var color = (response.custo_gold_status==1)?'green':'red';
                        $('[name="gold_old"],[name="gold_final"]').css('color',color);
                    }
                    $('[name="gold_old"],[name="gold_final"]').empty().val(gold??0);
                    var silver = response.custo_silver??0;
                    if(silver!=0){
                        silver = (response.custo_silver_status==1)?'+'+silver:'-'+silver;
                        var color = (response.custo_silver_status==1)?'green':'red';
                        $('[name="silver_old"],[name="silver_final"]').css('color',color);
                    }
                    $('[name="silver_old"],[name="silver_final"]').empty().val(silver??0);
                    //$("#bottom_block").show();
                    calculation();
                });
            }else{
                $("#udhar_form").trigger('reset');
                $("#custo_txn_jump").attr('href');
            }
        });

        $(document).on('input','.udhar_input_field',function(e){
            if($("#name").val()!="" || $("[name='custo_id']").val()!=""){
                const target = $(this).data('target');
                const old_value = $('[name="'+target+'_old"]').val(); 
                if(old_value!=""){
                     const curr_val = $(this).val()??0;
                     var op_arr = $(this).attr('name').replace(target+"_","").split("_");
                     const op = op_arr[0];
                     const mode = op_arr[1];
                     var final_value = 0;
                     if(curr_val!=""){
                        const opps_trgt = (op=='in')?'out':'in';
                        const input = target+'_'+opps_trgt+'_';
                        $('[name="'+input+mode+'"]').val("");
                        if(target == 'cash'){
                            var mode_alt = (mode=='off')?'on':'off';
                            $('[name="'+input+mode_alt+'"]').val("");
                            var alt_input = target+'_'+op+'_';
                            $('[name="'+alt_input+mode_alt+'"]').val("");
                        }

                        if(op=='in'){
                            final_value = +old_value+ +curr_val;
                        }else{
                            final_value = +old_value- +curr_val;
                        }
                        //final_value = +old_value+ +curr_val;
                        /*if(op=='in'){
                             $('[name="'+target+'_out_'+mode+'"]').val("");
                             final_value = +old_value+ +curr_val;
                             if(target == 'cash'){
                                var mode_alt = (mode=='off')?'on':'off';
                                $('[name="'+target+'_out_'+mode_alt+'"]').val("");
                             }
                         }else{
                             $('[name="'+target+'_in_'+mode+'"]').val("");
                             final_value = +old_value- +curr_val;
                             if(target == 'cash'){
                                
                            }
                         }*/
                     }else{
                             final_value = old_value;
                         $('[name="'+target+'_final"]').val(+ + old_value);
                     }
                     var color = (final_value>0)?'green':'red';
                     final_value = (final_value>0)?"+"+final_value:final_value;
                     $('[name="'+target+'_final"]').val(final_value);
                     $('[name="'+target+'_final"]').css('color',color);
                     if((curr_val!="" && curr_val!=0) && final_value!=0 ){
                         var color = (final_value>0)?'green':'red';
                         $('[name="'+target+'_final"]').val(final_value);
                         $('[name="'+target+'_final"]').css('color',color);
                    } 
                }
                calculation();
            }else{
                //e.preventDefault();
                toastr.error("Please Select the customer first !");
                $("#name").focus();
                $(this).val("");
            }
        });
        
        /*$(document).on("input",".convert_source",function(e){
            const id = $(this).attr('id');
            const target_field = {"bhav_cnvrt_from":["bhav_final_udhar","bhav_udhar_money"],"bhav_cnvrt_to":["bhav_final_cnvrt","bhav_udhar_metal"]};
            const reset_field = {"bhav_cnvrt_from":"bhav_cnvrt_to","bhav_cnvrt_to":"bhav_cnvrt_from"};
            if($("[name='conver_into']").val()!=""){
                const main_value = $("#"+target_field[id][0]).val();
                const self_value = $(this).val()??0;
                const test_main_value = Math.abs(main_value);
                const test_self_value = Math.abs(self_value);
                if(test_self_value<=test_main_value){
                    var  total = (main_value>0)?+ main_value - +self_value:+ main_value + +self_value;
                    //$("#"+reset_field[$(this).attr('id')][1]).val("");
                    total = (total>0)?"+"+total:total;
                    $("#"+target_field[$(this).attr('id')][1]).val(total);
                }else{
                    $(this).focus();
                    toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                    return false;
                }
                $("#"+reset_field[id]).val("");
            }else{
                $(this).val("");
                toastr.error("In what you want to Convert !");
                $("[name='conver_into']").focus();
            }
        });*/

        var cut_pre_val = "";
        $(document).on("input",".convert_source",function(e){
            $('.bhav_cnvrt_btn').removeClass('btn-warning').addClass('btn-outline-warning');
            $('[type="radio"]').prop('checked',false);
            if($("[name='conver_into']").val()!=""){
                const id = $(this).attr('id');
                const target_field = {"bhav_cnvrt_from":["bhav_final_udhar","bhav_udhar_money"],"bhav_cnvrt_to":["bhav_final_cnvrt","bhav_udhar_metal"]};
                const reset_field = {"bhav_cnvrt_from":"bhav_cnvrt_to","bhav_cnvrt_to":"bhav_cnvrt_from"};
                const main_value = $("#"+target_field[id][0]).val();
                var self_value = $(this).val();
                if(self_value=="-" || self_value == "+"){
                    $(this).val("");
                    $("#"+target_field[id][1]).val("");
                    $(".bhav_cnvrt_btn").removeClass('btn-warning').addClass("btn-outline-warning");
                    $("#"+reset_field[id]).val("");
                    $("#"+target_field[reset_field[id]][1]).val("");
                    $('.row.block_at_bhav_cut').removeClass('active');
                }else{
                    const test_main_value = Math.abs(main_value);
                    var test_self_value = Math.abs(self_value);
                    
                    if(test_self_value > 0 || self_value!=""){
    
                        if(test_self_value <= test_main_value){
                            $('.row.block_at_bhav_cut').addClass('active');
                            var show_test = (main_value>0)?"+"+test_self_value:"-"+test_self_value;
                            $(this).val(show_test);
                            const total = test_main_value-test_self_value;
                            //alert(total);
                            var test_total = (main_value>0)?'+'+total:"-"+total;
                            var color = (main_value>0)?'green':'red';
                            $("#"+target_field[id][1]).val(test_total);
                            $("#"+target_field[id][1]).css('color',color);
                            cut_pre_val = self_value;
                        }else{
                            $(this).focus();
                            toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                            $(this).val(cut_pre_val);
                        }
                    }else{
                        $(this).val("");
                        $(this).css("color","");
                        $('.row.block_at_bhav_cut').removeClass('active');
                    }
                }
            }else{
                $(this).val("");
                toastr.error("In what you want to Convert !");
                $("[name='conver_into']").focus();
            }
            
        });


        

        function loadbhavcut(){
            if($('#chav_cut_check').is(':checked')){
                var cut_val = false;
                $('.convert_source').each(function(i,v){
                    if($(this).val()!=""){
                        cut_val=true;
                    }
                });
                if(cut_val){
                    $(".row.block_at_bhav_cut").addClass('active');
                }
                const source = $('[name="cash_final"]');
                const color = source.css('color');
                $('[name="bhav_final_udhar"]').css('color',color);
                $('[name="bhav_final_udhar"]').empty().val(source.val());
                loadconvertmetal();
            }else{
                $(".row.block_at_bhav_cut").removeClass('active');
            }
        }

        function loadconvertmetal(){
            if($('[name="conver_into"]').val()!=""){
                $('.metal').hide();
                const sel_metal = $('[name="conver_into"]').val();
                $(".metal").hide();
                $(".metal."+sel_metal).show();
                $('[name="cnvrt_unit"] option.'+sel_metal+'_1').prop('selected',true);
                const source = $('[name="'+sel_metal+'_final"]');
                const color = source.css('color');
                $('[name="bhav_final_cnvrt"]').empty().val(source.val());
                $('[name="bhav_final_cnvrt"]').css('color',color);
                $('[name="bhav_cnvrt_rate"]').empty().val(price[sel_metal]);
            }else{
                $('#bhav_cnvrt_rate').val("");
                $('[name="cnvrt_unit"]').val("");
                $('.metal.gold,.metal.silver').hide();
            }
        }

        function calculation(){
            loadbhavcut();
        }
        $(document).on('change','#chav_cut_check',function(e){
            loadbhavcut();
        })

        $(document).on('change','[name="conver_into"]',function(e){
            loadconvertmetal();
        });
		
        $(document).on('click','.bhav_cnvrt_btn',function(e){
            //e.preventDefault();
            if($('[name="conver_into"]').val()!=""){
                if($('#bhav_cnvrt_rate').val()!="" && $('[name="cnvrt_unit"]').val() !=""){
                    const direction = $(this).attr('id');

                    const rate_value = $('#bhav_cnvrt_rate').val()
                    const unit = $('[name="cnvrt_unit"]').val();
                    const cnvrt_into = $('[name="conver_into"]').val();
                    
                    const unit_div = (cnvrt_into=='silver')?1000:unit;
                    
                    const rate = rate_value/unit_div;
                    var ahead = true;

                    var initial =   "bhav_final_";
                    var value   =   "bhav_cnvrt_";
                    var final =   "bhav_udhar_";
                    
                    const data_arr = {
                        'bhav_cnvrt_right': {
                            'source': ['udhar', 'from', 'money'],
                            'target': ['cnvrt', 'to', 'metal']
                        },
                        'bhav_cnvrt_left': {
                            'source': ['cnvrt', 'to', 'metal'],
                            'target': ['udhar', 'from', 'money']
                        }
                    };                 


                    const src_final = $('[name="'+initial+data_arr[direction]['source'][0]+'"]').val();
                    const src_value_field = $('[name="'+value+data_arr[direction]['source'][1]+'"]');
                    const src_value = src_value_field.val();
                    
                    const src_final_field = $('[name="'+value+data_arr[direction]['source'][1]+'"]');

                    const src_test_final = Math.abs(src_final)??0;
                    if(src_value > src_test_final){
                        ahead = false;
                        src_value_field.focus();
                        toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                    }
                    if(ahead){
                        const src_test_value = Math.abs(src_value)??0;
                        var src_remains = +src_test_final - +src_test_value;
                        src_remains = (src_final>0)?"+"+src_remains:"-"+src_remains;
                        const src_final_color = (src_final>0)?"green":"red";
                        $('[name="'+final+data_arr[direction]['source'][2]+'"]').val(src_remains).css('color',src_final_color);
                        var converted = (data_arr[direction]['source'][0]=="udhar")?src_test_value/rate:src_test_value*rate;
						
						
						//alert(direction);
                        //alert(converted);
						converted = (direction=='bhav_cnvrt_right')?converted.toFixed(3):converted;
                        converted = ((src_value>0)?'+':'-')+converted;
						
                        const trgt_final = $('[name="'+initial+data_arr[direction]['target'][0]+'"]').val();
                        $('[name="'+value+data_arr[direction]['target'][1]+'"]').val(converted);
                        
                        const trgt_val = $('[name="'+value+data_arr[direction]['target'][1]+'"]').val();
                        
                        var trgt_remains = +trgt_final + +trgt_val
                        trgt_remains = (trgt_remains>0)?'+'+trgt_remains:trgt_remains;
                        const trgt_color = (trgt_remains<0)?'red':'green';
                        $('[name="'+final+data_arr[direction]['target'][2]+'"]').val(trgt_remains).css('color',trgt_color);
                        
                        $('.bhav_cnvrt_btn').removeClass('btn-warning').addClass('btn-outline-warning');
                        $(this).removeClass('btn-outline-warning').addClass('btn-warning');
                    }
                }else{
                    toastr.error("Please Provide The rate & Unit !");
                }
            }else{
                $('[name="conver_into"]').addClass('is-invalid');
                $('[name="conver_into"]').focus();
                toastr.error("In What You Want to Convert !");
            }
        });
        /*$(document).on('click','.bhav_cnvrt_btn',function(e){
            e.preventDefault();
            if($('[name="conver_into"]').val()!=""){
                if($('#bhav_cnvrt_rate').val()!="" && $('[name="cnvrt_unit"]').val() !=""){

                    const into  = $(this).attr('id');
    
                    const rate_value = $('#bhav_cnvrt_rate').val()
                    const unit = $('[name="cnvrt_unit"]').val();

                    const rate = rate_value/unit;


                    const desire_final = {'bhav_cnvrt_right':'bhav_final_udhar','bhav_cnvrt_left':'bhav_final_cnvrt'};
                    const desire_input = {'bhav_cnvrt_right':'bhav_cnvrt_from','bhav_cnvrt_left':'bhav_cnvrt_to'};
                    const target_input = {'bhav_cnvrt_right':'bhav_cnvrt_to','bhav_cnvrt_left':'bhav_cnvrt_from'};

                    const target_final = {'bhav_cnvrt_right':'bhav_final_cnvrt','bhav_cnvrt_left':'bhav_final_udhar'}
                    const target_remains = {'bhav_cnvrt_right':'bhav_udhar_metal','bhav_cnvrt_left':'bhav_udhar_money'}
                    var from = 0;
                    var to = 0;
                    var ahead = true;
                    
                    const input_final_val =  Math.abs($('[name="'+desire_final[into]+'"]').val());
                    
                    const input_source_val = Math.abs($('[name="'+desire_input[into]+'"]').val());
    
                    if($('[name="'+desire_input[into]+'"]').val()=="" || $('[name="'+desire_input[into]+'"]').val()==0){
                        $('[name="'+desire_input[into]+'"]').addClass('is-invalid');
                        $('[name="'+desire_input[into]+'"]').focus();
                        ahead = false;
                        toastr.error("Conversion Source Required !");
                    }else{
                        if(input_source_val > input_final_val){
                            ahead = false;
                            $('[name="'+desire_input[into]+'"]').focus();
                            toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                        }
                    }
                    if(ahead){
                        $('.bhav_cnvrt_btn').removeClass('btn-warning').addClass('btn-outline-warning');
                        $(this).removeClass('btn-outline-warning').addClass('btn-warning');
                        $('[name="bhav_cnvrt_from"],[name="bhav_cnvrt_to"]').removeClass('is-invalid');
                        
                        var converted = (into=='bhav_cnvrt_right')?input_source_val/rate:input_source_val*rate;
                        converted = converted??false;
                        alert(converted);
                        if(converted){
                            converted = ($('[name="'+desire_input[into]+'"]').val()>0)?"+"+converted:"-"+converted;
                            
                            $('[name="'+target_input[into]+'"]').val(converted);
                            
                            const initial = $('[name="'+target_final[into]+'"]').val();
                            alert(initial);
                            const new_cnvrt = Math.abs(converted);

                            var remains = +initial + +converted;
                            
                            remains = (remains>0)?"+"+remains:remains;
                            const color_rmns = (remains>0)?"green":"red";
                            $('[name="'+target_remains[into]+'"]').val(remains);
                            $('[name="'+target_remains[into]+'"]').css('color',color_rmns);
                            
                        }else{
                            toastr.error("Conversion Error !");
                        }
                    }
                }else{
                    toastr.error("Please Provide The rate & Unit !");
                }
            }else{
                $('[name="conver_into"]').addClass('is-invalid');
                $('[name="conver_into"]').focus();
                toastr.error("In What You Want to Convert !");
            }
        });*/

        /*$(document).ready(function() {
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
                    $('.btn').prop("disabled", true);
                    $('#loader').removeClass('hidden');
                    },
                    success: function(response) {
                        $('.btn').prop("disabled", false);
                        $('#loader').addClass('hidden');
                        if(response.success){
                            $("#custo_modal").modal('hide');
                            $("#custo_plus_form").trigger('reset');
                            success_sweettoatr(response.success);
                            if(response.data){
                                const data_stram = response.data.custo_num+"/"+response.data.custo_full_name+"-"+response.data.custo_fone;
                                alert(data_stram);
                                $('[name="custo_id"]').val(response.data.id);
                                $('[name="cash_old"] ,[name="gold_old"] ,[name="silver_old"] ,[name="cash_final"],[name="gold_final"],[name="silver_final"]').val(0);
                                $('#name').val(data_stram);
                                $("#bottom_block").show();
                            }
                        }else{
                            if(typeof response.errors !='string'){
                                $.each(response.errors,function(i,v){
                                    $('[name="'+i+'"]').addClass('is-invalid');
                                    $.each(v,function(ind,val){
                                        toastr.error(val);
                                    });
                                });
                            }else{
                                toastr.error(response.errors);
                            }
                        }
                    },
                    error: function(response) {
                        
                    }
                });
            });

            $(document).on('mouseenter','#customerlist li',function(){
                $(this).addClass('hover');
            });
            $(document).on('mouseleave','#customerlist li',function(){
                $(this).removeClass('hover');
            });

            $(document).on('keydown',function(e){
                const input_element = $(':focus');
                const list_vis = $("#customerlist").css('display');
                if(input_element.prop('name')=="name" && list_vis=='block'){
                    if(event.key=='ArrowUp' || event.key=='ArrowDown'){
                        var li_index = $("#customerlist li.hover").index();
                        var li_count =  $("#customerlist li").length-1;
                        $("#customerlist li").removeClass('hover');
                        if(event.key=='ArrowUp'){
                            if(li_index!=-1){
                                $("#customerlist li").eq(li_index-1).addClass('hover');
                            }

                        }
                        if(event.key=='ArrowDown'){
                            if(li_index!=li_count){
                                $("#customerlist li").eq(li_index+1).addClass('hover');
                            }
                        }
                    }else{
                        if(event.key=='Tab'){
                            $("#customerlist li.hover>a").trigger('click');
                        }
                    }
                }
            });
        });*/

		
		 $(document).on('keydown',function(e){
                const input_element = $(':focus');
                const list_vis = $("#customerlist").css('display');
                if(input_element.prop('name')=="name" && list_vis=='block'){
                    const $list = $("#customerlist");
                    if(event.key=='ArrowUp' || event.key=='ArrowDown'){
                        var li_index = $("#customerlist li.hover").index();
                        var li_count =  $("#customerlist li").length-1;
                        $("#customerlist li").removeClass('hover');
                        if(event.key=='ArrowUp'){
                            if(li_index!=-1){
                               li_index--;
                            }
                        }
                        if(event.key=='ArrowDown'){
                            if(li_index!=li_count){
                                li_index++;
                            }else{
                                li_index = 0;
                            }
                        }
                        //const $hovered = $items.eq(li_index).addClass('hover');
                        const $hovered = $("#customerlist li").eq(li_index).addClass('hover');

                        // ✅ Auto-scroll logic
                        const ulScrollTop = $list.scrollTop();
                        const ulHeight = $list.outerHeight();
                        const liOffsetTop = $hovered.position().top + ulScrollTop;
                        const liHeight = $hovered.outerHeight();

                        if (liOffsetTop + liHeight > ulScrollTop + ulHeight) {
                            // scroll down to bring item into view
                            $list.scrollTop(liOffsetTop + liHeight - ulHeight);
                        } else if (liOffsetTop < ulScrollTop) {
                            // scroll up to bring item into view
                            $list.scrollTop(liOffsetTop);
                        }
                        /*if(event.key=='ArrowUp'){
                            if(li_index!=-1){
                                $("#customerlist li").eq(li_index-1).addClass('hover');
                            }

                        }
                        if(event.key=='ArrowDown'){
                            if(li_index!=li_count){
                                $("#customerlist li").eq(li_index+1).addClass('hover');
                            }
                        }*/
                    }else{
                        if(event.key=='Tab'){
                            $("#customerlist li.hover>a").trigger('click');
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
                    toastr.error(data.error);
                }else{
                    let custo = data.custo;
                    // $("#custo_modal").modal('hide');
                    // $("#custo_plus_form").trigger('reset');
                    // success_sweettoatr(response.success);
                    const data_stram = custo.num+"/"+custo.name+"-"+custo.contact;
                    $('[name="custo_id"]').val(custo.id);
                    $('[name="cash_old"] ,[name="gold_old"] ,[name="silver_old"] ,[name="cash_final"],[name="gold_final"],[name="silver_final"]').val(0);
                    $('#name').val(data_stram);
                    //$("#bottom_block").show();

                    /*$("#custo_id").val(custo.id);
                    $("#custo_type").val(data.type);
                    let name = custo.custo_full_name;
                    let mob = custo.custo_fone;
                    $('#custo').val(name+' -( '+mob+' )');
                    var enrol_count = $("#times").val()??1;
                    for(var i=1;i<=enrol_count;i++){
                        $('input[name="name[]"]').val(name);
                    }*/
                    success_sweettoatr("Customer succesfully Added !");
                    $("#custo_modal").modal('hide');
                    resetcustoform(true);
                }
            });

		var submit_btn = "";
        $('button[type="submit"]').click(function(){
            submit_btn = $(this).val();
        });

        $("#udhar_form").submit(function(e){
            e.preventDefault();
            var formdata = $(this).serialize();
			formdata+= '&action='+submit_btn;
            var path = $(this).attr('action');
            $("#loader").show();
            $.post(path,formdata,function(response){
				submit_btn = "";
                if(response.errors){
                    if(typeof response.errors !='string'){
                        $.each(response.errors,function(i,v){
                            $('[name="'+i+'"]').addClass('is-invalid');
                            $.each(v,function(ind,val){
                                toastr.error(val);
                            });
                        });
                    }else{
                        toastr.error(response.errors);
                    }
                }else if(response.success){
					//alert(response);
                    success_sweettoatr(response.success);
					if(response.url){
                        window.open(response.url,'_blank');
                    }
                    location.href="{{ route('udhar.index') }}";
                }
                $("#loader").hide();
                is_submit = false;
            });
        })
    </script>
@endsection
