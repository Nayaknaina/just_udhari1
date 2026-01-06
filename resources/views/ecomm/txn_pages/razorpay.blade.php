@php 
	$transaction_data = $data['transaction_data'];
	$razorpay_url = $data['razorpay_url'];
@endphp

@if(!empty($transaction_data) && is_array($transaction_data))
    @php 
        $api_key = $transaction_data['api']['key'];
        $order = $transaction_data['order'];
        $order_id = $order->id;
        $call_back = $transaction_data['callback_url'];
	@endphp
	<form id="failure_form" name="txn_form" action="{{ $call_back }}" method="post">
        <input type="hidden" name="razorpay_order_id" value="{{ $order_id }}">
        <input type="hidden" name="custom_response" value="cancel">
    </form>
		<script src="{{  $razorpay_url }}"></script>
        <script>
            function startPayment() {
                var options = {
                    key: "{{ $api_key }}",
                    amount: "{{ $order->amount }}",
                    currency: "{{ $order->currency }}",
                    order_id: "{{ $order_id }}",
                    theme:
                    {
                        "color": "#738276"
                    },
                    callback_url: "{{ $call_back }}",
					modal: {
						ondismiss: function () {
                            txn_form.submit();
						}
					}
                };
                var rzp = new Razorpay(options);
                rzp.open();
            }
            startPayment();
        </script>
@endif