@php 
    $allow = (!isset($print) || $print==true )?true:false;
@endphp
@if($allow)
<html>
    <head>
        <title>Print Tag</title>
        <link rel="stylesheet" href=""> <!-- Replace with real CSS path -->
        
        @if($size)
             @php 
                $machine = json_decode($size->machine,true);
                $tag = json_decode($size->tag,true);
                $image = json_decode($size->image,true);
                $info = json_decode($size->info,true);
                $one = json_decode($size->one,true);
                $two = json_decode($size->two,true);
             @endphp
        @endif
        <style>
            .item_tag{
                @if($machine['l'] && $machine['l']!="" && $machine['l']!=0)
                margin-left:{{ $machine['l'] }}mm;
                @endif
                @if($machine['r'] && $machine['r']!="" && $machine['r']!=0)
                margin-right:{{ $machine['r'] }}mm;
                @endif
                @if($tag['l'] && $tag['l']!="" && $tag['l']!=0)
                padding-left:{{ $tag['l'] }}mm;
                @endif
                @if($tag['r'] && $tag['r']!="" && $tag['r']!=0)
                padding-right:{{ $tag['r'] }}mm;
                @endif
                @if($tag['w'] && $tag['w']!="" && $tag['w']!=0)
                width:{{ $tag['w'] }}mm;
                @endif
                @if($tag['h'] && $tag['h']!="" && $tag['h']!=0)
                height:{{ $tag['h'] }}mm;
                @endif
                @if($tag['v'] && $tag['v']!="" && $tag['v']!=0)
                /*margin-bottom:{{ $tag['v'] }}mm;*/
                @endif
                page-break-after: always;
                /* page-break-inside: avoid; */
                /* height:min-content; */
            }
            .code_image{
                @if($image['w'] && $image['w']!="" && $image['w']!=0)
                width:{{ $image['w'] }}mm;
                @endif
                @if($image['s'] && $image['s']!="" && $image['s']!=0)
                padding:{{ $image['s'] }}px;
                @endif
            }
            .code_image >img{
                height:100%;
                width:auto;
            }
            .detail{
                @if($info['w'] && $info['w']!="" && $info['w']!=0)
                width:{{$info['w'] }}mm;
                @endif
                @if($info['f'] && $info['f']!="" && $info['f']!=0)
                font-size:{{ $info['f'] }}px;
                @endif
                @if(($one['p'] && $one['p']!="" && $one['p']!=0) || ($two['p'] && $two['p']!="" && $two['p']!=0))
                display: inline-flex;
                @endif
            }
            @php 
                $one_pos = ($one['p'] && $one['p']=='one')?1:2;
                $two_pos = ($two['p'] && $two['p']=='two')?1:2;
            @endphp
            .item_info_one{
                @if($one['w'] && $one['w']!="" && $one['w']!=0)
                width:{{ $one['w'] }}mm;
                @endif 
                order:{{ $one_pos }}; 
            }
            .item_info_two{
                @if($two['w'] && $two['w']!="" && $two['w']!=0)
                width:{{ $two['w'] }}mm;
                @endif  
                 order:{{ $two_pos }}; 
            }
            @page {
                    /* size: auto; */
                    margin: 0;
                    height:min-content;
                }
            @media print {
                @page {
                    /* size: auto; */
                    margin: 0;
                    height:{{ $tag['h'] }}mm;
                }
                body,html{
                    background-color: transparent;
                }
            }
        </style>
    </head>
    <body style="margin:0;padding:0;width:min-content;">
@else 
    <style>
        .machine{
            background:{{ (!$allow)?'transparent':'gray' }};
        }
    </style>
@endif
@if($stock->count()>0)
       
        <div id="machine" class="machine m-auto {{ (!$allow)?'col-md-3':'' }}"  >
    @foreach($stock as $si=>$stk)
        @php 
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
            <div class="item_tag default" id="{{ $code_id }}" style="display:inline-flex;background:white;border:1px solid gray; border-radius:10px;">
                <div class="code_image">
                    <img id="{{ $code_id }}" src="{{ @$code_image }}" alt="Qwer Tre" class="m-auto tag_image">
                </div>
                <div class="detail">
                    <ul class="item_info_one info_block" style="list-style: none;padding:0 2px;margin:0;font-size:10px;align-content:center;">
                        <li class="name">NAME : <span style="float:right;">{{ $stk->product_name }}</span></li>
                        <li class="wght">WEIGHT : <span style="float:right;">{{ $gross??'' }}/{{ $net??'' }} Gm</span></li>
                        <li class="gross_wght">GROSS : <span style="float:right;" class="gross">{{ $gross??'--' }} Gm</span></li>
                        <li class="less_wght">LESS : <span style="float:right;" class="less">{{ $less??'--' }} Gm</span></li>
                        <li class="net_wght">NET : <span style="float:right;" class="net">{{ $net??'--' }} Gm</span></li>
                    </ul>
                    <ul class="item_info_two info_block" style="list-style: none;padding:0 2px;margin:0;font-size:10px;align-content:center;">
                        <li class="id">ID : <span style="float:right;" class="id_val">{{ $stk->product_code }}</span></li>
                        <li class="code">CODE : <span style="float:right;" class="code_val">{{ str_replace(" ","_",$stk->$req_code) }}</span></li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
        @if($url_addon!="" && !$allow)
            <div class="col-12 text-center">
                @php
                    $url_addon = "?".ltrim($url_addon,"&");
                @endphp
                <button type="button" data-url="{{ route('idtags.printtag') }}{{ $url_addon }}" class="btn btn-sm btn-dark" type="button" target="_blank"  id="print_btn"><i class="fa fa-print"></i> Print</button>

                <a  href="{{ route('idtags.preview') }}{{ $url_addon }}&req=stock" class="btn btn-sm btn-dark" type="button" target="_blank"  id="pdf_preview"><i class="fa fa-print"></i> Pdf Preview</a>

                <button type="button" class="btn btn-sm btn-dark" type="button"   id="zebra_print"><i class="fa fa-print"></i> Zebra Print</button>
                
            </div>
        @endif
@else 
    <span class="text-primary w-100 mt-2"><i class="fa fa-info-circle"></i> No Stock !</span>
@endif

@if($allow)

    </body>

</html>
@endif