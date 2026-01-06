
<table id="CsTable" class="table table-bordered table-hover">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Name</th>
        <th style="border:1px solid;width:10%" class="text-center">Scheme Amt</th>
        <th style="border:1px solid;width:10%">Validity</th>
        <th style="border:1px solid;width:10%"> EMI</th>
        <th style="border:1px solid;width:10%">Interest Grant</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>
    @if(count($schemes)>0)
    @foreach($schemes as $key=>$row)
        <tr>
            <td>{{ $schemes->firstItem() + $key }}</td>
            <td>{{$row->scheme_head }}<hr class="m-1">{{$row->scheme_sub_head }}</td>
            <td>{{$row->scheme_amount}} </td>
            <td>{{$row->scheme_validity}} Months</td>
            <td> {{ $row->scheme_emi }} </td>
            <td>{{$row->scheme_interest_value }}
            </td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('schemes.edit', $row->id)}}">Edit</a>
                <form action="{{ route('schemes.destroy',$row->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Category post?')">Delete</i></button>
                </form>
                <a class="btn btn-sm btn-outline-warning scheme_detail_view" href="{{ route('schemes.show',$row->id) }}">View </a>
                </div>
            </td>

        </tr>

    @endforeach
    @else
    <tr><td colspan="8" class="text-danger text-center">No Schemes Yet !</td></tr>
    @endif

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

