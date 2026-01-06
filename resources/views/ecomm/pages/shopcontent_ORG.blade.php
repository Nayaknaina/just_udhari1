	<style>
        .product_detail_layer{
            position:absolute;
            /*background:#00000080;*/
            top:0;
            bottom:0;
            left:0;
            right:0;
            display: none;
            justify-content: center;
            align-items: center;
            /*background: rgba(0, 0, 0, 0.5); /* Optional background for contrast */
        }
        .product_detail_layer.active{
            display:flex;
        }
    </style>
<!-- <div class="row pb-3"> -->
    @if(count($products))
        @foreach($products as $prdcti=>$product) 
			@php 
                $category = strtolower($product->stock->category->name??'Artificial');
                if(in_array($category,['gold','silver'])){
                    $ini_rate = app("{$category}_rate");
                    $one_k_rate = $ini_rate/24;
                    $ini_rate = $product->stock->caret*$one_k_rate;
                    $ini_strike = false;
                }else{
                    $ini_rate = $product->rate;
                    $ini_strike = $product->strike_rate??false;
                }
                $quantity = $product->stock->available;
                $multi_quant = ($category!="artificial")?(($quantity!=0)?$quantity:$product->stock->quantity):1;
                $rate = round($multi_quant*$ini_rate,2);
                $strike = ($ini_strike)?'<h6 class="text-muted"><del>'.$multi_quant*$ini_strike.'</del>Rs</h6>':'';
            @endphp
            <div class="col-lg-4 col-md-6 col-sm-12 col-6 pb-1">
                <div class="card product-item border-1 mb-4">
                    <div class="text-center card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <img class="img-fluid w-100" src="{{ url("ecom/products/{$product->thumbnail_image}")}}" alt="{{ $product->name}}">
						
						<a href="{{ url("{$ecommbaseurl}product/{$product->url}") }}" class="product_detail_btn" target="_blank">
                        <span class="product_detail_layer">
                            <!--<i class="fa fa-eye m-auto text-white"> View ?</i>-->
                        </span>
                        </a>
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3">{{ $product->name}}</h6>
                        <div class="d-flex justify-content-center">
                            <h6>{{ $rate}}/- Rs.</h6>
                            {!! $strike  !!}
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border product_action_div">
                        <a href="{{ url("{$ecommbaseurl}product/{$product->url}") }}" class="btn btn-sm text-dark p-0 product_action_btn"><i class="fas fa-eye text-primary mr-1"></i><span class="product_action_label">View Detail</span></a>
                        <a href="{{ url("{$ecommbaseurl}addtokart/{$product->url}") }}" class="btn btn-sm text-dark p-0 product_action_btn addtokart"><i class="fas fa-shopping-cart text-primary mr-1"></i><span class="product_action_label">Add To Cart</span></a>
                    </div>
                    <div class="d-inline-flex" id="shop_social_share">
                        <a class="text-dark px-2" href="https://www.facebook.com/sharer/sharer.php?u={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank" >
                            <i class="fab fa-facebook-f"></i>
                        </a>
						
						<a class="text-dark px-2" href="https://twitter.com/intent/tweet?text={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <!-- <a class="text-dark px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-pinterest"></i>
                        </a> -->
                        <a class="text-dark px-2" href="https://wa.me/?text={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    <div class="col-12 pb-1">
        
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mb-3">
            {{ $products->links("pagination::bootstrap-4") }}
            <!-- <li class="page-item disabled">
            <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
            </li> -->
        </ul>
        </nav>
    </div>
<!-- </div> -->
<script>
    $(document).ready(function(){
        var list_request = false;
          $(".addtokart").click(function(e){
            if(!list_request){
              $("#page_await").show();
              e.preventDefault();
              const url = $(this).attr('href');
              $.get(url,"",function(response){
                $("#page_await").hide();
                alert(response.msg);
                if(response.status){
                  const data_arr = (response.data).split("#");
                  const type_arr = ["wishlist","kart"];
                  $("#"+(type_arr[data_arr[0]])+"_count").text(data_arr[1]);
                }
              });
            }else{
              alert("Request already Sent, \nPlease wait for Response...!")
            }
          });
    });
	
    $('.product-img').mouseenter(function(){
        $(this).find('span.product_detail_layer').addClass('active');
    });
    $('.product-img').mouseleave(function(){
        $(this).find('span.product_detail_layer').removeClass('active');
    });
</script>