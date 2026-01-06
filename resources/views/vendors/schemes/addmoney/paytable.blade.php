<table id="CsTable" class="table table_theme table-bordered scheme-custo-table dataTable">
  <thead>
	  <tr>
		  <th>S.N.</th>
		  <th>CUSTOMER</th>
		  <th class="text-center">ENROLL</th>
		  <th class="text-center">PAYMENT</th>
		  <th>ACTION</th>
	  </tr>
  </thead>
  <tbody id="data-list">
	
	@if($customers->count()>0)
    @php 
        $currentPage = $customers->currentPage();
    @endphp
    @foreach($customers as $ci=>$custo)
    <tr class="{{ ($custo->is_winner)?'winner':'' }}" >
        <td>{{ $customers->firstitem()+$ci }} @if($custo->is_winner)<span class="winner-crown">&#9813;</span> @endif</td>
        <td>
            {{ $custo->customer_name }} ( <b>{{ $custo->assign_id }}</b> )
            @if($custo->customer_name != @$custo->info->custo_full_name)
                <hr class="m-1">
                ( {{ @$custo->info->custo_full_name }} )
            @endif
            <hr class="m-1">
            <b>{{ @$custo->info->custo_fone }}</b>
            @if(@$custo->info->custo_mail!="")
                <hr class="m-1">
                {{ @$custo->info->custo_mail }}
            @endif
        </td>
        <td class="text-center">
        {{ @$custo->schemes->scheme_head }}<hr class="m-1">
        <b>GROUP : </b>{{ @$custo->groups->group_name }}<hr class="m-1">
        <b>EMI : </b>{{ $custo->emi_amnt??'0' }} Rs.
        </td>
        @php
        $payable = $custo->emi_amnt*($custo->schemes->scheme_validity??1); 
		$token = $custo->token_remain;
        $paid = $custo->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt');
        $bonus = $custo->emipaid->sum('bonus_amnt');
        @endphp
        <td>
            <b>Payable : </b> {{ $payable??'0' }} Rs.<hr class="m-1">
            <b>Paid : </b> {{ $paid??'0' }} Rs.<hr class="m-1">
			<b>Token :</b> {{ $token??'0' }} Rs.<hr class="m-1">
            <b>Bonus : </b> {{ $bonus??'0' }} Rs.
        </td>
        <td class="text-center">
            @php 
            $action_lbl = '<li class="fa fa-rupee"></li> PAY';
            $btn_class = 'btn-outline-success';
            if(($paid=$payable) && ($custo->emipaid->max('emi_num')==($custo->schemes->scheme_validity??1))){
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

  <!--</tbody>
</table>-->
