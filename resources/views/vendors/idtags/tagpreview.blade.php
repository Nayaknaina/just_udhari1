
    @if(count($data['data'])>0)
       
       @if(isset($rcv_size))
            @php 
                $machine = json_decode($rcv_size->machine,true);
                $tag = json_decode($rcv_size->tag,true);
                $image = json_decode($rcv_size->image,true);
                $info = json_decode($rcv_size->info,true);
                $one = json_decode($rcv_size->one,true);
                $two = json_decode($rcv_size->two,true);
             @endphp
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
            .tag_code{
                @if($image['w'] && $image['w']!="" && $image['w']!=0)
                width:{{ $image['w'] }}mm;
                height:{{ $tag['h'] }}mm;
                @endif
                @if($image['s'] && $image['s']!="" && $image['s']!=0)
                padding:{{ $image['s'] }}px;
                @endif
            }
            .tag_code >img{
                height:auto;
                width:{{ $image['w'] }}mm;
            }
            .detail{
                @if($info['w'] && $info['w']!="" && $info['w']!=0)
                width:{{$info['w'] }}mm;
                height:{{ $tag['h'] }}mm;
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
                    padding: 0;
                    height:{{ $tag['h'] }}mm;
                    width:{{ $tag['w'] }}mm;
                }
                body,html{
                    background-color: transparent;
                }
            }
        </style>
        <style>
            @page{
                margin:0;
                padding:0;
            }
            /*table {
                width: 100%;
                border-collapse: collapse;
            }
            ul{
                list-style:none;
                padding:0;
                padding-left:2px;
                margin:0;
            }
            td.tag_code{
                border-right:1px dotted black;
            }
            td.tag_code.small,td.tag_code.medium,td.tag_code.folded,td.tag_code.string{
                width:30%;
            }
            td.tag_detail.small{
                width:70%;
                font-size:40%;
            }
            td.tag_detail.medium{
                width:70%;
                font-size:65%;
            }
            td.tag_detail.folded{
                width:70%;
                font-size:85%;
            }
            td.tag_detail.string{
                width:70%;
                font-size:80%;
            }*/
        </style>
        <table class="tag_data">
            <tbody>
                @foreach($data['data'] as $key=>$value)
                    @php 
                        $qr = "";
                        switch($code){
                            case 'barcode':
                                    break;
                            default:
                                if($key==0){
                                    include app_path('Services/phpqrcode/qrlib.php');
                                }
                                ob_start();
                                $errorCorrection = QR_ECLEVEL_L;    // Error correction level (L, M, Q, H)
                                $matrixPointSize = 6;               // Size of each "pixel" (module)
                                $margin = 0;        
                                QRcode::png($value["{$code}"],null,$errorCorrection,$matrixPointSize,$margin);
                                $imageData = ob_get_contents();
                                ob_end_clean();
            
                                // Encode image data to base64
                                $base64 = base64_encode($imageData);
                                $qr = "data:image/png;base64,{$base64}";
                                break;
                        }
                    @endphp
                    @php 
                        $p_code = str_replace(" ","_",$value["$code"]);
                        $p_code_id = $code.'_img_'.$p_code;
                    @endphp 
                    <tr class="item_tag" id="{{ $p_code_id }}">
                        <td class="tag_code">
                            <img src="{{ @$qr }}" alt="{{ $value['product_name'] }}" class="code_image_src" id="{{ "{$code}_{$value["{$code}"]}" }}" >
                        </td>
                        <td class="detail ">
                            @php 
                                $prop_arr = json_decode($value['property'],true);
                                $gross = @$prop_arr['gross_weight'];
                                $net = @$prop_arr['net_weight'];
                            @endphp
                            <ul class="item_info_one info_block" style="list-style: none;padding:0 2px;margin:0;font-size:10px;align-content:center;">
                                <li class="name">NAME : <span style="float:right;">{{ $value["product_name"] }}</span></li>
                                <li class="wght">WEIGHT : <span style="float:right;">{{ $gross.'/'.$net }} Gm. Gm</span></li>
                            </ul>
                            <ul class="item_info_two info_block" style="list-style: none;padding:0 2px;margin:0;font-size:10px;align-content:center;">
                                <li class="id">ID : <span style="float:right;">{{ $value["product_code"] }}</span></li>
                                <li class="code">CODE : <span style="float:right;">{{ $value["{$code}"] }}</span></li>
                            </ul>
                            {{--<ul>
                                <li class="name">NAME : <span>{{ $value["product_name"] }}</span></li>
                                <li class="wght">WEIGHT : <span>{{ $gross.'/'.$net }} Gm.</span></li>
                                <li class="id">ID : <span>{{ $value["product_code"] }}</span></li>
                                <li class="code">CODE : <span>{{ $value["{$code}"] }}</span></li>
                            </ul>--}}
                        </td>
                    </tr>
                @endforeach     
            </tbody>
        </table>
        @else 
            <div style="width:100%;text-align:center;color:red;">Please Select the Specific Size than Print !</div>
        @endif
    @else 
        <div style="width:100%;text-align:center;color:red;">No Stock Found !</div>
    @endif

