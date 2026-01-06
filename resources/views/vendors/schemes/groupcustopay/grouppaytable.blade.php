
@if($customers->count()>0)
	@php 
        $currentPage = $customers->currentPage();
        $sn = ($customers->perPage()*($currentPage-1))+1;
    @endphp
    @foreach($customers as $ck=>$custo)
            @php 
				$emi_sum_query = $custo->emipaid->whereIn('action_taken',['A','U']);
				//$emi_count = $emi_sum_query->count('emi_num')??0;
				
				$emi_count = $emi_sum_query->max('emi_num')??0;
				$emi_sum = $emi_sum_query->sum('emi_amnt')??0;
            @endphp
        <tr {!! ($emi_sum != 0 && $emi_sum < $custo->emi_amnt)?'class="table-warning"':'' !!}>
            <td>{{ $sn++ }}</td>
            <td>
                <b>{{ $custo->customer_name }}</b>
                @if(@$custo->info->custo_full_name != $custo->customer_name)
                <hr class="m-2 p-0">
                ( {{ @$custo->info->custo_full_name }} )
                @endif
                </td>
            <td class="text-center">
                {{ @$custo->info->custo_fone }}
                @if(@$custo->info->custo_mail!="")
                <hr class="m-2">
                {{ @$custo->info->custo_mail }}
                @endif
            </td>
            <td class="text-center text-info">
                {{ @$custo->emi_amnt }} Rs.
            </td>
           
            <td class="text-center">
           <b style="font-size:1rem"> {{ $emi_count }}</b>/{{ $custo->schemes->scheme_validity }}
            </td>
            <td class="text-center text-success">
            <b style="font-size:1rem">{{ $emi_sum }}</b> / {{ $custo->schemes->scheme_validity*$custo->emi_amnt }} Rs.
            </td>
            <td class="text-center text-danger">
				@php
                    //echo "Payable : ".$custo->emi_amnt*$data."<br>";
                    //echo "Month Num : ".$data."<br>";
                    //echo "Paid at month : ".$emi_sum_query->where('emi_num',$data)->sum('emi_amnt')."<br>";
                    $emi_num = ($emi_count==0)?1:$emi_count;
                    $due_amnt = ($data == 'all')?($custo->schemes->scheme_validity*$custo->emi_amnt)- $emi_sum:$custo->emi_amnt-$emi_sum_query->where('emi_num',$data)->sum('emi_amnt');
                @endphp
                {{ $due_amnt }} Rs.
            </td>
            <td class="text-center">
            <a href="{{ route("shopscheme.emipay",$custo->id)}}" class="btn btn-outline-info">
                <li class="fa fa-eye"></li>
            </a>
            </td>
        </tr>
    @endforeach
@else 
    <tr><td colspan="8" class="text-center text-danger">No Record Found !</td></tr>
@endif

<script>
    var txn_modal_body =$("#view_txn_modal").find('.modal-dialog').find('.modal-content').find('.modal-body');
    $('.view_scheme_txns').click(function(e){
        e.preventDefault();
        txn_modal_body.empty();
        txn_modal_body.load($(this).attr('href'),"",function(){
            $("#view_txn_modal").modal();
        });
    })
</script>