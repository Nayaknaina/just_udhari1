  @extends('layouts.vendors.app')

  @section('content')

  @php 
	/*$anchor = ['<a href="'.route('billing.all',['sale']).'" class="btn btn-sm btn-outline-info py-1"><i class="fa fa-list"></i> All</a>','<a href="'.route('billing',['sale']).'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
	$data = new_component_array('newbreadcrumb',"Sell Bill") */
  @endphp 

  @php
  $anchor = ['<a href="'.route('billing','sale').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> New</a>','<a href="'.route('bill.settings').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-gear"></i> Setting</a>'];
	$data = new_component_array('newbreadcrumb',"Sell Bill") 
  @endphp
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <style>
        .form-control.no-border.is-invalid{
            border:1px solid red!important;
        }
    </style>

     <style>
                .extra-sm .form-control{
                        height:10%;
                }

              .btn-custom {
    padding: 8px 18px;
    border-radius: 8px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-block;
    margin: 6px;
    transition: 0.25s ease-in-out;
    color: white;
    text-decoration: none;
}

/* 🔵 Preview Button */
.btn-print {
    background: #1e1e1e;
}
.btn-print:hover {
    background: #000;
    transform: translateY(-2px);
}

/* 🟢 Download PDF */
.btn-download {
    background: #2ecc71;
}
.btn-download:hover {
    background: #27ae60;
    transform: translateY(-2px);
}

/* 🟣 View PDF */
.btn-view {
    background: #9b59b6;
}
.btn-view:hover {
    background: #8e44ad;
    transform: translateY(-2px);
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
            @if(!empty($bill))
            <form id = "submitForm" method="POST" action="{{ route('billing',['sale'])}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row mb-2 " >
                <div class="col-md-9 col-12">
                    <div class="row">
                        <div class="form-group col-md-4 mb-2">
                            <div class="input-group" id="bill_type_select">
                                <label  class="input-group-text py-0">Bill Type </label>
                                @php 
                                    $bill_type_arr = ['e'=>'Rough Estimate','g'=>'GST'];

                                @endphp 
                                <label class="form-control h-auto">{{ $bill_type_arr["{$bill->bill_prop}"] }}</label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <div class="input-group" id="bill_type_select">
                                <label class="input-group-text py-0">Party Type</label>
                                @php 
                                    $party_type_arr = ['c'=>'Customer','s'=>'Supplier','w'=>'Whole-Sellere'];

                                @endphp 
                                <label class="form-control h-auto">{{ $party_type_arr["{$bill->party_type}"] }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8 mb-2">
                            <div class="input-group" id="bill_type_select">
                                <label class="input-group-text py-0">Party Name</label>
                                <label class="form-control h-auto">{{ $bill->party_name ." (  {$bill->party_mobile} )" }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12 ">
                    <div class="row px-3">
                        <div class="form-group text-center  col-4 p-0">
                            <label class="input-group-text">Bill No.</label>
                            <hr  class="m-0">
                                <label class="form-control h-auto px-0">{{ $bill->bill_number }}</label>
                        </div>
                        <div class="form-group text-center col-4 p-0">
                            <label class="input-group-text">Bill DATE</label>
                            <hr  class="m-0">
                                <label class="form-control h-auto px-0">{{ date('d-m-Y',strtotime($bill->bill_date)) }}</label>
                        </div>
                        <div class="form-group text-center col-4 p-0">
                            <label class="input-group-text">DUE DATE</label>
                            <hr  class="m-0">
                                <label class="form-control h-auto px-0">{{ date('d-m-Y',strtotime($bill->due_date)) }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .extra-sm .form-control{
                        height:10%;
                }
            </style>
            <div class="row">
                <div class="col-12 ">
                   @include("vendors.billings.sale.saleview")
                </div>
            </div>
            <div class="row">
                @if($bill->payments->count() > 0)
                    <div class="col-md-7 text-center">
                        <div class="table-responsive">
                            <table class="table table_theme table-bordered">
                                <thead>
                                    <tr><th colspan="3" class="text-centrer">PAYMENT DETAIL</th></tr>
                                    <tr>
                                        <th width="25%">
                                            MEDIUM
                                        </th>
                                        <th width="30%">
                                            AMOUNT
                                        </th>
                                        <th width="40%">
                                            REMARK
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="billing" id="payment_data">
                                    @foreach($bill->payments as $bp=>$pay)
                                        <tr>
                                            <td>
                                                {{ ucfirst(str_replace("-","",$pay->pay_method)) }}
                                            </td>
                                            <td>
                                                {{ $pay->pay_value  }}Rs
                                            </td>
                                            <td>
                                                {{ $pay->pay_remark??'--' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                @else 
                <div class="col-md-9"></div>
                @endif
                <div class="col-md-3">
                    <table class="table table-bordered table_theme mb-0">
                        <thead style="display:none;">
                            <tr>
                                <th>TOTAL</th>
                                <td>NET</td>
                                <td>FINE</td>
                                <td>AMOUNT</td>
                            </tr>
                        </thead>
                        <tbody class="billing" >
                            <tr class="no-hover">
                                <th >SUB</th>
                                <td class="input-group ">
                                    <label class="form-control no-border no-hover text-right m-0 p-0">
                                        {{ $bill->sub }}
                                    </label>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            @php $unit_arr = ['w'=>'Gm','r'=>'Rs','p'=>'%']; @endphp 
                            <tr class="no-hover">
                                <th >DISCOUNT</th>
                                <td class="input-group text-right">
                                    @if($bill->discount)
                                    <label class="form-control no-border no-hover text-right m-0 p-0">
                                        {{ $bill->discount }}
                                    </label>
                                        <span>{{ $unit_arr["{$bill->discount_unit}"] }}</span>
                                    @else 
                                    <label class="form-control no-border no-hover text-right m-0 p-0">
                                        -
                                    </label>
                                    @endif
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>GST</th>
                                <td class="input-group">
                                    <label class="form-control no-border no-hover text-right m-0 p-0">
                                       {{ $bill->gst }}
                                    </label>
                                    <span>&nbsp;%</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>ROUND</th>
                                <td class="input-group">
                                    <label class="form-control no-border no-hover text-right m-0 p-0">
                                       {{ $bill->round  }}
                                    </label>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>TOTAL</th>
                                <td class="input-group">
                                    <label class="form-control no-border no-hover text-right m-0 p-0 text-info">
                                       {{ $bill->final  }}
                                    </label>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>PAYMENT</th>
                                <td class="input-group">
                                    <label class="form-control no-border no-hover text-right m-0 p-0 {{ ($bill->payment>0)?'text-success':'text-danger' }}">
                                       {{ $bill->payment  }}
                                    </label>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th >BALANCE</th>
                                <td class="input-group ">
                                    <label class="form-control no-border no-hover text-right m-0 p-0 {{ ($bill->balance>0)?'text-danger':'text-success' }}">
										{{-- $bill->balance --}}
                                       {{ $bill->final - $bill->payment  }}
                                    </label>
                                    <span>Rs</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{--<div class="row" >
                <div class="col-12 text-center pt-3">
                    <button type = "submit" class="btn btn-danger"> Submit </button>
                    <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                </div>
            </div>--}}
			
			<div class="row" >
                <div class="col-12 text-center pt-1">
                    <hr class="p-0 m-1" style="border-top:1px solid lightgray;">
                    <a href="{{ route('billing.view',['sale',$bill->bill_number,'print']) }}"type = "button" class="btn btn-dark" target="_blank"><i class="fa fa-print"></i> Preview </a>
                </div>
            </div>

             <div class="row">
                <div class="col-12 text-center pt-1">
                    <hr class="p-0 m-1" style="border-top:1px solid lightgray;">

                    <a href="{{ route('billing.view',['sale',$bill->bill_number,'print']) }}" 
                    type="button" 
                    class="btn-custom btn-print" 
                    target="_blank">
                        <i class="fa fa-print"></i> Preview
                    </a>

                    <a href="{{ route('bill.download', $bill->id) }}" class="btn-custom btn-download">
                        📄 Download PDF
                    </a>

                    <a href="{{ route('bill.pdfview', $bill->id) }}" class="btn-custom btn-view">
                        👁️ View PDF
                    </a>
                    <!-- Add Settings Button -->
                    <a href="{{ route('bill.settings') }}" class="btn-custom" style="background: #e67e22;">
                        ⚙️ Bill Settings
                    </a>

                </div>
            </div>

            </form>
            @else 
                <p class="text-center text-danger"><span>Bill Not Found !</span></p>
            @endif
            </div>
            </div>
            </div>
            <!-- <ul id="item_list"style="display:none;">
                
            </ul> -->
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    
  </section>
    @include('vendors.commonpages.newcustomerwithcategory')
  @endsection

  @section('javascript')

  <script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
    $('select.my_select').myselect96();
    $(document).ready(function(){
        $('section.content').append(`<ul id="item_list"style="display:none;"></ul>`);
    });
    
    $(document).on('input change','.form-control',function(){
        $(this).removeClass('is-invalid');
    });

    $(document).on('keydown', function(e) {
        //if((e.shiftKey && e.key === 'ArrowDown') || e.key === 'Tab'){
        if(e.key === 'Tab' && $(document).find('ul#item_list').css('display')=='block'){
        $(document).find('ul#item_list > li.hover > a').trigger('click');
        return false;
        }
        if(e.key === 'ArrowDown' || e.key === 'ArrowUp'){
        var input = $(document).find(':focus');
            if(input.hasClass('item_input')){
                if($(document).find('ul#item_list').css('display')=='block'){
                    const ul = $(document).find('ul#item_list');
                    var li_index = ul.find('li.hover').index();
                    var new_li = false;
                    if(e.key=="ArrowDown" && li_index!=(ul.find('li').length - 1)){
                        new_li = li_index + 1;
                    }else if(e.key=="ArrowUp" && li_index != 0){
                        new_li = li_index - 1;
                    }
                    if(new_li !== false){
                        ul.find('li').removeClass('hover');
                        ul.find('li').eq(new_li).addClass('hover');
                    }
                    return false;
                }
                var ahead = true;
                const tbody_id = input.closest('tbody').attr('id');
                const tr_ind = $('tbody#'+tbody_id+' >tr').index(input.closest('tr'));
                /*if(e.key === 'Tab'){
                    const td_index = $('tbody#'+tbody_id+' >tr.item_tr').eq(tr_ind).find('td').index(input.closest('td'));
                    const td_count = $('tbody#'+tbody_id+' >tr.item_tr').eq(tr_ind).find('td').length - 1;
                    
                    ahead = (td_index == td_count)?true:false;
                }*/
                if(ahead){
                    var ttl_tr = $('tbody#'+tbody_id+' >tr').length;
                    if(e.key === 'ArrowUp'){
                        $(`tbody#${tbody_id}>#item_tr_${tr_ind-1}`).find(`td>input.item`).focus();
                    }else{
                        if(ttl_tr == tr_ind+1){
                            var tr_new = $('tbody#'+tbody_id+' >tr').eq(0).clone();
                            tr_new.attr('id','item_tr_'+ttl_tr);
                            $.each(tr_new.find('input,select,a'),function(ini,inv){
                                if($(this).prop('tagName')=='SELECT'){
                                    $(this).val($(this).find('option:selected').val());
                                }else{
                                    $(this).val("");
                                }
                                $(this).attr('id',$(this).attr('id').replace(/\d+$/, tr_ind+1));
                            });
                            $("tbody#"+tbody_id).append(tr_new);
                            if(e.key === 'ArrowDown'){
                                $(`tbody#${tbody_id}>#item_tr_${ttl_tr}`).find(`td>input.item`).focus();
                            }
                        }else{
                            $('tbody#'+tbody_id+' >tr').eq(tr_ind+1).find('td>input.item').focus();
                        }
                    }
                }
            }
    }
    });

    $(document).on('focus','.item',function(){
        if($("#item_list").css('display')=='block'){
            $("#item_list").hide();
            $("#item_list").empty();
        }
    });

    //var input_index = false;
    var count = 0;
    $(document).on('input','.item',function(){
        let input = $(this);
        const tbody_id = input.closest('tbody').attr('id');
        const input_index = $('tbody#'+tbody_id+' >tr.item_tr').index(input.closest('tr.item_tr'))??false;
        
        if(input.val()!=""){
            $("#item_list").empty().append(`<li><span class="fa fa-spinner fa-spin"></span>Loading Content..</li>`);showitem($(this));
            // const ids = $(document).find("input[name='id[]'].id").map(function() {
            //                 let val = $(this).val();
            //                 return val !== "" ? val : null; 
            //             }).get().filter(Boolean);
            //alert(ids);
            $.get("{{ route('billing.find.stock') }}","keyword="+$(this).val(),function(response){
                let lis = '';
                //alert(ids);
                
                if(response.stocks){
                    if(response.stocks.length > 0 ){                        
                        $.each(response.stocks,function(si,stock){
                            if($(document).find(`input[name='id[]'][value='${stock.id}']`).length==0){
                                const data_arr = {
                                    "caret":stock.caret,
                                    "count":stock.count,
                                    "gross":stock.gross,
                                    "less":stock.less,
                                    "net":stock.net,
                                    "tunch":stock.tunch,
                                    "wastage":stock.wastage,
                                    "fine":stock.fine,
                                    "ele_charge":stock.element_charge,
                                    "ele_is":stock.have_element,
                                    "rate":stock.rate,
                                    "labour_value":stock.labour,
                                    "labour_unit":stock.labour_unit,
                                    "other":stock.charge,
                                    "disc":stock.discount,
                                    "disc_unit":stock.discount_unit,
                                };
                                const data = JSON.stringify(data_arr).replace(/"/g, '&quot;');
                                const class_name = (si==0)?'hover':'';
                                lis += `<li class="${class_name}"><a href="javascript:void(null);" data-title="${stock.name}" data-target="${stock.id}" data-desc="${data}"  data-parent="item_tr_${input_index}"class="get_item"><b>${si+1}: </b>${stock.name} !</li>`;
                            }
                        });
                    }else{
                        lis = `<li><a href="javascript:void(null);" >No Item !</li>`;
                    }
                }
                count++;
                if(lis!=''){
                    $("#item_list").html(lis);
                    $("#item_list").attr('data-parent','item_tr_'+input_index);
                    showitem(input);
                }else{
                    $("#item_list").data('data-parent','');
                    $("#item_list").html(`<li><a href="javascript:void(null);" >No Item !</li>`);
                }
            });
        }else{
            $('.item_tbody > tr').eq(input_index).removeClass().addClass('item_tr');
            $("input.type").eq(input_index).val("");
            $('.item_tbody > tr').eq(input_index).find('input,select:not(.op)').val("");
            $("#item_list").hide().empty();
            itemsummery();
            // $(document).find('.ttl').eq(input_index).trigger('input');
            // $(document).find('.rate').eq(input_index).trigger('input');
        }
    });

    

    function showitem(item){
        const input = item;
        const offset = input.offset();
        const inputHeight = input.outerHeight();
        const list = $('#item_list');

        // Temporarily show to get its height
        list.css({ visibility: 'hidden', display: 'block' });
        const listHeight = list.outerHeight();
        list.css({ visibility: '', display: 'none' });

        const windowBottom = $(window).scrollTop() + $(window).height();
        const spaceBelow = windowBottom - (offset.top + inputHeight);

        // Positioning logic
        const topPos = (spaceBelow > listHeight)
        ? offset.top + inputHeight  // show below
        : offset.top - listHeight;  // show above

        list.css({
            position: 'absolute',
            top: topPos,
            left: offset.left,
            display: 'block',
            zIndex: 999
        });
    }

    $(document).on('click','.get_item',function(){
        var title = $(this).data('title');
        var id = $(this).data('target');
        var data = $(this).data('desc');
        var input_index = $(this).data('parent').replace('item_tr_','');
        $("#item_list").empty().hide();
        $("input.item").eq(input_index).val(title);
        $("input.id").eq(input_index).val(id);
        $("input.name").eq(input_index).focus();
        
        if(data.caret){
            $('select.caret').eq(input_index).val(data.caret);
        }
        if(data.count){
            $('input.piece').eq(input_index).val(data.count);
        }
        if(data.gross){
            $('input.gross').eq(input_index).val(data.gross);
        }
        if(data.less){
            $('input.less').eq(input_index).val(data.less);
        }
        if(data.net){
            $('input.net').eq(input_index).val(data.net);
        }
        if(data.tunch){
            $('input.tunch').eq(input_index).val(data.tunch);
        }
        if(data.wastage){
            $('input.wstg').eq(input_index).val(data.wastage);
        }
        if(data.fine){
            $('input.fine').eq(input_index).val(data.fine);
        }
        if(data.ele_charge){
            $('input.chrg').eq(input_index).val(data.ele_charge);
        }
        if(data.labour_value){
            $('input.lbr').eq(input_index).val(data.labour_value);
            $('select.lbrunit').eq(input_index).val(data.labour_unit);
        }
        if(data.other){
            $('input.other').eq(input_index).val(data.other);
        }
        if(data.disc){
            $('input.disc').eq(input_index).val(data.disc);
        }
        if(data.disc_unit){
            $('select.discunit').eq(input_index).val(data.disc_unit);
        }
        itemsummery();
    });

    $(document).on('change','.caret',function(){
        const caret = $(this).val()??false;
        const index = $('tbody#sale_form > tr').index($(this).closest('tr'));
        if(caret){
            const unit_val = 100/24;
            const tunch = Math.round((unit_val * caret));
            $(document).find('.tunch').eq(index).val(tunch);
            $(document).find('.wstg').eq(index).trigger('input');
        }
    });

    $(document).on('input','.less',function(){
         const index = $('tbody#sale_form > tr').index($(this).closest('tr'));
         const gross = $(document).find('.gross').eq(index).val()??false;
         if(gross){
            $(document).find('.net').eq(index).val(gross - $(this).val());
            const wstg = $(document).find('.wstg').eq(index).val()??0;
            const tunch = $(document).find('.tunch').eq(index).val()??0;
            const less = $(document).find('.less').eq(index).val()??0;
            if(+less > +gross){
                $(this).val("");
                toastr.error("Less Can't be Greater to Gross !");
            }else{
               var  fine  = net = gross - less;
                $(document).find('.net').eq(index).val(net);
                if(wstg){
                    const minus_perc = 100 - (+tunch + +wstg);
                    const net = $(document).find('.net').eq(index).val();
                    fine = net - (net * minus_perc/100);
                }
                $(document).find('.fine').eq(index).val(fine);
            }
         }else{
            toastr.error("Gross Weight required !");
            $(this).val("");
         }
         itemsummery();
    });

    $(document).on('input','.wstg',function(){
        const index = $('tbody#sale_form > tr').index($(this).closest('tr'));
        const net = $(document).find('.net').eq(index).val()??false;
        if(net){
            const tunch = $(document).find('.tunch').eq(index).val()??0;
            const wstg = $(document).find('.wstg').eq(index).val()??false;
            var fine = net;
            if(wstg){
                const minus_perc = 100 - (+tunch + +wstg);
                fine = net - (net * minus_perc/100);
            }
           $(document).find('.fine').eq(index).val(fine);
        }else{
            toastr.error("Net Weight required !")
            $(this).val();
        }
        itemsummery();
    })

    $(document).on('input change','.rate,.lbr,.lbrunit,.chrg,.other,.disc,.discunit',function(){
        itemsummery();
    });

    function itemsummery(){
        var count = piece = gross = net = fine = chrg = other = total = 0;
        $(document).find('.item').each(function(ei,ev){
            if($(this).val()!=""){
                count++;
                const curr_piece = +$(document).find('input.piece').eq(ei).val()??0;
                const curr_gross = +$(document).find('input.gross').eq(ei).val()??0;
                const curr_less = +$(document).find('input.less').eq(ei).val()??0;
                const curr_net = +$(document).find('input.net').eq(ei).val()??0;
                const curr_tunch = +$(document).find('input.tunch').eq(ei).val()??0;
                const curr_wstg = +$(document).find('input.wstg').eq(ei).val()??0;
                const curr_fine = +$(document).find('input.fine').eq(ei).val()??0;
                const curr_chrg = +$(document).find('input.chrg').eq(ei).val()??0;
                const curr_rate = +$(document).find('input.rate').eq(ei).val()??0;
                const curr_lbr = +$(document).find('input.lbr').eq(ei).val()??0;
                const curr_lbr_unit = $(document).find('input.lbrunit').eq(ei).val()??0;
                const curr_other = +$(document).find('input.other').eq(ei).val()??0;
                const curr_disc = +$(document).find('input.disc').eq(ei).val()??0;
                const curr_disc_unit = $(document).find('input.discunit').eq(ei).val()??0;
                piece+= curr_piece;
                gross+= curr_gross;
                net+= curr_net;
                fine+= curr_fine;
                chrg+= curr_chrg;
                other+= curr_other;
                total+= total;
                
                var nw_ttl = (curr_rate * curr_fine);
                
                if(curr_disc_unit == 'p'){
                    nw_ttl += ((nw_ttl * disc)/100);
                }else if(curr_disc_unit == 'p'){
                    nw_ttl += disc;
                }
                if(curr_lbr_unit=='p'){
                    nw_ttl += (nw_ttl * lbr)/100 ;
                }else if(curr_lbr_unit=='w'){
                    nw_ttl += (fine * lbr);
                }else if(curr_lbr_unit == 'r'){
                    nw_ttl += lbr;
                }
                nw_ttl+= (curr_chrg + curr_other);
                $(document).find('input.ttl').eq(ei).val(nw_ttl)??0;
                total += nw_ttl;
            }
        });
        
        $("#list_item").val(count);
        $("#list_piece").val(piece);
        $("#list_gross").val((gross.toFixed(3)).toString().replace(/\.0+$/, ''));
        $("#list_net").val((net.toFixed(3)).toString().replace(/\.0+$/, ''));
        $("#list_fine").val((fine.toFixed(3)).toString().replace(/\.0+$/, ''));
        $("#list_chrg").val((chrg.toFixed(2)).toString().replace(/\.0+$/, ''));
        $("#list_other").val((other.toFixed(2)).toString().replace(/\.0+$/, ''));
        $("#list_total").val((total.toFixed(2)).toString().replace(/\.0+$/, ''));
        calculatebilltotal();
    }


    $(document).find('.rate,.ttl, #discount, #gst').on('input',function(){
        calculatebilltotal();
    });
    $("#discount_unit").change(function(){
        calculatebilltotal();
    });

    function calculatebilltotal(){
        var bill_sub = 0;
        $('.item_tbody tr').find('.ttl').each(function(i,v){
            var itm_ttl = $(v).val()??false;
            if(itm_ttl){
                bill_sub += +$(v).val()??0; 
            }
        });
        $('#sub').val(bill_sub.toFixed(2).toString().replace().replace(/\.0+$/, ''));
        $('#list_total').val(bill_sub.toFixed(2).toString().replace().replace(/\.0+$/, ''));
        var disc = $("#discount").val()??0;
        bill_sub -= (disc!=0)?(($("#discount_unit").val()=='r')?disc:(bill_sub * disc)/100):0;
        
        var gst = $("#gst").val()??0;
        var gst_val = (bill_sub * gst)/100;
        var total_dflt = (+bill_sub + +gst_val);
        var total =  Math.round(total_dflt);
        var round = total - +total_dflt;
        $("#total").val(total.toFixed(2).toString().replace().replace(/\.0+$/, ''));
        $("#round").val(round.toFixed(2).toString().replace().replace(/\.0+$/, ''));
    }

    $("#custo_type").on('change',function(){
        const custo_type = $(this).val();
        if(custo_type!=""){
            $("#mobile").val('').prop('readonly',true);
            $.get("{{ route('global.customers.search') }}","type="+custo_type,function(response){
                var option = '';
                if(response){
                    option = '<option value="" data-source="" data-target="#mobile">Find Name/Mobile</option>';
                    $.each(response,function(custoi,custo){
                        option+= `<option value="${custo.id}" data-source="${custo.mobile}" data-target="#mobile">${custo.name}</option>`;
                    });
                }else{
                    option = '<option value="">No Party Found !</option>';
                }
                $("#customer").empty().append(option);
                $("#customer").redraw();
            });
        }
    })

    $("a.option_btn").click(function(e){
        e.preventDefault();
        $("#option_pages").empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i>Loading...</span></p>').show();
        $.get($(this).attr('href'),"",function(response){
            $("#option_pages").empty().append(response);
        });
    });

    function digitonly(event,num){
        let inputValue = event.target.value;

            // Allow only digits using regex
            inputValue = inputValue.replace(/[^0-9]/g, '');  // Remove anything that's not a digit

            // Ensure that the input has exactly 10 digits
            if (inputValue.length > num) {
                inputValue = inputValue.slice(0, 10);  // Trim to 10 digits
            }

            // Update the input field with the valid input
            event.target.value = inputValue;
    }

    /*function triggerselect(){
        $(document).find('select.select2').each(function(){
            $(this).select2();
        });
    }*/

    $("#submitForm").submit(function(e){
        e.preventDefault();
        $(document).find('.form-control').removeClass('is-invalid');
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.status){
                success_sweettoatr(response.msg);
                location.href = response.next;
            }else if(response.error){

            }else if(response.errors){
                $.each(response.errors,function(i,v){
                    $(`[name="${i}"]`).addClass('is-invalid')
                    toastr.error(v);
                });
            }
        });
    });
    </script>
    @endsection

