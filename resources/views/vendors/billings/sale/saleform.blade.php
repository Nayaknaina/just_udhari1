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
            </tr>
        </thead>
        <tbody class="billing item_tbody" id="sale_form">
            @for($i=0;$i<=5;$i++)
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
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_item">
                </td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_piece">
                </td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_gross">
                </td>
                <td></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_net">
                </td>
                <td colspan="2"></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_fine">
                </td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_chrg">
                </td>
                <td colspan="2"></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_other">
                </td>
                <td ></td>
                <td>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_total">
                </td>
            </tr>
        </tfoot>
    </table>
</div>
