
<div class="col-12 p-0">
    <div class="table-responsive">
        <table class="table table-bordered text-cenrer">
            <thead>
                <tr class="bg-dark text-white">
                    <th>DATE</th>
                    <th>Order</th>
                    <th>Order Type</th>
                    <th>Txn Number</th>
                    <th>Amount</th>
                    <th>Medium</th>
                    <th>Txn Message</th>
                    <th>Txn Status</th>
                </tr>
            </thead>
            <tbody>
            @if($txns->count()>0)
                @php $txn_stts = ['danger','success'];  @endphp 
                @foreach($txns as $txnk=>$txn)
                    <tr class="table-{{ ($txn->txn_status==1)?'success':'danger' }}">
                        <td>{{ date('d-M-Y H:i:s',strtotime($txn->created_at)) }}</td>
                        <td>{{ $txn->order_number }}</td>
                        <td>{{ ($txn->txn_for=='order')?'ITEM':'SCHEME' }}</td>
                        <td>{{ $txn->txn_number }}</td>
                        <td>{{ $txn->order_amount }}</td>
                        <td>{{ $txn->txn_medium??'---' }}</td>
                        <td>{{ $txn->txn_res_msg??'---' }}</td>
                        <td>{{ $txn->gateway_txn_status??'---' }}</td>
                    </tr>
                @endforeach
            @else 
                <tr><td colspan="4" class="text-center text-danger">No Txns !</td></tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
@if($txns->count()>0)
    <div class="col-12 pb-1">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mb-3">
                {{ $txns->links("pagination::bootstrap-4") }}
            </ul>
        </nav>
    </div>
@endif