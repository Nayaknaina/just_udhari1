@if($items->count()>0)
    @foreach($items as $key=>$item)
        <tr>
            <td>{{ $items->firstItem() +  $key }}</td>
            <td>
                <b>{{ $item->category }}</b>
                <hr class="m-1">
                {{ $item->detail }}
            </td>
            @php 
                $item_prop = json_decode($item->property,true);
                if(!empty($item_prop)){
                    $gross = $item_prop['gross'];
                    $net = $item_prop['net'];
                    $fine = $item_prop['fine'];
                }
            @endphp
            @if(in_array($item->category,['Gold','Silver']))

            <td>
                <ul>
                    <li><b>GROSS : </b><span>{{ $gross }} Gm</span></li>
                    <li><b>NET - </b><span>{{ $net }} Gm</span></li>
                    <li><b>FINE - </b><span>{{ $fine }} Gm</span></li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><b>PURITY : </b><span>{{ $item_prop['pure'] }} %</span></li>
                </ul>
            </td>
            @else
            <td>--</td>
            <td>--</td>
            @endif
            
            <td>
                @if($item->flip)
                {{ $item->activeflip->post_p }} Rs
                <hr class="m-1">
                <small><strike>{{ $item->principal }} Rs</strike></small>
                @else 
                {{ $item->principal }} Rs
                @endif
            </td>
            <td>
                @if($item->flip)
                {{ $item->activeflip->post_i }} Rs
                <hr class="m-1">
                <small><strike>{{ $item->interest }} Rs</strike></small>
                @else 
                {{ $item->interest }} Rs
                @endif
            </td>
            <td>
                {{ date('d-M-Y',strtotime($item->batch->girvy_issue_date)) }}
            </td>
            <td>
                {{ date('d-M-Y',strtotime($item->batch->girvy_return_date)) }}
            </td>
            <td>
                @php 
                    $item_status = ($item->status==0)?'PAID':"UNPAID";
                @endphp
                <span class="badge badge-{{ ($item->status==0)?'success':'danger' }}">{{ $item_status }}</span>
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i> Note</a>
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle btn-sm border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-weight:bold;">
                        &#8942;
                    </button>
                    <div class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item text-info" href="{{ route('girvi.item.txn',$item->id) }}"><i class="fa fa-eye"></i> Txns</a>
                        <a class="dropdown-item text-danger" href="{{ route('girvi.destroy',['item',$item->id]) }}"><i class="fa fa-times"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else 
<tr><td colspan="11" class="text-center text-danger">No Girvi Items !</td></tr>
@endif