<style>
ul.detail>li>span:before {
    content:": ";
}
ul.detail>li{
    border-bottom:1px dashed lightgray;
}

</style>
<section>
    <div class="row">
        @switch($switch)
            @case('customer')
                @php $entry = ['1'=>"E-Comm",'2'=>"System",'3'=>"Bill"] @endphp
                <ul class="py-0 col-12 m-0 detail">
                    <li class="row">
                        <b class="col-sm-5 col-12">NAME</b>
                        <span class="col-sm-7 col-12">{{ $data->custo_full_name }}</span>
                    </li>
                    <li class="row">
                        <b  class="col-sm-5 col-12">MOBILE</b>
                        <span  class="col-sm-7 col-12">{{ $data->custo_fone }}</span></li>
                    <li class="row">
                        <b class="col-sm-5 col-12">E-MAIL</b>
                        <span class="col-sm-7 col-12">{{ $data->custo_mail }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">STATE</b>
                        <span class="col-sm-7 col-12">{{ $data->state_name }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">DISTRICT</b>
                        <span class="col-sm-7 col-12">{{ $data->dist_name }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">TEHSIL</b>
                        <span class="col-sm-7 col-12">{{ $data->teh_name }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">AREA</b>
                        <span class="col-sm-7 col-12">{{ $data->area_name }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">PINCODE</b>
                        <span class="col-sm-7 col-12">{{ $data->pin_code }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">ADDRESS</b>
                        <span class="col-sm-7 col-12">{{ $data->custo_address }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">ENTRY POINT</b>
                        <span class="col-sm-7 col-12">{{ $entry[$data->cust_type] }}</span>
                    </li>
                    <li class="row">
                        <b class="col-sm-5 col-12">SCHEME BALANCE</b>
                        <span class="col-sm-7 col-12">{{ $data->schemebalance->remains_balance }} Rs.</span>
                    </li>
                </ul>
                @break
            @case('payment')
                @if($data->count()>0)
                    @php 
                        $bg_arr = ["E"=>'edit_li','D'=>'delete_li','U'=>'update_li'];

                    @endphp
                <ul class="py-0 col-12 m-0 detail">
                    @foreach($data as $pk=>$pay)
                    <li class="row {{ @$bg_arr[$pay->action_taken] }}">
                        <b class="col-sm-5 col-12">{{ $pay->medium }}</b>
                        <span class="col-sm-7 col-12">{{ $pay->amount }} Rs.</span>
                    </li>
                    @endforeach
                </ul>
                @else 
                <p class="text-center text-danger col-12">No Record !</p>
                @endif
                @break
            @default 
                <p class="text-center text-danger col-12">No Record !</p>
                @break
        @endswitch
    </div>
</section>
<style>
    .update_li{
        color:blue;
    }
    .delete_li{
        color:red;
        opacity:0.3;
    }
    .edit_li{
        color:blue;
        opacity:0.3;
    }
</style>