<style>
    #item_list{
        padding:0px;
        list-style:none;
        border:1px solid gray;
        position: absolute;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        background-color: white;
        min-height:auto;
        height:200px;
        overflow-y:scroll;
    }
    #item_list > li{
        padding:2px;
    }
    #item_list > li:hover,#item_list >li.hover{
        background:#efefef;
    }
    td select {
        appearance: none;         /* Standard */
        -webkit-appearance: none; /* Safari/Chrome */
        -moz-appearance: none;    /* Firefox */
        background: none;         /* Optional: Remove background */
        border: 1px solid #ccc;   /* Optional: Add your own border */
        padding-right: 10px;      /* Adjust space for text */
    }
	
    td .form-control{
        padding:2px 5px!important;
    }
</style>
<style>
    table#sale_table > tbody > tr >td input,
    table#sale_table > tbody > tr >td select,
    table#sale_table > tfoot > tr >td input{
        text-align: center;
    }
</style>
<style>
	tr.deleted > td:not(:last-child){
        opacity:0.5;
        cursor:not-allowed;
        pointer-events: none;
    }
    .item{
        width:200px;
    }
    .gross,.less,.net,.fine{
        width:50px;
    }
    .chrg,.other{
        width:70px;
    }
    .rate{
        width:80px;
    }
    .ttl{
        width:100px;
    }
    .discount_chrg,.labour_chrg{
        width:70px;
    }
	.item_del_sel:after{
        color:gray;
        cursor:pointer;
        content:"\2610";
        font-size:120%;
    }
    .item_del_sel.checked:after{
        content:"\2611";
        color:red;
    }
</style>


