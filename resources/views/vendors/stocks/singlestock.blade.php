
<div class="row">
    <div class="col-12">
        <div class=" table-responsive">
            <table class="table table-bordered table-stripped">
                <thead >
                    <tr class="bg-info">
                        <th>S.N.</th>
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
                @if(!empty($stock))      
                    @php 
                        $prop = ($stock->item_type!='artificial')?json_decode($stock->property,true):false;
                    @endphp
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            {{ $stock->product_name }}
                            <hr class="m-1">
                            @if($stock->item_type=='artificial')
                            <b><i style="color:blue;"><u>ARTIFICIAL</u></i></b>
                            @else 
                                @if($stock->item_type=='loose') 
                                <i style="color:blue;">loose</i>
                                <hr class="m-1">
                                @endif
                            @if($prop)
                            <b>CARAT :</b> {{ $prop['carat'] }}
                            @else 
                                <b>-----</b>
                            @endif
                            @endif
                        </td>
                        <td class="text-right">
                            {{ $stock->rate }} Rs.
                        </td>
                        <td class="text-center">
                        @if($stock->item_type=='loose') 
                            <b>-----</b>
                        @else
                            {{ $stock->quantity }}
                        @endif
                        </td>
                        <td class="text-center">
                            @if($stock->item_type!='artificial')
                                <b>GROSS Wt. :</b> {{ $prop['gross_weight'] }}
                                <hr class="m-1">
                                <b>WASTAGE :</b> {{ $prop['wastage'] }}
                            @else 
                                <b>----</b>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stock->item_type!='artificial')
                                <b>PURITY :</b> {{ $prop['purity'] }}
                                <hr class="m-1">
                                <b>FINE :</b> {{ $prop['fine_purity'] }}
                            @else 
                                <b>----</b>
                            @endif
                            
                        </td>
                        <td>
                        @if($stock->item_type!='artificial')
                            {{ $prop['net_weight'] }} 
                        @else 
                            <b>----</b>
                        @endif
                        </td>
                        <td>
                        @if($stock->item_type!='artificial')
                            {{ $prop['fine_weight'] }}
                        @else 
                            <b>----</b>
                        @endif
                        </td>
                        <td class="text-right">
                        @if($stock->item_type!='artificial')
                            {{ $stock->labour_charge }} Rs.
                        @else 
                            <b>----</b>
                        @endif
                        </td>
                        <td class="text-right">
                            {{ $stock->amount }} Rs.
                        </td>
                    </tr>
                    @if(!empty($stock->elements) && $stock->elements->count()>0)
                        @php  $elements = $stock->elements  @endphp
                        @foreach($elements as $elek=>$ele)
                    <tr>
                        <td class="text-right">E&#10148;</td>
                        <td>
                        {{ $ele->name }}
                        <hr class="m-1">
                        <b>CARAT :</b> {{ $ele->caret }}
                        </td>
                        <td>
                            ------
                        </td>
                        <td>
                        {{ $ele->quant }}
                        </td>
                        <td>
                            ------
                        </td>
                        <td>
                            ------
                        </td>
                        <td>
                            ------
                        </td>
                        <td>
                            ------
                        </td>
                        <td>
                            ------
                        </td>
                        <td>
                            {{ $ele->cost }} Rs.
                        </td>
                    </tr>
                    @endforeach
                @endif
                    @else 
                        <tr>
                            <td colspan="10" class="text-center text-danger">No Items !</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>