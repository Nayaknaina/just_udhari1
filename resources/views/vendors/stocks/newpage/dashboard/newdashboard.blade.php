  @extends('layouts.vendors.app')

  @section('content')
<style>
.stock_prop{
    list-style:none;
}
.stock_block{
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.stock_block{
    border-radius: 8px;
    transition: all 0.3s ease;
}
.block_select:hover > .stock_block{
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    border: 2px solid #ff6b35;
}
.category-header i {
    margin-right: 0.5rem;
    color: #ff6b35;
}
</style>
@php 
	$anchor = ['<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> New</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>'];
	$data = new_component_array('newbreadcrumb',"Stock Dashboard") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row">
                <!-- left column -->
                 @php 
                    $icon_arr = ['gold'=>'coins','silver'=>'medal','artificial'=>'star','stone'=>'gem','franchise'=>''];
                 @endphp
                 @foreach($dash_arr as $title=>$value)
                    <div class="col-md-4">
                        @php 
                            $stock = strtolower($title);
                        @endphp
                        <a href="{{ route('stock.new.dashboard',["{$stock}"]) }}" class="block_select">
                            <div class="card card-default stock_block  mt-4">
                                <div class="card-header text-center p-1">
                                    <h6 class="category-header"><i class="fas fa-{{ $icon_arr["{$stock}"] }}"></i> {{ strtoupper($stock) }}</h6>
                                </div>
                                <div class="card-body p-1" >
								{{--<ul class="row p-0 stock_prop w-100 m-auto">
                                        <li class="col-4 p-1 m-auto text-center">
                                            <small>GROSS</small>
                                            <hr class="m-0 p-0 my-1">
                                            <strong>{{ $value['gross']??'-'}} gm</strong>
                                        </li>
                                        <li class="col-4 p-1 m-auto text-center">
                                            <small>NET</small>
                                            <hr class="m-0 p-0 my-1">
                                            <strong>{{ $value['net']??'-'}} gm</strong>
                                        </li>
                                        <li class="col-4 p-1 m-auto text-center">
                                            <small>FINE</small>
                                            <hr class="m-0 p-0 my-1">
                                            <strong>{{ $value['fine']??'-'}} gm</strong>
                                        </li>
                                    </ul>--}}
									
									<ul class="row p-0 stock_prop w-100 m-auto">
                                        @if(!in_array($stock,['artificial','stone','franchise']))
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>GROSS</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['gross']??'-'}} Gm</strong>
                                            </li>
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>NET</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['net']??'-'}} Gm</strong>
                                            </li>
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>FINE</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['fine']??'-'}} Gm</strong>
                                            </li>
                                        @elseif($stock=='artificial')
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>ITEM</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['num']??'-'}}</strong>
                                            </li>
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>COST</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['total']??'-'}} Rs.</strong>
                                            </li>
                                        @elseif($stock=='stone')
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>ITEM</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['num']??'-'}}</strong>
                                            </li>
                                            <li class="col-4  p-1 m-auto text-center">
                                                <small>NET</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['net']??'-'}} Gm.</strong>
                                            </li>
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>COST</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['total']??'-'}} Rs.</strong>
                                            </li>
										@elseif($stock=='franchise')
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>NET</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['net']??'-'}} Gm</strong>
                                            </li>
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>ITEM</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['count']??'-'}}</strong>
                                            </li>
                                            <li class="col-4 p-1 m-auto text-center">
                                                <small>COST</small>
                                                <hr class="m-0 p-0 my-1">
                                                <strong>{{ $value['total']??'-'}} Rs</strong>
                                            </li>
                                        @else 
                                            <li class="col-12 p-1 m-auto text-center">
                                            <small>FAULTY</small>
                                        </li>
                                        @endif
                                    </ul>
									
                                </div>
                            </div>
                        </a>
                    </div>
                 @endforeach
            </div>
        </div>
    </section>
    @endsection('content')
        