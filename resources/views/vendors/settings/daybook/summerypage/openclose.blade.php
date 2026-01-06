<div class="col-md-12 p-0">
    <label class="text-center text-white bg-dark form-control">
        {{ $date }} | {{ ($target=='open')?'OPENING':'CLOSING' }}
    </label>
    
    @if($data->count()>0)
    @php 
        $src_full_name = ['imp'=>'Stock Import','ins'=>'Stock Save','sll'=>'Sell','prc'=>'purches','udh'=>'Udhar','cut'=>'Bhav Cut','sch'=>'Scheme'];
        $ini_count = count($src_full_name);
    @endphp
    <style>
        table#open_close_summery thead  tr.source_head >th{
            background:linear-gradient(to right,#ff6c5c42,white,#ff6c5c42);
            color:tomato;
            border:1px dashed black;
            text-align: center;
        }
        table#open_close_summery tr.txn_title th{
            border:1px dotted tomato;
            text-align: center;
        }
        table#open_close_summery tbody td{
            text-align: center;
        }
        table#open_close_summery tbody td:not(:last-child),table#open_close_summery tbody th:not(:last-child){
            border-right:1px dotted tomato;
        }
        table#open_close_summery tbody td table td{
            text-align: center;
        }
    </style>
    <div class="table-responsive px-2 open_close_summery_area">
            <table class="w-100" id="open_close_summery">
                @foreach($data as $key=>$src)
                    <thead>
                        <tr class="source_head">
                            <th>{{ strtoupper($src_full_name[$key]) }}</th>
                            <th>IN</th>
                            <th>OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>GOLD</th>
                            <td class="text-success">
                                    {{ $src->gold_net_plus??0 }} Gm
                            </th>
                            <td class="text-danger">
                                    {{ $src->gold_net_minus??0 }} Gm
                            </th>
                        </tr>
                        <tr>
                            <th>SILVER</th>
                            <td class="text-success">
                                {{ $src->silver_net_plus??0 }} Gm
                            </td>
                            <td class="text-danger">
                                {{ $src->silver_net_minus??0 }} Gm
                            </td>
                        </tr>
                        <tr>
                            <th>STONE</th>
                            <td class="text-success">
                               {{ $src->stone_count_plus??0 }}Pc.
                            </td>
                            <td class="text-danger">
                                {{ $src->stone_count_minus??0 }}Pc.
                            </td>
                        </tr>
                        <tr>
                            <th>ARTIFICIAL</th>
                            <td class="text-success">
                                {{ $src->art_count_plus??0 }}Pc.
                            </td>
                            <td class="text-danger">
                                {{ $src->art_count_minus??0 }}Pc.
                            </td>
                        </tr>
                        <tr>
                            <th>AMOUNT</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td class="text-info amount_txn"><i>CASH</i></td>
                            <td class="text-success">{{ $src->money_shop_plus??0 }}Rs.</td>
                            <td class="text-danger">{{ $src->money_shop_minus??0 }}Rs.</td>
                        </tr>
                        <tr>
                            <td class="text-info"><i>BANK</i></td>
                            <td class="text-success">{{ $src->money_bank_plus??0 }}Rs.</td>
                            <td class="text-danger">{{ $src->money_bank_minus??0 }}Rs.</td>
                        </tr>
                    </tbody>
                    @php 
                    if(isset($src_full_name[$key])){
                        unset($src_full_name[$key]);
                    }
                    @endphp
                @endforeach
                @if($ini_count > count($src_full_name))
                    @foreach($src_full_name as $key=>$source)
                        <thead>
                            <tr class="source_head">
                                <th>{{ strtoupper($source) }}</th>
                                <th>IN</th>
                                <th>OUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>GOLD</th>
                                <td class="text-success">
                                0 Gm
                                </th>
                                <td class="text-danger">
                                0 Gm
                                </th>
                            </tr>
                            <tr>
                                <th>SILVER</th>
                                <td class="text-success">
                                0 Gm
                                </td>
                                <td class="text-danger">
                                0 Gm
                                </td>
                            </tr>
                            <tr>
                                <th>STONE</th>
                                <td class="text-success">
                                0 Pc.
                                </td>
                                <td class="text-danger">
                                0 Pc.
                                </td>
                            </tr>
                            <tr>
                                <th>ARTIFICIAL</th>
                                <td class="text-success">
                                    {{ $src->art_count_plus??0 }}Pc.
                                </td>
                                <td class="text-danger">
                                    {{ $src->art_count_minus??0 }}Pc.
                                </td>
                            </tr>
                            <tr>
                                <th>AMOUNT</th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td class="text-info amount_txn"><i>CASH</i></td>
                                <td class="text-success">{{ $money_cash_plus??0 }}Rs.</td>
                                <td class="text-danger">{{ $money_cash_minus??0 }}Rs.</td>
                            </tr>
                            <tr>
                                <td class="text-info"><i>BANK</i></td>
                                <td class="text-success">{{ $money_bank_plus??0 }}Rs.</td>
                                <td class="text-danger">{{ $money_bank_minus??0 }}Rs.</td>
                            </tr>
                        </tbody>
                    @endforeach 
                @endif
            </table>
    </div>
    @else 
    <p class="text-center text-danger"><i class="fa fa-info-circle"></i> No Transactions yet !</p>
    @endif
</div>