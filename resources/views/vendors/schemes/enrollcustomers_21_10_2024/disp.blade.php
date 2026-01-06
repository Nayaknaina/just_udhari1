
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
            <td class="text-right"><b>{{ $row->emi_amnt}}</b> </td>
            <td class="text-right">{{ $row->token_amt}} </td>
            <td>{{ date('d-M-Y',strtotime($row->created_at))}} </td>
            <td> 
                <div class="">
                    <a class="btn btn-outline-success editButton" href="{{ route('enrollcustomer.edit',$row->id)}}">Edit</a>
                    <!-- <form action="{{ route('enrollcustomer.destroy',$row->id)}}" >
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger enroll_custo_delete" >
                        Delete
                    </button> -->
                    </form>
                    <a href="{{ route('enrollcustomer.destroy',$row->id) }}" class="btn btn-danger enroll_custo_delete" >Delete</a>
                    <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('enrollcustomer.destroy',$row->id)}}">
                        Delete
                    </button> -->

                </div>
             </td>
        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $enrollcustomers,'type'=>1])

    

 


