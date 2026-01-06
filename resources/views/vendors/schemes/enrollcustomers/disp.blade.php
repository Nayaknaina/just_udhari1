
<table id="CsTable" class="table table_theme table-striped text-nowrap table-bordered align-middle dataTable">
    <thead class = "">
        <tr>
            <th >S.N.</th>
            <th >Entry</th>
            <th >Customer Name</th>
            <th >Assign ID</th>
            <th >Schemes</th>
            <th > Group </th>
            <th > EMI Amount </th>
            <th >Token Amt</th>
            <th >Enroll Date</th>
            <th >Action</th>
            <th >Withdraw</th>
        </tr>
    </thead>
    <tbody>
	@php 
        $currentPage = $enrollcustomers->currentPage();
    @endphp
    @foreach($enrollcustomers as $key=>$row)

        <tr class="{{ (@$row->schemes->ss_status=='0')?'table-disabled':'' }}">
            <td>{{ $enrollcustomers->firstitem() + $key }}</td>
			<td>{{ date('d-M-Y H:i:s',strtotime($row->created_at)) }}</td>
            <td class="text-center">
                {{ $row->customer_name}} 
                @if($row->customer_name != @$row->info->custo_full_name)
                    <hr class='m-1 p-0'>
                    ( {{ @$row->info->custo_full_name }} )
                @endif
                <hr class='m-1 p-0'>
                <b>{{ @$row->info->custo_fone }}</b>
                @if(@$row->info->custo_mail!="")
                <hr class='m-1 p-0'>
                {{ @$row->info->custo_mail }}
                @endif

            </td>
            <td>{{ $row->assign_id }} </td>
            <td>{{ @$row->schemes->scheme_head}} </td>
            <td>{{ @$row->groups->group_name}} </td>
            <td class="text-right"><b>{{ $row->emi_amnt??'0' }} Rs.</b> </td>
           <td class="text-center">{{ $row->token_amt??'0' }} Rs.{!! ($row->pay_medium!="")?'<hr class="m-1">'.$row->pay_medium:"" !!} </td>
            <td class="text-center"><b>{{ ($row->entry_at!="")?date('d-M-Y',strtotime($row->entry_at)):"=" }}</a></td>
            <td> 
                <div class="">
                    <a class="btn btn-outline-info editButton m-1" href="{{ route('enrollcustomer.edit',$row->id)}}"><li class="fa fa-edit"></li></a>
                    <!-- <form action="{{ route('enrollcustomer.destroy',$row->id)}}" >
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger enroll_custo_delete" >
                        Delete
                    </button> -->
                    </form>
                    <a href="{{ route('enrollcustomer.destroy',$row->id) }}" class="btn btn-danger enroll_custo_delete m-1" ><li class="fa fa-times"></li></a>
                    <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('enrollcustomer.destroy',$row->id)}}">
                        Delete
                    </button> -->

                </div>
             </td>
			 <td>
                @php 
                    $withdraw = ($row->is_withdraw)?'active':'';
                @endphp
                <a href="{{ route('enrolls.withdraw',$row->id) }}" class="m-auto withdraw_btn {{ $withdraw }}" onclick="withdrawcustomer();"></a>
             </td>
        </tr>

    @endforeach

    </tbody>
  </table>
<div class="card-footer bg-white py-0">
  @include('layouts.theme.datatable.pagination', ['paginator' => $enrollcustomers,'type'=>1])
</div>

    

 


