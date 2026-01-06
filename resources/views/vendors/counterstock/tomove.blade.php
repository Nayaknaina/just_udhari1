<form action="{{ route('stockcounters.update',[$counter->id,'to'=>'move'])}}" id="counter_stock_form" method="post"  onsubmit="submitcounterform($(this),event)">
    @csrf
    @method("put")    
    <label class="form-control text-center bg-dark h-auto m-0">{{ $counter->name }} / {{ $counter->box_name }}</label>
    <div class="form-control h-auto">
        <ul class="counter_stock_edit row p-0 m-0" style="list-style:none;">
            <li class="col-12 text-center"><b>{{ ucfirst($counter->stock->item_type) }}</b></li>
            <li class="col-12"><hr class="m-1"></li>
            <li class="col-4"><b>INIT</b></li>
            <li class="col-8 text-center">{{ $counter->stock_quantity.(($counter->stock->unit=='grms')?' Grm':'') }}</li>
            <li class="col-4"><b>AVAIL</b></li>
            <li class="col-8 text-center">{{ $counter->stock_avail.(($counter->stock->unit=='grms')?' Grm':'') }}</li>
        </ul>
    </div>
    <label class="form-control text-center bg-light h-auto m-0">Move To</label>
    <div class="form-control h-auto">
        <select name="counter" class="form-control  text-center">
            @if($counter_list->count()>0)
                <option value="">Counter ?</option>
                @foreach($counter_list as $ci=>$cval)
                    <option value="{{ $cval['name'] }}">{{ $cval['name'] }}</option>
                @endforeach
            @else 
            <option value="">No Counter !</option>
            @endif
        </select>
        <select name="box" class="form-control  text-center">
            @if($counter_list->count()>0)
                <option value="">Box ?</option>
                @foreach($box_list as $bi=>$bval)
                    <option value="{{ $bval['box_name'] }}">{{ $bval['box_name'] }}</option>
                @endforeach
            @else 
            <option value="">No Box !</option>
            @endif
        </select>
        <div class="input-group mb-3 text-center">
            @if($counter->stock->item_type!='genuine' && $counter->stock_avail!=0)

                <input type="text" name="quant" value="{{ $counter->stock_avail }}" class="form-control text-center">
            @else 
                <input type="hidden" name="quant" value="{{ $counter->stock_avail }}">
                <label class="form-control" >{{ $counter->stock_avail }}</label>
                @if($counter->stock->unit=='grms')
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">Grm.</span>
                </div>
                @endif
            @endif
        </div>
    </div>
    <hr class="m-1 p-0">
    <div class="form-group  text-center mb-2">
        <input type="hidden" name="id" value="{{ $counter->id }}">
        <input type="hidden" name="to" value="move">
        <button type="submit" class="btn btn-success"name="do" value="action">Ok <li class="fa fa-share-square"></li></button>
    </div>
</form>