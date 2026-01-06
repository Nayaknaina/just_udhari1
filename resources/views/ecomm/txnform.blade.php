@if(!empty($transaction_data) && is_array($transaction_data))

    <form action="{{ $paytm_url }}" id="txn_form" method="post" name="txn_form">
        @csrf
        @foreach($transaction_data as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
    </form>
@endif

