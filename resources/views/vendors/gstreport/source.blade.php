
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
        $title_arr = ["Date","Reference","Amount","Paid","Unpaid"];
		$type_arr  = ['s'=>"Sell",'p'=>"Purchase",'jb'=>'Just Bill']; 
        $field_arr["{$class}"] = [];
        switch($class){
            case 'Bill':
                $field_arr["{$class}"] = ["created_at","bill_number","final","payment","balance"];
                break;
            default:
                break;
        }
		
		
    @endphp
<div class="col-12 p-0">
    <ul class="row p-0 trgt_info_block">
        <li class="col-5 title">SOURCE</li>
        <li class="col-7 info text-primary"> {{ @$type_arr["{$type}"] }}</li>
        <hr class="col-12 m-1 p-0">
        @foreach($title_arr as $key=>$title)
        @php 
        $field = @$field_arr["{$class}"][$key];
        @endphp
        <li class="col-5 title">{{ @$title }}</li>
        <li class="col-7 info"> {{ @$data->$field; }}</li> 
        @endforeach 
    </ul>
</div>
