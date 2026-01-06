@if($items->count() > 0)
    <tbody class="data_area">
    @foreach($items as $ik=>$item)
        <tr class="text-center">
            <td >{{ $items->firstitem() + $ik }}</td>
            <td>
                {{ @$item->item_name }}
            </td>
            <td>
                {{ @$item->itemgroup->item_group_name }}
                <hr class="m-0 my-1 p-0">
                <small><i><b>{{ $item->itemgroup->cat_name??'--' }} / {{ $item->itemgroup->coll_name??'--' }}</b></i></small>
            </td>
            @php 
                $unit_arr = ['p'=>'%','r'=>'Rs','w'=>'Rs/Gm'];
            @endphp
            <td>
                {{ @$item->hsn_code }}
                @if(@$item->tax_value)
                <hr class="m-0 my-1 p-0">
                <small><i><b>TAX : {{ @$item->tax_value }}{{ @$unit_arr[@$item->tax_unit] }}</b></i></small>
                @endif
            </td>
            <td>
                {{ @$item->stock_method  }}
                @if(@$item->stock_method=='tag')
                <hr class="m-0 my-1 p-0">
                <small><b>{{@$item->tag_prefix??'-' }} | {{ (@$item->tag_digit)?@$item->tag_digit." Digits":'-' }}</b></small>
                @endif
            </td>
            <td>
                <ul style="list-style:none;padding:0;">
                    <li>KARET : <b>{{ $item->karet??'--' }}</b></li>
                    <li>TOUNCH : <b>{{ $item->tounch??'--' }}</b></li>
                </ul>
            </td>
            <td>
                <ul style="list-style:none;padding:0;">
                    <li>WASTAGE : <b>{{ $item->wastage??'--' }}</b></li>
                    <li>LABOUR : <b>{{ @$item->labour_value??'--' }}@if(@$item->labour_value && @$item->labour_unit){{ @$unit_arr[@$item->labour_unit] }}@endif</b></li>
                </ul>
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-outline-dark dropdown-toggle p-0 px-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &vellip;
                    </button>
                    <div class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton" style="min-width:auto;">
                        <a class="dropdown-item text-info edit_btn" href="{{ route('stock.edit.itemgroup',['item',$item->id]) }}"><i class="fa fa-edit"></i> Edit</a>
                        <a class="dropdown-item text-danger delete_btn" href="{{ route('stock.delete.itemgroup',['item',$item->id]) }}"><i class="fa fa-times"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
@endif