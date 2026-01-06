@if($bills->count()>0)
	@php $fnl_total = $fnl_payment = $fnl_balance = 0; @endphp
    <tbody class="data_area" id="data_area">
        @foreach($bills as $bk=>$bill)
            <tr class="text-center @if($bill->status!='c'){{ ($bill->status=='d')?'hard':'soft' }}-delete @endif">
                <td>{{ $bills->firstitem() + $bk }}</td>
                <td>
					<ul style="list-style:none;">
						<li><b>BILL : </b>{{ date('d-m-Y',strtotime($bill->bill_date)) }}</li>
						<li><b>DUE : </b>{{ date('d-m-Y',strtotime($bill->due_date)) }}</li>
					</ul>
                </td>
                <td>
                    {{ $bill->bill_number }}
                </td>
                <td>
                    @php 
                        $type_arr = ['e'=>'Rought Estimate','g'=>'GST']
                    @endphp
                    {{ @$type_arr["{$bill->prop}"]  }}
                </td>
                <td>
                    {{ $bill->party_name }}
                    <hr class="m-0">
                    {{ $bill->party_mobile }}
                </td>
                <td class="text-center">
                    {{ $bill->final }}
                </td>
				
                <td class="{{ ($bill->payment < $bill->final)?'text-danger':'text-success' }}">
                    {{ $bill->payment }}
                </td>
                <td class="{{ ($bill->payment > $bill->total)?'text-danger':'text-success' }}">
                    {{ $bill->balance }}
				</td>
				<td>
				{{ date('d-m-Y H:i:a',strtotime($bill->updated_at)) }}
				</td>
				{{--<td>
					<a href="{{ route('billing.view',['sale',$bill->bill_number]) }}" class="btn btn-outline-dark btn-sm py-0 px-1"><i class="fa fa-eye"></i></a>
				</td--}}
                <td class="text-center">
					@if($bill->status=='c')
                    <div class="dropdown" id="dropdown_{{ $bk }}">
                        <button class="btn btn-outline-dark dropdown-toggle p-0 px-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            &#8942;
                        </button>
                        <div class="dropdown-menu border-light border-dark" aria-labelledby="dropdownMenuButton" style="min-width: inherit;box-shadow:1px 2px 3px lightgray;">
                            <a class="dropdown-item text-dark bill_view" id="bill_view" href="{{ route('billing.view',['sale',$bill->bill_number]) }}"><i class="fa fa-eye"></i> View</a>
                            <a class="dropdown-item text-info bill_edit" id="bill_edit"  href="{{ route('billing.edit',['sale',$bill->bill_number]) }}" data-redirect="true" data-mpin-check="true"><i class="fa fa-edit"></i> Edit</a>
							<a class="dropdown-item text-danger bill_delete" id="bill_delete"  href="{{ route('billing.delete',['sale',$bill->bill_number,'done']) }}"><i class="fa fa-times"></i> Delete</a>
							
                        </div>
                    </div>
					@else
						<a class="dropdown-item text-dark bill_view btn btn-outline-dark"  id="bill_view" href="{{ route('billing.view',['sale',$bill->bill_number]) }}" ><i class="fa fa-eye"></i></a>
					@endif
                </td>
				@php 
					$fnl_total += $bill->final;
					$fnl_payment += $bill->payment;
					$fnl_balance +=	$bill->balance;		
				@endphp
            </tr>
        @endforeach
    </tbody>
    <tfoot class="data_area">
        <tr class="text-center">
            <td colspan="5">
				
            </td>
            <td>
                <label class="form-control mb-0 text-info">{{ $fnl_total }}</label>
            </td>
            <td>
                <label class="form-control mb-0 text-success">{{ $fnl_payment }}</label>
            </td>
            <td>
				@php $td_class = ($fnl_balance < 0)?'danger':'success'  @endphp
                <label class="form-control mb-0 text-{{ $td_class }}"><b>{{ $fnl_balance }}</b></label>
            </td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
@else 
	<!--<tr><td colspan="9" class="text-center text-danger">No Bill Record !</td></tr>-->
@endif