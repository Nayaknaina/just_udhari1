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
</style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'New Udhari',[['title' => 'Edit  Udhari Tsn']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('udhar.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="'.route('udhar.index').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> List</a>','<a href="'.route('udhar.ledger').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Ledger</a>'];
$data = new_component_array('newbreadcrumb',"New Udhar") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 

    <section class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-default" style="border-top:1px dashed #ff2300;;">
                        <div class="card-header text-center">
                            <h6 class="m-0">({{ $udhar->account->custo_num }})/{{  $udhar->account->custo_name  }} - {{  $udhar->account->custo_mobile  }} </h6>
                        </div> 
                        <form id="udhar_form" action="{{ route('udhar.update',$udhar->id) }}" role="form" autocomplete="off">
                            @csrf
                            @method('put') 
                            @php 
                                $num_dir = [["out","-"],["in",'+']];
                                $udhar_med = ["B"=>"on","S"=>'off'];

                                @${"amount_{$num_dir[$udhar->amount_udhar_status][0]}_{$udhar_med[$udhar->amount_udhar_holder]}"} = $udhar->amount_udhar;


                                $old_quant = $udhar->account->pretxn($udhar->created_at);
                                @${"gold_{$num_dir[$udhar->gold_udhar_status][0]}"} = $udhar->gold_udhar;
                                @${"silver_{$num_dir[$udhar->silver_udhar_status][0]}"} = $udhar->silver_udhar;
                            @endphp
							
                            <input type="hidden" name="udhar_id" value="{{ $udhar->id }}">
							<div class="card-body" id="form_container">
                                <div class="row  block_at_bhav_cut" >
                                    <div class="col-md-5 col-12 mb-3">
                                        <div class="card border-dark">
                                            <div class="card-header text-center p-1 udar_block_head">
                                                <h3 class="card-title col-12 text-dark">Cash 
                                                    <img src="{{ asset('main/assets/cstm_imgs/game-icons_take-my-money.svg') }}" alt="Money Icon" width="30" height="30">
                                                </h3>
                                            </div>
                                            <div class="card-body udhar_block">
                                                <div class="row">
                                                        @php 
                                                            $old_amnt = ($old_quant->amount)?(($old_quant->amount>0)?"+{$old_quant->amount}":($old_quant->amount)):$udhar->amount_curr;
                                                            
                                                        @endphp
                                                    <div class="form-group unit rs_unit col-12">
                                                        <label for="old_cash" >Old</label>
                                                        <input type="text" class="form-control cash_block" style="color:{{ ($old_amnt<0)?'red':'green' }}" name="cash_old" placeholder="" readonly value="{{ $old_amnt }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <label for="old_cash" class="text-danger col-12">Udhar/Out(-)</label>
                                                            <div class="input-group">
                                                                <span class="col-6   unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-danger  text-danger udhar_input_field" name="cash_out_off"  data-target="cash"  placeholder="Cash" value="{{ @$amount_out_off }}">
                                                                </span>
                                                                <span class="col-6  unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-danger  text-danger udhar_input_field" name="cash_out_on"  data-target="cash"  placeholder="Online" value="{{ @$amount_out_on }}">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                        <label for="old_cash" class="text-success col-12">Jama/In(+)</label>
                                                            <div class="input-group">
                                                                <span class="col-6   unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-success  text-success udhar_input_field" name="cash_in_off"   data-target="cash"   placeholder="Cash" value="{{ @$amount_in_off }}">
                                                                </span>
                                                                <span class="col-6  unit rs_unit">
                                                                    <input type="text" class="form-control cash_block border-success  text-success udhar_input_field" name="cash_in_on"   data-target="cash"   placeholder="Online" value="{{ @$amount_in_on }}">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php 
                                                        $amnt_fnl = $old_amnt+(-@$amount_out_off - @$amount_out_on + @$amount_in_off + @$amount_in_on);
                                                        $amnt_fnl = (@$amnt_fnl>0)?"+{$amnt_fnl}":($amnt_fnl??0)
                                                    @endphp
                                                    <div class="form-group unit rs_unit col-12">
                                                        <label for="old_cash">Final</label>
                                                        <input type="text" class="form-control cash_block" style="color:{{ ($amnt_fnl<0)?'red':'green' }};" name="cash_final" placeholder="" readonly value="{{ $amnt_fnl }}">
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
                                                        @php 
                                                            $old_gold = ($old_quant->gold)?(($old_quant->gold>0)?"+{$old_quant->gold}":($old_quant->gold)):$udhar->gold_curr;
                                                            
                                                        @endphp
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" >Old</label>
                                                            <input type="text" class="form-control gold_block" style="color:{{ ($old_gold<0)?'red':'green' }};" name="gold_old" placeholder="" readonly value="{{ $old_gold??0 }}">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-danger">Udhar/Out(-)</label>
                                                            <input type="text" class="form-control gold_block border-danger  text-danger udhar_input_field" name="gold_out_off"  data-target="gold"  placeholder="" value="{{ @$gold_out }}">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-success">Jama/In(+)</label>
                                                            <input type="text" class="form-control gold_block border-success  text-success udhar_input_field" name="gold_in_off" data-target="gold" placeholder="" value="{{ @$gold_in }}">
                                                        </div>
                                                        @php 
                                                            $gold_fnl = $old_gold+(-@$gold_out +  @$gold_in);
                                                            $gold_fnl = (@$gold_fnl>0)?"+{$gold_fnl}":($gold_fnl??0);
                                                        @endphp
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash">Final</label>
                                                            <input type="text" class="form-control gold_block" style="color:{{ (@$gold_fnl<0)?'red':'green' }};"name="gold_final" placeholder="" readonly value="{{ $gold_fnl }}">
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
                                                        @php 
                                                            $old_silver = ($old_quant->silver)?(($old_quant->silver>0)?"+{$old_quant->silver}":($old_quant->silver)):$udhar->silver_curr;
                                                        @endphp
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" >Old</label>
                                                            <input type="text" class="form-control silver_block" style="color:{{ ($old_silver<0)?'red':'green' }};" name="silver_old" placeholder="" readonly value="{{ $old_silver??0 }}">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-danger">Udhar/Out(-)</label>
                                                            <input type="text" class="form-control silver_block border-danger text-danger udhar_input_field" name="silver_out_off" data-target="silver" placeholder="" value="{{ @$silver_out }}">
                                                        </div>
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash" class="text-success">Jama/In(+)</label>
                                                            <input type="text" class="form-control silver_block border-success  text-success udhar_input_field" name="silver_in_off" data-target="silver" placeholder="" value="{{ @$silver_in }}">
                                                        </div>
                                                        @php 
                                                            $silver_fnl = $old_silver+(-@$silver_out +  @$silver_in);
                                                            $silver_fnl = (@$silver_fnl>0)?"+{$silver_fnl}":($silver_fnl??0);
                                                        @endphp
                                                        <div class="form-group unit gm_unit">
                                                            <label for="old_cash">Final</label>
                                                            <input type="text" class="form-control silver_block" style="color:{{ (@$silver_fnl<0)?'red':'green' }};" name="silver_final" placeholder="" readonly value="{{ $silver_fnl }}">
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center pt-2 mt-2" style="border-top:1px solid lightgray;">
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

@endsection
@section('javascript')
    <script>
        $(document).on('input','.udhar_input_field',function(e){
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
                }else{
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
