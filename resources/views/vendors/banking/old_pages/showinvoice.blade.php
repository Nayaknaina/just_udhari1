
<div id="preview_table">
    <table  class="table table-bordered ">
        <tbody>
            <tr id="table_head">
                <td style="width:60%">
                    <ul  style="list-style:none;padding:0;" class="top_info">
                        <li class="row">
                            <b class="col-4">NAME</b>
                            <span class="col-8">{{ $justbill->custo_name??"----" }}</span>
                        </li>
                        <li class="row">
                            <b class="col-4">MOBILE</b>
                            <span class="col-8">{{ $justbill->custo_mobile??"----" }}</span>
                        </li>
                        <li class="row">
                            <b class="col-4">ADRESS</b>
                            <span class="col-8">{{ $justbill->custo_addr??"----" }}</span>
                        </li>
                        <li class="row">
                            <b class="col-4">GSTIN</b>
                            <span class="col-8">{{ $justbill->custo_gst??"-----" }}</span>
                        </li>
                        @php 
                            $custo_state =   states($justbill->custo_state,true);
                        @endphp 
                        <li class="row">
                            <b class="col-4">STATE/CODE</b>
                            <span class="col-8">{{ $custo_state->name }} / {{ $custo_state->code }}</span>
                        </li>
                    </ul>
                </td>
                <td style="width:40%">
                    <ul  style="list-style:none;padding:0;" class="top_info">
                        <li class="row">
                            <b class="col-4">INVOICE No.</b>
                            <span class="col-8">{{ $justbill->bill_no }}</span>
                        </li>
                        <li class="row">
                            <b class="col-4">DATE</b>
                            <span class="col-8">{{ $justbill->bill_date }}</span>
                        </li>
                        <li class="row">
                            <b class="col-4">HSN CODE</b>
                            <span class="col-8">{{ $justbill->bill_hsn }}</span>
                        </li>
                        <li class="row">
                            <b class="col-4">GSTIN</b>
                            <span class="col-8">{{ $justbill->bill_gst }}</span>
                        </li>
                        @php 
                            $vnsr_state =   states($justbill->bill_state,true);
                        @endphp 
                        <li class="row">
                            <b class="col-4">STATE/CODE</b>
                            <span class="col-8">{{ $vnsr_state->name }} / {{ $vnsr_state->code }}</span>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="p-0 semi-table" style="border:none;">
                    <table  class="table table-bordered m-0">
                        <thead>
                            <tr class="bg-dark">
                                <th>SN.</th>
                                <th>ITEM</th>
                                <th>QUANTITY</th>
                                <th>RATE</th>
                                <th>AMOUNT</th>
                            </tr>
                        </thead>
                        @if($justbill->items->count() > 0)
                        
                        <tbody id="item_body">
                            @php 
                                $sn=1; 
                                $tr_ini = 5;
                                $defaul_row = $tr_ini-$justbill->items->count()
                            @endphp 
                            @foreach($justbill->items as $i_key=>$item)
                            <tr class="filled">
                                <td><b>{{ $sn++ }}</b></td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->quant }}{{ ($item->unit!='unit')?" ".$item->unit:"" }}</td>
                                <td class="text-center">{{ $item->rate }} Rs.</td>
                                <td class="text-right">{{ $item->sum }}</td>
                            </tr>
                            @endforeach
                            @if($defaul_row > 0)
                                @for($i=0;$i<=$defaul_row;$i++)
                                    <tr class="blank_row">
                                        <td></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-right"></td>
                                    </tr>
                                @endfor
                            @endif
                        @else
                            <tr><td colspan="5" class="text-center text-danger">No Items !</tr>
                        @endif
                        </tbody>
                        <tfoot >
                            <tr>
                                <td colspan="3" rowspan="5" class="p-0">
                                <h5 class="text-left col-12"> Payment Info </h5>
                                <hr class="m-1">
                                    @php 
                                        $pyment_info = json_decode($justbill->psyment,true);
                                    @endphp
                                    <ul  style="list-style:none;">
                                        <li class="row">
                                            <b class="col-4">BANK NAME</b>
                                            <span class="col-8">{{ $pyment_info['name']??"----" }}</span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">CHECK No.</b>
                                            <span class="col-8">{{ $pyment_info['check']??"----" }}</span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">CASH</b>
                                            <span class="col-8">{{ $pyment_info['cash']??"----" }}</span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BALANCE</b>
                                            <span class="col-8">{{ $justbill->remains??"----" }}</span>
                                        </li>
                                    </ul>
                                    <h6 class="text-left col-12"><b> Invoice Amount In Word</b> </h6>
                                    <p class="col-12" id="invoice_amount_word">sdfdsf sfsdfds sfsd fsfsdf </p>
                                </td>
                                <td class="text-right">SUB TOTAL</td>
                                <td class="text-right">
                                {{ $justbill->sub }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">DISCOUNT %</td>
                                <td class="text-right">{{ $justbill->discount }}</td>
                            </tr>
                            <tr>
                                @php 
                                    $gst = json_decode($justbill->gst,true);
                                @endphp
                                <td class="text-right">GST ({{ $gst['val'] }}) %</td>
                                <td class="text-right">{{ $gst['amnt'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">ROUNDOFF</td>
                                <td class="text-right">{{ $justbill->roundoff }}</td>
                            </tr>
                            <tr>
                                <td class="text-right">TOTAL</td>
                                <td class="text-right">{{ $justbill->total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="p-0 m-0 semi-table" style="border:none;" id="vendor_buss_info_td">
                    <table  class="table m-0" >
                        <tbody>
                            <tr id="vendor_buss_info">
                                <td>
                                    <h5>Banking Detail</h5>
                                    <hr class="m-1">
                                    <ul  style="list-style:none;" class="vndr_info">
                                        <li class="row">
                                            <b class="col-4">BANK NAME</b>
                                            <span class="col-8"></span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BANK BRANCH</b>
                                            <span class="col-8"></span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BANK A/C No.</b>
                                            <span class="col-8"></span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BANK IFSC Code</b>
                                            <span class="col-8"></span>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <h5>Declaration</h5>
                                    <hr class="m-1">
                                    <ul  style="list-style:none;">
                                        <li class="row">
                                            <b class="col-4">BANK NAME</b>
                                            <span class="col-8"></span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BANK BRANCH</b>
                                            <span class="col-8"></span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BANK A/C No.</b>
                                            <span class="col-8"></span>
                                        </li>
                                        <li class="row">
                                            <b class="col-4">BANK IFSC Code</b>
                                            <span class="col-8"></span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr id="signature">
                                <td> 
                                    <h6>Purchaser Signature</h6>
                                    <br>
                                </td>
                                <td> 
                                    <h6>Authorised Signature</h6>
                                    <br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
   