
    @if(count($data['data'])>0)
       
        @php 
            $size_arr = [
                "small"=>[25,10],
                "medium"=>[35,15],
                "folded"=>[50,20],
                "string"=>[60,20],
                ];
            //$page_width = $size_arr[$size][0]*2.83465;
            //$page_height = $size_arr[$size][1]*2.83465; 
            $page_width = (isset($size_arr[$size]))?$size_arr[$size][0].'mm':'inherit';
            $page_height = (isset($size_arr[$size]))?$size_arr[$size][1].'mm':'inherit';
        @endphp
        @if(isset($size_arr[$size]))
        <style>
            @page{
                margin:0;
                padding:0;
            }
            html,body{
                margin:0;
                padding:0;
                height:auto;
                background-color: gray;
                width:{{ $page_width }};
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
    
            tr {
                background-color: white;
                width: {{ $page_width }};
                height: {{ $page_height }};
            }
            img{
                width:100%;
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
            }
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
                    <tr>
                        <td class="tag_code {{ $size }}">
                            <img src="{{ @$qr }}" alt="{{ $value['product_name'] }}" class="code_image_src" id="{{ "{$code}_{$value["{$code}"]}" }}" >
                        </td>
                        <td class="tag_detail {{ $size }}">
                            @php 
                                $prop_arr = json_decode($value['property'],true);
                                $gross = @$prop_arr['gross_weight'];
                                $net = @$prop_arr['net_weight'];
                            @endphp
                            <ul>
                                <li class="name">NAME : <span>{{ $value["product_name"] }}</span></li>
                                <li class="wght">WEIGHT : <span>{{ $gross.'/'.$net }} Gm.</span></li>
                                <li class="id">ID : <span>{{ $value["product_code"] }}</span></li>
                                <li class="code">CODE : <span>{{ $value["{$code}"] }}</span></li>
                            </ul>
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

