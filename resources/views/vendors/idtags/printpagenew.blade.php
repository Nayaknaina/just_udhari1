@if($stock->count()>0)
<style>
    .item_tag{
        width:100mm;
        height:10mm;
        border:1px solid gray;
        margin-bottom:2mm;
    }
    .code_image{
        height:inherit;
    }
    
    .info_block{
        margin:0;
        font-size:2mm;
        text-align:left;
       padding-left:1mm;
    }
    @if($req_code == 'qrcode')
    .code_image{
        width:10mm;
    }
    .code_image >img{
        height:inherit;
        width:10mm;
        padding:1mm;
    }
    .detail{
        display:inline-flex;
    }
    .item_info_one {
        width:23mm;
    }
    .item_info_two{
        width:33mm;
    }
    .bar_code_info{
        display:none;
    }
    @elseif($req_code == 'barcode')
     .code_image{
        width:33mm;
    }
     .code_image >img{
        height:7mm;
        width:auto;
        max-width:33mm;
        padding:1mm 1mm 0 1mm;
    }
    .detail{
        width:33mm;
    }
    .item_info_two{
        display:none;
    }
    .bar_code_info{
        margin:0;
        padding:0;
        line-height:initial;
        font-size:2mm;
        font-weight:bold;
    }
    @endif
</style>
@php 
    $stock_type = false;
@endphp
<div id="machine" class="machine m-auto"  >
    <input type="hidden" name="code_type" id="code_type" value="{{ $req_code }}">
    @foreach($stock as $si=>$stk)
        @php 
            if($si==0){
                $stock_type = $stk->item_type;
            }
            $code_image = "";
            switch($req_code){
                case 'barcode':
                    if($si==0){
                        include_once app_path('Services/phpbarcode_clean/barcodelib.php');
                    }
                    $generator = new BarcodeGenerator(); 
                    $barcodeData = $generator->getBarcode($stk->$req_code);
                    $base64 = base64_encode($barcodeData);
                    $code_image = "data:image/png;base64,{$base64}";
                    break;
                default:
                    if($si==0){
                        include app_path('Services/phpqrcode/qrlib.php');
                    }
                    ob_start();
                    $errorCorrection = QR_ECLEVEL_L;    // Error correction level (L, M, Q, H)
                    $matrixPointSize = 6;               // Size of each "pixel" (module)
                    $margin = 0;        
                    QRcode::png($stk->$req_code,null,$errorCorrection,$matrixPointSize,$margin);
                    $imageData = ob_get_contents();
                    ob_end_clean();

                    // Encode image data to base64
                    $base64 = base64_encode($imageData);
                    $code_image = "data:image/png;base64,{$base64}";
                    break;
            }
        @endphp
        @php 
            $code = str_replace(" ","_",$stk->$req_code);
            $code_id = $req_code.'_img_'.$code;
            $prop_arr = json_decode($stk->property,true);
            $gross = @$prop_arr['gross_weight'];
            $net = @$prop_arr['net_weight'];
            $less = $gross - $net;
        @endphp 
        <div class="item_tag default" id="{{ $code_id }}" style="display:inline-flex;background:white; border-radius:10px;">
                <div class="code_image">
                    <img id="{{ $code_id }}" src="{{ @$code_image }}" alt="Qwer Tre" class="m-auto tag_image">
                    <p class="bar_code_info">{{ str_replace(" ","_",$stk->$req_code) }}</p>
                </div>
                <div class="detail">
                    @if($stk->item_type=='stone')
                    @php 
                        @$stone_name = rtrim(explode('-',$stk->owncategory->name)[1],')');
                    @endphp
                    <ul class="item_info_one info_block" style="list-style: none;">
                        <li class="gross_wght">NAME-<span  class="name">{{ $stone_name??'--' }} </span></li>
                        <li class="gross_wght">GROSS-<span  class="gross">{{ $stk->caret??'--' }}C</span></li>
                        <li class="less_wght">PRICE-<span class="less">{{ $stk->amount??'--' }}</span></li>
                    </ul>
                    <ul class="item_info_two info_block" style="list-style: none;">
                        <li class="id">ID-<span class="id_val">{{ $stk->product_code }}</span></li>
                        <li class="code">CODE-<span class="code_val">{{ str_replace(" ","_",$stk->$req_code) }}</span></li>
                    </ul>
                    @else 
                    <ul class="item_info_one info_block" style="list-style: none;">
                        <li class="gross_wght">GROSS-<span  class="gross">{{ $gross??'--' }} Gm</span></li>
                        <li class="less_wght">LESS-<span class="less">{{ $less??'--' }} Gm</span></li>
                        <li class="net_wght">NET-<span class="net">{{ $net??'--' }} Gm</span></li>
                    </ul>
                    <ul class="item_info_two info_block" style="list-style: none;">
                        <li class="id">ID-<span class="id_val">{{ $stk->product_code }}</span></li>
                        <li class="code">CODE-<span class="code_val">{{ str_replace(" ","_",$stk->$req_code) }}</span></li>
                    </ul>

                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-12 text-center mt-2">
            <button type="button" class="btn  btn-dark" type="button" id="zebra_print" data-stock="{{ $stock_type }}">
                <i class="fa fa-print"></i> Print Label
            </button>
        </div>
@else 
        <span class="text-primary w-100 mt-2"><i class="fa fa-info-circle"></i> No Stock !</span>
@endif

            <br>


@include('vendors.idtags.js.zpltagprintnew')