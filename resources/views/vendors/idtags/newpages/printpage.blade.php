@if($stock->count() > 0)
<style>
    ul#all_tags{
        display:inline-flex;
        list-style:none;
        width:100%;
    }
    .tag_strip_li{
        height:10mm;
		margin: 3px auto;
    }
    table.tag_strip{
        background:white;
        width:100mm;
        /* max-height:10mm; */
        height: 10mm;
        table-layout: fixed;
        overflow: hidden;
        border-collapse: collapse;  /* removes spacing between cells */
        border-spacing: 0;          /* extra spacing reset */
        margin: 0;                  /* remove top/bottom margin */
        padding: 0;        
    }
    th{
        font-size:80%;
    }
    table.tag_strip > thead >tr > th{
        font-size:80%;
        height:3%;
    }
    table.tag_strip > tbody >tr > td{
        height:7%;
    }
    table.tag_strip > tbody >tr,table.tag_strip > tbody >tr{
        line-height:normal;
    }
    table.tag_strip > tbody > tr >td,table.tag_strip > thead>tr>th{
        border:none;
        text-align:left;
        padding:0;
        line-height:normal;
    }
    table.tag_strip > tbody tr >td:not(.right_content) >ul >li{
        font-size:50%;
        margin:0;
        padding-left:2px;
        font-weight:bold;
        line-height:normal;
    }
    tbody>tr>td.left_content{
        width:33mm;
    }
    tbody >tr>td.center_content{
        width:30mm;
    }
    tbody>tr>td.right_content{
        width:7mm;
        text-align:center;
    }
    tbody>tr>td.last_content{
        width:30mm;
    }
    tbody>tr>td.right_content>img{
        width: inherit;
        padding: 0;
    }
</style>
<ul id="all_tags" class="m-2 flex-wrap p-0">
@foreach($stock as $sk=>$item)
    @php 
            
            $code_image = "";
            switch($req_code){
                case 'barcode':
                    if($sk==0){
                        include_once app_path('Services/phpbarcode_clean/barcodelib.php');
                    }
                    $generator = new BarcodeGenerator(); 
                    $barcodeData = $generator->getBarcode($item->tag);
                    $base64 = base64_encode($barcodeData);
                    $code_image = "data:image/png;base64,{$base64}";
                    break;
                default:
                    if($sk==0){
                        include app_path('Services/phpqrcode/qrlib.php');
                    }
                    ob_start();
                    $errorCorrection = QR_ECLEVEL_L;    // Error correction level (L, M, Q, H)
                    $matrixPointSize = 6;               // Size of each "pixel" (module)
                    $margin = 0;        
                    QRcode::png($item->tag,null,$errorCorrection,$matrixPointSize,$margin);
                    $imageData = ob_get_contents();
                    ob_end_clean();

                    // Encode image data to base64
                    $base64 = base64_encode($imageData);
                    $code_image = "data:image/png;base64,{$base64}";
                    break;
            }
        @endphp
    @php 
        $huid = ($item->huid!="")?"HUID: {$stock->huid}":"";
        $zpl = "^XA";
        $zpl.="^FO100,15^A0N,20,20^FD".$item->name."^FS";
        $zpl.="^FO100,45^A0N,18,18^FDGross: ".($item->gross??'-')."gm^FS";
        $zpl.="^FO100,65^A0N,18,18^FDLess: ".($item->less??'-')."gm^FS";
        $zpl.="^FO100,85^A0N,18,18^FDNet: ".($item->net??'-')."gm^FS";
        $zpl.="^FO300,45^A0N,18,18^FDTag: ".($item->tag??'xxxxxx')."^FS";
        $zpl.="^FO300,65^A0N,18,18^FD".($huid)."^FS";
        $zpl.="^FO300,85^A0N,18,18^FDKarat: ".($item->caret??'NA')."K^FS";
        $zpl.="^FO440,20^BQN,3,3";
        $zpl.="^FDLA,".($item->tag??'xxxxxx')."^FS";
        $zpl.="^XZ";
        $zpl_print = "data-print='{$zpl}'";
    @endphp
   <li class="tag_strip_li" {!! @$zpl_print !!}>
        <table class="tag_strip">
            <thead>
                <th colspan="4">{{ $item->name }}</th>
            </thead>
            <tbody>
                <tr>
                    <td class="left_content">
                        <ul>
                            <li>Gross : {{ $item->avail_gross }}</li>
                            <li>Less : {{ $item->avail_less }}</li>
                            <li>Net : {{ $item->avail_net }}</li>
                        </ul> 
                    </td>
                    <td class="center_content">
                        <ul>
                            <li>TAG : {{ $item->tag }}</li>
                            <li>HUID : {{ $item->huid }}</li>
                            <li>KARAT : {{ $item->caret }}</li>
                        </ul>
                    </td>
                    <td class="right_content">
                        <img id="{{ $item->tag }}" src="{{ @$code_image }}" alt="{{ $item->tag }}" class="m-auto tag_image">
                    </td>
                    <td class="last_content">

                    </td>
                </tr>
            </tbody>
        </table>
   </li>
@endforeach
<input type="hidden" id="tag_record" value="{{ @$count }}">
</ul>
<hr class="w-100 p-0 m-2">
<button class="m-auto btn-sm btn btn-success mb-2" onClick="printDirect();"><i class="fa fa-print"></i> Print</button>
@else 
<p class="text-center text-danger w-100 m-2">
    <span style="text-shadow: 1px 2px 3px white;"><b>No Stock Found !</b></span>
</p>
@endif

<script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>
<script>
    $(function () {
        window.printDirect = function () {
            if (!qz.websocket.isActive()) {
                qz.websocket.connect().then(doPrint).catch(() => alert("⚠️ QZ Tray not running"));
            } else {
                doPrint();
            }
            function doPrint() {
                qz.printers.find("ZDesigner ZD230-203dpi ZPL")
                    .then(printer => {
                        let config = qz.configs.create(printer);

                        let zplCommands = $("ul#all_tags > li[data-print]").map(function () {
                            return { type: 'raw', format: 'plain', data: $(this).data("print") };
                        }).get();
                        if (zplCommands.length === 0) {
                            alert("Please select at least one label to print.");
                            return;
                        }

                        return qz.print(config, zplCommands);
                    })
                    .then(() => alert("✅ Labels sent to printer!"))
                    .catch(err => alert("❌ Print failed: " + err));
            }
        };
    });
</script>
<script>
    // $(document).ready(function(){
    //     printDirect();
    // });
</script>