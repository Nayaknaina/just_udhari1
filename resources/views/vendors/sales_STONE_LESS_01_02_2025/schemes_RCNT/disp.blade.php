
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr>
            <th >S.N.</th>
            <th >Name</th>
            <th >Image</th>
            <th >Detail</th>
            <th >Validity</th>
            <th >EMI</th>
            <th >Interest Grant</th>
            <th >Role</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($schemes as $key=>$row)

        <tr>
            <td>{{ $schemes->firstItem() + $key }}</td>
            <td>{{ $row->scheme_head??$row->schemes->scheme_head }}<hr class="m-1">{{ $row->scheme_sub_head??$row->schemes->scheme_sub_head }}</td>
            <td class="text-center">
                @if($row->scheme_img!="")
                <img src="{{ asset($row->scheme_img)}}" class="img-fluid img-thumbnail" style="width:200px;">
                @else
                NA
                @endif
            </td>
            @php
                $valid_scale_array = ["d"=>'Day','m'=>'Month','y'=>'Year'];
            @endphp
            <td class="text-center">
                <a class="btn btn-sm btn-outline-warning scheme_detail_view" href="{{ route('shopscheme.show',$row->id) }}">View ? </a>
            </td>
            <td>{{$row->schemes->scheme_validity." ".$valid_scale_array["{$row->schemes->scheme_validity_scale}"] }}</td>
            <td> {{ $row->schemes->scheme_emi }} Rs. </td>
            <td>
                @if($row->schemes->scheme_interest == 1)
                @php
                $interest_scale_array = ["perc"=>'%','amnt'=>'Rs.'];
                @endphp
                {{$row->scheme_interest_value." ".$interest_scale_array["{$row->schemes->scheme_interest_scale}"] }}
                @else
                    {{ "NA" }}
                @endif
            </td>
            <td>{{@$row->roles->name}}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('shopscheme.edit', $row->id)}}">Edit</a>
                <!-- <form action="{{ route('schemes.destroy',$row->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Category post?')">Delete</i></button>
                </form>  -->
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $schemes,'type'=>1])

    <div class="modal" tabindex="-1" id="scheme_detail_model">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SCHEME DETAIL</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal" aria-label="Close" onclick="$('#scheme_detail_model').modal('hide');">&cross;</button>
            </div>
            <div class="modal-body p-0" id="scheme_detail_model_body">
                <p>Nothing Here !</p>
            </div>
            </div>
        </div>
    </div>

 <script>
    $('.scheme_detail_view').click(function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        $("#scheme_detail_model_body").empty().load(url);
        $("#scheme_detail_model").modal();
    });
</script>


