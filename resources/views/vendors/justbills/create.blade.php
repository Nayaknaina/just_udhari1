  @extends('layouts.vendors.app')

  @section('stylesheet')

    <link rel="stylesheet" href="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/ui/trumbowyg.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/colors/ui/trumbowyg.colors.css') }}">

  @endsection
  @section('content')

  @php

  //$data = component_array('breadcrumb' , 'New Just Bill',[['title' => 'New Just Bill']] ) ;
  $vendr_state =  app('userd')->shopbranch->state;
  @endphp

  {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('bills.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Just Bills"=>route('bills.index')];
$data = new_component_array('newbreadcrumb',"New Just Bill",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
  <style>
        ul#custo_list,ul#item_list {
            position: absolute;
            list-style: none; /* Remove default bullet points */
            padding: 0;
            z-index: 1;
            box-shadow: 1px 2px 3px gray;
            justify-content: space-around; /* Space out the items inside the UL */
        }
        table>tbody>tr>td,table>tfoot>tr>td{
            padding:0!important;
        }
        .item_info{
            border:none;
            border-bottom:1px dashed gray;
        }
        .item_info:focus{
            border-bottom:1px solid gray;
            box-shadow: -1px -2px 5px 3px gray;
            background:#f9f9f9
        }
        .item_info:disabled,.item_info.readonly{
            border:none;
            /* border-top:none;
            border-bottom:none; */
        }
        tbody>tr.is-invalid > td,tbody>tr.is-invalid > td > input{
            /* border-top:1px solid red;
            border-bottom:1px solid red; */
            background:#fdf3f5;
        }
        #payment_block:before{
            content:"PAYMENT DETAIL";
            font-weight: bold;
            font-size: 20px;
            position: absolute;
            top: -25px;
            background: white;
            border: 1px solid gray;
        }
    </style>
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <!--<div class="card-header">
                        <h3 class="card-title"><x-back-button />  Create Sell Bill</h3>
                        </div>-->
                        <div class="card-body bg-white">
                            <form id = "submitForm" method="POST" action="{{ route('bills.store')}}" class = "myForm" enctype="" autocomplete="off">
                                @csrf
                                @method('post')
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                                    <!---Bill Info Form Element---->
                                    @php 
                                        $states = states();
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <label for="bill_num" class="input-group-text mb-0">Bill No.</label>
                                            </div>
                                            <input type="text" name="bill_num" id="bill_num" class="form-control" placeholder="Bill Number" value="{{ justbillsequence() }}" >
                                        </div>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <label for="bill_num" class="input-group-text mb-0">Bill Date</label>
                                            </div>
                                            <input type="date" name="bill_date" id="bill_date" class="form-control" placeholder="Bill Date" value="{{ date('Y-m-d', strtotime('now')) }}" required>
                                        </div>    
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <label for="vndr_gst" class="input-group-text mb-0">GSTIN</label>
                                            </div>
                                            <input type="text" name="vndr_gst" id="vndr_gst" class="form-control" placeholder="GST Number" value="{{ app('userd')->shopbranch->gst_num }}" required readonly>
                                        </div> 
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <label for="hsn" class="input-group-text mb-0">HSN Code</label>
                                            </div>
                                            <select name="hsn" id="hsn" class="form-control" required onchange="sethsngst();">
                                                {{ justbillhsn(true); }}
                                            </select>
                                        </div>               
                                        <div class="form-group mb-1">
                                            <label for="bill_num">State / Code</label>
                                            <select name="vndr_state" id="vndr_state" class="form-control" required>
                                                @if($states->count()>0)
                                                    @foreach($states as $si=>$state)
                                                        <option value="{{ $state->code }}" {{ ($state->code==$vendr_state)?'selected':''; }}>{{ $state->name." / ".$state->code }}</option>
                                                    @endforeach
                                                @else 
                                                    <option value="">No State/Code</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-1">
                                                <label for="custo_name">Customer Name</label>
                                                <input type="text" name="custo_name" id="custo_name" class="form-control" placeholder="Customer Name" oninput="existingcustomer($(this))"  >
                                                <ul class="col-12 custo_list" id="custo_list" style="display:none;"></ul>
                                            </div>
                                            <div class="form-group col-md-6 mb-1">
                                                <label for="custo_mobile">Customer mobile</label>
                                                <input type="text" name="custo_mobile" id="custo_mobile" class="form-control" placeholder="Customer Mobile"  oninput="digitonly(event,10);existingcustomer($(this));"  onchange="">
                                                <ul class="col-12 custo_list" id="custo_list" style="display:none;"></ul>
                                            </div>
                                            <div class="form-group col-12 mb-1">
                                                <label for="custo_addr" >Address</label>
                                                <textarea id="custo_addr" name="custo_addr" class="form-control" placeholder="Enter Full Address" rows="2"></textarea>
                                            </div>
                                            <div class="form-group col-md-6 mb-1">
                                                <label for="custo_gst">GSTIN</label>
                                                <input type="text" name="custo_gst" id="custo_gst" class="form-control" placeholder="Customer GST Number"   >
                                            </div>
                                            <div class="form-group col-md-6 mb-1">
                                                <label for="custo_state">State / Code</label>
                                                <select name="custo_state" class="form-control" id="custo_state" required>
                                                    @if($states->count()>0)
                                                        @foreach($states as $si=>$state)
                                                            <option value="{{ $state->code }}" {{ ($state->code==$vendr_state)?'selected':''; }} >{{ $state->name." / ".$state->code }}</option>
                                                        @endforeach
                                                    @else 
                                                        <option value="">No State/Code</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                                    <div class="table-responsive">
                                        <table id="CsTable" class="table table_theme table-bordered table-stripped dataTable">
                                            <thead class="">
                                                <tr>
                                                    <th width="5%">S.N.</th>
                                                    <th width="35%">ITEM</th>
                                                    <th>QUANTITY</th>
                                                    <th>RATE</th>
                                                    <th>MAKING</th>
                                                    <th>TOTAL</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for($i=0;$i<=5;$i++)
                                                <tr id="item_{{ $i }}" class="item_row">
                                                    <td class="text-center">{{ $i+1  }}</td>
                                                    <td>
                                                        <input type="text" name="name[]" class="form-control w-100 item_info" placeholder="Item Name" >
                                                    </td>
                                                    <td >
                                                        <div class="input-group">
                                                            <input type="text" name="quant[]" class="form-control  item_info  text-right entry" placeholder="Grms or Unit)" oninput="calculatetotal()">
                                                            <div class="input-group-append">
                                                                <select name="unit[]" class="bg-white item_info" style="border:none;" required>
                                                                    <option value="grms">Grms</option>
                                                                    <option value="unit">Unit</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="rate[]" class="form-control w-100 item_info  text-right entry" placeholder="Rate/Unit"  oninput="calculatetotal()" {{ ($i==0)?'required':'' }} >
                                                    </td>
                                                    <td>
                                                        <input type="text" name="chrg[]" class="form-control w-100 item_info  text-right entry" placeholder="Charge/Making"  oninput="calculatetotal()" readonly value="0">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="item_sum[]" class="form-control w-100 item_info  text-right entry" placeholder="Total"  readonly {{ ($i==0)?'required':'' }}>
                                                    </td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-light">
                                                    <td class="text-center" style="vertical-align:middle;">
                                                        <a href="javascript:void(null);" class="btn btn-warning" style="color:maroon;" id="add_rows">
                                                            <li class="fa fa-caret-up w-100">
                                                            <span class="fa fa-plus-circle w-100"></span>
                                                            </li>
                                                        </a>
                                                    </td>
                                                    <td colspan="3" >
                                                        
                                                    </td>
                                                    <td style="text-align:center;vertical-align:middle;">
                                                        <b style="">SUM</b>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="sum" class="form-control item_info readonly  text-right" value="" id="sum" readonly required style="font-weight:bold;">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                                    <div class="col-md-2 col-12 p-1">
                                        <div class="input-group mb-3">
                                            <!-- <div class="input-group-prepend">
                                                <label class="input-group-text  px-1" id="basic-addon1">Sub</label>
                                            </div> -->
                                            <input type="text" name="sub" class="form-control readonly text-center  px-1" value="0" id="sub" required style="font-weight:bold;"  readonly Placeholder="Sub Total">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 p-1">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text  px-1" id="basic-addon1">Disc%</label>
                                            </div>
                                            <input type="text" name="disc" class="form-control readonly text-center  px-1" value="0" id="disc" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12 p-1">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text px-1" id="basic-addon1">GST<span id="hsn_gst"></span>%</label>
                                            </div>
                                            <input type="hidden" name="gst_set" value="">
                                            <input type="text" name="gst_val" class="form-control  readonly text-center  px-1" value="0" id="gst_val" required style="font-weight:bold;" onfocus="$(this).select()"  oninput="calculatetotal()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 p-1">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text  px-1" id="basic-addon1">RoundOff</label>
                                            </div>
                                            <input type="text" name="round" class="form-control readonly text-center  px-1" value="0" id="round" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12 p-1">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text  px-1" id="basic-addon1">Total</label>
                                            </div>
                                            <input type="text" name="total" class="form-control readonly text-right  px-1" value="0" id="total" required style="font-weight:bold;" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4 pt-3" style="border:1px dashed black;position:relative;" id="payment_block">
                                    <div class="col-md-5 col-12 form-group p-1">
                                        <label for="bank">Bank Name</label>
                                        <input type="text" name="payment['name']" class="form-control readonly " value="" id="bank"  style="font-weight:bold;"  Placeholder="Bank Name">
                                    </div>
                                    <div class="col-md-3 col-12 form-group  p-1">
                                        <label for="check">Check Number</label>
                                        <input type="text" name="payment['check']" class="form-control readonly " value="" id="check"  style="font-weight:bold;"  Placeholder="Check Number">
                                    </div>
                                    <div class="col-md-2 col-12 form-group p-1">
                                        <label for="cash">Cash</label>
                                        <input type="text" name="payment['cash']" class="form-control readonly " value="" id="cash"  style="font-weight:bold;"  Placeholder="Cash Amount">
                                    </div>
                                    <div class="col-md-2 col-12 form-group p-1">
                                        <label for="remain">Balance</label>
                                        <input type="text" name="remain" class="form-control readonly text-center" value="" id="remain" style="font-weight:bold;"  Placeholder="Balance Amount">
                                    </div>
                                </div>
                                <div class="row my-4 pt-3" style="border-bottom:1px solid gray;position:relative;" id="declare_block">
                                    <div class="col-12 form-group p-1">
                                        <label for="declr">Declaration/Desclaimer</label>
                                        <!-- <textarea class="form-control" name="declr" id="declr" ></textarea> -->
                                        <textarea class="form-control" name="declr" id="declr" ><ul><li>Subjected to {{ districts("",app('userd')->shopbranch->district) }} Jurisdiction only</li><li>E & O.E</li></ul></textarea>
                                    </div>
                                </div>
                                <div class="row mb-2" style="">
                                    <div class="col-12 text-center form-group">
                                        <input type="hidden" name="word" value="">
                                        <button type="submit" name="make" value="bill" class="btn btn-danger">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        
    </section>
    
  @endsection

  @section('javascript')

    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/trumbowyg.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/langs/fr.min.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/base64/trumbowyg.base64.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/colors/trumbowyg.colors.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/noembed/trumbowyg.noembed.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/pasteimage/trumbowyg.pasteimage.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/template/trumbowyg.template.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/preformatted/trumbowyg.preformatted.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/ruby/trumbowyg.ruby.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/upload/trumbowyg.upload.js') }}"></script>
        
<script>
    $('#declr').trumbowyg().on('tbwinit tbwfocus tbwblur tbwchange tbwresize tbwpaste tbwopenfullscreen  tbwclosefullscreen tbwclose', function(e){
                console.log(e.type);
    });
</script>
<script>

    $(document).ready(function() {
        $("#submitForm").trigger('reset');
        
        sethsngst();
        
        $('#submitForm').submit(function(e) {
            $(document).find("#toast-container").remove();
            $("input,select").removeClass('is-invalid');
            e.preventDefault(); // Prevent default form submission
            if(emptycheck()){
                var formAction = $(this).attr('action') ;
                var formData = $(this).serialize() ;
    
                $.post(formAction,formData,function(response){
                    if(!response.valid){
                        if(response.errors){
                            var num = 0;
                            var focus = "";
                            $.each(response.errors,function(i,v){
                                if(i.indexOf(".") > 0){
                                    var ele_arr = i.split('.');
                                    if(num == 0){
                                        focus = $('[name="'+ele_arr[0]+'[]"]').eq(ele_arr[1]);
                                    }
                                    $('[name="'+ele_arr[0]+'[]"]').eq(ele_arr[1]).addClass("is-invalid");
                                    var pre_msg = "";
                                    $.each(v,function(ci,cv){
                                        if(pre_msg!=""){
                                            if(pre_msg!=cv){
                                                toastr.error(cv);
                                            }
                                        }else{
                                            pre_msg = cv;
                                            toastr.error(cv);
                                        }
                                    });
                                }else{
                                    if(num == 0){
                                        focus = $('[name="'+i+'"]');
                                    }
                                    $('[name="'+i+'"]').addClass("is-invalid");
                                    toastr.error(v[0]);
                                }
                                num++;
                                focus.focus();
                            });                    
                        }else{
                            toastr.error(response.msg);
                        }
                    }else{
                        if(response.status){
                            window.location.href = response.next;
                            success_sweettoatr(response.msg);
                        }else{
                            toastr.error(response.msg);
                        }
                    }
                });
            }else{
                toastr.error("Recheck the Entries !");
            }
            
        });
    });
    $("#add_rows").click(function(e){
        e.preventDefault();
        const trs = $('tbody>tr');
        var tr = trs.eq(0).clone();
        var num = trs.length;
        tr.attr('id','item_'+num);
        tr.find('td').eq(0).text(num+1);
        $(tr.find('td')).each(function(i,v){
            $(this).find('input').val("");
        });
        $(tr.find('td>input')).prop('required',false);
        $('tbody').append(tr);

    });

    $(document).on('focus','[name="name[]"]',function(e){
        $(this).select();
        var ind = $(this).closest('tr').index();
        //alert("Cuur "+ind);
        if(ind > 0){
            var new_ind = ind-1;
            if($('[name="name[]"]').eq(new_ind).val()==""){
                $('[name="name[]"]').eq(new_ind).focus();
            }
        }
    });

    $(document).on('focus','.entry',function(){
        var index =  $(this).closest('tr').index();
        var item = $('[name="name[]"]').eq(index);
        if(item.val()==""){
            item.focus();
        }
    });

    $(document).on('change','.entry',function(){
        $(this).removeClass('is-invalid');
    });

    function existingcustomer(self){
        var custo = self.val();
        if(custo!=""){
            $.get("{{ route('bills.customer') }}","value="+custo,function(response){
                if(response!="" ){
                    self.next('.custo_list').empty().append(response).show();
                }
            });
        }
    }

    $(document).on('click','.custo_target',function(){
        var cust_data = $(this).data('target').split('-');
        $("#custo_name").val(cust_data[0]);
        $("#custo_mobile").val(cust_data[1]);
        $(".custo_list").hide();
    });


    $("#gst_type").change(function(e){
        if($(this).val()!=''){ 
            $('#gst_val').prop('readonly',false); 
            $('#gst_val').select();
        }else{
            $('#gst_val').prop('readonly',true);
            $('#gst_val').val(0);
        }
    });

    function sethsngst(){
        var hsn_option = $("#hsn").find('option:selected');
        const gst  = hsn_option.data('target');
        $("[name='gst_set']").val(gst)
        $("#hsn_gst").empty().text('('+gst+')');
    }

    function calculatetotal(){
        var sub =  0;
        var disc = $('[name="disc"]').val()??0;
        var gst =  $('[name="gst_set"]').val()??0;
        var err = false;
        $(document).find('.toast.toast-error').remove();
        
        $('tbody>tr').each(function(i,v){
            if($('[name="name[]"]').eq(i).val()!=""){
                var quant = $('[name="quant[]"]').eq(i).val()??0;

                var rate = $('[name="rate[]"]').eq(i).val()??0;

                var chrg = $('[name="chrg[]"]').eq(i).val()??0;

                if(quant!=0 && rate!=0 && chrg>=0){
                    var sum = (quant*rate)+ +chrg;
                    var sum_fix = sum.toFixed(2);
                    $('[name="item_sum[]"]').eq(i).val(sum_fix);
                    sub+= +sum_fix;
                }else{
                    $('[name="item_sum[]"]').eq(i).val("");
                }
            }
        });
        var new_sub = sub.toFixed(2);
        $('[name="sub"]').val(new_sub);
        $('[name="sum"]').val(new_sub);
        var  disc_total = +new_sub - +(new_sub*disc)/100;
        var gst_amnt = (disc_total*gst)/100;

        total = +disc_total + +gst_amnt;
        var total_fix = total.toFixed(2)
        var round_total = Math.round(total_fix)
        var round_off = round_total-total_fix;
        $('[name="gst_val"]').val(gst_amnt.toFixed(2));
        $('[name="round"]').val(round_off.toFixed(2));
        //$('[name="total"]').val(total.toFixed(2));
        $('[name="total"]').val(round_total);
        (err)?toastr.error("Recheck The ENTRY !"):null;
        const amount_word = numberintoword(round_total);
        $('[name="word"]').val(amount_word);
    }

    function numberintoword(num){
        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
        const thousands = ["", "Thousand", "Million", "Billion", "Trillion"];
        if (num === 0) return "Zero";
        let result = "";
        let i = 0;
        while (num > 0) {
            if (num % 1000 !== 0) {
                result = `${convertHundreds(num % 1000)} ${thousands[i]} ${result}`;
            }
            num = Math.floor(num / 1000);
            i++;
        }
        return result.trim();
    }
    function convertHundreds(num) {
        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

        if (num === 0) return "";
        if (num < 20) return ones[num];
        if (num < 100) return `${tens[Math.floor(num / 10)]} ${ones[num % 10]}`;
        return `${ones[Math.floor(num / 100)]} Hundred ${convertHundreds(num % 100)}`.trim();
    }

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

    function emptycheck(){
        var ok = true;
        $('.is-invalid').removeClass('is-invalid');
        var focus = "";
        var count = 0;
        var ele = "";
        if($('[name="custo_name"]').val()==""){
            count++;
            if(focus==""){
                focus = $('[name="custo_name"]');
            }
            $('[name="custo_name"]').addClass('is-invalid');
        }
        if($('[name="custo_mobile"]').val()==""){
            count++;
            if(focus==""){
                focus = $('[name="custo_mobile"]');
            }
            $('[name="custo_mobile"]').addClass('is-invalid');
        }
        if($('[name="bill_num"]').val()==""){
            count++;
            if(focus==""){
                focus = $('[name="bill_num"]');
            }
            $('[name="bill_num"]').addClass('is-invalid');
        }
        if($('[name="bill_date"]').val()==""){
            count++;
            if(focus==""){
                focus = $('[name="bill_date"]');
            }
            $('[name="bill_date"]').addClass('is-invalid');
        }
        if(count==0){
            $('[name="name[]"]').each(function(i,v){
                // var count = 0;
                // var ele = "";
                if($(this).val()!=""){
                    if($('[name="quant[]"]').eq(i).val()==""){
                        count++;
                        if(focus==""){
                            focus = $('[name="quant[]"]').eq(i);
                        }
                        $('[name="quant[]"]').eq(i).addClass('is-invalid');
                    }
                    if($('[name="rate[]"]').eq(i).val()==""){
                        count++;
                        if(focus==""){
                            focus = $('[name="rate[]"]').eq(i);
                        }
                        $('[name="rate[]"]').eq(i).addClass('is-invalid');
                    }
                    if($('[name="chrg[]"]').eq(i).val()==""){
                        count++;
                        if(focus==""){
                            focus = $('[name="chrg[]"]').eq(i);
                        }
                        $('[name="chrg[]"]').eq(i).addClass('is-invalid');
                    }
                }
                if(count!=0 && count<=3){
                    ok=false;
                    $('tbody>tr').eq(i).addClass('is-invalid');
                }
            });
        }else{
            ok = false;
        }
        //$('tbody>tr>td,tbody>tr>td>input').removeClass('is-invalid');
        (focus!="")?focus.focus():null;
        return ok;
    }

    </script>

  @endsection

