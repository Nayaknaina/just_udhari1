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
    </div>
</section>