@extends('layouts.vendors.app')
@section('content')
@include('layouts.theme.css.datatable')

 @php 
	$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import Stock</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add Stock</a>'];
	$path = ["Stocks"=>route('stock.new.dashboard')];
	$data = new_component_array('newbreadcrumb',"Item name & Group",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    
<style>
	tbody.data_area > tr >td,tbody.data_area > tr >td >ul{
		font-size:90%;
	}
	div.edit{
		border: 1px dashed blue;
	}
	#item_group_block.edit #item_group_block_title:before{
		content:"Edit ";
	}
	
	#item_group_block.edit #create>span:before{
		content:"Update";
	}
	#item_group_block #item_group_block_title:before{
		content:"Create New ";
	} 
	#item_group_block  #create>span:before{
		content:"Create";
	}
	tr.edited_data{
		/*background-color: lightblue;*/
		/*background-color: #e9f2ff;*/
	}
	tr.edited_data > td{
		border-top: 1px dashed blue !important;
		border-bottom: 1px dashed blue !important;
		/*box-shadow: 1px 1px 1px inset!important;*/
		/* opacity: 0.5; */
		color: #0000a7 !important;
	}
	tr.deleted_data > td{
		border-top: 1px dashed red !important;
		border-bottom: 1px dashed red !important;
		/*box-shadow: 1px 1px 1px inset!important;*/
		opacity: 0.5;
		color: red !important;
	}
