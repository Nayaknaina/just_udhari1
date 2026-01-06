
<!-- <div class="row pb-3"> -->
    @if(count($products))
        @foreach($products as $prdcti=>$product)
            <div class="col-lg-4 col-md-6 col-sm-12 col-6 pb-1">
                <div class="card product-item border-1 mb-4">
                    <div class="text-center card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <img class="img-fluid w-100" src="{{ url("ecom/products/{$product->thumbnail_image}")}}" alt="{{ $product->name}}">
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3">{{ $product->name}}</h6>
                        <div class="d-flex justify-content-center">
                            <h6>{{ $product->rate}} /- Rs.</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border product_action_div">
                        <a href="{{ url("{$ecommbaseurl}product/{$product->url}") }}" class="btn btn-sm text-dark p-0 product_action_btn"><i class="fas fa-eye text-primary mr-1"></i><span class="product_action_label">View Detail</span></a>
                        <a href="{{ url("{$ecommbaseurl}addtokart/{$product->url}") }}" class="btn btn-sm text-dark p-0 product_action_btn addtokart"><i class="fas fa-shopping-cart text-primary mr-1"></i><span class="product_action_label">Add To Cart</span></a>
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
</script>