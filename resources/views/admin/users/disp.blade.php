
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Shop Name</th>
        <th style="border:1px solid;width:10%">Name</th>
        <th style="border:1px solid;width:10%">Mobile No</th>
        <th style="border:1px solid;width:10%">Subscription</th>
        <th style="border:1px solid;width:10%">Schemes</th>
        <th style="border:1px solid;width:10%">Payment Gateways</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($users as $key=>$user)

        <tr>
            <td>{{ $users->firstItem() + $key }}</td>
            <td>{{$user->shop->shop_name }}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->mobile_no}}</td>
            <td>

                @if($user->subscriptions->isEmpty())
                <span>No Subscriptions</span>

                @else
                    <ul>
                        @foreach($user->subscriptions as $subscription)
                            <li> {{ $subscription->product->title }} (Expires on: {{ date('d-M-Y' , strtotime($subscription->expiry_date)) }}) <a class="btn btn-outline-info btn-sm" href="{{ route('shoprights.index', ['shop_id' => $user->shop_id, 'product_id' => $subscription->product_id]) }}">
                                <i class="fa fa-edit"></i> Rights
                            </a>
                             </li>
                        @endforeach
                    </ul>
                @endif

                {{-- @if($user)
                    @foreach($user->roles as $role)
                    <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-danger rounded-full">{{ $role->name }}</span>
                    @endforeach
                @endif --}}

            </td>
			
            <td>
                @if($user->schemes->count()>0)
                <ol>
                    @foreach($user->schemes as $si=>$scheme)
                        <li>
                            {{ $scheme->scheme_head }}. <a href="{{ route('schemes.show',$scheme->scheme_id) }}" class="btn btn-outline-info btn-sm scheme_detail_view"><i class="fa fa-eye"></i> View</a>
                        </li>
                    @endforeach
                </ul>
                @else
                    <p class="text-secondary text-center"><i><b>Not Assigned !</b></i></p>
                @endif
            </td>
			<td>
                @if($user->gateways->count()>0)
                <ol>
                    @foreach($user->gateways as $gi=>$gateway)
                        <li>
                            {{ $gateway->gateway_name }}
                        </li>
                    @endforeach
                </ol>
                @else 
                    <p class="text-secondary text-center"><i><b>Not Assigned !</b></i></p>
                @endif
            </td>
            <td class="text-center">
				<ul style="list-style:none;" class="ul_btn_parent">
                    <li><a class="btn btn-outline-success" href="{{route('users.edit', $user->id)}}"> Edit </a></li>
                    <li><a class="btn btn-outline-danger" href="{{route('ecommsetups.index', $user->branch_id)}}"> Ecom Web Set </a></li>
                    <li><a class="btn btn-outline-info" href="{{ route('schemes.assign.to',$user->id) }}"> Scheme Assign </a></li>
                    <li><a class="btn btn-outline-info" href="{{ route('paymentgateway.show',$user->id) }}"> Payment Gateway </a></li>
                </ul>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>
<style>
    .ul_btn_parent > li{
        margin:5px 0px;
    }
</style>
  @include('layouts.theme.datatable.pagination', ['paginator' => $users,'type'=>1])
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
