
<div class="card">
    <div class="card-header p-2">
        <h3>{{ $schemedetail->scheme_head }}</h3>
        <hr class="m-1">
        <h4 class="card-title col-12">{{ $schemedetail->scheme_sub_head }}</h4>
    </div>
    <div class="card-body p-2">
        <div class="col-12 p-0">
            {!! $schemedetail->scheme_detail_one !!}
        </div>
        @if($schemedetail->scheme_table_one!="")
            <div class="table-responsive">
                @php 
                    $col_arr = ['thead'=>'th','tbody'=>'td'];
                    $table_one = json_decode($schemedetail->scheme_table_one,true);
                @endphp
                <table class="table table-default table-bordered table-stripped">
                    @foreach($table_one as $head=>$tr)
                        {!! "<".$head.">" !!} 
                        @foreach($tr as $tri=>$tds)
                            <tr>
                            @foreach($tds as $tdi=>$td)
                            {!! "<".$col_arr[$head].">" !!}  {{  $td }} {!! "</".$col_arr[$head].">" !!} 
                            @endforeach
                            </tr>
                        @endforeach
                        {!! "</".$head.">" !!} 
                    @endforeach
                </table>
            </div>
        @endif
        @if($schemedetail->scheme_detail_two!="")
        <div class="col-12 p-0">
            {!! $schemedetail->scheme_detail_two !!}
        </div>
        @endif
        @if($schemedetail->scheme_table_two!="")
            <div class="table-responsive">
                @php 
                    $col_arr = ['thead'=>'th','tbody'=>'td'];
                    $table_two = json_decode($schemedetail->scheme_table_two,true);
                @endphp
                <table class="table table-default table-bordered table-stripped">
                    @foreach($table_two as $head=>$tr)
                        {!! "<".$head.">" !!} 
                        @foreach($tr as $tri=>$tds)
                            <tr>
                            @foreach($tds as $tdi=>$td)
                            {!! "<".$col_arr[$head].">" !!}  {{  $td }} {!! "</".$col_arr[$head].">" !!} 
                            @endforeach
                            </tr>
                        @endforeach
                        {!! "</".$head.">" !!} 
                    @endforeach
                </table>
            </div>
        @endif
    </div>
</div>