</style>
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-12" style="box-shadow:-1px 2px 3px 4px rgb(255, 202, 164);border-radius:15px;"  id="item_group_block">
                            <h5 class="col-12 p-1 text-primary" style="border-bottom:1px solid #f0c0c0;">
							<span id="item_group_block_title"></span> Item</h5>
                            <form role="form" name="newitem" id="newitem" action="{{ route('stock.create.item') }}" method="post" >
                                @csrf                  
                                <div class="row">
                                    <div class="form-group mb-2 col-12">
										<input type="hidden" name="item_id" value="" id="item_id">
                                        <label for="item_name" class="mb-0">Item Name</label>
                                        <input type="text" class="form-control name" value="" id="item_name" name="item_name">
                                    </div>
                                    <div class="form-group mb-2 col-12">
                                        <label for="item_coll" class="mb-0">Item Group</label>
                                        <div class="input-group">
                                            <select name="item_group" class="form-control" id="item_group">
                                                @php 
                                                    $groups = itemgroups();
                                                @endphp
                                                @if($groups->count() > 0)
                                                    <option value="">Select </option>
                                                    @foreach($groups as $grpi=>$group)
                                                        <option value="{{ $group->id }}">{{ $group->item_group_name }}</option>
                                                    @endforeach
                                                @else 
                                                    <option value="" class="default">No Data ! </option>
                                                @endif
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-sm btn-dark m-0" onclick="$('#item_group_name').focus()" data-toggle="modal" data-target="#item_group_modal">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-2 col-md-6">
                                        <label for="item_hsn" class="mb-0">HSN Code</label>
                                        <input type="text" class="form-control" id="item_hsn" name="item_hsn" placeholder="HSN Code" oninput="digitonly(event,4)">
                                    </div>
                                    <div class="form-group mb-2 col-md-6">
                                        <label class="mb-0">Tax</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control col-8 mb-0" id="tax_value" name="tax_value" placeholder="GST"  oninput="decimalonly(event,2);">
                                            <select class="form-control mb-0 text-center" name="tax_unit" id="tax_unit">
                                                <option value="">Unit</option>
                                                <option value="p">%</option>
                                                <option value="r">Rs</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-2 col-md-6">
                                        <label for="item_hsn" class="mb-0">Stock Method</label>
                                        <div class="input-group">
                                            <label class="form-control px-1 h-auto" for="both"> <input type="radio" name="method" value="both" id="both" checked=""> Both</label>
                                            <label class="form-control  px-1 h-auto" for="loose"> <input type="radio" name="method" value="loose" id="loose"> Loose</label>
                                            <label class="form-control  px-1 h-auto" for="tag"><input type="radio" name="method" value="tag" id="tag"> Tag</label>
                                        </div>
                                    </div>
                                    <div class="form-group mb-2 col-md-6">
                                        <label class="mb-0">Tag</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control col-5 mb-0 text-right" id="tag_prefix" name="tag_prefix" placeholder="Prefix">
                                            <input type="number" class="form-control col-7 mb-0" id="tag_digit" name="tag_digit" style="border-radius:0 5px 5px 0!important;" placeholder="Digits" max="5" min="1" title="Max 5 Digits"  oninput="digitonly(event,1)">
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    #dflt_sl_val{
                                        position:relative;
                                    }
                                    #dflt_sl_val > span{
                                        border:1px solid gray;
                                        border-radius:5px;
                                        padding:2px;
                                        position:relative;
                                        z-index:1;
                                        background-color:white;
                                    }
                                    #dflt_sl_val:after{
                                        content:"";
                                        position:absolute;
                                        top:50%;
                                        border-bottom:1px dashed gray;
                                        left:0;
                                        width:100%;
                                    }
                                </style>
								<div class="col-12">
									<div class="row">
										<h6 class="col-12 my-1 p-0" id="dflt_sl_val"><span><small> Default Sale Value</small></span></h6>
										<div class="form-group mb-2 col-md-3 col-6 p-0">
											<label for="item_karet" class="mb-0"> Karet</label>
											<div class="input-group">
												<input type="text" class="form-control text-center" id="item_karet" name="item_karet" value="" oninput="digitonly(event,2);tunchkaretconvert($(this));">
												<label class="input-group-text py-0" style="border-radius:0 5px 5px 0;">K</label>
											</div>
										</div>
										<div class="form-group mb-2 col-md-3 col-6 p-0">
											<label for="item_tunch" class="mb-0"> Tunch</label>
											<div class="input-group">
												<input type="text" class="form-control" id="item_tunch" name="item_tunch" value=""  oninput="decimalonly(event,2);tunchkaretconvert($(this));">
												<label class="input-group-text py-0" style="border-radius:0 5px 5px 0;">%</label>
											</div>
										</div>
										<div class="form-group mb-2 col-md-2 col-5 p-0">
											<label for="item_wastage" class="mb-0"> Wastage</label>
											<input type="text" class="form-control" id="item_wastage" name="item_wastage" value=""  oninput="decimalonly(event,3);">
										</div>
										<div class="form-group mb-2 col-md-4 col-7 p-0">
											<label class="mb-0"> Labour</label>
											<div class="input-group">
												<input type="text" class="form-control mb-0" id="lbr_value" name="lbr_value" placeholder="Charge" oninput="decimalonly(event,3);">
												<select class="form-control mb-0 text-center" name="lbr_unit" id="lbr_unit">
													<option value="">Unit</option>
													<option value="p">%</option>
													<option value="w">Gm</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<hr class="my-2 p-0 col-12" style="border-top:1px solid lightgray;">
										<input type="hidden" name="operation" value="" id="operation">
										<div class="col-6 text-center mb-1">
											<button  id="reset_form" type="reset" class="btn btn-sm btn-outline-info btn-round ">Reset</button>
										</div>
										<div class="col-6 text-center mb-1">
											<button name="create" id="create" value="item" type="submit" class="btn btn-sm btn-success btn-round"><span id=""></span></button>
										</div>
									</div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8 col-12 ">
                            <div class="form-inline mb-1" id="filter_area">
                                <select name="type" id="type" class="form-control m-auto"  onchange="changeEntries();">
                                    <option value="">Matterial ?</option>
                                    @foreach(categories(1,true) as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <select name="cat" id="cat" class="form-control m-auto"  onchange="changeEntries();">
                                    <option value="">Type/Jewellery</option>
                                    @foreach(categories(3) as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="keyword" id="keyword" placeholder="Group/Item" class="form-control m-auto"  oninput="changeEntries();">
                                <div class="input-group  m-auto">
                                    <select name="entries" id="entries" class="form-control px-1 text-center"  onchange="changeEntries();">
                                        <option value="10">10</option>
                                        <option value="20" selected>20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <label class="input-group-text p-0" style="border-radius:0 5px 5px 0;">Entry</label>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="CsTable" class="table table_theme table-bordered">
                                    <thead id="data_thead">
                                        <tr>
                                            <th>SN</th>
                                            <th>ITEM</th>
                                            <th>GROUP</th>
                                            <th>HSN</th>
                                            <th>METHOD</th>
                                            <th>PURITY</th>
                                            <th>OTHER</th>
                                            <th>&vellip;</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="text-center text-primary" id="data_loader">
                                    <span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span>
                                </div>
                            </div>
                            <div class="paging col-12" id="paging">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" tabindex="-1" role="dialog" id="item_group_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-1" style="background-color:#ff6c0021;border-bottom:1px solid lightgray;">
                    <h5 class="modal-title">New Item Group</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-1">
                    <div class="col-12 p-2" id="block_back">
                        <div class="w-100 bg-white" >
                            <form role="form" name="newitemgroup" id="newitemgroup" action="{{ route('stock.create.item',['group']) }}" autocomplete="off">
                                @csrf
                                <div class="form-group p-0 mb-1" style="border-bottom:1px dashed gray;">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-6 col-12 p-0 mb-1">
                                                <label class="m-0">Matterial</label>
                                                <select name="item_group_cat" class="form-control" id="item_group_cat">
                                                    <option value="">Find Matterial</option>
                                                    @foreach(categories(1,true) as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-12 p-0 mb-1">
                                                <label class="m-0">Type/Jewellery</label>
                                                <select name="item_group_col" class="form-control my_select" id="item_group_col">
                                                    <option value="">Find Jewellery</option>
                                                    @foreach(categories(3) as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 p-0 mb-1">
                                                <label class="m-0">Group Name/Title</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control w-auto" id="item_group_name" name="item_group_name" placeholder="Group Title !">
                                                    <div class="input-group-append">
                                                        <button type="submit" name="save" value="item_group" class="btn btn-sm btn-success m-0">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 pb-2">
                                    <ul id="item_group_block" class="default p-0 m-0 w-100" style="list-style:none;display:inline-flex;flex-wrap:wrap;">
                                        <li class="text-info text-center w-100">Recently Addedd !</li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')'
@include('layouts.theme.js.datatable')

<script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
	$('select.my_select').myselect96(true);
    function getresult(url) {
        $("tbody.data_area").remove();
        $('#CsTable').DataTable().destroy();
		$('#CsTable').find('tbody').remove();  // remove old tbody
		$('#CsTable').find('tfoot').remove();  // 
        $("#data_loader").show();
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $("#entries").val(),
                "type": $("#type").val()??'',
                "cat":$('#cat').val()??'',
                "keyword":$("#keyword").val()??'',
            },
            success: function (data) {
                $("#data_loader").hide();
                $(document).find('.data_area').remove();
                $(data.html).insertAfter('thead#data_thead');
                $("#paging").html(data.paging);
                $('#CsTable').DataTable();
            },
            error: function (data) {
                $("#data_loader").hide();
            },
        });
    }

    getresult(url) ;

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var pageUrl = $(this).attr('href');
        getresult(pageUrl);

    });

    function changeEntries() {

        getresult(url) ;

    }

	function tunchkaretconvert(input){
        const field = input.attr('id');
        const value = input.val()??false;
        if(value && value!=""){
            if(field == 'item_karet'){
                $("#item_tunch").val(Math.round(100/24 * value));
            }
            if(field == 'item_tunch'){
                $("#item_karet").val(Math.round(24/100 * value));
            }
        }else{
            $("#item_tunch,#item_karet").val("");
        }
    }

	
	
	const create_url  = "{{ route('stock.create.item') }}";
    $("#newitem").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = $(this).serialize();
        $(this).find('.form-control').removeClass('is-invalid');
        $.post(path,formdata,function(response){
			if(response.update){
                if(response.status){
                    success_sweettoatr(response.msg);
                    /*$("#newitem").attr('action',create_url);
                    $("#newitem").trigger('reset');
                    $("#newitem").find('select,input,radio').prop('disabled',false);
                    $("#item_id").val('');
                    $("#operation").val('');
                    $("#item_group_block").removeClass('edit');*/
                    location.reload();
                }else{
                    if(response.errors){
                        $.each(response.errors,function(ei,ev){
                            $("#"+ei).addClass('is-invalid');
                            toastr.error(ev);
                        });
                    }else{
                        toastr.error(response.msg);
                    }
                }
            }else{
				if(response.done){
					success_sweettoatr(response.done);
					$("#recent_items").show();
					let name = $("#item_name").val()??false;
					let group = $('#item_group option:selected').text()??false;
					if(name && group ){
						let tr = `<tr>
									<th class="p-1">${name}</th>
									<td class="p-1">${group}</td>
									</tr>`;
						if($("#recent_items_table").hasClass('default')){
							$("#recent_items_table").removeClass('default').empty().append(tr);
						}else{
							$("#recent_items_table").append(tr);
						}
					}
					$("#newitem").trigger('reset');
				}else if(response.fail){
					toastr.error(response.fail);
				}else if(response.errors){
					$.each(response.errors,function(ei,ev){
						$("#"+ei).addClass('is-invalid');
						toastr.error(ev);
					});
				}
			}
        });
    });

    $("#newitemgroup").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = $(this).serialize();
        $(this).find('.form-control').removeClass('is-invalid');
        $.post(path,formdata,function(response){
            if(response.done){
                success_sweettoatr(response.done);
                if(response.item_group){
                    var count = $(document).find('li.items_group_li').length;
                    //alert(count);
                    var li = `<li class="border border-info p-1 m-1 items_group_li "><span class="badge badge-sm badge-info">${count+1}</span> ${response.item_group.item_group_name}</li>`;
                    if($("#item_group_block").hasClass('default')){
                        $("#item_group_block").removeClass('default');
                        $("#item_group_block").empty().append(li);
                    }else{
                        $("#item_group_block").append(li);
                    }
                    if($("#item_group option:selected").hasClass('default')){
                        $("#item_group option:selected.default").text('Select ')
                    }
                    $("#item_group").append(`<option value="${response.item_group.id}">${response.item_group.item_group_name}</option>`);
                }
                if(response.coll){
                    $("select#item_group_col").append(`<option value="${response.coll.id}">${response.coll.name}</option>`);
                    $('#item_group_col').redraw(true);
                }
                if(response.cat){
                    $("select#item_group_cat").append(`<option value="${response.cat.id}">${response.cat.name}</option>`);
                    //$('#item_group_cat').redraw();
                }
                $("#newitemgroup").trigger('reset');
            }else if(response.fail){
                toastr.error(response.fail);
            }else if(response.errors){
                $.each(response.errors,function(ei,ev){
                    $("#"+ei).addClass('is-invalid');
                    toastr.error(ev);
                });
            }
        });
    });
	
	$(document).on('click','.edit_btn',function(e){
        e.preventDefault();
        const update_url = $(this).attr('href');
        $(document).find('tbody>tr').removeClass('edited_data');
        $(this).closest('tr').addClass('edited_data')
        $.get($(this).attr('href'),'',function(response){
            if(response.status){
                $("#newitem").attr('action',update_url);
                const data = response.data;
                $("#item_id").val(data.id??'');
                if(data.exist > 0){
                     $("#item_name").prop('disabled',true);
                     $("#item_group").prop('disabled',true);
                     if(data.stock_method!='both'){
                         if(data.stock_method=='tag'){
                            /*$("#tag_prefix,#tag_digit").prop('disabled',true)*/
                            $("#loose").prop('disabled',true);
                         }else{
                             $("#tag").prop('disabled',true);
                         }
                     }
                }else{
                    $("#item_name").prop('disabled',false);
                     $("#item_group").prop('disabled',false);
                     $('input[name="method"]').prop('disabled',false);
                }
                $("#item_name").val(data.item_name??'');
                $("#item_group").val(data.group_id??'');
                $("#item_hsn").val(data.hsn_code??'');
                $("#tag_prefix").val(data.tag_prefix??'');
                $("#tag_digit").val(data.tag_digit??'');
                $("#lbr_value").val(data.labour_value??'');
                $("#lbr_unit").val(data.labour_unit??'');
                $("#tax_value").val(data.tax_value??'');
                $("#tax_unit").val(data.tax_unit??'');
                $("#item_karet").val(data.karet??'');
                $("#item_tunch").val(data.tounch??'');
                $("#item_wastage").val(data.wastage??'');
                $(`#${data.stock_method}`).prop('checked',true);
                $("#operation").val('edit');
                $("#item_group_block").addClass('edit');
            }else{
                toastr.error(response.msg);
                $("#reset_form").click();
            }
        });
    });
	
	$(document).on('click','.delete_btn',function(e){
        e.preventDefault();
        var self_tr  = $(this).closest('tr');
        var q = confirm("Sure to Delete ?");
        if(q==true){
            $.get($(this).attr('href'),"",function(response){
                if(response.status){
                    success_sweettoatr(response.msg);
                    if($("#item_group_block").hasClass('edit')){
                        $("#reset_form").click();
                    }
                    self_tr.removeClass('edited_data');
                    self_tr.addClass('deleted_data');
                }else{
                    toastr.error(response.msg);
                }
            });
        }
    });
	
	$("#reset_form").click(function(){
        resettheform();
    });
	
	function resettheform(){
        $("#newitem").attr('action',create_url);
        $("#item_id,#operation").val('');
        $("#item_group_block").removeClass('edit');
        $('#newitem').find('input,select,radio').prop('disabled',false);
        $(document).find('tbody>tr').removeClass('edited_data');
    }
	
</script>
@endsection