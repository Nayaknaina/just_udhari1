<form action="{{ route('stockcounters.update',[$counter->id,'to'=>'pop'])}}" id="counter_stock_form" method="post" onsubmit="submitcounterform($(this),event)">
    @csrf
    @method("put")
    <label class="form-control text-center bg-dark h-auto">{{ $counter->name }} / {{ $counter->box_name }}</label>
    <div class="form-control h-auto">
        <ul class="counter_stock_edit row p-0 m-0" style="list-style:none;">
            <li class="col-12 text-center"><b>{{ ucfirst($counter->stock->item_type) }}</b></li>
            <li class="col-12"><hr class="m-1"></li>
            <li class="col-4"><b>INIT</b></li>
            <li class="col-8 text-center">{{ $counter->stock_quantity.(($counter->stock->unit=='grms')?' Grm':'') }}</li>
            <li class="col-4"><b>AVAIL</b></li>
            <li class="col-8 text-center">{{ $counter->stock_avail.(($counter->stock->unit=='grms')?' Grm':'') }}</li>
            <li class="col-12 "><hr class="p-0 my-2 bg-dark"></li>
            <li class="col-4"><b>OUT</b></li>
            <li class="col-8 text-center">
            @if($counter->stock->item_type!='genuine' && $counter->stock_avail!=0)
            <div class="input-group mb-3">
                <input type="text" name="quant" value="{{ $counter->stock_avail }}" class="form-control h-auto px-1 py-0 text-center" required >
                 @if($counter->stock->unit=='grms')
                <div class="input-group-append">
                    <span class="input-group-text px-1 py-0" id="basic-addon2"><b>Grm.</b></span>
                </div>
                @endif
            </div>
            @else 
            <input type="hidden" name="quant" value="{{ $counter->stock_avail }}">
            {{ $counter->stock_avail.(($counter->stock->unit=='grms')?' Grm':'') }} 
            @endif
            </li>
        </ul>
    </div>
    <hr class="my-2 p-0">
    <div class="form-group  text-center mb-2">
        <input type="hidden" name="id" value="{{ $counter->id }}">
        <input type="hidden" name="to" value="pop">
        <button type="submit" class="btn btn-success"name="do" value="action">Ok <li class="fa fa-share-square"></li></button>
    </div>
</form>