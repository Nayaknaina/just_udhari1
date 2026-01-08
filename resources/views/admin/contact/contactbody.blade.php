{{-- @if($data->count()>0) 
    @foreach($data as $ci=>$cnct) 

    <tr>
        <td>
            {{ print_r($cnct->toArray()) }}
        </td>
    </tr>
    @endforeach

@else 
    <tr>
        <td>
            Pagal ho kya ?
        </td>
    </tr>
@endif--}}

@if($enquiries->count() > 0)
    @foreach($enquiries as $enq)
        <tr  class="{{ $enq->is_read ? '' : 'font-weight-bold text-dark table-info' }}" data-id="{{ $enq->id }}">
            <td>{{ $loop->iteration + ($enquiries->perPage() * ($enquiries->currentPage() - 1)) }}</td>
            <td>{{ $enq->created_at->format('d M, Y') }}<br>
                <small class="text-muted">{{ $enq->created_at->format('h:i A') }}</small>
            </td>
            <td><strong>{{ $enq->name }}</strong>
                @if(!$enq->is_read)
                    <span class="badge badge-danger ml-2">New</span>
                @endif
            </td>
            <td>{{ $enq->email }}</td>
            <td>{{ $enq->phone_number ?? '-' }}</td>
            <td>{{ Str::limit($enq->subject, 50) }}</td>
            <td>
                <button type="button" class="btn btn-info btn-sm view-btn" 
                        data-id="{{ $enq->id }}"
                        data-name="{{ $enq->name }}"
                        data-email="{{ $enq->email }}"
                        data-phone="{{ $enq->phone_number ?? '-' }}"
                        data-subject="{{ $enq->subject }}"
                        data-message="{{ nl2br(e($enq->message)) }}"
                        data-date="{{ $enq->created_at->format('d M, Y h:i A') }}"
                        data-read="{{ $enq->is_read ? '1' : '0' }}">
                    <i class="fa fa-eye"></i> View
                </button>

                <button type="button" class="btn btn-danger btn-sm delete-btn" data-path="{{ route('contact.destroy',$enq->id) }}">
                    <i class="fa fa-trash"></i>
                </button>

               
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="fa fa-inbox fa-3x mb-3"></i><br>
            No contact enquiries found.
        </td>
    </tr>
@endif