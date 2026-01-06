@if(isset($export) && $export=='pdf')
<html>
    <head>
        <title>Group Stock Export</title>
    </head>
    <body>
<style>
    table{
        width:100%;
        border-collapse: collapse;
    }
    td>hr{
        border:none;
        border-bottom:1px solid lightgray;
        margin:0;
    }
    th{
        padding:5px;
    }
    td,th{
        text-align:center;
        border:1px solid gray;
    }
    td{
        padding: 1px;
    }
    td > ul{
        padding:0;
        margin:0;
    }
    td > ul > li{
        text-align:initial;
        margin:0 2px;
        padding:0 2px; 
        flex-wrap:wrap;
    }
    td > ul > li >span{
        float:right;
    }
    tr#no-border > td,tr#no-border >td{
        border:none;
    }
    td,th {
        font-size:60%;
    }
	
	thead > tr > td{
		font-size:75%;
	}
</style>

<table>
    <thead id="data_thead">
        <tr id="no-border">
            <td colspan="4" style="text-align:left;">
                @if($page_e)
                    <b>PAGE {{ @$page_e." | " }} </b>
                @endif
                STOCK  | {{ date('d-M-Y',strtotime('now')) }} 
            </td>
            <td colspan="5" style="text-align:right;font-weight:bold;margin:0;">
                @if(!empty($data))
					<p>
					@foreach($data as $dk=>$d)
                    <span>@if($dk>0) {{ '|' }} @endif{{ ucfirst($d) }}</span>
                    @endforeach
					</p>
                @endif
            </td>
        </tr>
        <tr>
            <th>SN</th>
            <th>ITEM</th>
            <th>CARET</th>
            <th>PIECE</th>
            <th>GROSS</th>
            <th>NET</th>
            <th>FINE</th>
            <!--<th>Rate</th>-->
            <th>TOTAL</th>
        </tr>
    </thead>
@endif
@if($stocks->count()>0)

<tbody id="data_area" class="data_area">
        @php 
            $count = $total_items = $total_gross = $total_net = $total_fine = $stchrg = $other = $rate_sum =  $total = 0;
        @endphp
        @foreach($stocks as $sk=>$stock)
			@php 
				$column_check = ($stock_title=='artificial')?'total_avail_num':'total_avail_net';
				$ini_column = str_replace("_avail","",$column_check);
				$avail_diff = $stock->$ini_column - $stock->$column_check;
				$tr_class = ($avail_diff==$stock->$ini_column)?'not_available':'';
			@endphp
        <tr id="tr_{{ $stock->item_id }}"  class="{{ $tr_class }}">
            <td  class="text-center"> {{ (isset($export) && $export=='pdf')?$sk+1:$stocks->firstitem() + $sk }}</td>
            <td >
                @if(!isset($export))
					<a href="{{ route('stock.new.inventory.item',$stock->item_id) }}">
				@endif
				{{ $stock->name }}
				@if(!isset($export))
				</a>
				@endif
            </td>
            <td  class="text-center">{{ (@$stock->caret)?@$stock->caret.'K':'-' }}</td>
            <td  class="text-center">
				@php 
                    $num = ($stock_status=='avail')?$stock->total_avail_num:($stock->total_num-$stock->total_avail_num)
                @endphp
			    {{ (@$num)?$num:'-' }}
			</td>
            <td  class="text-center">
				@php 
                    $gross = ($stock_status=='avail')?$stock->total_avail_gross:($stock->total_gross-$stock->total_avail_gross)
                @endphp
			    {{ (@$gross)?@number_format($gross,3,'.', '')."Gm":'-' }}
			</td>
			
            <td  class="text-center">
				@php 
                    $net = ($stock_status=='avail')?$stock->total_avail_net:($stock->total_net-$stock->total_avail_net)
                @endphp
			    {{ (@$net)?@number_format($net,3,'.', '')."Gm":'-' }}
			</td>
            <td class="text-center">
				@php 
					$fine = false;
					$multiplier = ($stock->tunch)?$stock->tunch/100:(($stock->caret)?$stock->caret/24:1);
					$avail_fine = $net * $multiplier;
					$fine = $avail_fine??false;
                @endphp
			    {{ (@$fine)?@number_format($fine,3,'.', '')."Gm":'-' }}
			</td>
            {{--<td class="text-center">
			{{ (@$stock->total_rate)?@number_format($stock->total_rate,2,'.', '').'Rs':'-' }}
			</td>--}}
            <td class="text-center">{{ (@$stock->total_sum)?@number_format($stock->total_sum,2,'.', '').'Rs':'-' }}</td>
			@if(!isset($export))
            <td class="text-center">
                <a class="btn btn-sm btn-outline-dark p-0 px-1" href="{{ route('stock.new.inventory.item',$stock->item_id) }}"><i class="fa fa-eye"></i> </a>
            </td>
			@endif
            {{--<td>
                <input type="checkbox" name="item[]" value="{{ $stock->id }}" id="item_{{ $sk }}" onchange="$(this).toggleClass('d-none');$('#dropdown_{{ $sk }}').toggleClass('d-none');" class="item_checked d-none" {!! @$zpl_print !!} >
                <div class="dropdown" id="dropdown_{{ $sk }}">
                    <button class="btn btn-outline-dark dropdown-toggle p-0 px-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#8942;
                    </button>
                    <div class="dropdown-menu border-light" aria-labelledby="dropdownMenuButton" style="min-width: inherit;box-shadow:1px 2px 3px lightgray;">
                        <a class="dropdown-item text-info stock_item_edit" href="{{ route('stock.new.edit',["stock"=>@$stock->stock_type,"item"=>@$stock->item_id]) }}"><i class="fa fa-edit"></i> Edit</a>
                        
                        <label class="w-100 px-3 py-1 " style="cursor:pointer;" for="item_{{ $sk }}">
                            &check;&nbsp;Select
                        </label>
                    </div>
                </div>
            </td>--}}
        </tr>
        @php 
            $count++;
            $total_items += $num??0; 
            $total_gross+= $gross??0;
            $total_net+= $net??0;
            $total_fine+= $fine??0;
			$total+= $stock->total_sum??0;
			$rate_sum+= $stock->total_rate??0
        @endphp 
        @endforeach
    </tbody>
    <tfoot class="text-center data_area">
        <tr>
            <td colspan="2"><label class="form-control m-0">{{ @$count }} Items</label></td>
            <td ></td>
            <td>
                <div class="">
                    <label class="form-control m-0">{{ @$total_items }}</label>
                </div>
            </td>
            <td>
                <div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($total_gross,3) }}</label>
                    <span class="over-text">Gm</span>
                </div>
            </td>
            <td>
                <div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($total_net,3) }}</label>
                    <span class="over-text">Gm</span>
                </div>
            </td>
            <td>
                <div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($total_fine,3) }}</label>
                    <span class="over-text">Gm</span>
                </div>
            </td>
            {{--<td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($rate_sum,2) }}</label>
                    <span class="over-text">Rs</span>
                </div>
			</td>--}}
			<td >
				<div class="over-text-container text-center">
                    <label class="form-control m-0">{{ @$total }}</label>
                    <span class="over-text">Rs</span>
                </div>
			</td>
			@if(!isset($export))
            <td>
                
            </td>
			@else 
			@endif
        </tr>
    </tfoot>
@endif
@if(isset($export) && $export=='pdf')
</table>
</body>
</html>
@endif

@if($stocks->count()==0)
	<!--<tbody class="data_area"><tr><td colspan="10" class="text-center text-danger"> No Stock !</td></tr></tbody>-->

@endif