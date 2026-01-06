@extends('ecomm.site')
@section('title', "Myu Whishlist")
@section('content')
@php 
    @$$activemenu = 'active';
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2 {{ ($txn_data->txn_status==0)?'text-danger':'text-success' }}" style="margin:auto;">Payment {{ ($txn_data->txn_status==0)?"Failed":"Success" }}</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

@php 
    //dd($txn_data->order->shippingaddress)
    //dd($txn_data->shippingaddress);
@endphp
<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
    <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 customer_info_block pt-2">
			@if($txn_data->txn_res_msg!="")
			@php 
                $msg_class = ($txn_data->gateway_txn_status=='gateway_txn_status')?'success':'danger';
            @endphp
            <div class="alert alert-{{ $msg_class }} text-danger text-center">{{ $txn_data->txn_res_msg }}</div>
			@endif
            <div class="table-responsive">
                    @php 
                        $order = $txn_data->order;
                    @endphp
                <table class="table table-bordered bg-white" >
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <td  colspan="2">{{ $txn_data->order_number }}</td>
                            <th colspan="3">Address :</td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td colspan="2">{{ date("Y-m-d h:i a",strtotime($txn_data->order->created_at)) }}</td>
                            <td  colspan="3" rowspan="3">
                                <address>
                                    @php 
                                        //dd($address);
                                    @endphp
                               {!! str_replace("()","",str_replace("<br><br>","<br>",$address->custo_address."<br>".$address->area_name."<br>".$address->teh_name."<br>".$address->state_name.'<br>'."(".$address->pin_code.")")) !!}
                                </address>
                            </td>
                        </tr>
                        <tr>
                            <th>Txn Number</th>
                            <td  colspan="2">{{ $txn_data->txn_number }}</td>
                            <!-- <td  colspan="3"></td> -->
                        </tr>
                        <tr>
                            <th>Txn Date</th>
                            <td  colspan="2">{{ date("Y-m-d h:i a",strtotime($txn_data->created_at)) }}</td>
                            <!-- <td  colspan="3"></td> -->
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td colspan="6" class="p-0">
                                <table class="table table-bordered">
                                    <thead class="bg-secondary">
                                        <th>SN</th>
                                        <th class="text-center">Product</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $final_total = 0 ;
                                            $count = 1;
                                        @endphp
                                        @foreach($order->orderdetail as $dtlkey=>$detail)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td class="text-center">
                                                <img src="{{ asset("ecomm/products/{$detail->product->thumbnail_image}") }}" class="img-responsive" alt="{{ $detail->product->name }}" >
                                                {{ $detail->product->name }}
                                            </td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ $detail->curr_cost }}</td>
                                            @php 
                                                $total = $detail->quantity*$detail->curr_cost;
                                                $final_total+= $total;
                                                $count++;
                                            @endphp
                                            <td>{{ $total }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right" colspan="4">Sub Total</th>
                                            <td>{{  $final_total }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right" colspan="4">Payable</th>
                                            <th>Rs. {{  $final_total }} /-</th>
                                        </tr>
                                        <tr>
                                            <th class="text-right" colspan="4">Status</th>
                                            @php 
                                                $status_txt = ($txn_data->txn_status==0)?"UNPAID":"PAID";
                                                $status_class = ($txn_data->txn_status==0)?"text-danger":"text-success";
                                            @endphp
                                            <th class="{{ $status_class }}">{{ $status_txt }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>  
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection