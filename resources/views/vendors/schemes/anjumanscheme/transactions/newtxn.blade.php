@extends('layouts.vendors.app')

@section('content')


@section('css')

    @include('layouts.theme.css.datatable')
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
        background-color: #ffcaad;;
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
        color:lightgray;
        cursor:pointer;
    }
    label.action:hover{
        color:inherit;
    }
    .action:before{
        content:'\2714';
    }
    .success-checked.checked{
        border:1px solid green;
        color:green;
    }
    .danger-checked.checked{
        border:1px solid maroon;
        color:maroon;
    }
    #txn_form{
        box-shadow:1px 2px 3px gray inset;
        border:1px solid lightgray;
        border-radius:0 0  20px 20px;
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
</style>

@endsection
@php 
$anchor = ['<a href="'.route('anjuman.all.txns').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$data = new_component_array('newbreadcrumb',"Anjuman Scheme Payment") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body py-1">
                            <div class="row">
                                <div class="col-md-4 p-0" id="pay_block">
                                    <ul class="row p-0 m-0" style="list-style:none;" id="scheme">
                                        <li  class="col-md-8 text-center p-0 py-1">
                                            <h6 id="scheme_name" class="m-0">Scheme Name</h6>
                                        </li>
                                        <li  class="col-md-4 text-center p-0 py-1">
                                            <h5 id="scheme_type"  class="m-0">Scheme Type</h5>
                                        </li>
                                    </ul>
                                    <ul class="row p-0 m-0 text-center" style="list-style:none;" id="emi_info">
                                        <li class="col-6 text-warning alert alert-outline-warning p-0 m-0">
                                            <b>DUE</b>
                                            <hr class="m-0" style="border-top:1px dotted">
                                            <span id="due_quantity">EMI DUE</span>
                                        </li>
                                        <li class="col-6 text-info alert alert-outline-info p-0 m-0">
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
                                        <li class="col-6 text-success alert alert-outline-success p-0 m-0 scheme_info">
                                            <b>DEPOSIT</b>
                                            <hr class="m-0">
                                            <span id="deposit_quantity">EMI DEPOSITE</span>
                                        </li>
                                        <li class="col-6 text-danger alert alert-outline-danger p-0 m-0 scheme_info">
                                            <b>WITHDRAW</b>
                                            <hr class="m-0">
                                            <span id="withdraw_quantity">QUANTITY WITHDRAW</span>
                                        </li>
                                        <li class="col-12 p-0 text-right" id="scheme_info_show">
                                            <button type="button" class="btn btn-sm bg-white text-primary" style="border:1px dashed orange" onclick="$('.scheme_info').toggle('slow');$(this).find('i').toggleClass('fa-angle-down fa-angle-up');">
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                        </li>
                                    </ul>
                                    <ul class="p-0 title_value m-0 px-2" style="list-style:none;border-bottom:1px dashed gray;" id="custo_info">
                                        <li><b>NAME</b><span  id="name" >Customer Name</span><span></li>
                                        <li ><b>CONTACT</b><span  id="contact" >Customer Contact</span></li>
                                        <li><b>ENROLLED</b><span  id="enroll"  >Enrolled Name</span></li>
                                        <li ><b>DATE</b><span id="date"  >Enrolled Date</span></li>
                                    </ul>
                                    <div class="col-12 p-0 p-2" >
                                        <form action="{{ route('anjuman.new.txns.save',$id) }}" id="txn_form"  class="py-2 px-3"> 
                                            @csrf
                                            <input type="hidden" name="scheme_id" id="scheme_id" value="">
                                            <input type="hidden" name="enroll_id" id="enroll_id" value="">
                                            <div class="form-group input-group mb-2">
                                                <input type="date" name="date" id="date" class="form-control text-center" value="{{ date('Y-m-d',strtotime('now')) }}">
                                            </div>
                                            <div class="form-group input-group  mb-2">
                                                <label for="deposite" class="input-button form-control text-center success-checked action checked h-auto px-1">
                                                    <input type="radio" name="action" id="deposite" value="deposite" checked> Deposite
                                                </label>
                                                <label for="withdraw" class="input-button form-control text-center danger-checked action h-auto px-1">
                                                    <input type="radio" name="action" id="withdraw" value="withdraw"> Withdraw
                                                </label>
                                            </div>
                                            <div class="form-group input-group text-center  mb-2">
                                                <input type="text" name="quantity" class="form-control text-center" id="quantity" value="">
                                                <span class="input-group-text">
                                                    <b id="unit_gold" style="display:none;" class="unit">Grm</b>
                                                    <b id="unit_cash" style="display:none;" class="unit">Rs.</b>
                                                    <b  class="unit">Unit</b>
                                                </span>
                                            </div>
                                            <hr style="border-top:1px solid gray" class="m-2">
                                            <div class="form-group text-center mb-0">
                                                <button type="submit" name="do" value="txn" class="btn btn-success">Done</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-8 table-responsive">
                                    <table id="csTable"class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
                                        <thead>
                                            <tr >
                                                <th>SN</th>
                                                <th>TXN</th>
                                                <th>TYPE</th>
                                                <th>DATE</th>
                                                <!--<th>ACTION</th>-->
                                            </tr>
                                        </thead>
                                        <tbody id="dataarea">
                                            <tr class="bg-light">
                                                <td colspan="5" class="text-center text-danger">
                                                    <span><i class="fa fa-spinner fa-spin"></i>Loading Content...</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
    <script>
        $(document).ready(function(){
            $.get("{{ route('anjuman.new.txns',$id) }}",'',function(response){
                if(response.form_data){
                    let form_data = response.form_data;
                    $("#scheme_id").val(form_data.scheme_id);
                    $("#enroll_id").val(form_data.enroll_id);
                    $("#scheme_name").html(form_data.scheme);
                    $("#scheme_type").html(form_data.type.toUpperCase());
                    $("#scheme_valid").html(form_data.validity+" Month");
                    $("#emi_quantity").html((form_data.emi)?form_data.emi+" "+form_data.unit:"Not Fixed !");
                    $("#payable_quantity").html((form_data.emi)?form_data.payable+" "+form_data.unit:"Not Fixed !");
                    $("#deposit_quantity").html(form_data.deposit+" "+form_data.unit);
                    $("#withdraw_quantity").html(form_data.withdraw+" "+form_data.unit);
                    $("#due_quantity").html((form_data.emi)?form_data.due+" "+form_data.unit:"Not Fixed !");
                    $("#scheme_name").html(form_data.scheme);
                    $("#scheme_name").html(form_data.scheme);
                    $("#scheme_name").html(form_data.scheme);
                    $("#scheme_name").html(form_data.scheme);
                    $("#name").html(form_data.name);
                    $("#contact").html(form_data.contact);
                    $("#enroll").html(form_data.enroll);
                    $("#date").html(form_data.date);
                    $("#quantity").val((form_data.emi)?form_data.emi:'');
                    $('.unit').hide();
                    $("#unit_"+form_data.type).show();
                    let tr = '<tr class="bg-light"><td colspan="5" class="text-center text-danger">No Txns !</td></tr>';
                    if(response.txns.length>0){
                        tr = '';
                        $.each(response.txns,function(txni,txnv){
                            var show_class = (txnv.txn_status=='1')?'success':'danger';
                            tr+=`<tr class=' text-`+show_class+`'>
                                    <td class="text-center">`+(txni+1)+`</td>
                                    <td class="text-center">`+(txnv.txn_quant+" "+form_data.unit)+`</td>
                                    <td class="text-center">`+((txnv.txn_status=='1')?'Deposit':'Withdraw')+`</td>
                                    <td class="text-center">`+(txnv.txn_date)+`</td>
                                    <!--<td class="text-center">
                                        <a href="#/`+txnv.id+`" class="btn btn-outline-info editButton"><i class="fa fa-edit"></i> Edit</a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="#/`+txnv.id+`">
                                        <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </td>-->
                                </tr>`
                        });
                    }
                    $("#dataarea").html(tr);
                }else{
                    let div = `<div class="alert alert-danger text-center">
                                    No Record !
                                </div>`;
                    $("#pay_block").empty().append(div);
                    $("#txn_form").trigger('reset');
                }
            });

            $('label.action').on('click',function(){
                $('.action').removeClass('checked');
                $(this).addClass('checked');
            });

            $("#txn_form").submit(function(e){
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
                        location.reload();
                    }else{
                        toastr.error(response.msg)
                    }
                });
            })
        });
    </script>
    @include('layouts.theme.js.datatable')
    @include('layouts.vendors.js.passwork-popup')
@endsection