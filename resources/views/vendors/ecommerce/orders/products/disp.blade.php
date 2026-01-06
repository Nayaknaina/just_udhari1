@if($order_data->count()>0)
    @foreach($order_data as $key=>$row)
        <tr>
            <td>{{ $order_data->firstItem() + $key }}</td>
            <td>{{ date("d-M-Y H:i:a",strtotime($row->created_at)) }}</td>
            <td class="text-center">
                <a class="detail_show" href="{{ route('ecomorders.orderdetail',$row->id) }}">{{$row->order_unique }}</a>
            </td> 
            @php 
                $custo_detail = $row->customer;
                $custo_info = @$custo_detail->custo_full_name;
                $custo_info .=($custo_info=="")?@$custo_detail->custo_fone:'<hr class="m-1 p-0">'.$custo_detail->custo_fone;
            @endphp
            <td class="text-center">{!! $custo_info !!}</td> 
            <td  class="text-center">  {{ $row->quantity }}</td>
            <td class="text-center"> {{ $row->total }} Rs.</td>
            @php 
                $status_arr = ['Pending','Paid','Pay Attempt'];
                $color_arr = ['warning','success','danger'];
            @endphp
            <td  class="text-center">
                <b class="text-{{ $color_arr[$row->pay_status] }}">{{ $status_arr[$row->pay_status] }}</b>
            </td>
            <td  class="text-center"> 
                <a href="{{ route('ecomorders.productordertxns',['id'=>$row->id]) }}" class="btn btn-outline-info"><li class="fa fa-eye"></li></a>
            </td>
            <!-- <td >
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <li class="fa fa-pencil"></li> <span class="fa fa-chevron-circle-down"></span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: auto !important;">
                        <a class="dropdown-item text-secondary" href="#">Status</a>
                        <a class="dropdown-item text-info" href="#">Payment</a>
                    </div>
                </div>

            </td> -->

        </tr>
    @endforeach
@else 
    <tr><td class="text-center text-danger" colspan="9"> No Record !</td></tr>
@endif



