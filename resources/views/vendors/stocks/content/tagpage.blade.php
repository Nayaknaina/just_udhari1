<div class="container">
    <div class="row">
        <div class="col-12" style="border-bottom:1px solid lightgray;background: #fff1f1;">
            @php 
                $code_arr = ['qrcode'=>"QR-Code",'barcode'=>"Bar-Code"]
            @endphp
            <h5 class="modal-title">
                {{ ucfirst($metal) }} Stock {{ $code_arr[$req_code] }}
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h5>
        </div>
        <div class="col-12 text-center pt-2">
            @include('vendors.idtags.printpagenew',compact('stock','req_code'))
        </div>
    </div>
</div>