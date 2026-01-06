@extends('layouts.vendors.app')

@section('content')

<style>
    .scheme_summery,.scheme_action{
        padding: 0;
        list-style:none;
        margin:0;
    }
    .scheme_summery > li > span{
        float:right;
    }
    .scheme_summery > li > b{
        font-size:80%;
    }
	#tab_buttons{
        list-style: none;
        display:inline-flex;
        padding:0;
        align-content: center;
        margin:0;
    }
    #tab_buttons > li{
        padding:5px 15px;
        position: relative;
        align-content: center;
    }
    #tab_buttons > li > a{
        color:#f95600;
    }
    #tab_buttons > li.active{
        background:white;
        font-weight:bold;
        padding:5px;
        /* border-top:1px solid #f95600; */
        border-left: 1px solid lightgray;
        border-top: 1px solid lightgray;
    }
    
    #tab_buttons > li.active>a{
        color:#f95600;
        font-size:150%;
    }
    #tab_buttons > li.active:after{
        content:"";
        position: absolute;
        height:100%;
        right:-5px;
        top:0;
        padding:0 5px;
        transform:skew(10deg);
        /* border-right:1px solid #f95600; */
        border-right:1px solid lightgray;
        background:white;
    }
</style>
@php 
$anchor = ['<a href="'.route('anjuman.scheme').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> Scheme</a>'];
$data = new_component_array('newbreadcrumb',"Anjuman Scheme Dashboard") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-12" > 
                    <div class="card card-default" style="border-top:1px dashed #ff2300;"> 
                        <div class="card-header p-0 px-2">
                           <ul id="tab_buttons">
                           </ul>
                       </div>
                       <div class="card-body" id="scheme_block">
                            
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
		
		$(document).on('click','.tab_btn',function(e){
            e.preventDefault();
            $('.tab_block').hide();
            $($(this).attr('href')).show();
            $('.tab_btn_li').removeClass('active');
            $(this).parent('li').addClass('active');
        });

        $.get("{{ route('anjuman.dashboard') }}","",function(response){
            let div = '';
            let btn ='';
            let count = 0;
            if(response){
                $.each(response,function(i,v){
                    if(v.length > 0 ){
                        let act = (count==0)?' active':'';
                        let show = (count==0)?'':'style="display:none;"';
                        btn += `<li class="tab_btn_li`+act+`"><a href="#`+i+`" class="tab_btn">`+i.toUpperCase()+`</a></li>`;
                        div += `<div class="row tab_block" id="`+i+`" `+show+`>`;
                        $.each(v,function(vi,vv){
                            div+=`<div class="col-md-3">
                                    <div class="card card-default border-secondary" style="box-shadow:1px 2px 3px gray;border-radius:0 0 20px 20px;">
                                        <div class="card-header p-2">
                                            <h2 class="card-title text-primary">`+(vv.title)+`</h2>
                                        </div>
                                        <div class="card-body p-2">`;
                                        let unit = (vv.type=='gold')?'Gm.':'Rs.';
                                        let  emi = (vv.fix_emi)?vv.emi_quant:false;
                                        let emi_lbl = (emi)?emi+' '+unit:'No Fix !';
                                        let  validity = vv.validity;
                                        let customers = vv.enrolls_count;
                                        let payable = (emi)?emi*validity*customers:false;
                                        let deposite = vv.deposit_sum??0;
                                        let due = (payable)?(+payable - +deposite):false;
                                        let withdraw = vv.withdraw_sum??0;
                                            div+=  `<ul class="scheme_summery">
                                                <li><b>DATE</b><span>`+(vv.start)+`</span></li>
                                                <li><b>EMI</b><span>`+emi_lbl+`</span></li>
                                                <li><b>VALIDITY</b><span>`+validity+` Month</span></li>
                                                <li><b>ENROLLED</b><span>`+customers+`</span></li>
                                                <li class="text-info"><b>TOTAL R.</b><span>`+((payable)?payable:'No Fix')+`</span></li>
                                                <li style="border-top:1px dashed orange;"></li>
                                                <li class="text-success"><b>DEPOSIT</b><span>`+deposite+` `+unit+`</span></li>
                                                <li class="text-danger"><b>WITHDRAW</b><span>`+withdraw+` `+unit+`</span></li>
                                                <li class="text-warning"><b>DUE</b><span>`+((due)?due+' '+unit:'No Fix')+`</span></li>
                                            </ul>
                                        </div>
                                        <div class="card-footer p-2">
                                            <ul class="row p-0 scheme_action">
                                                <li class="col-6 p-0 mb-1">
                                                    <a href="{{ route('anjuman.enroll') }}/`+(vv.id)+`" class="btn btn-sm btn-outline-info w-100">Enroll</a>
                                                </li>
                                                <li class="col-6 p-0 mb-1">
                                                    <a href="{{ route('anjuman.payment') }}/`+(vv.id)+`" class="btn btn-sm btn-outline-success w-100">Payment</a>
                                                </li>
                                                <li class="col-12 p-0 ">
                                                    <a href="{{ route('anjuman.due') }}/`+(vv.id)+`" class="btn btn-sm btn-outline-warning w-100">Month DUE</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        div+=     `</div>`;
                        count++;
                    }
                });
            }
            $("#scheme_block").html(div);
            $("#tab_buttons").html(btn);
        });
		
    });
</script>
@endsection