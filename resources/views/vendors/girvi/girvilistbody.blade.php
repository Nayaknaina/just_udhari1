@if($txns->count() > 0)
    @php 
        $sign = ['-','+'];
        $num = $sum_p = $sum_i = 0;
    @endphp
    @foreach($txns as $ti=>$txn)
        <tr>
            <td>{{ $txns->firstItem() + $ti }}</td>
            <td>{{ date('d-m-Y',strtotime($txn->updated_at)) }}</td>
            <td>{{ $txn->customer->custo_name }}</td>
            <td>{{ $txn->customer->custo_mobile }}</td>
            <td class="text-{{ ($txn->pay_principal!=0)?(($txn->txn_status==0)?'danger':'success'):'info' }}">{{ $sign[$txn->txn_status].$txn->pay_principal }} Rs</td>
            <td class="text-{{ ($txn->pay_interest!=0)?(($txn->txn_status==0)?'danger':'success'):'info' }}">{{ $sign[$txn->txn_status].$txn->pay_interest }} Rs</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width:unset;">
                        &#8643;&#9783;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item text-info" href="#"><i class="fa fa-edit"></i> Edit</a>
                        <a class="dropdown-item text-success" href="#"><i class="fa fa-print"> </i> Print</a>
                        <a class="dropdown-item text-danger" href="#"><i class="fa fa-times"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        @php 
            $num++ ;
            $sum_p += $sign[$txn->txn_status].$txn->pay_principal;
            $sum_i += $sign[$txn->txn_status].$txn->pay_interest;
        @endphp
        @if($num== $txns->count())
            
        @endif
    @endforeach
    <tr class="border-dark foot">
        <td class="text-center border-dark" colspan="4"><b>TOTAL</b></td>
        <td class="border-dark text-{{ ($sum_p!=0)?(($sum_p > 0)?'success':'danger'):'info' }}">{{ $sum_p }} Rs.</td>
        <td class="border-dark text-{{ ($sum_i!=0)?(($sum_i > 0)?'success':'danger'):'info' }}">{{ $sum_i }} Rs.</td>
        <td  class="border-dark"></td>
    </tr>
@else 
<!--<tr><td class="text-center text-danger" colspan="7">No Record !</td></tr>-->
@endif