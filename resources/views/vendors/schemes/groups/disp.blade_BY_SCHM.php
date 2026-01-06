<div class="table-responsives">
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr>
            <th >S.N.</th>
            <th >Scheme Name</th>
            <th >Group Name</th>
            <th >Enrollment</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>
		@php 
                $currentPage = $scheme_groups->currentPage();
                $sn = ($scheme_groups->perPage()*($currentPage-1))+1;
		@endphp
    @foreach($scheme_groups as $key=>$row)
	{{--<tr>
            <td>
                <h5>{{ $sn++  }}</h5>
            </td>
            <td colspan="3" class="text-center" style="font-size:15px;"><h6><i><u>SCHEME</u> :</i> {{ $row->scheme_head}}</h6></td>
        </tr>--}}
        @if($row->mygroups->count()>0)
            @php  $num = 1; @endphp 
            @foreach($row->mygroups as $gk=>$groups)
            <tr>
				
                <td class="text-center">{{ $sn++ }}</td>
				<td>{{ $row->scheme_head}}</td>
                <td>{{ $groups->group_name}} </td>
                <td class="text-center">
                    {!! '<b style="font-size:15px;">'.$groups->members->count().'</b>'." / ".$groups->group_limit !!}
                </td>
                <td class="text-center"> <div class="">
                    <a class="btn btn-outline-success editButton" href="{{route('group.edit', $groups->id)}}">Edit</a>
                    
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('group.destroy',$groups->id) }}">
                        Delete
                    </button>

                    </div>
                </td>
            </tr>
            @php  $num++  @endphp
            @endforeach
        @endif

    @endforeach

    </tbody>
  </table>
</div>

<div class="col-12">
  @include('layouts.theme.datatable.pagination', ['paginator' => $scheme_groups,'type'=>1])
</div>



