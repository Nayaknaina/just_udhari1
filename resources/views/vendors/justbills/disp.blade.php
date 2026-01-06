@php 

//$bill_count = $salebills->count();
$bill_count = 0;
@endphp
@if($justbills->count() > 0)
    @foreach($justbills as $key=>$row)
        <tr class="text-center">
            <td>{{ $justbills->firstItem() + $key }}</td>
            <td>
                {{ $row->custo_name }}
                <hr class="m-1">
                {{ $row->custo_mobile }}
            </td>
            <td class="text-center">
                <a href="{{ route("bills.show",$row->id) }}" class="view_bill detail_info">{{ @$row->bill_no }}</a>
                <hr class="m-1">
                {{ @$row->bill_date }}
            </td>
            <td class="text-center">{{ @$row->items->count() }}</td>
            <td>{{ $row->sub }} Rs</td>
            <td>{{ $row->discount }} %</td>
            <td>
                @php 
                    $gst = json_decode($row->gst,true);
                @endphp
                <label class="m-0">GST ({{ $gst['val'] }}) % : <b>{{ $gst['amnt'] }}</b></label>
                <hr class="m-1 p-0">
                <ul class="p-0" style="list-style:none;">
                @if($row->igst!=0)
                    <li>
                        @php 
                            $igst = json_decode($row->igst,true);
                        @endphp
                        IGST ({{ $igst['val'] }}) :  <b>{{ $igst['amnt'] }}</b>
                    </li>
                @endif
                @if($row->cgst!=0)
                    <li>
                        @php 
                            $cgst = json_decode($row->cgst,true);
                        @endphp
                        CGST ({{ $cgst['val'] }}) :  <b>{{ $cgst['amnt'] }}</b>
                    </li>
                @endif
                @if($row->sgst!=0)
                    <li>
                        @php 
                            $sgst = json_decode($row->sgst,true);
                        @endphp
                        SGST ({{ $sgst['val'] }}) :  <b>{{ $sgst['amnt'] }}</b>
                    </li>
                @endif
                </ul>
            </td>
            <td>{{ $row->total }} Rs.</td>
            <td>
                <div class="d-flex justify-content-between">

               <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('bills.destroy',$row->id) }}">
                    Delete
                </button>

                </div>
            </td>

        </tr>

    @endforeach
@else 
    <tr><td colspan="11s" class="text-center text-danger">No Bills !</td></tr>
@endif
    

@include('layouts.vendors.js.passwork-popup')

