@extends('layouts.vendors.app')

@section('content')
<style>
    #item_image_preview{
        position:absolute;
        margin:auto;
        background:white;
        width:auto;
        max-width:80vw;
        z-index:1;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px;
        border: 1px dashed gray;
        top:0;
        box-shadow:1px 2px 2px 4px gray;
    }
</style>
    @php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Import Stock",$path) 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 

    <section class = "content">
        <div class = "container-fluid">
            <div class = "row">
                <!-- left column -->
                <div class="col-md-12 p-0">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-body p-1" id="stock_content_area">
                            @php $stock_page = strtolower($entry_data->stock_type) @endphp
                            @include("vendors.stocks.newpage.stockpages.recent{$stock_page}items",compact('entry_data','stock_data'))
                        </div>
                    </div>
                    <div class="" id="item_image_preview" style="display:none;">
                        <h6 style="border-bottom:1px solid lightgray;">Item Image <button type="button" class="close btn btn-sm btn-outline-danger px-1 py-0" style="float:right;" onClick="$('#item_image_preview_src').attr('src','');$('#item_image_preview').hide();">&times</button></h6>
                        <div class="item_image_preview_area text-center" id="item_image_preview_area" >
                            <img src="" class="img-thumbnail img-responsive w-100 h-auto" id="item_image_preview_src" style="max-width:100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('javascript')
<script>
    $(document).find('.item_image_prev').click(function(e){
        e.preventDefault();
        $("#item_image_preview_src").attr('src',$(this).data('image'));
        $("#item_image_preview").show();
    });

</script>
@if($print && $print=='print')
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

                            let zplCommands = $("tbody > tr[data-print]").map(function () {
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
        $(document).ready(function(){
            printDirect();
        });
    </script>
@endif
@endsection