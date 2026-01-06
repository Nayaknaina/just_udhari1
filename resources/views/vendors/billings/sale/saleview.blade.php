<style>
    #item_list{
        padding:0px;
        list-style:none;
        border:1px solid gray;
        position: absolute;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        background-color: white;
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
@php 
	$ttl_count = $ttl_gross = $ttl_net = $ttl_fine = $ttl_chrg = $ttl_other = $ttl_total = 0;
@endphp
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
            @foreach($bill->billitems as $ii=>$item)
                <tr class="item_tr" id="item_tr_{{ $item->id }}">
                    {{--<td class="text-center">
                       s/p
                    </td>--}}
                    <td  class="text-center">{{ @$item->item_name }}</td>
                    <td class="text-center">{{ @$item->caret??'-' }}</td>
                    <td class="text-center" >{{ @$item->piece??'-' }}</td>
                    <td  class="text-center">{{ ($item->gross)?$item->gross."gm":'-' }}</td>
                    <td  class="text-center">{{ ($item->less)?$item->less."gm":'-' }}</td>
                    <td  class="text-center">{{ ($item->net)?$item->net."gm":'-' }}</td>
                    <td  class="text-center">{{ ($item->tunch)?$item->tunch:'-' }}</td>
                    <td  class="text-center">{{ ($item->wastage)?$item->wastage:'-' }}</td>
                    <td  class="text-center">{{ ($item->fine)?$item->fine."gm":'-' }}</td>
                    <td  class="text-center">{{ ($item->element)?$item->element."rs":'-' }}</td>
                    <td  class="text-center">{{ ($item->rate)?$item->rate."rs":'-' }}</td>
                    @php $unit_arr = ['w'=>'Gm','r'=>'Rs','p'=>'%']; @endphp 
                    <td  class="text-center">
                        @if($item->labour)
                           {{ $item->labour.@$unit_arr["{$item->labour_unit}"] }}
                        @else 
                        -
                        @endif
                    </td>
                    <td  class="text-center">{{ ($item->other)?$item->other:'-' }}</td>
                    <td  class="text-center">
                        @if($item->discount )
                           {{ $item->discount .@$unit_arr["{$item->discount_unit}"] }}
                        @else 
                        -
                        @endif
                    </td>
                    <td  class="text-center">{{ ($item->total)?$item->total."Rs":'-' }}</td>
                </tr>
				@php 
					$ttl_count+=  $ii+1;
					$ttl_gross+=  @$item->gross??0;
					$ttl_net+=  @$item->net??0;
					$ttl_fine+=  @$item->fine??0;
					$ttl_chrg+=  @$item->element??0;
					$ttl_other+=  @$item->other??0;
					$ttl_total+=  @$item->total??0;
				@endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_count }}" id="list_piece">
                </th>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_gross }}" id="list_gross">
                </th>
                <td></td>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_net }}" id="list_net">
                </th>
                <td colspan="2"></td>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_fine }}" id="list_fine">
                </th>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_chrg }}" id="list_chrg">
                </th>
                <td colspan="2"></td>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_other }}" id="list_other">
                </th>
                <td></td>
                <th>
                    <input type="text" class="form-control no-border no-hover item_input" readonly value="{{ $ttl_total }}" id="list_total">
                </th>
            </tr>
        </tfoot>
    </table>
</div>
