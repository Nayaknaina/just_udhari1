
@if($enquiries->count()>0)
    @php 
        $currentPage = $enquiries->currentPage();
        $sn = ($enquiries->perPage()*($currentPage-1))+1;
    @endphp
    @foreach($enquiries as $ek=>$enquiry)
        <tr class="{{ ($enquiry->status==01)?'table-info':(($enquiry->status==11)?'table-success':(($enquiry->status==10)?'table-danger':'')) }}" >
        <td>{{ $sn++ }}</td>
        <td>{{ date("d-M-Y h:i:a",strtotime($enquiry->created_at)) }}</td>
        <td>
            {{ $enquiry->customer->custo_full_name }}
            @if($enquiry->customer->custo_fone!="")
            <hr class="m-1">
            {{ $enquiry->customer->custo_fone }}
            @endif
            @if($enquiry->customer->custo_mail!="")
            <hr class="m-1">
            {{ $enquiry->customer->custo_mail }}
            @endif
        </td>
        <td>
            <a href="{{ route('shopscheme.show',$enquiry->scheme_id) }}" class="link scheme_detail_view">
                {{ @$enquiry->scheme->scheme_head }}
                @if(@$enquiry->scheme->scheme_sub_head!="")
                <hr class="m-1">
                {{ @$enquiry->scheme->scheme_sub_head }}
                @endif
            </a>
        </td>
        <td>
            {{ $enquiry->message }}
        </td>
        <td id="status_{{ $enquiry->id }}">
            <b id="status">
        @switch($enquiry->status)
            @case(01)
            READ
            @break
            @case(11)
            ENROLLED
            @break
            @case(10)
            REJECTED
            @break
            @default
            PENDING
            @break
        @endswitch
        </b>
        <hr class="m-1">
        <span id="status_updated_at">
        {{ date("d-M-Y h:i:a",strtotime($enquiry->updated_at)) }}
            </span>
        </td>
        <td>
            @if($enquiry->status != 10 && $enquiry->status != 11)
            <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            &#x2632; <li class="fa fa-angle-double-down"></li>
            </button>
			<div class="dropdown-menu my_drop" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item"  href="{{ route('enrollcustomer.create',['id'=>$enquiry->id]) }}">ENROLL</a>
                <a class="dropdown-item enquiry_status_mark" href="{{ route('shopschemes.enquiry.mark',['id'=>$enquiry->id,'mark'=>'RJCT']) }}">REJECT</a>
                @if($enquiry->status != 01)
                <a class="dropdown-item enquiry_status_mark" href="{{ route('shopschemes.enquiry.mark',['id'=>$enquiry->id,'mark'=>'RD']) }}">READ</a>
                @endif
                 <a class="dropdown-item text-danger editButton" href="{{ route('shopschemes.enquiry.mark',['id'=>$enquiry->id,'mark'=>'DLT']) }}">DELETE</a>
            </div>
            </div>
            @else
				
            <!--<button class="" type="button" id="dropdownMenuButton" >-->
             <a class="btn btn-outline-secondary text-danger editButton" href="{{ route('shopschemes.enquiry.mark',['id'=>$enquiry->id,'mark'=>'DLT']) }}">&#x2632;&cross;</a>
            <!--</button>-->
            @endif
        </td>
        </tr>
    @endforeach
@else  
    <tr><td colspan="8" class="text-danger text-center"> No Enquiries Yet !</td></tr>
@endif
@include('layouts.vendors.js.passwork-popup')
<script>
    $('.scheme_detail_view').click(function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        $("#scheme_detail_model_body").empty().load(url);
        $("#scheme_detail_model").modal();
    });
    $('.enquiry_status_mark').click(function(e){
      e.preventDefault();
      $.get($(this).attr('href'),"",function(response){
        if(response.status){
          success_sweettoatr(response.msg);
          location.reload();
        }else{
          toastr.error(response.msg);
        }
      });
    });
</script>