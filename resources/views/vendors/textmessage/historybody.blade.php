@if($sms_record->count()>0)
    @php $custo_type_arr = ['c'=>"Customer",'s'=>'Supplier']; @endphp
    @foreach($sms_record as $sk=>$sms)
        <tr>
            <td class="text-center">{{ $sms_record->firstItem() + $sk }}</td>
            <td class="text-center">{{ $sms->sub_section }}</td>
            <td class="text-center">
                {{ $sms->msg_route }}<hr class="m-1 p-0">{{ $sms->msg_header }}
            </td>
            <td class="text-center"> 
                {{ $sms->custo_contact }}
            </td>
            <td class="text-center">
                {{ $sms->msg_content }}
            </td>
            <td class="text-center">
                <b class="text-{{ ($sms->status)?'success':'danger' }}">{{ ($sms->status)?'Success':'Failed' }}</b>
                <hr class="m-0 p-0">
                <i class="text-info">{{ $sms->remark }}</i>
            </td>
			<td class="text-center">
			{{ date('d-M-Y h:i a',strtotime($sms->created_at)) }}
			</td>
            <td class="text-center">
				<div class="dropdown">
                    <button class="btn btn-{{ ($sms->status)?'outline-':'' }}secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        &#8942;
                    </button>
                    <div class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton" style="min-width:unset!important;">
					{{--<a href="{{ route('textmessage.resend') }}/{{ $sms->id }}" class="dropdown-item text-info resend_message">
                            <i class="fa fa-paper-plane"></i> Resend
                        </a>
                        <a class="dropdown-item text-danger delete_message" href="{{ route('textmessage.delete') }}/{{ $sms->id }}" ><i class="fa fa-times"></i> Delete</a>--}}
						
						<a class="text-info dropdown-item" href="{{ route('textmessage.resend') }}/{{ $sms->id }}" data-redirect="false" data-mpin-check='true' >
                            <i class="fa fa-paper-plane"></i> Resend
                        </a>

                        <a class="dropdown-item text-danger" href="{{ route('textmessage.delete') }}/{{ $sms->id }}" data-redirect="false" data-mpin-check='true'>
                            <i class="fa fa-times"></i> Delete
                        </a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else 
 <!--<tr><td colspan="8" class="text-center text-danger"> No Record ! </td></tr>-->
@endif