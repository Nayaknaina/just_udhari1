
@if($enroll_custos->count()>0)
    @foreach($enroll_custos as $ei=>$enroll)
    <tr class={{ ($enroll->status==0)?'table-danger':'' }}>
        <td class="text-center">1</td>
        <td class="text-center">
            {{ ucfirst($enroll->custo_name) }}
            <hr class="m-1">
            {{ $enroll->customer->custo_fone }}</td>
        <td class="text-center">
            {{ $enroll->scheme->title }}
            <hr class="m-1">
            <b>{{ $enroll->scheme->validity}} Month</b>
        </td>
        @php 
            $unit_arr = ['gold'=>'Gm.','cash'=>'Rs.'];
        @endphp
        <td class="text-center">
            EMI : {{ ($enroll->scheme->fix_emi)?$enroll->scheme->emi_quant.$unit_arr[$enroll->scheme->type]:'No Fix !' }}
            @if($enroll->scheme->fix_emi)
            <hr class="m-1">
            <span class="text-info"><b>Payable :</b>{{ ($enroll->scheme->emi_quant*$enroll->scheme->validity).$unit_arr[$enroll->scheme->type] }}</span>
            @endif
        </td>
        @php 
            $paid = $enroll->txns->where('txn_status',1)->whereIn('txn_action',['A','U'])->sum('txn_quant')??0;
            $with = $enroll->txns->where('txn_status',0)->whereIn('txn_action',['A','U'])->sum('txn_quant')??0;
        @endphp
        <td>
            <span class="text-success"><b>DEPOSIT :</b> {{ $paid.$unit_arr[$enroll->scheme->type] }}</span>
            <hr class="m-1"> 
            <span class="text-danger"><b>WITHDRAW :</b> {{ $with.$unit_arr[$enroll->scheme->type] }}</span>
            @if($enroll->scheme->fix_emi)
            <hr class="m-1">
            <span class="text-warning"><b>Due :</b> {{ ($enroll->scheme->emi_quant*$enroll->scheme->validity - $paid).$unit_arr[$enroll->scheme->type] }}</span>
            @endif
        </td>
        <td class="text-center">
            <a class="btn btn-outline-info" href="{{route('anjuman.new.txns', [$enroll->id])}}">
                ₹ Pay
            </a>
        </td>
    </tr>
    @endforeach
@else 
<tr><td colspan="7" class="text-center text-danger">No Record !</td></tr>
@endif
