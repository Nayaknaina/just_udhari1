@extends('layouts.vendors.app')
@section('content')
@include('layouts.theme.css.datatable')
	@php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Edit Stock",$path) 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <style>
    td select {
        appearance: none;         /* Standard */
        -webkit-appearance: none; /* Safari/Chrome */
        -moz-appearance: none;    /* Firefox */
        background: none;         /* Optional: Remove background */
        border: 1px solid #ccc;   /* Optional: Add your own border */
        padding-right: 10px;      /* Adjust space for text */
    }
    #item_image_viewer{
        position:absolute;
        top:0;
        left:0;
        bottom:0;
        right:0;
        background:#00000012;

        display: flex;           /* enables centering */
        justify-content: center; /* horizontal center */
        align-items: center;     /* vertical center */
        z-index: 9999;           /* ensu*/
    }
    #item_image_viewer_content{
        position:relative;
    }
    #item_image_viewer_close{
        position: absolute;
        right:0;
    }
    #item_image_viewer .row {
        background: #fff;        /* optional white box background */
        border-radius: 10px;    
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    </style>
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    
                   @if($stocks->count()>0)
                    <div class="row">
                        @php 
                            $selected_stock = "";
                            if(isset($stock_cat)){
                                $selected_stock = strtolower($stock_cat);
                                $$selected_stock = 'selected';
                            }
                        @endphp
                        <div class="form-inline col-md-6">
                            <div class="input-group m-1">
                                <label for="stock" class="input-group-text p-1">Stock</label>
                                <label class="form-control w-auto">{{ ucfirst(@$selected_stock) }}</label>
                            </div>
                            <div id="response" class="p-0 px-1 m-0 alert" style="display:none;">
                                <b id="response" class=""></b>
                                <button class="btn btn-sm btn-danger px-1 py-0 m-0" id="" href="#response" onClick="$('#response > span').empty();$('#response').hide();">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <form id="stock_edit_form" action="{{ route('stock.new.edit',['stock'=>$selected_stock]) }}" method="post">
                            @csrf
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="CsTable" class="table_theme table table-bordered">
                                    <thead id="data_thead">
                                        <tr>
                                            <th>SN</th>
                                            <th>ITEM</th>
                                            <th>TAG</th>
                                            <th>PIECE</th>
                                            <th>Rate</th>
                                            <th>Other</th>
                                            <th >Disc.</th>
                                            <th>IMAGE</th>
                                            <th>RFID</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $ski=>$stock)
                                        <tr>
                                            <td class="text-center">
                                                {{ $ski+1 }}
                                            </td>
                                            <td>
                                                <input type="hidden" name="item[]" value="{{ $stock->id }}">
                                                <label style="text-wrap:nowrap;">{{ $stock->name }} 
                                                @if($stock->image)
                                                    <button type="button" data-title="{{ $stock->name }}" data-img="{{ asset($stock->image) }}" class="item_image btn- btn-sm btn-outline-info p-0 px-1" >IMG.</button>
                                                @endif
                                                </label>
                                            </td>
                                            <td>
                                                 <input type="text" class="form-control no-border tag item_input" name="tag[]" id="tag_{{ $ski }}" value="{{ $stock->tag }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control no-border piece item_input" name="piece[]" id="piece_{{ $ski }}" value="{{ $stock->count }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control no-border rate item_input" name="rate[]" id="rate_{{ $ski }}" value="{{ $stock->rate }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control no-border other item_input" name="other[]" id="other_{{ $ski }}" value="{{ $stock->charge }}">
                                            </td>
                                            <td style="width:60px;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control no-border disc item_input" name="disc[]" id="disc_{{ $ski }}" value="{{ $stock->discount }}">
                                                    <select class="form-control no-border discunit item_input px-1 text-center" name="discunit[]" id="discunit_{{ $ski }}">
                                                        <option value="">_?</option>
                                                        <option value="r" {{ ($stock->discount_unit=='r')?'selected':'' }}>Rs.</option>
                                                        <option value="p" {{ ($stock->discount_unit=='p')?'selected':'' }}>%</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <label for="image_{{ $ski }}" class="form-control mb-0 image_for" style="cursor:pointer;" id="image_for_{{ $ski }}"> 
                                                    Image
                                                </label>
                                                <input type="file" class="form-control no-border image item_input" name="image[]" id="image_{{ $ski }}" style="display:none;" accept="image/*">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control no-border rfid item_input" name="rfid[]" id="rfid_{{ $ski }}" value="{{ $stock->rfid }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control no-border ttl item_input" name="ttl[]" id="ttl_{{ $ski }}" value="{{ $stock->total }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" name="update" value="stock" class="btn btn-sm btn-success">
                                Update
                            </button>
                        </div>
                        </form>
                    </div>
                    @else 
                    <div class="row">
                        <div class="col-12 text-center text-danger">
                        <span>No Item Selected !</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <div id="item_image_viewer" style="display:none;">
        <div class="col-12 col-md-4" id="item_image_viewer_content">
            <button type="button" class="btn btn-sm btn-danger p-0 px-1" id="item_image_viewer_close" onclick="$('#item_image_viewer').hide();">&cross;</button>
            <h6 class="item_image_title"></h6>
            <div class="w-100 text-center" >
                <img src="" class="img-thumbnail img-responsive" style="width:100%;height:inherit;" id="item_image_path">
            </div>
        </div>
    </div>
@endsection

@section('javascript')
@php 
    $js_file = strtolower($selected_stock);
    $edit = true;
@endphp
@include("vendors.stocks.newpage.itemforms.js.{$js_file}calculate",compact('edit'))
<script>
    /*$('#stock_type').change(function(){
		const script_rev = {"gold":'.silver','silver':'.gold'};
        $("#item_form_loader").show();
        const stock = $(this).val().toLowerCase().replace(/[ -]/g, "");
		$(document).off(`${script_rev[stock]}`);
        $("#item_form_area").load("{{ route('stock.create.item.form') }}/"+stock,"",function(response){
            $("#item_form_loader").hide();
            $("#curr_entry_num").html($(document).find('#entry_num').val());
        });
    });*/

    $(document).on('click','.item_image',function(e){
        e.preventDefault();
        $("#item_image_title").text($(this).data(''));
        $("#item_image_path").attr('src','');
        const img_path = $(this).data('img')??false;
        if(img_path){
            $("#item_image_title").text($(this).data('title'));
            $("#item_image_path").attr('src',img_path);
            $("#item_image_viewer").show();
        }else{
            toastr.error("No Image Saved !");
        }
    });

    $("#stock_edit_form").submit(function(e){
        const path = $(this).attr('action');
        const formdata = new FormData(this);
        e.preventDefault();
        $.ajax({
            url:path,
            data:formdata,
            type:"POST",
            contentType:false,
            processData: false,
            success: function(response) {
                if(response.status){
                    $("#response > b").text(response.msg);
                    $("#response").addClass('alert-success').show();
                    success_sweettoatr(response.msg);
                }else if(response.errors){

                }else if(response.error){
                    $("#response > b").text(response.msg);
                    $("#response").addClass('alert-danger').show();
                    toastr.error($response.msg);
                }
            },
            error:function(xhr, status, error){

            } 
        });
    });
</script>
@endsection