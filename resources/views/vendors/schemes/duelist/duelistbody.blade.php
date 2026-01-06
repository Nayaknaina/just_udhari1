@if($duelist->count()>0)
    @if($export=='pdf')
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
		.remains_month{
            list-style:none;
            padding:2px;
        }
        ul.remains_month > li > span{
            //float:right;
        }
        ul.remains_month > li{
			margin:2px 0;
		}
		td,th {
            font-size:75%;
        }
    </style>
    <table>
        <thead>
            <tr id="no-border">
                <td colspan="2" style="text-align:left;">
                    SCHEME DUES | {{ date('d-M-Y',strtotime('now')) }} 
                </td>
                <td colspan="5" style="text-align:right;">
                    @if(!empty($data))
                    <ul style="list-style:none;display:inline-flex">
                        @foreach($data as $dk=>$d)
                        <li>@if($dk>0) {{ '|' }} @endif{{ ucfirst($d) }}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
            </tr>
            <tr>
                <th>S.N.</th>
                <th>Customer</th>
                <th class="text-center">Contact</th>
                <th class="text-center">Scheme</th>
                <th class="text-center">Group</th>
                <th class="text-center">Month</th>
                <th class="text-center">Amount</th>
            </tr>
        </thead>
        <tbody>


    @endif
    @foreach($duelist as $duek=>$due)
		@if(!empty($due))
        <tr>
            <td  class="text-center">{{ ($export=='pdf')?($duek+1):($duelist->firstItem()+$duek) }}</td>
            <td  class="text-center">
                {{ @$due->customer_name}}<b>({{ @$due->assign_id }})</b>
                <hr class="m-0 p-0">
                <small>( {{ @$due->info->custo_full_name }} )</small>
            </td>
            <td  class="text-center">{{ @$due->info->custo_fone}}</td>
            <td  class="text-center">{{ @$due->schemes->scheme_head}}</td>
            <td  class="text-center">{{ @$due->groups->group_name }}</td>
            @php 
                //print_r($due->toArray());
                $start = ($due->schemes->scheme_date_fix==1)?$due->schemes->scheme_date:$due->entry_at;
                //echo $start.'<br>';
                $scheme_start_month_name = date('M',strtotime($start));
                $scheme_start_month_num = date('m',strtotime($start));
                $today_date_month_num = date('m',strtotime('now'));
                $mnth_num_diff = $today_date_month_num - $scheme_start_month_num;
                $cur_emi_num = (($mnth_num_diff < 0)?(12 + $mnth_num_diff):$mnth_num_diff)+1;
                //echo $cur_emi_num;
                $choosed_emi = $due->emi_amnt;
                $payable = $choosed_emi * $due->schemes->scheme_validity;
                $paid_arr = [];
				//echo $due->emi_details;
                if(!empty($due->emi_details)){
                    $paid_arr_rcv = explode(',',$due->emi_details);
                    foreach($paid_arr_rcv as $pk=>$emis){
                        $emi_data = explode('~',$emis);
						$amount = $emi_data[1];
						if(isset($paid_arr[$emi_data[0]])){
							$amount+=$paid_arr[$emi_data[0]];
						}
                        $paid_arr[$emi_data[0]] = $amount;
                    }
                }else{
                    for($emi_i=0;$emi_i<=$cur_emi_num;$emi_i++){
                        $paid_arr[$emi_i] = 0;
                    }
                }
            @endphp
            <td>
				@php $remains_pay = 0 @endphp
                @if(!empty($paid_arr))
                    <ul style="" class="remains_month m-0">
                    @for($i=1;$i<=$cur_emi_num;$i++)
                        @php 
                            $new_num = $i-1;
                            $nw_month = date("M",strtotime("$scheme_start_month_name + $new_num Month"));
                        @endphp
                        @if(!isset($paid_arr[$i]) || $paid_arr[$i] != $choosed_emi)
                            @php $amnt =  $choosed_emi - (@$paid_arr[$i]??0) @endphp
                            <li><b>{{ $nw_month }}</b> - {{ $amnt }}Rs</li>
							@php 
								$remains_pay+=$amnt;
							@endphp
                        @endif
                    @endfor
                    </ul>
                @endif
            </td>
			
            <td class="text-center">
				{{ $remains_pay }} Rs
			</td>
        </tr>
		@endif
    @endforeach
@else 
    <!-- <tr><td class="text-center text-danger">No record !</td></tr> -->
@endif
@if($export=='pdf')
    </tbody>
</table>
@endif