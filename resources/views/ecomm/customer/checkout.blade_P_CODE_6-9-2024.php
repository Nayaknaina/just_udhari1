@extends('ecomm.site')
@section('title', "Checkout")
@section('stylesheet')
<style>
a.address_tabs.active:before{
  content:"Ship to \27A4  ";
  color:green;
  text-shadow:1px 0px 1px ;
}
label.address_tabs.active:before{
  content:"Ship to \27A4  ";
  color:green;
  text-shadow:1px 0px 1px ;
}
</style>
@endsection
@section('content')
@php 
    @$$activemenu = 'active';
@endphp

<!-- Page Header Start -->
<div class="container-fluid">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase mb-3 " style="margin:auto;">Checkout</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Checkout Start -->
<div class="container-fluid pt-5">
  <div class="row px-xl-5">
    <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
      @include('ecomm.customer.sidebar')
    </div>
    <div class="col-md-9 customer_info_block">
      @include('alert')
      <form action="{{ url("{$ecommbaseurl}orderplace")}}" role="" method="post" id="order_form">
        @csrf
        <div class=" row">
          <div class="col-lg-8 p-0">
            @php          
              $type = old("addr_type")??"old";
              ${"{$type}_active"} = "active";
              $$type = "checked";
              $display = ($type=='old')?'new':'old';
              ${"{$display}_style"} = 'none';
            @endphp 
            <ul class="col-12 p-0 d-inline-flex text-center address_tabs_ul" style="list-style:none;">
                <label class="col-6 p-0 address_tabs {{ @$old_active }} p-2" for="current_address_radio" data-target="#current_address"><input type="radio" name="addr_type" value="old" id="current_address_radio" style="display:none;" {{ @$old }}  >Current Address</label>
                <label class="col-6 p-0 address_tabs {{ @$new_active }} p-2" for="new_address_radio" data-target="#new_address"><input type="radio" name="addr_type" value="new" id="new_address_radio" style="display:none;" {{ @$new }} > Different Address</label>
            </ul>
              
            <div class="mb-4 col-12  shipping_address" id="current_address" style="display:{{ @$old_style }};" >
              <h4 class="font-weight-semi-bold mb-4">Current Shiping Address</h4>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label>Address Line 1</label>
                  <input class="form-control" type="text" name="old_addr_one" placeholder="Address Line 1" value="{{ old("old_addr_one") }}">
                  @error("old_addr_one")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-12 form-group">
                  <label>Address Line 2</label>
                  <input class="form-control" type="text" name="old_addr_two"  placeholder="Address Line 2" value="{{ old("old_addr_two") }}">
                </div>
                <div class="col-md-6 form-group">
                  <label class="required">State</label>
                  <select class="form-control addr_state" name="old_state" id="old_state" data-target="old_dist">
                    @if(count($states)>0)
                      <option value="">Select State</option>
                      @foreach($states as $si=>$state)
                        @php 
                          $choosed_state = old("old_state")??$customer->state_id;
                          $selected_state = ($state->code==$choosed_state)?'selected':'';
                        @endphp
                        <option value="{{ $state->code}}" {{ $selected_state }}>{{ $state->name }}</option>
                      @endforeach
                    @else 
                      <option value="">No State !</option>
                    @endif
                  </select>
                  @error("old_state")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 form-group">
                  <label class="required">City</label>
                  <select class="form-control addr_dist" name="old_dist" id="old_dist" >
                    @if(count($districts)>0)
                        <option value="">Select District</option>
                        @foreach($districts as $di=>$district)
                          @php 
                            $selected_dist = ($district->code==$customer->dist_id)?'selected':'';
                          @endphp
                          <option value="{{ $district->id}}" {{ $selected_dist }} >{{ $district->name }}</option>
                        @endforeach
                      @else 
                        <option value="">No District !</option>
                      @endif
                  </select>
                  @error("old_dist")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 p-0">
                  <div class="col-md-12 form-group">
                    <label>Tehsil</label>
                    <input class="form-control" type="text" name="old_teh" placeholder="Sub District / Tehsil" vlue="{{ old("old_teh") }}">
                    @error("old_teh")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="col-md-12 form-group">
                    <label>Area</label>
                    <input class="form-control" type="text" name="old_aera" placeholder="Area">
                    @error("old_teh")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="col-md-12 form-group">
                    <label>ZIP Code</label>
                    <input class="form-control" type="text" name="old_pin" placeholder="123456">
                    @error("old_teh")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 p-0">
                  <div class="col-md-12 form-group">
                    <label class="required">Full Address</label>
                    <textarea name="old_address" class="form-control" placeholder="Full Address" rows="8">{{ old("old_address")??$customer->custo_address }}</textarea>
                    @error("old_address")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-4 col-12  shipping_address" id="new_address" style="display:{{ @$new_style }}" >
              <h4 class="font-weight-semi-bold mb-4">Different Shipping Address</h4>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label>Address Line 1</label>
                  <input class="form-control" type="text" name="new_addr_one" placeholder="Address Line 1" value="{{ old("new_addr_one") }}">
                  @error("new_addr_one")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-12 form-group">
                  <label>Address Line 2</label>
                  <input class="form-control" type="text" name="new_addr_two"  placeholder="Address Line 2" value="{{ old("new_addr_two") }}">
                  @error("new_addr_two")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 form-group">
                  <label class="required">State</label>
                  <select class="form-control addr_state" name="new_state" id="new_state" data-target="new_dist">
                    @if(count($states)>0)
                      <option value="">Select State</option>
                      @foreach($states as $si=>$state)
                        <option value="{{ $state->code}}" >{{ $state->name }}</option>
                      @endforeach
                    @else 
                      <option value="">No State !</option>
                    @endif
                  </select>
                  @error("new_state")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 form-group">
                  <label class="required">City</label>
                  <select class="form-control addr_dist" name="new_dist" id="new_dist"  >
                    <option value="">Select</option>
                  </select>
                  @error("new_dist")
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 p-0">
                  <div class="col-md-12 form-group">
                    <label>Tehsil</label>
                    <input class="form-control" type="text" name="new_teh" placeholder="Sub District / Tehsil" value="{{ old("new_teh") }}">
                    @error("new_teh")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="col-md-12 form-group">
                    <label>Area</label>
                    <input class="form-control" type="text" name="new_aera" placeholder="Area" value="{{ old("new_aera") }}">
                    @error("new_aera")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="col-md-12 form-group">
                    <label>ZIP Code</label>
                    <input class="form-control" name="new_pin" type="text" placeholder="123456" value="{{ old("new_pin") }}">
                    @error("new_pin")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 p-0">
                  <div class="col-md-12 form-group">
                    <label class="required">Full Address</label>
                    <textarea name="new_address" class="form-control" placeholder="Full Address" rows="8"  id="">{{ old("new_address") }}</textarea>
                    @error("new_address")
                      <small class="text-danger">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card border-secondary mb-2">
              <div class="card-header bg-secondary border-0">
                <h4 class="font-weight-semi-bold m-0">Order Total</h4>
              </div>
              <div class="card-body p-2">
                <h5 class="font-weight-medium mb-2">Products</h5>
                @php 
                  $total = 0;
                @endphp
                @if(count($detail->toArray())>0)
                  @foreach($detail as $dtlk=>$dtl)
                  <div class="d-flex justify-content-between">
                    @php $total+= $dtl->curr_cost @endphp
                    <p>{{ $dtl->product->name }}</p>
                    <p>{{ $dtl->curr_cost }}</p>
                  </div>
                  @endforeach
                @else
                <div class="d-flex justify-content-between">
                  <p class="text-danger">No Products !</p>
                </div>
                @endif
                <hr class="mt-0">
                <div class="d-flex justify-content-between mb-2 pt-1">
                  <h6 class="font-weight-medium">Subtotal</h6>
                  <h6 class="font-weight-medium">{{ $total }}</h6>
                </div>
              </div>
              <div class="card-footer border-secondary bg-transparent" style="box-shadow:1px 2px 3px 3px;">
                <div class="d-flex justify-content-between mt-2">
                  <h5 class="font-weight-bold">Total</h5>
                  <h5 class="font-weight-bold">{{ $total }}</h5>
                </div>
              </div>
            </div>
            <div class="card border-secondary mb-5">
              <div class="card-header bg-secondary border-0">
                <h4 class="font-weight-semi-bold m-0">Payment</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="payment" id="paypal">
                    <label class="custom-control-label" for="paypal">Paypal</label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="payment" id="directcheck">
                    <label class="custom-control-label" for="directcheck">Direct Check</label>
                  </div>
                </div>
                <div class="">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="payment" id="banktransfer">
                    <label class="custom-control-label" for="banktransfer">Bank Transfer</label>
                  </div>
                </div>
              </div>
              <div class="card-footer border-secondary bg-transparent">
              <input type="hidden" name="order" value="{{ $order_data->order_unique }}">
              @php 
                $txn_stream_pre = str_shuffle(rand(100000,999999));
                $txn_stream_post = str_shuffle(rand(999999,100000));
                $txn_num = substr($txn_stream_pre.time().$txn_stream_post, 0, 10);
              @endphp
                <input type="hidden" name="txn" value="{{ $txn_num }}">
                <input type="hidden" name="amount" value="{{ $total }}">
                <button type="submit" name="place" value="order" id="place_order" class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Place Order</button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Checkout End -->
@endsection
@section('javascript')
<script>
  $("#place_order").click(function(){
    $("#page_await").show();
  })
</script>
@endsection