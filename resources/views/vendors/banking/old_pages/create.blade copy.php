  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'New Just Bill',[['title' => 'New Just Bill']] ) ;

  @endphp

  <x-page-component :data=$data />

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
    </style>
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                        <h3 class="card-title"><x-back-button />  Create Sell Bill</h3>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                    <form id = "submitForm" method="POST" action="{{ route('bills.store')}}" class = "myForm" enctype="">
                        @csrf
                        @method('post')
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            <!---Bill Info Form Element---->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="custo_name">Customer Name</label>
                                    <input type="text" name="custo_name" id="custo_name" class="form-control" placeholder="Customer Name" oninput="existingcustomer($(this))"  >
                                    <ul class="col-12 custo_list" id="custo_list" style="display:none;"></ul>
                                </div>
                                <div class="form-group">
                                <label for="custo_mobile">Customer mobile</label>
                                    <input type="text" name="custo_mobile" id="custo_mobile" class="form-control" placeholder="Customer Mobile"  oninput="digitonly(event,10);existingcustomer($(this));"  onchange="">
                                    <ul class="col-12 custo_list" id="custo_list" style="display:none;"></ul>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="" class="col-12">Address</label>
                                    <textarea id="custo_addr" name="custo_addr" class="form-control" placeholder="Enter Full Address" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bill_num">Bill No.</label>
                                    <input type="text" name="bill_num" id="bill_num" class="form-control" placeholder="Bill Number"  >
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="bill_date">Bill Date</label>
                                    <input type="date" name="bill_date" id="bill_date" class="form-control" placeholder="Bill Ddate" value="{{ date('Y-m-d', strtotime('now')) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th width="5%">S.N.</th>
                                            <th width="35%">ITEM</th>
                                            <th >RATE</th>
                                            <th  >QUANTITY</th>
                                            <th  >MAKING</th>
                                            <th  >TOTAL</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i=0;$i<=5;$i++)
                                        <tr id="item_{{ $i }}" class="item_row">
                                            <td class="text-center">{{ $i+1  }}</td>
                                            <td>
                                                <input type="text" name="name[]" class="form-control w-100 item_info" placeholder="Item Name" >
                                            </td>
                                            <td>
                                                <input type="text" name="quant[]" class="form-control w-100 item_info  text-right entry" placeholder="Quantity" oninput="calculatetotal()">
                                            </td>
                                            <td>
                                                <input type="text" name="rate[]" class="form-control w-100 item_info  text-right entry" placeholder="Rates"  oninput="calculatetotal()" {{ ($i==0)?'required':'' }} >
                                            </td>
                                            <td>
                                                <input type="text" name="chrg[]" class="form-control w-100 item_info  text-right entry" placeholder="Charge "  oninput="calculatetotal()">
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
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">Sub</label>
                                    </div>
                                    <input type="text" name="sub" class="form-control readonly text-right" value="0" id="sub" required style="font-weight:bold;"  readonly>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">Disc%</label>
                                    </div>
                                    <input type="text" name="disc" class="form-control readonly text-right" value="0" id="disc" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select name="gst_type" class="form-control item_info readonly text-center" style="font-weight:bold;padding:0;background-color: #f1f2f3 !important;border: 1px solid #bfc9d4" id="gst_type" >
                                            <option value="">GST%</option>
                                            <option value="sgst">SGST</option>
                                            <option value="cgst">CGST</option>
                                            <option value="igst">IGST</option>
                                        </select>
                                    </div>
                                    <input type="text" name="gst_val" class="form-control  readonly text-right" value="0" id="gst_val" required style="font-weight:bold;" onfocus="$(this).select()" readonly oninput="calculatetotal()">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">Total</label>
                                    </div>
                                    <input type="text" name="total" class="form-control readonly text-right" value="0" id="total" required style="font-weight:bold;" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            <div class="col-12 text-center form-group">
                                <button type="submit" name="make" value="bill" class="btn btn-danger">Create</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        
    </section>
    
  @endsection

  @section('javascript')

<script>

    $(document).ready(function() {
        $("#submitForm").trigger('reset');
        //$('#more_pay_modal').modal('show');
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


    function calculatetotal(){
        var sub =  0;
        var disc = $('[name="disc"]').val()??0;
        var gst =  $('[name="gst_val"]').val()??0;
        var err = false;
        $(document).find('.toast.toast-error').remove();
        
        $('tbody>tr').each(function(i,v){
            if($('[name="name[]"]').eq(i).val()!=""){
                var quant = $('[name="quant[]"]').eq(i).val()??0;

                var rate = $('[name="rate[]"]').eq(i).val()??0;

                var chrg = $('[name="chrg[]"]').eq(i).val()??0;

                if(quant!=0 && rate!=0 && chrg!=0){
                    var sum = (quant*rate)+ +chrg;
                    $('[name="item_sum[]"]').eq(i).val(sum);
                    sub+= +sum;
                }else{
                    $('[name="item_sum[]"]').eq(i).val("");
                }
            }
        });
        $('[name="sub"]').val(sub);
        $('[name="sum"]').val(sub);
        var  disc_total = +sub - +(sub*disc)/100;
        total = +disc_total + +(disc_total*gst)/100;
        $('[name="total"]').val(total);
        (err)?toastr.error("Recheck The ENTRY !"):null;
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

