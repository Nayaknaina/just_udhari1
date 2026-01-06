
<style>
    .trgt_info_block{
        list-style:none;
    }
    .trgt_info_block > li{
        position:relative;
    }
    .trgt_info_block > li.title{
        font-weight:bold;
    }
    .trgt_info_block > li.title:after{
        content:":";
        right:0;
        position:absolute;
    }
    .trgt_info_block > li.info{
        text-align:right;
    }
</style>
    @php 
        $title_arr = ["Name","Contact","Mail","Address","State",'District','Tehsil','Area','PinCode'];
        $field_arr["{$class}"] = [];
        switch($class){
            case 'Customer':
                $field_arr["{$class}"] = ["custo_full_name","custo_fone","custo_mail","custo_address","state_name","dist_name","teh_name","area_name","pin_code"];
                break;
            case 'Supplier':
                $field_arr["{$class}"] = ["supplier_name","mobile_no","-----","address","----","-----","-----","-----"];
                break;
            default:
                break;
        }
    @endphp
<div class="col-12 p-0">
    <ul class="row p-0 trgt_info_block">
        <li class="col-5 title">TYPE</li>
        <li class="col-7 info text-primary"> {{ $class }}</li>
        <hr class="col-12 m-1 p-0">
        @foreach($title_arr as $key=>$title)
        @php 
        $field = $field_arr["{$class}"][$key];
        @endphp
        <li class="col-5 title">{{ $title }}</li>
        <li class="col-7 info"> {{ $data->$field??'-----'; }}</li>
        @endforeach
    </ul>
</div>