
<!-- <div class="row pb-3"> -->
    
    @if($products->count()>0)
		 @if(!empty($slug_arr))
            <div class="col-12 bg-white mb-2">
                <style>
                .path_ul{
                    list-style-type: disc; /* Use 'circle' or 'square' if needed */
                /padding: 0;            /* Remove default padding */
                    margin: 0;             /* Remove default margin */
                    display: flex;         /* Align items in a row */
                    gap: 20px;             /* Space between list items */
                    flex-wrap: wrap;  
                    /*list-style-position: inside;     /* Wrap items if needed */
                }
                li.path_item{
                    font-size: 1.2rem;
                }
                li.path_item::marker{
                    padding-right:10px;
                }
                li.path_item:nth-child(1)::marker{
                    content:"\27A4";
                }
                li.path_item:not(:nth-child(1)){
                    padding-left:10px;
                }
                li.path_item:not(:nth-child(1))::marker{
                    content:"\276D";
                }
            </style>
            <ul class="path_ul" style="display:inline-flex;" >
                @foreach($slug_arr as $slk=>$slug)
                <li class="path_item bg-whilte">{{ ucfirst(str_replace("  "," ",str_replace('-',"",$slug))) }}</li>
                @endforeach
            </ul>
        </div>
        @endif
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
			@if($quantity>0)
            <div class="col-lg-4 col-md-6 col-sm-12 col-6 pb-1">
                <div class="card product-item border-1 mb-4">
                    <div class="text-center card-header product-img position-relative overflow-hidden bg-transparent border p-0" >
                       
                        <img class="img-fluid w-100" src="{{ url("ecom/products/{$product->thumbnail_image}")}}" alt="{{ $product->name}}" loading="lazy">
                        <a href="{{ url("{$ecommbaseurl}product/{$product->url}") }}" class="product_detail_btn" target="_blank">
                        <span class="product_detail_layer">
                            <!--<i class="fa fa-eye m-auto text-white">View ?</i>-->
                        </span>
                        </a>
						<a href="{{ url("{$ecommbaseurl}addtowishlist/{$product->url}") }}" class="wishlist_icon addtowishlist">
							<!--<span></span>-->
							<span class="fas fa-heart"></span>
						</a>
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-2 pb-2 pl-1 pr-1">
                        <h6 class="text-truncate mb-0">{{ $product->name}}</h6>
                        <div class="d-flex justify-content-center">
                            <h6>{{ $rate}}/- Rs.</h6>
                            {!! $strike  !!}
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border product_action_div p-0 pt-2 pb-2 pl-1 pr-1">
                        <a href="{{ url("{$ecommbaseurl}product/{$product->url}") }}" class="btn text-dark product_action_btn p-1"><i class="fas fa-eye text-primary mr-1"></i><span class="product_action_label">View Detail</span></a>
                        <a href="{{ url("{$ecommbaseurl}addtokart/{$product->url}") }}" class="btn text-dark  product_action_btn addtokart p-1"><i class="fas fa-shopping-cart text-primary mr-1"></i><span class="product_action_label">Add To Cart</span></a>
                    </div>
                    <div class="d-inline-flex p-1" id="shop_social_share">
                        <a class="text-white px-2" href="https://www.facebook.com/sharer/sharer.php?u={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank" >
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        
                        <a class="text-white px-2" href="https://twitter.com/intent/tweet?text={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <!-- <a class="text-dark px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-pinterest"></i>
                        </a> -->
                        <a class="text-white px-2" href="https://wa.me/?text={{ url("{$ecommbaseurl}product/{$product->url}") }}" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
			@endif
        @endforeach
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
    @else 
        <div class="alert alert-warning text-center m-auto">No Product Found !</div>
    @endif
    
<!-- </div> -->
