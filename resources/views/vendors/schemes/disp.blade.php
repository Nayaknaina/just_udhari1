
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr>
            <th >S.N.</th>
            <th >Name</th>
            <th >Image</th>
            <th >Detail</th>
            <th >Validity</th>
            <th >Dates</th>
            <th >EMI</th>
            <th >Interest Grant</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>

    @php 
        $currentPage = $schemes->currentPage();
        $sn = ($schemes->perPage()*($currentPage-1))+1;
    @endphp
    @foreach($schemes as $key=>$row)
        <tr>
            <td>{{ $sn++ }}</td>
            <td>{{ $row->scheme_head ?? $row->schemes->scheme_head }}
                <hr class="m-1">{{ $row->scheme_sub_head ?? $row->schemes->scheme_sub_head }}</td>
            <td class="text-center">
                @if($row->scheme_img!="")
                <img src="{{ asset($row->scheme_img)}}" class="img-fluid img-thumbnail" style="width:200px;">
                @else
                NA
                @endif
            </td>
            <td class="text-center">
                <a class="btn btn-sm btn-outline-warning scheme_detail_view" href="{{ route('shopscheme.show',$row->id) }}">View ? </a>
            </td>
            <td> {{ ($row->scheme_validity!="")?"{$row->scheme_validity} Month":"NA"; }} </td>
            <td>
                <span><b>E-Comm Launch : </b>{{ ($row->launch_date)?date('d-M-Y',strtotime($row->launch_date)):'NA' }}</span>
                <hr class="m-1" style="border-top:1px solid lightgray;">
                <span><b>Scheme Start : </b>{!! ($row->scheme_date_fix=='1')?(($row->scheme_date)?date('d-M-Y',strtotime($row->scheme_date)):'NA'):"<b>Enrolled</b>" !!}</span>
            </td>
            <td> 
                {{ ($row->emi_range_start != $row->emi_range_end)?"{$row->emi_range_start} - {$row->emi_range_end}":"{$row->emi_range_start}" }} Rs. 
            </td>
            @php 
                $interest = $row->scheme_interest;
                if(($row->emi_range_start == $row->emi_range_end) && $row->scheme_interest=="Yes"){
                    $interest = ($row->interest_type=='per')?($row->emi_range_start*$row->interest_rate)/100:$row->interest_amt;
                    $interest.=" Rs.";
                }
                $post_text = ($row->scheme_interest=="Yes")?(($row->interest_type=='per')?"<br>{$row->interest_rate}% of EMI":"<br>$row->interest_amt Rs. on EMI"):'';
            @endphp
            <td> {{ @$interest }}   <small class="text-secondary"><b>{!! $post_text !!}</b></small></td>
            
            <td>
                @if($row->ss_status == 1)
                @php 
                    $today = date("Y-m-d",strtotime('now'));
                    $full_start_date = ($row->scheme_date_fix=='1')?$row->scheme_date:$row->alt_start_date;
                    $start_date = ($full_start_date!="")?date("Y-m-d",strtotime("{$full_start_date}")):null;
                    $end_date = ($start_date!="")?date("Y-m-d",strtotime("{$start_date}+{$row->scheme_validity} Month")):false;
                    $initiated = ($start_date!="")?(($start_date<=$today)?true:false):null;
                @endphp
                <div class="d-flex justify-content-between">
                    @if($end_date && $end_date <$today)
                    <a class="btn btn-outline-success" href="{{route('shopscheme.edit', $row->id)}}">Relaunch</a>
                    @elseif($initiated)
                    <b class="badge badge-warning text-center">Initiated</b>
                    @else
                    <a class="btn btn-outline-warning" href="{{route('shopscheme.edit', $row->id)}}">Customise</a>
                    @endif
                </div>
                @else
                    <b class="badge badge-danger text-center">EXPIRED</b>
                @endif
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>
<div class="col-12">
  @include('layouts.theme.datatable.pagination', ['paginator' => $schemes,'type'=>1])
</div>
    <div class="modal" tabindex="-1" id="scheme_detail_model">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SCHEME DETAIL</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal" aria-label="Close" onclick="$('#scheme_detail_model').modal('hide');">&cross;</button>
            </div>
            <div class="modal-body p-0" id="scheme_detail_model_body">
                <p>Nothing Here !</p>
            </div>
            </div>
        </div>
    </div>

 <script>
    $('.scheme_detail_view').click(function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        $("#scheme_detail_model_body").empty().load(url);
        $("#scheme_detail_model").modal();
    });
</script>


