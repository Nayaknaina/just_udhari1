
@php

    $curr_dir = "assets/ecomm/images/newarrived/";
    $new_arrivals = scandir($curr_dir);

@endphp

@foreach($new_arrivals as $nind => $img)

@if($nind > 1)

    <div class="card product-item border-1">
        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
            <img class="img-fluid w-100" src="{{ asset("{$curr_dir}{$img}") }}" alt="">
        </div>
        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
            <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
            <div class="d-flex justify-content-center">
                <h6>$123.00</h6>
                <h6 class="text-muted ml-2"><del>$123.00</del></h6>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between bg-light border">
            <a href="{{ url("{$ecommbaseurl}shop") }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View
                Detail</a>
            <a href="" class="btn btn-sm text-dark p-0"><i
                    class="fas fa-shopping-cart text-primary mr-1"></i>Add To
                Cart</a>
        </div>
    </div>

@endif

@endforeach