<div class="table-responsive">
    <table class="table table-bordered table_theme" id="sale_table">
        <thead >
            <tr>
                <!-- <th>OP</th> -->
                <th>ITEM</th>
                <th>CARET</th>
                <th>PIECE</th>
                <th>GROSS</th>
                <th>LESS</th>
                <th>NET</th>
                <th>TUNCH</th>
                <th>WASTAGE</th>
                <th>FINE</th>
                <th>ST. CH.</th>
                <th>RATE</th>
                <th>LABOUR</th>
                <th>OTHER</th>
                <th>DISC.</th>
                <th>TOTAL</th>
                <th class="text-center text-danger">&cross;</th>
            </tr>
        </thead>
         @php 
            $count =  $sub_total = 0;
            $piece = $gross = $net = $fine = $chrg = $other = $total = 0; 
        @endphp
        <tbody class="billing item_tbody" id="sale_form">
            @foreach($bill_items as $i=>$bill_item)
                @php 
					$count++;
					$edit_allow = ($bill_item->tag || $bill_item->entry_mode=='tag')?'readonly':'';
                    $blocked = ($edit_allow=='')?"":' blocked';				
				@endphp 
                <tr class="item_tr" id="item_tr_{{ $i }}">
                    {{--<td class="text-center">
                       <select class="form-control no-border op item_input px-1 text-center" name="op[]" id="op_{{ $i }}" style="font-weight:bold;" title="S for Sell,P for purchase or pay !">
                            <option value="p">P</option>
                            <option value="s" selected>S</option>
                        </select>
                    </td>--}}
                    <td>
                        <input type="hidden" class="item_id" name="item_id[]" id="item_id_{{ $i }}" value="{{ $bill_item->id }}">
                        <input type="hidden" class="id" name="id[]" id="id_{{ $i }}" value="{{ $bill_item->stock_id }}">
                        <input type="hidden" class="stock" id="stock_{{ $i }}" value="{{ @$bill_item->stock->stock_type }}">
                        <input type="text" class="form-control no-border item item_input{{ $blocked }}" name="item[]" id="item_{{ $i }}" value="{{ $bill_item->item_name }}" readonly >
                    </td>
                    <td>
                        @php 
                            $caret = $bill_item->caret;
                            if($caret){
                                $caret = "k{$bill_item->caret}";
                                $$caret = 'selected';
                            }
                        @endphp
                        <select class="form-control no-border caret item_input px-1 text-center blocked" name="caret[]" id="caret_{{ $i }}" readonly>
                            <option value="">_?</option>
                            <option value="18" {{ @$k18 }}>18K</option>
                            <option value="20" {{ @$k20 }}>20K</option>
                            <option value="22" {{ @$k22 }}>22K</option>
                            <option value="24" {{ @$k24 }}>24K</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border piece item_input{{ $blocked }}" name="piece[]" id="piece_{{ $i }}" value="{{ $bill_item->piece }}" {{ $edit_allow }}>
						@php $piece+= $bill_item->piece??0; @endphp
                    </td>
                    <td>
                        <input type="text" class="form-control no-border gross item_input{{ $blocked }}" name="gross[]" id="gross_{{ $i }}" value="{{ $bill_item->gross }}" {{ $edit_allow }}>
						 @php $gross+= $bill_item->gross??0; @endphp
                    </td>
                    <td>
                        <input type="text" class="form-control no-border less item_input{{ $blocked }}" name="less[]" id="less_{{ $i }}" value="{{ $bill_item->less }}" {{ $edit_allow }}>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border net item_input{{ $blocked }}" name="net[]" id="net_{{ $i }}" readonly value="{{ $bill_item->net }}" {{ $edit_allow }}>
						 @php $net+= $bill_item->net??0; @endphp
                    </td>
                    <td>
                        <input type="text" class="form-control no-border tunch item_input blocked" name="tunch[]" id="tunch_{{ $i }}" value="{{ $bill_item->tunch }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border wstg item_input{{ $blocked }}" name="wstg[]" id="wstg_{{ $i }}" value="{{ $bill_item->wastage }}" {{ $edit_allow }}>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border fine item_input{{ $blocked }}" name="fine[]" id="fine_{{ $i }}" readonly value="{{ $bill_item->fine }}" {{ $edit_allow }}>
						@php $fine+= $bill_item->fine??0; @endphp
                    </td>
                    <td>
                        <input type="text" class="form-control no-border chrg item_input" name="chrg[]" id="chrg_{{ $i }}" value="{{ $bill_item->element }}">
						@php $chrg+= $bill_item->element??0; @endphp
                    </td>
                    <td>
                        <input type="text" class="form-control no-border rate item_input" name="rate[]" id="rate_{{ $i }}" value="{{ $bill_item->rate }}">
                    </td>
                    <td>
                        <div class="input-group labour_chrg">
                            <input type="text" class="form-control no-border lbr item_input" name="lbr[]" id="lbr_{{ $i }}" value="{{ $bill_item->labour }}">
                            <div class="input-append">
                                @php 
                                    $lbr_unit = $bill_item->labour_unit;
                                    if($lbr_unit){
                                        $lbr_unit = "lu{$lbr_unit}";
                                        $$lbr_unit = 'selected';
                                    }
                                @endphp
                                <select class="form-control no-border lbrunit item_input px-1 text-center" name="lbrunit[]" id="lbrunit_{{ $i }}">
                                    <option value="">_?</option>
                                    <option value="w" {{ @$lug }}>Gm.</option>
                                    <option value="p" {{ @$lup }}>%</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <!--<td>
                        <select class="form-control no-border lbrunit item_input px-1 text-center" name="lbrunit[]" id="lbrunit_{{ $i }}">
                            <option value="">_?</option>
                            <option value="rs">Rs.</option>
                            <option value="perc">%</option>
                        </select>
                    </td>-->
                    <td>
                        <input type="text" class="form-control no-border other item_input" name="other[]" id="other_{{ $i }}"  value="{{ $bill_item->other }}">
						 @php $other+= $bill_item->other??0; @endphp
                    </td>
                    <td>
                        <div class="input-group discount_chrg">
                            <input type="text" class="form-control no-border disc item_input" name="disc[]" id="disc_{{ $i }}"  value="{{ $bill_item->discount }}">
                            <div class="input-append">
                                @php 
                                    $disc_unit = $bill_item->discount_unit;
                                    if($disc_unit){
                                        $disc_unit = "du{$disc_unit}";
                                        $$disc_unit = 'selected';
                                    }
                                @endphp
                                <select class="form-control no-border discunit item_input px-1 text-center" name="discunit[]" id="discunit_{{ $i }}">
                                    <option value="">_?</option>
                                    <option value="r" {{ @$dur }}>Rs.</option>
                                    <option value="p"  {{ @$dup }}>%</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border ttl item_input" name="ttl[]" id="ttl_{{ $i }}" value="{{ $bill_item->total }}">
                    </td>
                    @php 
                            $sub_total += +$bill_item->total; 
                    @endphp 
                    <td class="text-center text-danger">
                        <label class="item_del_sel">
                            <input type="checkbox" name="delete_item[]" value="{{ $bill_item->id }}" style="display:none;">
                        </label>
                    </td>
                </tr>
            @endforeach
            @for($i=$count;$i<=6;$i++)
                <tr class="item_tr" id="item_tr_{{ $i }}">
                    {{--<td class="text-center">
                       <select class="form-control no-border op item_input px-1 text-center" name="op[]" id="op_{{ $i }}" style="font-weight:bold;" title="S for Sell,P for purchase or pay !">
                            <option value="p">P</option>
                            <option value="s" selected>S</option>
                        </select>
                    </td>--}}
                    <td>
                        <input type="hidden" class="id" name="id[]" id="id_{{ $i }}" value="">
                        <input type="hidden" class="stock" id="stock_{{ $i }}" value="">
                        <input type="text" class="form-control no-border item item_input" name="item[]" id="item_{{ $i }}">
                    </td>
                    <td>
                        <select class="form-control no-border caret item_input px-1 text-center" name="caret[]" id="caret_{{ $i }}">
                            <option value="">_?</option>
                            <option value="18">18K</option>
                            <option value="20">20K</option>
                            <option value="22">22K</option>
                            <option value="24">24K</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border piece item_input" name="piece[]" id="piece_{{ $i }}">
                    </td>
                    <td>
                        <input type="text" class="form-control no-border gross item_input" name="gross[]" id="gross_{{ $i }}">
                    </td>
                    <td>
                        <input type="text" class="form-control no-border less item_input" name="less[]" id="less_{{ $i }}">
                    </td>
                    <td>
                        <input type="text" class="form-control no-border net item_input" name="net[]" id="net_{{ $i }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border tunch item_input" name="tunch[]" id="tunch_{{ $i }}">
                    </td>
                    <td>
                        <input type="text" class="form-control no-border wstg item_input" name="wstg[]" id="wstg_{{ $i }}">
                    </td>
                    <td>
                        <input type="text" class="form-control no-border fine item_input" name="fine[]" id="fine_{{ $i }}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border chrg item_input" name="chrg[]" id="chrg_{{ $i }}">
                    </td>
                    <td>
                        <input type="text" class="form-control no-border rate item_input" name="rate[]" id="rate_{{ $i }}">
                    </td>
                    <td>
                        <div class="input-group labour_chrg">
                            <input type="text" class="form-control no-border lbr item_input" name="lbr[]" id="lbr_{{ $i }}">
                            <div class="input-append">
                                <select class="form-control no-border lbrunit item_input px-1 text-center" name="lbrunit[]" id="lbrunit_{{ $i }}">
                                    <option value="">_?</option>
                                    <option value="w">Gm.</option>
                                    <option value="p">%</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <!--<td>
                        <select class="form-control no-border lbrunit item_input px-1 text-center" name="lbrunit[]" id="lbrunit_{{ $i }}">
                            <option value="">_?</option>
                            <option value="rs">Rs.</option>
                            <option value="perc">%</option>
                        </select>
                    </td>-->
                    <td>
                        <input type="text" class="form-control no-border other item_input" name="other[]" id="other_{{ $i }}">
                    </td>
                    <td>
                        <div class="input-group discount_chrg">
                            <input type="text" class="form-control no-border disc item_input" name="disc[]" id="disc_{{ $i }}">
                            <div class="input-append">
                                <select class="form-control no-border discunit item_input px-1 text-center" name="discunit[]" id="discunit_{{ $i }}">
                                    <option value="">_?</option>
                                    <option value="r">Rs.</option>
                                    <option value="p" selected>%</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control no-border ttl item_input" name="ttl[]" id="ttl_{{ $i }}">
                    </td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$count }}" id="list_item">
                </td>
				<td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$piece }}" id="list_piece">
                </td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$gross }}" id="list_gross">
                </td>
				<td></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$net }}" id="list_net">
                </td>
                <td colspan="2"></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$fine }}" id="list_fine">
                </td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$chrg }}" id="list_chrg">
                </td>
                <td colspan="2"></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ @$other }}" id="list_other">
                </td>
                <td></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $bill_data->sub }}" id="list_total">
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
