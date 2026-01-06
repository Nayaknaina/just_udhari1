<div class="col-md-12">
<label class="text-center text-white bg-dark form-control">
    {{ $date }} | OPENING
</label>
@php 
    $src_full_name = ['imp'=>'Import','ins'=>'Save','sll'=>'Sell','prc'=>'purches','udh'=>'Udhar','sch'=>'Scheme'];
    $ini_count = count($src_full_name);
@endphp
<div class="table-responsive">
    @if($data->count()>0)
        @foreach($data as $key=>$src)


            @php 
            if(isset($src_full_name[$key])){
                unset($src_full_name[$key]);
            }
            @endphp
        @endforeach
        @if($ini_count > count($src_full_name))
            @foreach($src_full_name as $key=>$source)

            @endforeach 
        @endif
    @else 

    @endif
</div>
</div>