
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr>
            <th >S.N.</th>
            <th >Customer Name</th>
            <th >Assign ID</th>
            <th >Schemes</th>
            <th > Group </th>
            <th > EMI Amount </th>
            <th >Token Amt</th>
            <th >Enroll Date</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($enrollcustomers as $key=>$row)

        <tr class="{{ (@$row->schemes->ss_status=='0')?'table-disabled':'' }}">
            <td>{{ $enrollcustomers->firstItem() + $key }}</td>
            <td>{{ $row->customer_name}} </td>
            <td>{{ $row->assign_id }} </td>
            <td>{{ @$row->schemes->scheme_head}} </td>
            <td>{{ @$row->groups->group_name}} </td>
            <td><b>{{ $row->emi_amnt}}</b> </td>
            <td>{{ $row->token_amt}} </td>
            <td>{{ date('d-M-Y',strtotime($row->created_at))}} </td>
            <td> 
				<a class="btn btn-outline-success editButton" href="{{ route('enrollcustomer.edit',$row->id)}}">Edit</a>
				 <a href="{{ route('enrollcustomer.destroy',$row->id) }}" class="btn btn-danger enroll_custo_delete" >Delete</a>
			</td>
        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $enrollcustomers,'type'=>1])

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


