
<div class="row">

    @if(count($products) > 0)
    <style>
        .val{
            float:inline-end;
        }
    </style>
        @foreach($products as $key=>$row)

            <div class="col-lg-3 col-6 p-1">
                <div class="card product-card">
                <div class="dropdown sub_drop_over">
                    <button class="btn btn-outline-secondary dropdown-toggle m-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fa fa-caret-square-down"></span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class = "dropdown-item text-info" href="{{ route('images.show',$row->id) }}">  
                            <i class="fa fa-object-group" ></i> Gallery {{ $row->galleryimages->count() }}
                        </a> 
                        <a href="{{ route('ecomproducts.edit',$row->id) }}" class="editButton dropdown-item text-warning"><i class = "fa fa-edit"></i> Edit</a>
                        <a type="button" class="dropdown-item  text-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('ecomproducts.destroy',$row->id) }}">
                            <li class="fa fa-times"></li> Delete
                        </a>
                    </div>
                </div>
                <img src="{{ asset('ecom/products/'.$row->thumbnail_image.'') }}" class ="img-fluid">
                <div class="card-footer p-2">
                    <h6 class="text-center">{{ $row->name }}</h6>
                    <hr class="m-1 p-0">
                    @php 
                        $rate_label = "Sell Price";
                        $strik_label = "Strike Price";
                        $qunt_label = "Quantity";
                        $rate = $row->rate;
                        if(isset($row->stock->category->name) && in_array($row->stock->category->name,['Gold','Silver'])){
                            $rate_label = "Price (Purchase)";
                            $strik_label = "Labour (E-Comm)";
                            $qunt_label = "Weight";
                            $rate = $row->stock->rate;
                        }
                    @endphp
                    
                    <ul class="row p-0 prop m-0" style="list-style:none;">
                        <li class="col-12 p-0">{{ $rate_label }} <b class="val">{{ $rate }} Rs</b></li>
                        <li class="col-12 p-0">{{ $strik_label }} <b style="color:#828282;"  class="val">{{ $row->strike_rate }} Rs</b></li>
                        <li class="col-12 p-0">{{ $qunt_label }} <b  class="val">{{ @$row->stock->quantity }} {{ @$row->stock->unit }}</b></li>
                    </ul>
                </div>
                </div>
            </div>

        @endforeach

    @else

    <div class="col-lg-12 mt-5">
        <h3 class = "text-center"> No Product available </h3>
    </div>

    @endif

</div>

  @include('layouts.theme.datatable.pagination', ['paginator' => $products,'type'=>1])

