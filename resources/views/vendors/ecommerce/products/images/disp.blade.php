@if($images->count()>0)

@php 
        $product = $images[0]->ecommproduct;
    @endphp
    <div class="row">
        <style>
            .val{
                float:inline-end;
            }
            #rate_area{
                background: #ffffffa8;
                position:absolute;
                bottom:0;
                right:0;
                left:0;
            }
            #gall_image_label{
                position:relative;
                background: #d3d3d38c;
                border: 1px dashed gray;
                align-content:center;
            }
            #gall_image_label>span{
                position:absolute;
                font-size:200%;
                top:0;
                left:0;
                transform: rotate(45deg);
                text-shadow: 1px 2px 3px lightgray;
            }
            #gall_img_prev_close{
                position:absolute;
                right:0;
                top:0;
                display:none;
            }
			#gall_image_label>i{
                display:none;
            }
            #gall_image_label.processing >i{
                width: 100%;
                position: absolute;
                height: 100%;
                top: 0;
                left: 0;
                align-content: center;
                background: #000000ab;
                color: white;
                display:block;
            }
        </style>
		
        <div class="col-sm-4 col-xs-12 col-md-3">
            <div class="col-12 text-center p-0">
                <img src="{{ asset('ecom/products/'.$product->thumbnail_image.'') }}" class ="img-fluid img-thumbnail img-responsive p-0 m-auto" style="max-height:250px;height:auto;">
            </div>
            <div class="col-12" id="rate_area">
                <h6 class="text-center">{{ $product->name }}</h6>
                <hr class="m-1 p-0">
                @php 
                    $rate_label = "Sell Price";
                    $strik_label = "Strike Price";
                    $qunt_label = "Quantity";
                    $rate = $product->rate;
                    if(isset($product->stock->category->name) && in_array($product->stock->category->name,['Gold','Silver'])){
                        $rate_label = "Price (Purchase)";
                        $strik_label = "Labour (E-Comm)";
                        $qunt_label = "Weight";
                        $rate = $product->stock->rate;
                    }
                @endphp
                
                <ul class="row p-1 prop m-0" style="list-style:none;">
                    <li class="col-12 p-0">{{ $rate_label }} <b class="val">{{ $rate }} Rs</b></li>
                    <li class="col-12 p-0">{{ $strik_label }} <b style="color:#828282;"  class="val">{{ $product->strike_rate }} Rs</b></li>
                    <li class="col-12 p-0">{{ $qunt_label }} <b  class="val">{{ @$product->stock->quantity }} {{ @$product->stock->unit }}</b></li>
                </ul>
            </div>
        </div>
		<label class="col-sm-4 col-xs-12 col-md-3 text-center btn btn-default" style="max-height:300px;height:auto;" for="gall_image" id="gall_image_label">
            <span>&#128206;</span>
            <img src="" alt="Upload Image" id="gall_img_prev" class="img-thumbnail">
                <a href="javascript:void(null);"  id="gall_img_prev_close" onclick="$('#gall_img_prev').attr('src','');$(this).hide();" class="btn btn-sm btn-outline-danger">&cross;</a>
            <form name="gall_form" action="{{ route('images.store') }}" id="gall_form" enctype="multipart/form-data">
                @csrf
                <input type="file" name="gall_image" style="display:none;" id="gall_image">
                <input type="hidden" name="prdct_id" value="{{ $product->id }}">
            </form>
            <small id="img_error" class="text-danger" style="font-weight:bold;"></small>
			<i><span class="fa fa-spinner fa-spin"></span>Processing...</i>
        </label>
    </div>
    <hr style="border-top:1px solid lightgray;">	

    <div class="row">
    @foreach($images as $key=>$row)
    <div class="col-lg-3">
        <div class="card product-card">
            <img src="{{ asset('ecom/products/'.$row->images) }}" class ="img-fluid">
            <button type="button" class="btn btn-outline-danger delete-btn btn-sm  m-0" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('images.destroy',$row->id) }}" style="top:0;bottom:unset;">
                <li class="fa fa-times"></li>
            </button>
        </div>
    </div>
    @endforeach
    </div>
@else 
    <h1 class="text-center">No Product Gallery !</h1>
@endif
@include('layouts.theme.datatable.pagination', ['paginator' => $images,'type'=>1])
