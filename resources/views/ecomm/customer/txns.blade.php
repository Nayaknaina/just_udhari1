@extends('ecomm.site')
@section('title', "My Transactions")
@section('content')
@php 
    @$$activemenu = 'active';
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">My Transactions</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 col-12">
            <ul class="w-100 d-inline-flex p-0 m-0 txn_tab_bar">
                <li class=" col-6 text-center txn-tab">
                    <a href="{{ url("{$ecommbaseurl}transactions/scheme") }}" class="w-100 txn_tab_link active">SCHEME</a>
                </li>
                <li class="col-6 text-center txn-tab">
                    <a href="{{ url("{$ecommbaseurl}transactions/order") }}" class="w-100 txn_tab_link">ORDER</a>
                </li>
            </ul>
            <div class="col-12 p-0"  id="customer_txns_block">
                
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script>
    function pagedata(url) {
      //$("#product_load").show();
      $.get(url,"",function(response) {
        $("#customer_txns_block").empty().append(response.html);
        //$("#product_load").hide()  
      });
    }

    $(document).on('click','.page-link',function(e){
      e.preventDefault();
      $("#product_load").show();
      var url = $(this).attr('href');
      pagedata(url);
    });

    $('.txn_tab_link').click(function(e){
        e.preventDefault();
        $('.txn_tab_link').removeClass('active');
        $(this).addClass('active');
        pagedata($(this).attr('href'));
    });
    pagedata("{{ url("{$ecommbaseurl}transactions") }}");
</script>
@endsection