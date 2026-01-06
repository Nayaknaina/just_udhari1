@extends('ecomm.site')
@section('title', "My Detail")
@push('metatags')
  <meta property="og:title" content="{{ $product->name }}" />

  <meta property="og:url" content="{{ url(Request::getRequestUri()) }}" />
  <meta property="og:image" content="{{ url("ecom/products/{$product->thumbnail_image}") }}" />
  <meta property="og:type" content="article" />
  <meta property="og:description" content="{{ $product->description }}" />
  <meta property="og:locale" content="en_GB" />
  
@endpush
@section('content')
<!-- Page Header Start -->
 <div class="container-fluid bg-secondary mb-5 breadcrumb-section p-0 d-md-block d-none">
	<div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
		<!--<h1 class="font-weight-semi-bold text-uppercase mb-3">Product Detail</h1>-->
		<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Product Detail</h3>
		<div class="d-inline-flex">
			<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
			<p class="m-0 px-2">-</p>
			<p class="m-0">Product Detail</p>
		</div>
	</div>
</div>

<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Product Detail</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Product Detail</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->

<!-- Shop Detail Start -->
<div class="container-fluid py-2">
  <div class="row px-xl-5">
    <div class="col-lg-5 pb-5">
      <div id="product-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner border">
          
              
          <div class="carousel-item active">
            <img class="w-100 h-100" src="{{ url("ecom/products/{$product->thumbnail_image}") }}" alt="{{ $product->name }}" loading="lazy">
          </div>
          @if(count($product->galleryimages)>0)
            @foreach($product->galleryimages as $gall=>$img)
            <div class="carousel-item">
              <img class="w-100 h-100" src="{{ url("ecom/products/{$img->images}") }}" alt="{{ $product->name }}" loading="lazy">
            </div>
            @endforeach
          @endif
        </div>
        <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
          <i class="fa fa-2x fa-angle-left text-dark"></i>
        </a>
        <a class="carousel-control-next" href="#product-carousel" data-slide="next">
          <i class="fa fa-2x fa-angle-right text-dark"></i>
        </a>
      </div>
    </div>
	@php 
	  $category = strtolower($product->stock->category->name??'Artificial');
	  if(in_array($category,['gold','silver'])){
		$ini_rate = app("{$category}_rate");
        $one_k_rate = $ini_rate/24;
        $ini_rate = $product->stock->caret*$one_k_rate;
        $ini_strike = false;
        $labour = ($product->strike_rate!="")?'<small class="text-primary" style="border: 1px dashed gray;padding: 2px;">Labour : +'.($product->strike_rate*$product->stock->quantity).' Rs./-</small>':false;
	  }else{
		  $ini_rate = $product->rate;
          $ini_strike = $product->strike_rate??false;
	  }
	  $quantity = $product->stock->available;
      $multi_quant = ($category!="artificial")?(($quantity!=0)?$quantity:$product->stock->quantity):1;
      $rate = round($multi_quant*$ini_rate,2);
      $strike = ($ini_strike)?'<small class="text-muted"><del>'.$multi_quant*$ini_strike.'</del>Rs</small>':'';
	  
    @endphp
    <div class="col-lg-7 pb-5">
      <h3 class="font-weight-semi-bold">{{$product->name}} ( <i>{{ ucfirst(($product->stock->item_type=='genuine')?$category:$product->stock->item_type) }}</i> )</h3>
      <hr>
      <h3 class="font-weight-semi-bold mb-1" > RS <span id="bold_rate">{{ $rate }}</span> /- {!! @$strike !!}</h3>
      {!! @$labour !!}
      <p class="my-4">{!! $product->description !!}</p>
      <div class="mb-3 product_detail_feature">
        <form action="{{ url("{$ecommbaseurl}createorder")}}" method="post" role="form" id="check_out_form">
          @csrf
          <input type="hidden" name="product" value="{{ $product->id }}">
          <input type="hidden" name="url" value="{{ $product->url }}">
          <input type="hidden" name="quantity" value="1">
		  @php 
            $total = ($category=="artificial")?$rate:$rate+($product->strike_rate*$multi_quant);
          @endphp
          <input type="hidden" name="total" value="{{ $total }}">
          <input type="hidden" name="from" value="product">
          <button type="submit" name="checkout" value="yes" class="btn btn-primary text-white px-3 m-1"><i class="fa fa-check-circle mr-1"></i> Buy Now ?</button>
        </form>
        <hr>
        <h5 class="product_specification"><u>Specification</u></h5>
        <ul class="col-md-6 col-12">
          @if($category!='artificial')
          <li>Weight <span class="feature_value">{{ $multi_quant }} Grm</span></li>
          <!-- <li>Hall Mark <span class="feature_value">Dummy</span></li> -->
          <li>Carat <span class="feature_value">{{ $product->stock->caret }}</span></li>
          @else 
			  <style>
            span.feature_value.quant:before{
              position: absolute;
            }
          </style>
          <li>Quantity 
				<span class="feature_value quant">
					<ul class="row" style="list-style:none;">
						<li class="col-3 p-0">
						<button type="button" class="btn btn-default text-danger quant_btn" id="minus">-</button>
						</li>
						<li class="col-6 p-0"><input type="text" class="form-control  text-center" style="border: 1px solid lightgray;" id="quant" value="{{ $multi_quant }}" min="1" max="{{ $quantity }}" readonly></li>
						<li class="col-3 p-0">
						<button type="button" class="btn btn-default text-success quant_btn "  id="plus">+</button>
						</li>
					</ul>
				</span>
		  </li>
          @endif
        </ul>
      </div>
      
      <div class="d-flex align-items-center mb-4 pt-2">
        <a href="{{ url("{$ecommbaseurl}addtowishlist/{$product->url}")}}" class="btn btn-primary text-white px-3 m-1" id="addtowishlist"><i class="fas fa-heart mr-1"></i> Add To Wishlist</a>
        
        <a href="{{ url("{$ecommbaseurl}addtokart/{$product->url}")}}" class="btn btn-primary text-white px-3 m-1" id="addtokart"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</a>
       
      </div>
      <div class="d-flex pt-2 product_share_social">
        <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
        <div class="d-inline-flex">
          <a class="text-dark px-2 product_page_share" href="https://www.facebook.com/sharer/sharer.php?u={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank" >
            <i class="fab fa-facebook-f"></i>
          </a>
          <a class="text-dark px-2 product_page_share" href="" target="_blank">
            <i class="fab fa-twitter"></i>
          </a>
          <!-- <a class="text-dark px-2" href="">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a class="text-dark px-2" href="">
            <i class="fab fa-pinterest"></i>
          </a> -->
          <a class="text-dark px-2 product_page_share" href="https://wa.me/?text={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank">
            <i class="fab fa-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Shop Detail End -->
