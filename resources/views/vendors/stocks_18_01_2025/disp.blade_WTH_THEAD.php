
<style>
    .bill_show{
        color:blue;
        border:1px solid lightgray;
        padding:1px 2px;
    }
    .bill_show:hover{
        color:black;            
    }
    td.grms{
        position:relative;
    }
    td.grms:before{
        position:absolute;
        content:'grm';
        right:15px;
        color:gray;
    }
    td.active.grms:before{
        color:blue;
    }
    input.false{
        font-weight:bold;
        color:blue;
    }
</style>
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> 
        <th>S.N.</th>
        <th>Bill</th>
        <th>Name</th>
        <th>{{ ($metal =='artificial')?'Sell ':'' }}Rate</th>
        @if($metal =='artificial')
        <th>Quantity</th>
        @else 
        <th>Quantity/Weight</th>
        <th>Purity</th>
        @endif
        <th>Amount</th>
        <th>Place</th>
        <th>&check;</th>
        </tr>
    </thead>
    <tbody>

    @foreach($stocks as $key=>$row)
        <tr>
            <td>
                {{ $stocks->firstItem() + $key }}
            </td>
            <td><a href="{{ route('purchases.show',$row->purchase_id) }}" class="bill_show">{{ $row->purchase->bill_no }}</a></td>
            <td>
                {{$row->product_name }}
                <hr class="m-1">
                <b><i>{{ ucfirst($row->item_type) }}</i></b>
            </td>
            <td class="text-right">
                {{$row->rate}} Rs.
            </td>
            <td class="text-center">
                @php 
                    $qnt_unit = ($row->unit=='grms')?'grms':'';
                @endphp
                <b>INITIAL : </b>{{$row->quantity}} {{ $qnt_unit }}
                <hr class="m-1">
                <b>AVAILABLE : </b>{{$row->available}} {{ $qnt_unit }}
                <hr class="m-1">
                <b>COUNTER : </b>{{$row->counter}} {{ $qnt_unit }}
            </td>
            @if($metal !='artificial')
            <td>
                @php 
                    $property = json_decode($row->property,true);
                    //print_r($property);
                @endphp
                @if($row->item_type!="artificial")
                <b>PURITY : </b>{{ $property['purity'] }}
                <hr class="m-1">
                <b>FINE : </b>{{ $property['fine_purity'] }}
                @else 
                    <b>----</b>
                @endif
            </td>
            @endif
            <td  class="text-right">{{ $row->amount }} Rs.</td>
            <td class="{{ ($row->item_type!='artificial')?'grms':'Count' }} text-center">
                @php 
                    $to_place = $row->available - $row->counterplaced->sum('stock_avail');
                @endphp
                @if($to_place>0)
                <input type="hidden" name="item_type[]" value="{{ $row->item_type }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                <input type="hidden" name="to_place[]" value="{{ $to_place }}">
                <input type="text" name="quantity[]" value="{{ $to_place }}" class="form-control text-center true" placeholder="{{ ($row->item_type!='artificial')?'Grms':'Count' }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                @else 
                    {{ $to_place }}
                @endif
            </td>
            <td> 
                <label for="stock_{{ $stocks->firstItem() + $key }}" class="form-control text-success" style="width:fit-content;">
                        @if($to_place > 0 )
                        <input type="checkbox" id="stock_{{ $stocks->firstItem() + $key }}" name="stock[]" value="{{ $row->id }}" class="stock_check">
                        @else 
                            &check; 
                        @endif
                </label>
            </td>
        </tr>

    @endforeach

    </tbody>
  </table>
  @include('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])
  <script>
    $('.bill_show').click(function(e){
        e.preventDefault();
        $("#bill_modal_body").empty().load($(this).attr('href'));
        $("#bill_modal").modal();
        // $.get($(this).attr('href'),"",function(response){
        //     $("#bill_modal_body").empty().append(response);
        // });
    });
</script>
