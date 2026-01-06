@php 

//$bill_count = $salebills->count();
$bill_count = 0;
$person_type = ['c'=>'Customer','s'=>'Supplier'];
$source_type = ['s'=>'Sell','p'=>'Purchase','jb'=>'Just Bill'];
@endphp
@if($report->count() > 0)
    @foreach($report as $key=>$row)
        <tr class="text-center">
            <td>{{ $report->firstItem() + $key }}</td>
            <td>{{ date('d-M-Y h:i:a',strtotime($row->created_at)) }}</td>
            <td>
                <b>{{ $source_type["{$row->source}"] }}</b>
                <hr class="m-1">
                <a href="{{ route("gst.info",['source',$row->source,$row->source_id]) }}" class="detail_info" data-head="Source Detail">{{ $row->source_no }}</a>
            </td>
            <td >
                {{ $row->base_amnt }} Rs/-
            </td>
			@php  
                $class = ($row->action_taken=='D' || $row->amnt_status=='N')?'warning':(($row->amnt_status==1)?'success':'danger');
            @endphp
            <td class="text-{{ $class }}">
                {{ $row->gst_amnt }} Rs/-
                <hr class="m-1 border-{{ $class }}">
                {{ $row->gst  }} %
            </td>
            <td>
                 <ul class="gst_block">
                    <li><b>SGST({{ $row->sgst }}%)</b><span>{{ $row->sgst_amnt??'-' }}Rs</span></li>
                    <li><b>CGST({{ $row->cgst }}%)</b><span>{{ $row->cgst_amnt??'-'  }}Rs</span></li>
                    <li><b>IGST({{ $row->igst }}%)</b><span>{{ $row->igst_amnt??'-'  }}Rs</span></li>
                </ul>
            </td>
            <td>
                {{ $row->person_name }}
                <hr class="m-1">
                <a href="{{ route("gst.info",['person',$row->person_type,$row->person_id]) }}" class="detail_info" data-head="Person Detail">{{ $row->person_contact }}</a>
                <hr class="m-1">
                <b>( {{ $person_type["{$row->person_type}"] }} )</b>
            </td>
        </tr>

    @endforeach
@else 
    <tr><td colspan="8" class="text-center text-danger">No Bills !</td></tr>
@endif
    

@include('layouts.vendors.js.passwork-popup')

