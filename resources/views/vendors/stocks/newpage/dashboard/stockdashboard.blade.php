  @extends('layouts.vendors.app')

  @section('content')
    <style>
    .stock_prop{
        list-style:none;
    }
    .stock_block{
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .block_select:hover > .stock_block{
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    </style>
    <style>
    .filters-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }
    ul.stock_prop > li > strong{
        float:right;
    }
    .stock_img_block{
        height:150px!important;
        min-height:auto;
        width:auto;
        cursor:pointer;
        background-image:linear-gradient(to right,lightgray,white);
    }
	
	.stock_img_block.uploading{
        position:relative;
    }
    .stock_img_block.uploading:after{
        content: "Saving..";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 1;
        background: #fffc;
        padding-top: 50px;
        font-size: 110% inherit;
        font-weight: bold;
    }
	
    .stock_img_preview{
        /*height:100%;*/
        width:100%;
        /*display:none;*/
        height:auto;
        position:absolute;
        top:0;
        left:0;
        bottom:0;
        right:0;
    }
    .stock_img_action_button{
        position:absolute;
        top:0;
        right:0;
        z-index:1;
        /*display:none;*/
    }
    ul.stock_img_action{
        list-style:none;
        position:absolute;
        top:15%;
        right:5px;
        padding: 0;
        display:none;
        background-color: #f2f2f2de;
        list-style: none;
        border-radius: 10px;
        border:1px dashed gray;
        z-index:1;
    }
    ul.stock_img_action >li > button,ul.stock_img_action >li > label{
        border:unset;
        background:transparent;
        height:inherit;
        width:100%;
        margin:0;
        padding:5px;
        cursor:pointer;
    }
    ul.stock_img_action >li:hover > button,ul.stock_img_action >li:hover > label{
        background-color: white;
        border-radius: 8px;
    }
    </style>
	<style>
        .filter_section{
            position:absolute;
            top:0;
            width:max-content;
            border:1px solid gray;
            background:white;
            box-shadow:1px 2px 3px white;
            padding:5px;
            border-radius:10px;
            display: none;
        }
         .filter_section > ul{
            padding: 0;
            list-style: none;
            margin:0;
         }
         .filter_section > ul > li{
            gap:2px;
         }
         .filter_section > ul > li >strong{
            float:right;
         }
        .filter_section > h6 > span{
            float:right;
        }
    </style>
@php 
    $stock_name = ucfirst($stock);
	$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-primary py-1"><i class="fa fa-caret-left"></i></a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> New</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>'];
	$data = new_component_array('newbreadcrumb',"{$stock_name} Stock Dashboard") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-default" style="border-radius:8px;box-shadow:1px 2px 3px lightgray;">
                        <div class="card-body py-1">
                            <div class="row">
                                <div class="form-group col-md-2 col-6 p-1 mb-0">
                                    <label>Type:</label>
                                    <select id="item_type"  onchange="filteritem()" class="form-control">
                                         @if($item_list->count()>0)
                                            <option value="">All Types</option>
                                            @foreach($item_list as $ilk=>$list)
												@if($list->coll_name != "")
                                                <option value="{{ $list->coll_name }}">{{ $list->coll_name }}</option>
												@endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
								@if($stock!='artificial')
                                <div class="form-group col-md-2 col-6 p-1 mb-0">
                                    <label for="weight">Weight Range:</label>
                                    <div class="input-group p-0 h-auto">
                                        <input type="text" name="start_w" class="form-control" id="start_w" placeholder="Start" onInput="applyfilter()">
                                        <input type="text" name="end_w" class="form-control" id="end_w" style="border-radius:0 5px 5px 0!important;" placeholder="End" onInput="applyfilter()">
                                    </div>
                                </div>
								@endif
                                @if($stock=='gold')
                                <div class="form-group col-md-2 col-12 p-1 mb-0 form-inline">
                                    <label class="col-md-12 col-4">Caret :</label>
                                    <select name="caret" class="form-control col-md-12 col-8" id="caret" onChange="applyfilter();">
                                        <option value="">All Caret </option>
                                        <option value="18">18K</option>
                                        <option value="20">20K</option>
                                        <option value="22">22K</option>
                                        <option value="24">24K</option>
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- left column -->
                 @if($stock_data && $stock_data->count() > 0 )
                    @foreach($stock_data as $sdk=>$data)
                        <div class="col-md-3 m-auto item_blocks" id="item_{{ strtolower(str_replace(" ","_",$data->coll_name)) }}">
                            @php 
                                $coll = strtolower($data->coll_name);
                            @endphp
                            <div class="card card-default stock_block  mt-4">
                                <button type="button" data-target="#stock_img_action_{{ $sdk }}" class="btn btn-sm btn-outline-secondary stock_img_action_button mt-1">
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul id="stock_img_action_{{ $sdk }}" class="stock_img_action">
                                    <li>
                                        <button type="button" href="javascript:void(null);" class="text-danger stock_img_remove"> &cross; Remove</button>
                                    </li>
                                    <li>
                                        <label for="stock_img_{{ $sdk }}" class="text-info stock_img_change"> &#10064; Upload</label>
                                        <!--<button type="button" class="text-info stock_img_change" onclick="$(this).closest('.stock_img_label').click()"> &#10064; Upload</button>-->
                                    </li>
                                </ul>
								@php 
									$route = "javascript:void(null);";
									if($stock=="gold"){
										$route = route('stock.new.dashboard',["{$stock}","{$coll}"]);
									}else{
										$route = route('stock.new.groupinventory',['stock'=>$stock,'item_type'=>$coll]);
									}
								@endphp
                                <a href="{{ $route }}" {!! ($stock=="gold")?'class="block_select"':'' !!} >
                                    <div class="card-header text-center p-1 stock_img_block" style="overflow:hidden;">
									
                                        <input type="file" name="stock_img" id="stock_img_{{ $sdk }}" class="stock_img" value="" style="display:none;"  accept="image/*">
										<input type="hidden" name="item_stock" id="item_stock" class="item_stock" value="{{ strtolower($stock) }}">
                                        <input type="hidden" name="item_name" id="item_name" class="item_name" value="{{ strtolower($coll) }}">
										@php 
                                            $coll_img_name = strtolower($coll);
                                            $shop_root = auth()->user()->shop_id."_".auth()->user()->branch_id;
                                            $img_path = "assets/images/stockdashboard/{$shop_root}/{$stock}_{$coll_img_name}.png";
                                            $coll_img_path = (file_exists("{$img_path}"))?asset("{$img_path}"):false;
                                        @endphp
                                        <img src="{{ @$coll_img_path }}" class="img-responsive stock_img_preview m-auto p-1" style="display:{{ ($coll_img_path)?'':'none'; }}">
                                    </div>
                                    </a>
                                    <div class="card-body p-1">
                                        <h6 class="text-center">{{ strtoupper($coll) }}</h6>
                                        <ul class="row p-0 stock_prop w-100 m-auto">
											
											@if($stock=='artificial')
                                                <li class="col-6 p-1">
                                                    <small>PIECES</small>
                                                    <strong>{{ $data->total_piece??'-'}}</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>COST</small>
                                                    <strong>{{ $data->total_cost??'-'}} Rs</strong>
                                                </li>
											@elseif($stock=='Franchise-Jewellery')
                                                <li class="col-6 p-1">
                                                    <small>GROSS</small>
                                                    <strong>{{ $data->total_gross??'-'}} gm</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>NET</small>
                                                    <strong>{{ $data->total_net??'-'}} gm</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>PIECES</small>
                                                    <strong>{{ $data->total_count??'-'}}</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>COST</small>
                                                    <strong>{{ $data->total_cost??'-'}} Rs</strong>
                                                </li>
                                            @else 
                                                <li class="col-6 p-1">
                                                    <small>PIECES</small>
                                                    <strong>{{ $data->total_count??'-'}}</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>GROSS</small>
                                                    <strong>{{ $data->total_gross??'-'}} gm</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>NET</small>
                                                    <strong>{{ $data->total_net??'-'}} gm</strong>
                                                </li>
                                                <li class="col-6 p-1">
                                                    <small>FINE</small>
                                                    <strong>{{ $data->total_fine??'-'}} gm</strong>
                                                </li>
                                            @endif
											
                                        </ul>
										<div class="filter_section" id="filter_{{strtolower(str_replace(" ","_",$coll)) }}">

                                        </div>
                                    </div>
                                </div>
                        </div>
                    @endforeach
                 @else 
                    <div class="col-md-4 col-12 text-center  p-2 text-danger" style="margin:10px auto;border:1px dashed red;border-radius:10px;">
                        No Items !
                    </div>
                 @endif
            </div>
        </div>
    </section>
    
    
    <div class="modal" tabindex="-1" role="dialog" id="stock_item_detail" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                
            </div>
        </div>
    </div>

    @endsection('content')

    @section('javascript')
    <script>
		function filteritem(){
            const val = $("#item_type").val()??false;
            if(val){
                const item = val.replace(/\s+/g, '_').toLowerCase();
                if($(`#item_${item}`).length){
                    $('.item_blocks').hide();
                    $(`#item_${item}`).show();
                }else{
                    toastr.error("No Item To Show !");
                }
            }else{
                $('.item_blocks').show();
            }
			applyfilter();
        }
		
		function applyfilter(){
            const item = $("#item_type").val()??'';
            const start = $("#start_w").val()??'';
            const end = $("#end_w").val()??'';
            const caret = $("#caret").val()??'';
            $(".filter_section").empty().hide();
            $.get(url,`item=${item}&caret=${caret}&start=${start}&end=${end}&filter=${true}`,function(response){
                if(response.data){
                    const data = response.data;
                    $.each(data,function(i,v){
                        var item = v.coll_name;
                        var add_on = wt_range = ''; 
                        if(caret){
                            add_on = caret+"K";
                        }
                        if(start!="" || end!=""){
                            const new_start = (start!="")?start:end;
                            const new_end = (end!="")?end:start;
                            wt_range = `<li class="text-center"><small><b>( <i>${start}gm-${end}gm</i> )</b></small></li>`;
                        }
                        const filter_block = "#filter_"+item.replace(/\s+/g, '_').toLowerCase();
                        const filter_data = `<h6 style="font-size:90%;color:blue;margin:0;">
                                                <b>FILTERED</b><span>${add_on}</span>
                                            </h6>
                                            <ul class="filtered_ul">
                                                ${wt_range}
                                                <li>
                                                    <small>PIECES : </small>
                                                    <strong>${v.total_count}</strong>
                                                </li>
                                                <li >
                                                    <small>GROSS : </small>
                                                    <strong>${(v.total_gross).toFixed(3)} gm</strong>
                                                </li>
                                                <li >
                                                    <small>NET : </small>
                                                    <strong>${(v.total_net).toFixed(3)} gm</strong>
                                                </li>
                                                <li >
                                                    <small>FINE</small>
                                                    <strong>${(v.total_fine).toFixed(3)} gm</strong>
                                                </li>
                                            </ul>`;
                        $(filter_block).empty().append(filter_data).show();
                    });
                    //const item = response.
                }
            });
        }
		
		$('.block_select').click(function(e){
            if ($(e.target).is('label, input[type="file"]')) {
                return; // let file browse happen, don't trigger modal
            }

            e.preventDefault();
            //const href = $(this).attr('href');
             $(".modal-content").load($(this).attr('href'),"",function(){
                $("#stock_item_detail").modal();
            });
        });
        $('.stock_img').change(function(){
			$(this).closest('.stock_img_block').addClass('uploading');
            const ind = $('.stock_img').index(this);
            
        //$(document).on('change', '#imageInput', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            // validate type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                $(this).val('');
                return;
            }

            // validate size (example: max 5MB)
            const maxBytes = 5 * 1024 * 1024;
            if (file.size > maxBytes) {
                alert('Image is too large. Max 5MB.');
                $(this).val('');
                return;
            }

            // create temporary object URL and show preview
            const url = URL.createObjectURL(file);
            $('.stock_img_preview').eq(ind).attr('src', url).show();
            $('.stock_img_action').eq(ind).hide();
            //$('#preview').attr('src', url).show();

            // revoke the object URL after image is loaded (free memory)
            $('.stock_img_preview').eq(ind).on('load', function () {
                URL.revokeObjectURL(url);
            });
            uploadStockImage(file,ind);
        });
		
		function uploadStockImage(file, ind) {
            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}"); 
            formData.append('item',$(".item_name").eq(ind).val()??'');
            formData.append('type',$(".item_stock").eq(ind).val()??'');
            formData.append('image', file);

            // Optional: show upload status or loading indicator
            $('.stock_img_status').eq(ind).text('Uploading...');

            $.ajax({
                url: "{{ route('stock.new.dashboard.thumbnail') }}",   // <-- your Laravel route
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.status){
                        const path = response.path;
                        $('.stock_img_preview').eq(ind).attr('src',path);
                        success_sweettoatr("Image Saved !");
                    }else{
                        toastr.error(response.msg);
                    }
					$('.stock_img_block').eq(ind).removeClass('uploading');
                    //$('.stock_img_status').eq(ind).text('Uploaded!');
                    //console.log('Image uploaded:', response);
                    // Optional: save uploaded path or ID in hidden field
                    //$('.stock_img_path').eq(ind).val(response.file);
                },
                error: function (xhr) {
                    $('.stock_img_status').eq(ind).text('Upload failed');
					$('.stock_img_block').eq(ind).removeClass('uploading');
                }
            });
        }

        $(".stock_img_action_button").click(function(e){
            e.preventDefault();
            const trgt = $(this).data('target');
            $(trgt).toggle('slide');
        });
		
		/*$(".stock_img_action_button").hover(function(e){
            e.preventDefault();
            const trgt = $(this).data('target');
            $(trgt).toggle('slide');
        });*/

        $('.stock_img_remove').click(function(e){
            e.preventDefault();
            var q = confirm("Sure to Remove ?");
            if(q==true){
                const index = $('.stock_img_remove').index(this);
                $(".stock_img_preview").eq(index).attr('src','').hide();
                $(".stock_img_action").eq(index).hide();
            }
        });
    </script>
    @endsection
        