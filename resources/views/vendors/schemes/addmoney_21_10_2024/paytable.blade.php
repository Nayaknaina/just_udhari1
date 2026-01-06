@if($customers->count()>0)
    @foreach($customers as $ci=>$custo)
    <tr>
        <td>{{ $ci+1 }}</td>
        <td>{{ $custo->customer_name }}<hr class="m-1">
        {{ $custo->info->custo_fone }}<hr class="m-1">
        {{ $custo->info->custo_mail }}
        </td>
        <td class="text-center">
        {{ $custo->schemes->scheme_head }}<hr class="m-1">
        <b>GROUP : </b>{{ $custo->groups->group_name }}<hr class="m-1">
        <b>EMI : </b>{{ $custo->emi_amnt }}
        </td>
        @php
        $payable = $custo->emi_amnt*$custo->schemes->scheme_validity; 
        $paid = $custo->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt');
        $bonus = $custo->emipaid->sum('bonus_amnt');
        @endphp
        <td>
            <b>Payable : </b> {{ $payable }}<hr class="m-1">
            <b>Paid : </b> {{ $paid }}<hr class="m-1">
            <b>Bonus : </b> {{ $bonus }}
        </td>
        <td class="text-center">
            @php 
            $action_lbl = '<li class="fa fa-rupee"></li> PAY';
            $btn_class = 'btn-outline-success';
            if(($paid=$payable) && ($custo->emipaid->max('emi_num')==$custo->schemes->scheme_validity)){
            $action_lbl = '<li class="fa fa-eye"></li> View';
            $btn_class = 'btn-outline-info';
            }
            @endphp
            <a href="{{ route("shopscheme.emipay",$custo->id)}}" class="btn {{ $btn_class }}">
            {!! $action_lbl !!}
            </a>
        </td>
        
    </tr>
    @endforeach
    @else 
    <tr><td colspan="5" class="text-center text-danger">No Enrollments Yet !</td></tr>
    @endif