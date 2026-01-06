@php 
//dd($purchase);
@endphp
<div class="row">
    <div class="col-12">
@if(!empty($purchase))
        <ul class="row p-0" style="list-style:none;">
            <li class="col-md-2">DATE</li>
            <li class="col-md-3 text-right"> {{ $purchase->bill_date }}</li>
            <li class="col-md-2"></li>
            <li class="col-md-2">NUMBER</li>
            <li class="col-md-3 text-right"> {{ $purchase->bill_no }} </li>
            <li class="col-md-2">SUPPLIER</li>
            <li class="col-md-3 text-right"> {{ $purchase->supplier->supplier_name }} </li>
            <li class="col-md-2"></li>
            <li class="col-md-2">BATCH</li>
            <li class="col-md-3 text-right"> {{ $purchase->batch_no }} </li>
            <li class="col-12"><hr class="m-1"></li>
            <li class="col-md-2"><b>PAYABLE</b></li>
            <li class="col-md-3 text-right"><b><i> {{ $purchase->total_amount }} Rs.</i></b> </li>
            <li class="col-md-2"></li>
            <li class="col-md-2"><b>PAID</b></li>
            <li class="col-md-3 text-right"><b><i> {{ $purchase->pay_amount }} Rs.</i></b></li>
        </ul>
        <hr class="m-1">
        <div class=" table-responsive">
            <table class="table table-bordered table-stripped">
                <thead >
                    <tr class="bg-info">
                        <th>NAME</th>
                        <th>RATE</th>
                        <th>Qnt</th>
                        <th>Detail</th>
                        <th>PURE</th>
                        <th>NET Wt.</th>
                        <th>FINE Wt.</th>
                        <th>LABOUR</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if($purchase->stocks->count()>0)
                        @foreach($purchase->stocks as $sk=>$stock)
                            <tr>
                                <td>
                                    {{ $stock->name }}
                                    <hr class="m-1">
                                    @if($stock->item_type=='artificial')
                                    <b><i><u>ARTIFICIAL</u></i></b>
                                    @else 
                                    <b>CARAT :</b> {{ $stock->carat }}
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($stock->item_type!='artificial')
                                        {{ $stock->rate }} Rs.
                                    @else 
                                        <b>----</b>
                                    @endif
                                </td>
                                <td class="text-center">{{ $stock->quantity }}</td>
                                <td class="text-center">
                                    @if($stock->item_type!='artificial')
                                        <b>GROSS Wt. :</b> {{ $stock->gross_weight }}
                                        <hr class="m-1">
                                        <b>WASTAGE :</b> {{ $stock->wastage }}
                                    @else 
                                        <b>----</b>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stock->item_type!='artificial')
                                        <b>PURITY :</b> {{ $stock->purity }}
                                        <hr class="m-1">
                                        <b>FINE :</b> {{ $stock->fine_purity }}
                                    @else 
                                        <b>----</b>
                                    @endif
                                    
                                </td>
                                <td>
                                    {{ $stock->net_weight }}
                                </td>
                                <td>
                                    {{ $stock->fine_weight }}
                                </td>
                                <td class="text-right">
                                    {{ $stock->labour_charge }} Rs.
                                </td>
                                <td class="text-right">
                                    {{ round($stock->amount*$stock->quantity) }} Rs.
                                </td>
                            </tr>
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="9" class="text-center text-danger">No Items !</td>
                        </tr>
                    @endif
                </tbody>
                @if($purchase->stocks->count()>0)
                <tfoot>
                    <tr class="table-secondary">
                        <td colspan="2"></td>
                        <td class="text-center">
                            <b>{{ $purchase->total_quantity }}</b>
                            
                        </td>
                        <td colspan="2"></td>
                        <td class="text-center">
                            <b>{{ $purchase->total_weight }}</b>
                            
                        </td>
                        <td class="text-center">
                            <b>{{ $purchase->total_fine_weight }}</b>
                            
                        </td>
                        <td ></td>
                        <td class="text-center">
                            <b>{{ $purchase->total_amount  }} Rs.</b>    
                            
                        </td>
                    </tr>
                </tfoor>
                @endif
            </table>
        </div>
@else
    <div class="alert alert-waarning text-center">No Bill Found !</div>
@endif
    </div>
</div>