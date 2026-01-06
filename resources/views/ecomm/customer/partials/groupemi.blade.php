@if(!empty($data))
<td colspan="8" class="p-0">
    <table class="table">
        <thead>
            <tr>
                <th>GROUP</th>
				@if($scheme_data->scheme_date_fix !='1')
                    <th class="text-center">START</th>
                    <th class="text-center">END</th>
                @endif
                <th class="text-right">PAYABLE</th>
                <th class="text-right">PAID</th>
                <th class="text-right"> REMAINS</th>
                <th class="text-right"> BONUS</th>
                <th class="text-center"> <li class="fa fa-retweet"></li></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data as $gi => $groupsarr) 
            @php 
                $payable = $groupsarr->emi_amnt * $groupsarr->schemes->scheme_validity;
                $paid = $groupsarr->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt');
                $bonus = $groupsarr->emipaid->sum('bonus_amnt')??0;
                $remains = $payable - $paid;
                $url = url("{$ecommbaseurl}txnsdetail/{$groupsarr->id}");
                //$payurl = url("{$ecommbaseurl}emipay/{$groupsarr->id}");
				$payurl = url("{$ecommbaseurl}emipay");
            @endphp
            <tr>
                <th>
                    {{$groupsarr->groups->group_name}}
                </th>
				@if($scheme_data->scheme_date_fix !='1')
                <td class="text-center">{{ date("d-m-Y",strtotime($groupsarr->entry_at)) }}</td>
                <td class="text-center">{{ date("d-m-Y",strtotime("{$groupsarr->entry_at}+{$scheme_data->scheme_validity} Month")) }}</td>
                @endif
                <td class='text-right text-warning'>
                    {{$payable}} Rs.
                </td>
                <td class='text-right text-success'>
                    {{$paid}} Rs.
                </td>
                <td class='text-right text-danger'>
                    {{$remains}} Rs.
                </td>
                <td class='text-right text-info'>
                    {{$bonus}} Rs.
                </td>
                <td class='text-center'>
                    <ul style="list-style:none;display:inline-flex;padding:0;margin:0">
                        <li class="mx-1">
                            <a href="{{$url}}" class='scheme_txn_btn btn btn-sm btn-outline-primary'>
                                <i class="fa fa-outdent"></i>
                            </a>
                        </li>
                        <li class="mx-1">
                            <form action="{{ $payurl }}" method="post" >
                                @csrf
                                <input type="hidden" name="enroll" value="{{ $groupsarr->id }}">
                                <button type="submit" name="pay" value="gateway" type="submit" class="btn btn-sm btn-outline-success">
                                <i class="fa fa-rupee">?</i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
        </tbody>
        <style>
            .fa-rupee:before{
                content: "\f156";
            }
        </style>
</td>
@else
    <td class="text-center text-danger" colspan="5" > No Content !</span></td>
@endif
