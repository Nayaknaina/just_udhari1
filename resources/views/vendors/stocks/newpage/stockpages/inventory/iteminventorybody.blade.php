@if(isset($export) && $export=='pdf')
<html>
<head><title>Item Stock Export</title></head>
<body>
<style>
	@page{
		size: A4;  
	}
	table {
		width: 100%;            /* Fit table to page width */
		table-layout: fixed;    /* Force columns to shrink within width */
		border-collapse: collapse;
	}
	td, th {
		word-wrap: break-word;  /* Allow breaking long text */
		white-space: normal;    /* Prevent text from staying on one line */
		text-align:center;
        border:1px solid gray;
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
<table >
    <thead id="data_thead">
		<tr id="no-border">
            <td colspan="5" style="text-align:left;">
				@if($page_e)
                    <b>PAGE {{ @$page_e." | " }} </b>
                @endif
                STOCK  | {{ date('d-M-Y',strtotime('now')) }}
            </td>
            <td colspan="12" style="text-align:right;font-weight:bold;margin:0;">
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
            <th>TAG</th>
            <th>HUID</th>
            <th>CARET</th>
            <th>PIECE</th>
            <th>GROSS</th>
            <th>LESS</th>
            <th>NET</th>
            <th>TUNCH</th>
            <th>WSTG.</th>
            <th>FINE</th>
            <th>ST.CH.</th>
            <th>Lbr.</th>
            <th>Other</th>
            <th>Rate</th>
            <th>Disc.</th>
            <th>TOTAL</th>
        </tr>
    </thead>
@endif
@if($item_data->count()>0)

<tbody id="data_area" class="data_area">
        @php 
            $count = $gross = $net = $fine = $stchrg = $other = $rate = $total = $pc_count = 0;
        @endphp
        @foreach($item_data as $sk=>$stock)
			@php 
				$zpl = itemtagstream($stock);
				$zpl_print = (!empty($zpl))?"data-print='{$zpl}'":null;

				/*$stock_matterial = strtolower($stock->stock_type);
				$entry_mode = strtolower($stock->entry_mode);
				$zpl_print = "";
				if($entry_mode != 'loose'){
					$zpl = "^XA";
					
					$tag_gross = ($stock->avail_gross)?number_format($stock->avail_gross,3):0;

					if($stock_matterial!='stone'){
						$zpl.="^FO100,15^A0N,20,20^FD".$stock->name."^FS";
						$tag_net = ($stock->avail_net)?number_format($stock->avail_net,3):0;
						$tag_less = number_format(($tag_gross-$tag_net),3);
						$huid = ($stock->huid!="")?"HUID: {$stock->huid}":"";
						$tag_stchrg = ($stock->element_charge!="")?"St.Chrg: {$stock->element_charge}/-Rs.":"";
						$zpl.="^FO100,45^A0N,18,18^FDGross: ".($tag_gross??'-')."gm^FS";
						$zpl.="^FO100,65^A0N,18,18^FDLess: ".($tag_less??'-')."gm^FS";
						$zpl.="^FO100,85^A0N,18,18^FDNet: ".($tag_net??'-')."gm^FS";
						$zpl.="^FO300,45^A0N,18,18^FDTag: ".($stock->tag??'xxxxxx')."^FS";
						if($tag_stchrg!=""){
							$zpl.="^FO300,65^A0N,18,18^FD".($tag_stchrg)."^FS";
						}elseif($huid!=""){
							$zpl.="^FO300,65^A0N,18,18^FD".($huid)."^FS";
						}
						$zpl.="^FO300,85^A0N,18,18^FDKarat: ".($stock->caret??'-')."K^FS";
					}else{
						$zpl.="^FO100,25^A0N,20,20^FD".$stock->name."^FS";
						$zpl.="^FO100,55^A0N,18,18^FDWt.: ".(number_format($tag_gross,2)??'-')."CT^FS";
						$zpl.="^FO100,75^A0N,18,18^FDTag: ".($stock->tag??'xxxxxx')."^FS";
						$zpl.="^FO300,55^A0N,18,18^FDMRP:^FS";
						$zpl.="^FO300,75^A0N,18,18^FD".($stock->rate??'-')."Rs^FS";
					}
					$zpl.="^FO440,20^BQN,3,3"; 
					$zpl.="^FDLA,".($stock->tag??'xxxxxx')."^FS";   
					$zpl.="^XZ";
					$zpl_print = "data-print='{$zpl}'";
				}*/
			@endphp
		@php 
			$show_action = (in_array($avail,['sold','avail']))?true:false;
            $column_check = ($stock_cat=='artificial')?'avail_count':'avail_net';
            $ini_column = str_replace("avail_","",$column_check);
            $avail_diff = $stock->$ini_column - $stock->$column_check;
			$tr_class = ($avail_diff == $stock->$ini_column)?'item_sold':(($avail=='sold')?'item_sold':'');
        @endphp
        <tr id="tr_{{ $stock->id }}"  class="{{ $tr_class }}" @if(isset($export) && $export=='pdf' && $stock->$column_check==0) style="background-color:pink!important;" @endif >
            <td> {{ (isset($export) && $export=='pdf')?$sk+1:$item_data->firstitem() + $sk }}</td>
            <td >
				{{ $stock->name }}
				@if($stock->image)
                    <button data-img="{{ asset($stock->image) }}" class="item_image btn- btn-sm btn-outline-info p-0 px-1" >IMG.</button>
                @endif
			</td>
            <td class="text-center">{{ $stock->tag??'-' }}</td>
            <td class="text-center">{{ @$stock->huid??'-' }}</td>
            <td class="text-center">{{ (@$stock->caret)?@$stock->caret.'K':'-' }}</td>
			<td class="text-center">
				 @php 
                    $pc_num = ($avail=='avail')?$stock->avail_count:($stock->count-$stock->avail_count);
                @endphp
                {{ $pc_num??'-' }}
			</td>
			@php 
                /*$show_gross = false;
				$show_net = $stock->net - $stock->avail_net;
                if($show_net){
                    $show_gross = $stock->gross - $stock->avail_gross;
                }else{
                    $show_gross = $stock->gross;
                    $show_net = $stock->net;
                }*/
				/*if($avail=='avail'){
                    $show_gross = $stock->avail_gross;
                    $show_net = $stock->avail_net;
                }else{
                    if($stock->entry_mode=='tag' || $stock->tag!=''){
                        $show_gross = $stock->gross;
                        $show_net = $stock->net;
                    }else{
                        $show_gross = $stock->gross = $stock->avail_gross;
                        $show_net = $stock->net - $stock->avail_net;
                    }
                }*/
                /*if($avail!='avail'){
                    if($stock->entry_mode=='tag' || $stock->tag!=''){
                        $show_gross = $stock->gross;
                        $show_net = $stock->net;
                    }else{
                        $show_gross = $stock->gross = $stock->avail_gross;
                        $show_net = $stock->net - $stock->avail_net;
                    }
                }elseif($avail=='avail'){
                    $show_gross = $stock->avail_gross;
                    $show_net = $stock->avail_net;
                }*/
            @endphp
			
            {{--<td class="text-center">{{ ($stock->gross)?@number_format($stock->gross,3).'Gm':'-' }}</td>
            <td class="text-center">{{ ($stock->less)?@number_format($stock->less,3).'Gm':'-' }}</td>
            <td class="text-center">{{ ($stock->net)?@number_format($stock->net,3).'Gm':'-' }}</td>--}}
			
			<td class="text-center">
				 @php 
                    $show_gross = ($avail=='avail')?$stock->avail_gross:($stock->gross-$stock->avail_gross);
                @endphp
                {{ ($show_gross)?@number_format($show_gross,3).'Gm':'-' }}
			</td>
            <td class="text-center">
			@php 
				$less = $stock->less;
				if(!$less){
					$less = ($stock->avail_gross - $stock->avail_net)??null;
				}
				$less = ($less)?@number_format($less,3).'Gm':'-';
			@endphp
			{{ $less }}
			</td>
            <td class="text-center">
				@php 
                    $show_net = ($avail=='avail')?$stock->avail_net:($stock->net-$stock->avail_net);
                @endphp
                {{ ($show_net)?@number_format($show_net,3).'Gm':'-' }} 
			</td>
			
            <td class="text-center">{{ (@$stock->tunch)?@$stock->tunch:'-' }}</td>
            <td class="text-center">{{ (@$stock->wastage)?@$stock->wastage:'-' }}</td>
            <td class="text-center">
				@php 
					$show_fine  = false;
					$multiplier = ($stock->tunch)?$stock->tunch/100:(($stock->caret)?$stock->caret/24:1);
					$avail_fine = $show_net * $multiplier;
					$show_fine = $avail_fine??false;
                @endphp
                {{ ($show_fine)?@number_format($show_fine,3).'Gm':'-' }}  
			</td> 
            <td class="text-center">{{ (@$stock->element_charge)?@$stock->element_charge.'Rs':'-' }}</td>
            @php 
                $unit_perc = ['r'=>'Rs','w'=>'Gm','p'=>'%'];
            @endphp
            <td class="text-center">
				@if($stock->labour && $stock->labour_unit)
					{{ @$stock->labour }}{{ ($stock->labour_unit=='w')?"Rs/Gm":$unit_perc[$stock->labour_unit] }}
				@else 
					-
				@endif
                
            </td>
            <td class="text-center">{{ (@$stock->charge)?@$stock->charge.'Rs':'-' }}</td>
            <td class="text-center">{{ (@$stock->rate)?@$stock->rate.'Rs':'-'??'-' }}</td>
            <td class="text-center">
                @if($stock->discount && $stock->discount_unit)
                {{ @$stock->discount }}{{ @$unit_perc[$stock->discount_unit] }}
                @else 
					-
				@endif
            </td>
            <td class="text-center">{{ (@$stock->total)?@$stock->total.'Rs':'-' }}</td>
			@if(!isset($export))
		
			<td class="text-center">
                @if($show_action)
                    @if($avail == 'sold')
                        <input type="checkbox" name="item[]" value="{{ $stock->id }}" id="item_{{ $sk }}" onchange="" class="item_checked d-none" {!! @$zpl_print !!}>
                        <div class="dropdown  m-auto" id="dropdown_{{ $sk }}"  >
                            <button class="btn btn-outline-dark dropdown-toggle p-0 px-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                &#8942;
                            </button>
                            <div class="dropdown-menu border-light" aria-labelledby="dropdownMenuButton" style="min-width: inherit;box-shadow:1px 2px 3px lightgray;">
                                <a class="dropdown-item text-danger stock_item_delete" href="{{ route('stock.new.delete',["option_stock"=>@$stock->stock_type,"item"=>@$stock->id]) }}"><i class="fa fa-times"></i> Delete</a>
                                <label class="w-100 px-3 py-1 " style="cursor:pointer;" for="item_{{ $sk }}">
                                    &check; Select
                                </label>
                            </div>
                        </div>
                    @elseif($avail=='avail') 
                        <input type="checkbox" name="item[]" value="{{ $stock->id }}" id="item_{{ $sk }}" onchange="" class="item_checked d-none" {!! @$zpl_print !!}>
                        <div class="dropdown  m-auto" id="dropdown_{{ $sk }}"  >
                            <button class="btn btn-outline-dark dropdown-toggle p-0 px-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                &#8942;
                            </button>
                            <div class="dropdown-menu border-light" aria-labelledby="dropdownMenuButton" style="min-width: inherit;box-shadow:1px 2px 3px lightgray;">
                                <a class="dropdown-item text-info stock_item_edit" href="{{ route('stock.new.edit',["option_stock"=>@$stock->stock_type,"item"=>@$stock->id]) }}"><i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item text-danger stock_item_delete" href="{{ route('stock.new.delete',["option_stock"=>@$stock->stock_type,"item"=>@$stock->id]) }}"><i class="fa fa-times"></i> Delete</a>
                                <label class="w-100 px-3 py-1 " style="cursor:pointer;" for="item_{{ $sk }}">
                                    &check; Select
                                </label>
                            </div>
                        </div>
                    @endif
                @endif
            </td>
		
		{{--<td class="text-center">
				@if($stock->avail_net == 0) 
					<a class="stock_item_delete btn btn-sm btn-danger p-0 px-1" href="{{ route('stock.new.delete',["option_stock"=>@$stock->stock_type,"item"=>@$stock->id]) }}"><i class="fa fa-times"></i></a>
				@else 
                <input type="checkbox" name="item[]" value="{{ $stock->id }}" id="item_{{ $sk }}" onchange="" class="item_checked d-none" {!! @$zpl_print !!}>
                <div class="dropdown  m-auto" id="dropdown_{{ $sk }}"  >
                    <button class="btn btn-outline-dark dropdown-toggle p-0 px-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &#8942;
                    </button>
                    <div class="dropdown-menu border-light" aria-labelledby="dropdownMenuButton" style="min-width: inherit;box-shadow:1px 2px 3px lightgray;">
                        <a class="dropdown-item text-info stock_item_edit" href="{{ route('stock.new.edit',["option_stock"=>@$stock->stock_type,"item"=>@$stock->id]) }}"><i class="fa fa-edit"></i> Edit</a>
                        <a class="dropdown-item text-danger stock_item_delete" href="{{ route('stock.new.delete',["option_stock"=>@$stock->stock_type,"item"=>@$stock->id]) }}"><i class="fa fa-times"></i> Delete</a>
                        <label class="w-100 px-3 py-1 " style="cursor:pointer;" for="item_{{ $sk }}">
                            &check; Select
                        </label>
                    </div>
                </div>
				@endif
		</td>--}}
			@endif
        </tr>
        @php 
			$count++;
            $pc_count+= $pc_num;
            $gross+= $show_gross??0;
            $net+= $show_net??0;
            $fine+= $show_fine??0;
            $stchrg+= $stock->element_charge??0;
            $other+= $stock->charge??0;
            $total+= $stock->total??0;
			if(in_array($stock_cat,['stone','artificial'])){
				$rate+=@$stock->rate??0;
			}
        @endphp 
        @endforeach
    </tbody>
    <tfoot class="text-center data_area">
        <tr>
            <td colspan="2"><label class="form-control m-0">{{ @$count }} Items</label></td>
            <td colspan="3"></td>
			<td>
                <label class="form-control m-0">{{ @$pc_count??'-' }}</label>
            </td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($gross,3) }}</label>
                    <span class="over-text">Gm</span>
                </div>
			</td>
            <td></td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($net,3) }}</label>
                    <span class="over-text">Gm</span>
                </div>
			</td>
            <td></td>
            <td></td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @number_format($fine,3) }}</label>
                    <span class="over-text">Gm</span>
                </div>
			</td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @$stchrg }}</label>
                    <span class="over-text">Rs</span>
                </div>
			</td>
            <td></td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @$other }}</label>
                    <span class="over-text">Rs</span>
                </div>
			</td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @$rate }}</label>
                    <span class="over-text">Rs</span>
                </div>
			</td>
            <td></td>
            <td>
				<div class="over-text-container">
                    <label class="form-control m-0">{{ @$total }}</label>
                    <span class="over-text">Rs</span>
                </div>
			</td>
			@if(!isset($export))
            <td>
				@if($show_action)
                    @if($avail=='sold')
                        <form id="delete_form" action="{{ route('stock.new.delete') }}" method="post" class="option_form">
                            @csrf
                            <input type="hidden" name="option_stock" value="{{ strtolower(@$stock_cat) }}">
                            <button type="submit" name="operation" vaue="delete" class="btn btn-sm btn-danger form-control p-1 h-auto">&cross;</button>
                        </form>
                    @else
                        <button type="button" name="opt_action" id="opt_action" class="btn btn-outline-dark dropdown-toggle p-0 px-2" onclick="launchaction(this)" data-target="#option_drop_down">
							&#8942;
						</button>
                    @endif
                @endif
                <!--<button type="button" name="opt_action" id="opt_action" class="btn btn-outline-dark dropdown-toggle p-0 px-2" onclick="launchaction(this)" data-target="#option_drop_down">
                    &#8942;
                </button>-->
            </td>
			@endif
        </tr>
    </tfoot>
@else
	<!--<tbody id="data_area" class="data_area">
		<tr><td colspan="18" class="text-center text-danger">No Stock !</td></tr>
	</tbody>-->
@endif 
@if(isset($export) && $export=='pdf')
</table>
</body>
</html>
@endif