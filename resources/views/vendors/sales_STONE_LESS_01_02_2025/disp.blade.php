@php 

//$bill_count = $salebills->count();
$bill_count = 0;
@endphp
@if($salebills->count() > 0)
    @foreach($salebills as $key=>$row)
        <tr class="text-center">
            <td>{{ @$salebills->firstItem() + $key }}</td>
            <td>
                {{ @$row->custo_name }}
                <hr class="m-1">
                <a href="{{ route('sells.info',['custo'=>$row->customer->id]) }}" class="view_custo detail_info" data-head="Customer Detail">{{ @$row->custo_mobile }}</a>
            </td>
            <td class="text-center">
                <a href="{{ route("sells.show",$row->id) }}" class="view_bill detail_info">{{ @$row->bill_no }}</a>
                <hr class="m-1">
                {{ @$row->bill_date }}
            </td>
            <td class="text-center">{{ @$row->items->count() }}</td>
            <td>{{ $row->sub_total }}</td>
            <td>
                @if($row->gst_apply==1)
                    @php 
                        $gst = json_decode($row->gst,true);
                    @endphp
                        <h6> {{ $gst['val']." % = ".$gst['amnt'] }}Rs.</h6>
                        <hr class="m-1">
                        <ul class="gst_list">
                            @if($row->bill_state == $row->custo_state)
                            @php 
                                $sgst = json_decode($row->sgst,true);    
                                $cgst = json_decode($row->cgst,true);
                            @endphp
                            <li><b>SGST({{ $sgst['val'] }})%-</b>{{ $sgst['amnt'] }} Rs.</li>
                            <li><b>CGST({{ $cgst['val'] }})%-</b>{{ $cgst['amnt'] }} Rs.</li>
                            @else
                                @php 
                                $igst = json_decode($row->igst,true);
                                @endphp
                        <li><b>IGST({{ $igst['val'] }})-</b>{{ $igst['amnt'] }} %</li>
                        @endif
                    </ul>

                @else 
                    NA
                @endif
            </td>
            <td>{{ $row->discount }} %</td>
            <td>{{ $row->total }} Rs.</td>
            <td>
                <a href="{{ route('sells.info',['pay'=>$row->id]) }}" class="view_pays detail_info" data-head="Payment Detail">{{ $row->payment }} Rs.</a></td>
            <td>{{ @$row->remains }}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success editButton" href="{{route('sells.edit', $row->id)}}">Edit</a>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('sells.destroy',$row->id) }}">
                    Delete
                </button>

                </div>
            </td>

        </tr>

    @endforeach
@else 
    <tr><td colspan="11s" class="text-center text-danger">No Bills !</td></tr>
@endif
    


