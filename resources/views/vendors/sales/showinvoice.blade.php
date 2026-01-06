@php  
    $url = Request::path();
    $last_peace = explode('/',str_replace("vendors/sells/","",$url))[0]??false;
@endphp
@if($last_peace=='preview')
<html>
    <head>
        <title>TAX Invoice Receipt-{{ date('d-M-Y H:i:s',strtotime("now")) }}</title>
    <style>
        @page {
            size: A4;
            /* margin: 20mm; */
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        th,td,tr{
            margin:0;
            padding:0;
        }
        td,th{
            border:1px solid black;
           
        }
        /* td.semi-table{
            border:none!important;
        } */
        thead > tr > th{
            color:white;
            background:gray;
            padding:0.1%;
        }
        td >h4,td>h5{
            margin:0.4%;
        }
        td >hr{
            margin:0.4%;
            border-bottom:0;
        }
        td >tr{
            margin:0.1%;
        }
        td.semi-table{
            border:none;
        }
        #table_head > td{
            border-bottom:0;
            padding:1%;
        }
        #item_body > tr >td{
            border-top:0;
            border-bottom:0;
        }
        #item_body > tr > td{
            text-align:center;
        }
        #item_body > tr.blank_row > td{
            padding:1%;
        }
        .text-right{
            text-align:right;
            padding:0 1%;
        }
        #vendor_buss_info > td{
            width:50%;
        }
        ul.info_list > li,td>p {
            width:100%;
            display:inline-block;
        }
        ul.info_list > li >b{
            width:40%;
            display:inherit;
        }
        ul.info_list > li >span{
            width:60%;
        } 
        #invoice_amount_word{
            margin:1% 3% ;
        }
        @media print {
          #rough_label{
            color:unset;
          }
          #print_holder{
            display: none;
          }
        }
    </style>
    </head>
    <body>
        <table id="vendor_table" style="background:gray;color:white;">
            <tbody>
                <tr>
                    <td width="70%" style="font-weight:bold;font-size:110%;border-right:0;border-bottom:0;padding:0.5% 2%;">
                        TAX Invoice
                    </td>

                    <td width="15%" style="text-align:right;font-weight:bold;vertical-align:top;padding-top:0.5%;border-left:0;border-right:0;border-bottom:0;">
                        MOBILE :
                    </td>
                    <td width="15%" style="border-left:0;border-bottom:0;text-align:center;padding-top:0.5%">
                       {{ app('userd')->shopbranch->mobile_no }}<br>{{ app('userd')->shopbranch->mobile_no }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-top:0;border-bottom:0;">
                        <table style="color:white;">
                            <tbody>
                                <tr>
                                    <td width="15%" style="text-align:center;padding:2%;border:0;">
                                        @php 
                                            $prof_foto = (app('userd')->shopbranch->image!="" && file_exists("{app('userd')->shopbranch->image}"))?"{app('userd')->shopbranch->image}":"assets/images/icon/browse.png";
                                        @endphp
                                        <img src="{{asset("{$prof_foto}")}}" style="height:10vw;width:auto;margin:auto;" id="profile_image_placer">
                                    </td>
                                    <td width="85%" style="text-align:center;padding:2%;border:0;">
                                        <h1>{{ app('userd')->shopbranch->branch_name }}</h1>
                                        <p style="font-size:105%;">{{ app('userd')->shopbranch->address }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    @else 
        <style>
            #rough_label{
                color:#f95600;
            }
        </style>
        <div class="table-responsive mb-2">
    @endif
    
        <table  class="table table-bordered ">
            <tbody>
                @if($sell->gst_apply==0)
                <tr>
                    <td colspan="2">
                        <h3 style="text-align:center;margin-bottom:0;" id="rough_label">Rough Estimate</h3>
                    </td>
                </tr>
                @endif
                <tr id="table_head">
                    <td style="width:60%">
                        <ul  style="list-style:none;padding:0;" class="top_info info_list">
                            <li class="row">
                                <b class="col-4">NAME</b>
                                <span class="col-8">{{ $sell->custo_name??"----" }}</span>
                            </li>
                            <li class="row">
                                <b class="col-4">MOBILE</b>
                                <span class="col-8">{{ $sell->custo_mobile??"----" }}</span>
                            </li>
                            <li class="row">
                                <b class="col-4">ADRESS</b>
                                <span class="col-8">{{ $sell->custo_addr??"----" }}</span>
                            </li>
                            <li class="row">
                                <b class="col-4">GSTIN</b>
                                <span class="col-8">{{ $sell->custo_gst??"-----" }}</span>
                            </li>
                            @php 
                                $custo_state =   (!empty($sell->custo_state))?states($sell->custo_state,true):false;
                            @endphp 
                            <li class="row">
                                <b class="col-4">STATE/CODE</b>
                                {{--<span class="col-8">{{ @$custo_state->name }} / {{ @$custo_state->code }}</span>--}}
								<span class="col-8">
                                @if(!empty($custo_state))
                                    {{ $custo_state->name }} / {{ $custo_state->code }}
                                @else 
                                --- / ---
                                @endif
                                </span>
                            </li>
                        </ul>
                    </td>
                    <td style="width:40%">
                        <ul  style="list-style:none;padding:0;" class="top_info info_list">
                            <li class="row">
                                <b class="col-4">INVOICE No.</b>
                                <span class="col-8">{{ $sell->bill_no }}</span>
                            </li>
                            <li class="row">
                                <b class="col-4">DATE</b>
                                <span class="col-8">{{ $sell->bill_date }}</span>
                            </li>
                            <li class="row">
                                <b class="col-4">HSN CODE</b>
                                <span class="col-8">{{ $sell->bill_hsn }}</span>
                            </li>
                            <li class="row">
                                <b class="col-4">GSTIN</b>
                                <span class="col-8">{{ $sell->bill_gst }}</span>
                            </li>
                            @php 
                                $vnsr_state =   states($sell->bill_state,true);
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
                                    <th style="width:5%;">SN.</th>
                                    <th style="width:50%;">Item</th>
                                    <th style="width:15%;">Quantity/Weight</th>
                                    <th style="width:15%;">Caret</th>
                                    <th style="width:15%;">Making</th>
                                    <th style="width:15%;">Rate</th>
                                    <th style="width:15%;">Amouont</th>
                                </tr>
                            </thead>
                            @if($sell->items->count() > 0)
                            
                            <tbody id="item_body">
                                @php 
                                    $sn=1; 
                                    $tr_ini = 15;
                                    $defaul_row = $tr_ini-$sell->items->count();
                                @endphp 
                                @foreach($sell->items as $i_key=>$item) 
                                <tr class="filled">
                                    <td><b>{{ $sn++ }}</b></td>
                                    <td class="text-center" style="text-align:left;padding-left:1%;">{{ $item->item_name }}</td>
                                    <td class="text-center">{{ $item->item_quantity }}{{ ($item->quantity_unit=='grm')?" ".$item->quantity_unit:"" }}</td>
                                    <td class="text-center">{{ ($item->item_caret && $item->item_caret!="")?$item->item_caret." K":"---" }}</td>
                                    <td class="text-center">
                                        @if($item->labour_perc!=0 &&  $sell->sell_to=="W")
                                            {{ $item->labour_perc }} %
                                        @elseif($item->labour_charge!=0 &&  $sell->sell_to=="R")
                                            {{ $item->labour_charge }} Rs/Grm
                                        @else 
                                            ---
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->item_rate }} Rs.</td>
                                    <td class="text-right">{{ $item->total_amount }} Rs.</td>
                                </tr>
                                @if(!empty($item->elements)) 
                                    @php $elements = json_decode($item->elements,true); @endphp
                                    @foreach($elements as $elk=>$ele)
                                    <tr>
                                        <td></td>
                                        <td class="text-center" style="text-align:left;padding-left:1%;">{{ $ele['name'] }}</td>
                                        <td class="text-center">{{ $ele['quant'] }}</td>
                                        <td class="text-center">{{ $ele['caret'] }} K</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-right">{{ $ele['cost'] }} Rs</td>
                                    </tr>
                                    @endforeach
                                @endif
                                @endforeach
                                @if($defaul_row > 0)
                                    @for($i=0;$i<=$defaul_row;$i++)
                                        <tr class="blank_row">
                                            <td></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-right"></td>
                                        </tr>
                                    @endfor
                                @endif
                            @else
                                <tr><td colspan="7" class="text-center text-danger">No Items !</tr>
                            @endif
                            </tbody>
                            <tfoot >
                                <tr>
                                    <td colspan="5" rowspan="5" class="p-0">
                                    <h4 class="text-left col-12"> Payment Info </h4>
                                    <hr class="m-1">
                                        @php 
                                            $pyment_info = json_decode($sell->custo_bank,true);
                                        @endphp
                                        <ul  style="list-style:none;" class="info_list">
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
                                                <span class="col-8">{{ $sell->remains??"----" }}</span>
                                            </li>
                                        </ul>
                                        <h4 class="text-left col-12"> Invoice Amount In Word</h4>
                                        <p class="col-12" id="invoice_amount_word">{{ $sell->in_word??"----" }}</p>
                                    </td>
                                    <td class="text-right">Sub Total</td>
                                    <td class="text-right">
                                    {{ $sell->sub_total }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">Disc %</td>
                                    <td class="text-right">{{ $sell->discount }}</td>
                                </tr>
                                @if($sell->gst_apply==1)
                                <tr>
                                    @php 
                                        $gst = json_decode($sell->gst,true);
                                    @endphp
                                    <td class="text-right">GST ({{ @$gst['val'] }}) %</td>
                                    <td class="text-right">{{ @$gst['amnt'] }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-right">RoundOff</td>
                                    <td class="text-right">{{ $sell->roundoff }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">Total</td>
                                    <td class="text-right">{{ $sell->total }}</td>
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
                                    @php 
                                        $vndr_bnk_info = json_decode($sell->banking,true);
                                    @endphp
                                    <td style="vertical-align:top;">
                                        <h4>Banking Detail</h4>
                                        <hr class="m-1">
                                        <ul  style="list-style:none;" class="vndr_info info_list">
                                            <li class="row">
                                                <b class="col-4">BANK NAME</b>
                                                <span class="col-8">{{ $vndr_bnk_info['bank']??'----' }}</span>
                                            </li>
                                            <li class="row">
                                                <b class="col-4">BRANCH</b>
                                                <span class="col-8">{{ $vndr_bnk_info['branch']??'----' }}</span>
                                            </li>
                                            <li class="row">
                                                <b class="col-4">A/C No.</b>
                                                <span class="col-8">{{ $vndr_bnk_info['account']??'----' }}</span>
                                            </li>
                                            <li class="row">
                                                <b class="col-4">IFSC Code</b>
                                                <span class="col-8">{{ $vndr_bnk_info['ifsc']??'----' }}</span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td style="vertical-align:top;">
                                        <h4>Declaration</h4>
                                        <hr class="m-1">
                                        {!! $sell->declaration !!}
                                    </td>
                                </tr>
                                <tr id="signature">
                                    <td> 
                                        <h5>Purchaser Signature</h5>
                                        <br>
                                    </td>
                                    <td> 
                                        <h5>Authorised Signature</h5>
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
@if($last_peace=='preview')
        <div style="width:100%;text-align:center;padding:5% 0;" id="print_holder">
            <button type="button" onclick="printreceipt();">&#10063;Print ?</button>
        </div>
    </body>
</html>
<script>
    function printreceipt(){
        window.print();
    }
    //window.print()
</script>
@else 
</div>
@endif
   