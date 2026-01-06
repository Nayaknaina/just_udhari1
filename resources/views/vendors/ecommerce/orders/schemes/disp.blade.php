@if($order_data->count()>0)
    @foreach($order_data as $key=>$row)
        <tr>
            <td>{{ $order_data->firstItem() + $key }}</td>
            <td>{{ date("d-M-Y H:i:a",strtotime($row->created_at)) }}</td>
            <td class="text-center">
                {{ @$row->enroll->schemes->scheme_head }}
                <hr class="m-1 p-0">
                {{ @$row->group->group_name }}
            </td> 
            @php 
                $custo_detail = $row->customer;
                $custo_info = $custo_detail->custo_full_name;
                $custo_info .=($custo_info=="")?$custo_detail->custo_fone:'<hr class="m-1 p-0">'.$custo_detail->custo_fone;
                @endphp
            <td class="text-center"><b>{{ @$row->enroll->customer_name }}</b><hr class="m-1">{!! @$custo_info !!}</td> 
            <td class="text-center"> {{ $row->curr_cost }} Rs.</td>
            @php 
                $status_arr = ['Pending','Paid','Pay Attempt'];
                $color_arr = ['warning','success','danger'];
            @endphp
            <td  class="text-center">
                <b class="text-{{ @$color_arr[$row->schemetxn->txn_status] }}">{{ @$status_arr[$row->schemetxn->txn_status]??'------' }}</b>
            </td>
            <td  class="text-center"> 
                <a href="{{ route('ecomorders.schemetxns',$row->id) }}" class="btn btn-outline-info"><li class="fa fa-eye"></li></a>
            </td>
            <!-- <td class="text-center">
                <a href="" class="btn btn-outline-secondary"><li class="fa fa-pencil"></li></a>
            </td> -->

        </tr>
    @endforeach
@else 
    <tr><td class="text-center text-danger" colspan="9"> No Record !</td></tr>
@endif



