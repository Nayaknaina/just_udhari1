
<hr class="m-0" style="border-top:1px dashed lightgray;">
<style>
    table.surce_sum_data{
        width:100%;
        border-bottom: 1px dashed tomato;
    }
    table.surce_sum_data thead th{
        /*background:teal;
        color:white;*/
        background: linear-gradient(to right,#f3a282,white,#f3a282);
        color: tomato;
        border: 1px solid tomato;
    }
    table.surce_sum_data thead th,table.surce_sum_data tbody td{
        text-align:center;
    }
    table.surce_sum_data tbody th{
        font-weight:normal;
    }
</style>

@if($data->count()>0)
    @php 
        $src_full_name = ['imp'=>'Stock Import','ins'=>'Stock Save','sll'=>'Sell','prc'=>'purches','udh'=>'Udhar','cut'=>'Bhav Cut','sch'=>'Scheme'];
        $ini_count = count($src_full_name);
    @endphp
<div class="col-12 mt-2">
    <div class="row">
        @foreach($data as $key=>$src)
            <div class="col-md-3 col-12 mb-1">
                <table class="surce_sum_data">
                    <thead>
                        <tr>
                            <th colspan="3">{{ strtoupper($src_full_name[$key]) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>GOLD</th>
                            <td class="text-danger">{{ $src->gold_net_minus }} Gm</td>
                            <td class="text-success">{{ $src->gold_net_plus }} Gm</td>
                        </tr>
                        <tr>
                            <th>SILVER</th>
                            <td class="text-danger">{{ $src->silver_net_minus }} Gm</td>
                            <td class="text-success">{{ $src->silver_net_plus }} Gm</td>
                        </tr>
                        <tr>
                            <th>STONE</th>
                            <td class="text-danger">
                                {{ $src->stone_cost_minus??0 }}Rs <small>({{$src->stone_count_minus??0}})</small>
                            </td>
                            <td class="text-success">
                                {{ $src->stone_cost_plus??0 }}Rs <small>({{$src->stone_count_plus??0}})</small>
                            </td>
                        </tr>
                        <tr>
                            <th>ARTIFICIAL</th>
                            <td class="text-danger">
                                {{ $src->art_cost_minus??0 }}Rs <small>({{$src->art_count_minus??0}})</small>
                            </td>
                            <td class="text-success">
                                {{ $src->art_cost_plus??0 }}Rs <small>({{$src->art_count_plus??0}})</small>
                            </td>
                        </tr>
                        <tr>
                            <th>MONEY</th>
                            <td class="text-danger">{{ $src->money_sum_minus??0 }} Rs</td>
                            <td class="text-success">{{ $src->money_sum_plus??0 }} Rs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @php 
            if(isset($src_full_name[$key])){
                unset($src_full_name[$key]);
            }
            @endphp 
        @endforeach
     @if($ini_count > count($src_full_name))
            @foreach($src_full_name as $key=>$source)
                <div class="col-md-3 col-12 mb-1">
                    <table class="surce_sum_data">
                        <thead>
                            <tr>
                                <th colspan="3">{{ strtoupper($source) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>GOLD</th>
                                <td class="text-danger">0 Gm</td>
                                <td class="text-success">0 Gm</td>
                            </tr>
                            <tr>
                                <th>SILVER</th>
                                <td class="text-danger">0 Gm</td>
                                <td class="text-success">0 Gm</td>
                            </tr>
                            <tr>
                                <th>STONE</th>
                                <td class="text-danger">
                                0 Rs <small>(0)</small>
                                </td>
                                <td class="text-success">
                                    0 Rs <small>(0)</small>
                                </td>
                            </tr>
                            <tr>
                                <th>ARTIFICIAL</th>
                                <td class="text-danger">
                                    0 Rs <small>(0)</small>
                                </td>
                                <td class="text-success">
                                    0 Rs <small>(0)</small>
                                </td>
                            </tr>
                            <tr>
                                <th>MONEY</th>
                                <td class="text-danger">0 Rs</td>
                                <td class="text-success">0 Rs</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
     @endif
     
    </div>
</div>
@else 
<p class="text-center text-danger"><i class="fa fa-info-circle"></i> No Transactions !</p>
@endif