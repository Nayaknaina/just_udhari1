@if($txnsdata->count()>0)
    @foreach($txnsdata as $txnk=>$txn)
        @php 
            $pay_status = ['Unpaid','Paid'];
            $pay_color = ['danger','success'];
			$url_add_on  = "orderdetail";
        @endphp
        <tr class="table-{{ $pay_color[$txn->txn_status] }}">
            <td>{{ $txnsdata->firstItem() + $txnk }}</td>
            <td>{{ $txn->created_at }}</td>
            <td>{{ $txn->txn_by??'-----' }}</td>
            @if(isset($type) && in_array($type,['order','scheme']))
                @php 
                    $url_add_on = ($type=="scheme")?'schemetxns':'orderdetail';
                    $custo_var = ($type=="scheme")?$txn->schemeorder->enroll->custo:$txn->order->customer;
                    $custo_info = $custo_var->custo_full_name;
                    $custo_info .=($custo_info=="")?$custo_var->custo_fone:'<hr class="m-1 p-0">'.$custo_var->custo_fone;
                @endphp
            <td class="text-center">
                @if($type=='scheme')
                    <b>{{ $txn->schemeorder->enroll->customer_name }}</b>
                    <hr class="m-1 p-0">
                @endif
                {!! $custo_info !!}
            </td>
                @if($type=='scheme')
                    @php  $enroll = $txn->schemeorder->enroll; @endphp
                    <td  class="text-center">
                        {{ $enroll->schemes->scheme_head }}
                        <hr class="m-1 p-0">
                        {{ $enroll->groups->group_name }}
                    </td>
                @endif
            @endif

            <td><a href="{{ route("ecomorders.{$url_add_on}",$txn->orders_id) }}" class="btn btn-outline-secondary">{{ $txn->order_number??'-----' }}</a></td>
            <td>
                <b>SYS : </b>{{ $txn->txn_number }}
                <hr class="m-1 p-0">
                <b>GTW : </b>{{ $txn->gateway_txn_id }}
        </td>
            <td class="text-right">{{ $txn->order_amount  }} Rs.</td>
            <td>{{ $txn->txn_mode??'-----' }}</td>
            <td>{{ $txn->txn_medium??'-----' }}</td>
            <td>
                <b>CODE : </b>{{ $txn->txn_res_code??'-----' }}
                <hr class="m-1 p-0">
                <b>MSG : </b>{{ $txn->txn_res_msg??'-----' }}
            </td>
            
            <td>
                <b>SYS : </b><span class="text-{{ $pay_color[$txn->txn_status] }}">
                    {{ $pay_status[$txn->txn_status]??'-----' }}
                    </span>
                <hr class="m-1 p-0">
                <b>GTW : </b>{{ $txn->gateway_txn_status??'-----' }}
            </td>
            <!-- <td></td>         -->
        </tr>
    @endforeach
@else 
    <tr>
        <td class="text-center text-danger" colspan="{{ (isset($type))?(($type=="scheme")?'12':'11'):'10' }}">No Ttransaction !</td>
    </tr>
@endif