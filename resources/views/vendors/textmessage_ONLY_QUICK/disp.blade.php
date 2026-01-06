@if(!empty($tamplates) && $tamplates->count()>0)
    @foreach($tamplates as $tk=>$tmplt)
        <tr>
            <td class="text-center">sn</td>
            <td class="text-center">{{ $tmplt->head }}</td>
            <td class="text-center">{{ $tmplt->body }}</td>
            <td class="text-center">{{ $tmplt->variables }}</td>
            <td class="text-center">{{ $tmplt->detail??"----" }}</td>
            <td class="text-center" >
                @php 
                    $status_arr = ['off','on'];
                @endphp
                    <a href="{{ route('textmsgeapi.show',[$tmplt->id,'status'=>'tamplate'])}}" class="socket"><span class="switch {{ $status_arr[$tmplt->status] }}"></span></a>
            </td>
            <td class="text-center">
                <a href="{{ route('textmsgeapi.edit',[$tmplt->id,"edit"=>'tamplate']) }}" class="btn btn-outline-info btn-sm editButton m-1" ><li class="fa fa-edit"></li></a>
                
                <button type="button" class="btn btn-outline-danger m-1" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('textmsgeapi.destroy',$tmplt->id) }}">
                    <li class="fa fa-times"></li>
                </button> 
            </td>
        </tr>
    @endforeach
@else 
    <tr><td colspan="7" class="text-center text-danger">No Tamplates !</td></tr>
@endif
  