@endsection
@section('javascript')
  <script>
    $(document).ready(function(){
      var list_request = false;
      $("#addtokart,#addtowishlist").click(function(e){ 
        if(!list_request){
          $("#page_await").show();
          e.preventDefault();
          const url = $(this).attr('href');
          const quant = $("#quant").val()??1;
          $.get(url,"quant="+quant,function(response){
            $("#page_await").hide();
            showpopupmessage(response.msg);
            if(response.status){
              const data_arr = (response.data).split("#");
              const type_arr = ["wishlist","kart"];
              $("#"+(type_arr[data_arr[0]])+"_count").text(data_arr[1]);
            }else{
				setTimeout(function(){
                  window.location.href = "{{ url("{$ecommbaseurl}login") }}";
                },1000);
			}
          });
        }else{
          alert("Request already Sent, \nPlease wait for Response...!")
        }
      });

      $("#check_out_form").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = $(this).serialize();
        $.post(path,formdata,function(response){
          var res = JSON.parse(response);
            if(res.status){
                window.location.href = "{{url("{$ecommbaseurl}") }}/"+res.next;
            }else{
              //alert(res.msg);
			  showpopupmessage(res.msg);
			  setTimeout(function(){
                  window.location.href = "{{ url("{$ecommbaseurl}login") }}";
                },1000);
            }
        });
      });
    });
	
	const quant = "{{ $quantity }}";
    $('.quant_btn').click(function(e){
      e.preventDefault();
      var symbol = $(this).attr('id');
      var dflt = $("#quant").val()??0;
      if(symbol=='plus' && +dflt < +quant){
        dflt++;
      }
      if(symbol=='minus' && +dflt > +1){
        dflt--;
      }
      $("#quant").val(dflt);
      nowrate(dflt);
    });
    
    const rate = "{{ $ini_rate }}";
    function nowrate(quant){
      var total = quant*rate;
      $("#bold_rate").text(total);
      $("[name='quantity']").val(quant);
      $("[name='total']").val(total);
    }
	
  </script>
@endsection
