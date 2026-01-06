@if($substones->count()>0)

    @foreach($substones as $sbk=>$substone)
    
    {{--<div class="col-md-3">
        <div class="card data_pane">
            <div class="card-header  bg-secondary ">
                <h3 class="card-title col-12">
                    {{ @$substone->category->name }}
                </h3>
            </div>
            <div class="card-body p-2">
            <ul class="row p-0 m-0 data_ul" style="list-style:none;position:relative;">
                <li class="col-5"><b>COUNT</b></li>
                <li  class="col-7 text-right">{{ $substone->num }}</li>
                <li class="col-5"><b>WEIGHT</b></li>
                <li  class="col-7 text-right">{{ $substone->avail }}</li>
            </ul>
            </div>
            <a href="{{ route('stocks.index',['stock'=>'stone','type'=>$substone->category->id]) }}" class="overlay_link text-center p-4" style="display:none;"><i class="fa fa-list"></i></a>
        </div>
    </div>--}}
    <div class="col-md-3 mb-2">
        <div class="card data_pane">
            <div class="card-header  bg-secondary ">
                <h3 class="card-title col-12">
                    {{ @$substone->name }}
                </h3>
            </div>
            <div class="card-body p-2">
            <ul class="row p-0 m-0 data_ul" style="list-style:none;position:relative;">
                <li class="col-5"><b>COUNT</b></li>
                <li  class="col-7 text-right">{{ $substone->num }}</li>
                <li class="col-5"><b>WEIGHT</b></li>
                <li  class="col-7 text-right">{{ $substone->avail??0 }} Ct.</li>
                <li class="col-5"><b>COST</b></li>
                <li  class="col-7 text-right">{{ $substone->cost??0 }} Rs</li>
            </ul>
            </div>
            <a href="{{ route('stocks.index',['stock'=>'stone','type'=>$substone->id]) }}" class="overlay_link text-center p-4" style="display:none;"><i class="fa fa-list"></i></a>
        </div>
    </div>
    @endforeach
@else 
    <div class="alert alert-outline-warning text-center">No Stone Stock Found !</div>
@endif