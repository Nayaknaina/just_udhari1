<tbody>
@if($data->count()>0)
    @php  $items = $data->items()  @endphp
    @for($i=0;$i<count($items);$i++)
        @php
            $current = $items[$i];         // today's row
            $prevDay = $items[$i + 1]?? null;
            if ($prevDay) {
                $current->gold_open        = $prevDay->gold_net;
                $current->silver_open      = $prevDay->silver_net;
                $current->stone_open       = $prevDay->stone_count;
                $current->artificial_open  = $prevDay->artificial_count;
                $current->money_open       = $prevDay->money_val;
            } else {
                $current->gold_open        = 0;
                $current->silver_open      = 0;
                $current->stone_open       = 0;
                $current->artificial_open  = 0;
                $current->money_open       = 0;
            }
            $is_today = ($items[$i]->entry_date == date('Y-m-d',strtotime('now')))?true:false;
        @endphp
    <tr>
        <td>{{ $data->firstitem() + $i }}</td>
        <td>{{ number_format($current->gold_open,3) }}</td>
        @if(!$is_today)
            <td>{{ number_format($items[$i]->gold_net,3) }}</td>
        @else 
            <td>--</td>
        @endif
            <td>{{ number_format($current->silver_open,3) }}</td>
        @if(!$is_today)
        <td>{{ number_format($items[$i]->silver_net,3) }}</td>
        @else 
            <td>--</td>
        @endif
        <td>{{ $current->stone_open }}</td>
        @if(!$is_today)
            <td>{{ $items[$i]->stone_count }}</td>
        @else 
           <td>--</td>
        @endif
        <td>{{ $current->artificial_open }}</td>
        @if(!$is_today)
            <td>{{ $items[$i]->artificial_count }}</td>
        @else 
            <td>--</td>
        @endif
        <td>{{ number_format($current->money_open,2) }}</td>
        @if(!$is_today)
            <td>{{ number_format($items[$i]->money_val,2) }}</td>
        @else 
            <td>--</td>
        @endif
        <td>{{ $items[$i]->entry_date }}</td>
        <td class="text-center">
            <a href="{{ route('shop.detail',$items[$i]->entry_date) }}" class="btn btn-outline-secondary p-0 px-1"><i class="fa fa-eye"></i></a>
        </td>
    </tr>
    @endfor
@else 
<tr>
    <td colspan="" class="text-center text-danger">No Record !</td>
</tr>
@endif
</tbody>
