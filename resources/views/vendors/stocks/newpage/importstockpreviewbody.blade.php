@if($stocks->count() > 0)
<tbody id="data_area" class="data_area">
    @php 
        $unit_arr = ['r'=>'Rs','p'=>'%','w'=>'gm'];
    @endphp
    @foreach($stocks as $stki=>$stock)
        <tr>
            <td>{{ $stock->name }}</td>
            <td>{{ $stock->tag }}</td>
            <td>{{ $stock->huid }}</td>
            <td>{{ $stock->caret }}</td>
            <td>{{ $stock->gross }}</td>
            <td>{{ $stock->less }}</td>
            <td>{{ $stock->net }}</td>
            <td>{{ $stock->tunch }}</td>
            <td>{{ $stock->wastage }}</td>
            <td>{{ $stock->fine }}</td>
            <td>{{ $stock->element_charge }}</td>
            <td>
				@if($stock->labour)
                 {{ $stock->labour }}{{ @$unit_arr[$stock->labour_unit] }}
                @endif
			</td>
            <td>{{ $stock->chrg }}</td>
            <td>{{ $stock->rate }}</td>
            <td>
				@if($stock->discount)
                {{ $stock->discount }}{{ @$unit_arr[$stock->discount_unit] }}
                @endif
			</td>
            <td>{{ $stock->total }}</td>
        </tr>
    @endforeach
</tbody>
<tfoot class="data_area">
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</tfoot>
@else 

@endif