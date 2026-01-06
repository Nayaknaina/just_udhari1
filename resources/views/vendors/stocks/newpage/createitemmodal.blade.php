<div class="modal" tabindex="-1" role="dialog" id="newmodal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header py-0" style="background-color:#ff6c0021;border-bottom:1px solid lightgray;">
                <h5 class="modal-title"> Create Item </h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-2" id="form_model">
                <form role="form" name="newitem" id="newitem" action="{{ route('stock.create.item') }}">
                    @csrf
                    <div class="row">
                        <div class="form-group mb-2 col-md-6">
                            <label for="item_name" class="mb-0">Item Name</label>
                            <input type="text" class="form-control name" value="" id="item_name" name="item_name">
                        </div>
                        <div class="form-group mb-2 col-md-6">
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
                                    <button type="button" class="btn btn-sm btn-dark m-0" onclick="$('#block_back').addClass('active');$('#item_group_name').focus()">
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
                            <div class="input-group" >
                                <input type="text" class="form-control col-8 mb-0" id="tax_value" name="tax_value" placeholder="GST" oninput="decimalonly(event,2);">
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
                                 <label class="form-control px-1" for="both"> <input type="radio" name="method" value="both" id="both" checked> Both</label>
                                <label class="form-control  px-1" for="loose"> <input type="radio" name="method" value="loose" id="loose"> Loose</label>
                                <label class="form-control  px-1"><input type="radio" name="method" value="tag" id="tag"> Tag</label>
                            </div>
                        </div>
                        <div class="form-group mb-2 col-md-6">
                            <label class="mb-0">Tag</label>
                            <div class="input-group" >
                                <input type="text" class="form-control col-5 mb-0 text-right" id="tag_prefix" name="tag_prefix" placeholder="Prefix">
                                <input type="number" class="form-control col-7 mb-0" id="tag_digit" name="tag_digit"  placeholder="Digits" max="5" min="1" title="Max 5 Digits" oninput="digitonly(event,1)">
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
                        <h6 class="col-12 my-1 p-0 text-primary" id="dflt_sl_val"><span> Default Sale Value</span></h6>
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
								<input type="text" class="form-control" id="item_tunch" name="item_tunch" value="" oninput="decimalonly(event,2);tunchkaretconvert($(this));">
								<label class="input-group-text py-0" style="border-radius:0 5px 5px 0;">%</label>
							</div>
                        </div>
                        <div class="form-group mb-2 col-md-2 col-5 p-0">
                            <label for="item_wastage" class="mb-0"> Wastage</label>
                            <input type="text" class="form-control" id="item_wastage" name="item_wastage" value="" oninput="decimalonly(event,3);">
                        </div>
                        <div class="form-group mb-2 col-md-4 col-7 p-0">
                            <label  class="mb-0"> Labour</label>
                            <div class="input-group" >
                                <input type="text" class="form-control mb-0" id="lbr_value" name="lbr_value" placeholder="Charge" oninput="decimalonly(event,3)">
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
                        <div class="col-12 text-center mb-1">
                            <button name="create" id="create" value="item" class="btn btn-sm btn-success btn-round"> Create </button>
                        </div>
                    </div>
                    </div>
                </form>
                <div class="col-12" id="recent_items">
                    <table class="table table-bordered">
                        <tbody id="recent_items_table" class="default">
                            <tr><td class="text-info text-center p-1" > Recently Created !</td></tr>
                        </tbody>
                    </table>
                </div>
                <style>
                    #block_back{
                        position:absolute;
                        top:0%;
                        left:0;
                        width:100%;
                        height:100%;
                        bottom: 0;
                        background: #0000009c;
                        display:none;
                        z-index: 2;
                    }
                    #block_back.active{
                        display:block;
                    }
                    .drop_down_ul{
                        position:absolute;
                        bottom:0;
                        width:100%;
                        display:none;
                    }
                </style>
                <div class="col-12 pt-5" id="block_back">
                    <div class="w-100 bg-white" >
                        <form role="form" name="newitemgroup" id="newitemgroup" action="{{ route('stock.create.item',['group']) }}" autocomplete="off">
                            @csrf
                            <div class="form-group p-2 mb-1" style="border:1px dashed gray;">
                                <h5>
                                    <label for="item_group_name">Create Item Group</label>
                                    <button type="button" class="btn btn-sm btn-danger icon-btn py-0 px-1" style="float:right;" onclick="$('#block_back').removeClass('active');$('#newitemgroup').trigger('reset');$('#item_group_cat_list , #item_group_col_list').hide();$('#item_group_block').addClass('default').empty().append(`<li class='text-info text-center w-100'>Recently Addedd !</li>`);">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </h5>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 col-12 p-0 mb-1">
                                            <label class="m-0">Category</label>
                                            <select name="item_group_cat" class="form-control" id="item_group_cat">
                                                <option value="">Find Type</option>
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