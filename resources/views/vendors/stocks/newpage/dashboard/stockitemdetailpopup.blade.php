<style>
    label.stock_img_label_two{
        height:150px;
    }
    ul.main,.varient >ul{
        list-style:none;
    }
    .varient >ul >li>strong{
        font-size:80%;
    }
    ul.main > li{
        margin:10px 0;
    }
    div.varient{
        background:#d3d3d329;
        border-radius:8px;
        border:1px solid lightgray;
        margin-bottom: 5px;
    }
    .varient_head{
        font-size:80%;
    }
    .varient_head_hr{
        border-top:1px dashed gray;
    }
    .varient_head > strong,.main > li > strong{
        float:right;
    }
    img.stock_img_two{
        width:100%;
        height:auto;
    }
</style>
<div class="modal-header py-1">
    <h5 class="modal-title">{{ ucfirst($stock) }}/{{ ucfirst($item) }}</h5>
    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-5 col-12 py-2" style="overflow:hidden;">
            <label for="stock_img" class="form-control m-0 stock_img_label_two"  style="display:none;"></label>
			@php 
				$coll_img_name = strtolower($item);
				$shop_root = auth()->user()->shop_id."_".auth()->user()->branch_id;
				$img_path = "assets/images/stockdashboard/{$shop_root}/{$stock}_{$coll_img_name}.png";
				$coll_img_path = (file_exists("{$img_path}"))?asset("{$img_path}"):false;
			@endphp
			@if($coll_img_path)
				<img src="{{ @$coll_img_path }}" class="img-responsive stock_img_preview m-auto p-1" style="display:{{ ($coll_img_path)?'':'none'; }}">
			@else 
				<img src="" class="stock_img_two img-responsive img-thumbnail" style="height:100%;">
			@endif
        </div>
        <div class="col-md-7 col-12">
            <ul class="row p-0 main">
                <li class="col-12"><small>PIECES</small><strong>{{ $stock_data->sum('total_count'); }}/Pcs</strong></li>
                <li class="col-12"><small>GROSS</small><strong>{{ $stock_data->sum('total_gross'); }}/gm</strong></li>
                <li class="col-12"><small>NET</small><strong>{{ $stock_data->sum('total_net'); }}/gm</strong></li>
                <li class="col-12"><small>FINE</small><strong>{{ $stock_data->sum('total_fine'); }}/gm</strong></li>
            </ul>
        </div>
    </div>
    <div class="row">
        @if($stock_data->count()>0)
            @foreach($stock_data as $sdk=>$data)
                <div class="col-md-6 col-12 p-1 m-auto">
                    <a href="{{ route('stock.new.groupinventory',['stock'=>$stock,'item_type'=>$item,'caret'=>$data->caret]) }}">
                        <div class="col-12 varient py-2">
                            <h6 class="varient_head">{{ $data->caret }}K <strong> {{ $data->total_count }}Pcs.</strong></h6>
                            <hr class="p-0 my-1 varient_head_hr">
                            <ul class="row p-0 text-center">
                                <li class="col-4 px-1">
                                    <small>GROSS</small>
                                    <hr class="p-0 m-1">
                                    <strong> {{ $data->total_gross??'-' }}/gm</strong>
                                </li>
                                <li class="col-4  px-1">
                                    <small>NET</small>
                                    <hr class="p-0 m-1">
                                    <strong> {{ $data->total_net??'-' }}/gm</strong>
                                </li>
                                <li class="col-4  px-1">
                                    <small>FINE</small>
                                    <hr class="p-0 m-1">
                                    <strong> {{ $data->total_fine??'-' }}/gm</strong>
                                </li>
                            </ul>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</div>