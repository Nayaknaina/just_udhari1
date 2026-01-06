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
        tr.element_tr table{
            width:100%;
            border:1px solid lightgray;
        }
        td input:hover,td select:hover{
            border-bottom:1px dashed gray!important;
        }
        tr.element_tr table thead th{
            padding:1px 2px;
            font-weight:normal;
            font-style:italic;
            border:1px solid lightgray;
            text-align:center;
            text-transform: uppercase;
            font-size:80%;
            text-wrap: nowrap;
            background:transparent;
            text-shadow:1px 2px 3px lightgray;
        }
        tr.element_tr table tbody td{
            font-size:70%;
            padding:0px;
        }
         tbody tr td input:not([type="checkbox"]),
         tbody tr td select{
            background:transparent;
            padding:2px 1px;
            text-align: center;
            -webkit-appearance: none;  /* Safari, Chrome */
            -moz-appearance: none;     /* Firefox */
            appearance: none;          /* Standard */
            background-image: none;    /* Remove arrow if browser uses background image */
        }
        tr.element_tr table tbody td:first-child{
            text-align: center;
            font-weight:bold;
            vertical-align: middle;
        }
        tr.element_tr table tbody td:last-child{
            vertical-align: middle;
        }
        label.ele_del_check_label{
            padding: 0 1px;
            line-height:1;
            background:white;
            border:1px dotted maroon;
            cursor:pointer;
        }
        label.ele_del_check_label:after{
            content:"\2713";
            color:lightgray;
        }
        label.ele_del_check_label.checked{
            border:1px solid red;
        }
        label.ele_del_check_label.checked:after{
            color:red;
        }
        tr.to-delete >td:not(:last-child),
        tr.to-delete >td:not(:last-child) input,
        tr.to-delete >td:not(:last-child) select{
            pointer-events: none;
            opacity:0.7;
            color:red!important;

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
                        <div class="col-12">
                            <form id="stock_edit_form" action="{{ route('stock.new.edit',['stock'=>$selected_stock]) }}" method="post">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table_theme table-bordered">
                                        <thead id="data_thead">
                                            <tr>
                                                <th>SN</th>
                                                <th>ITEM</th>
                                                <th>TAG</th>
                                                <th>RFID</th>
                                                <th>HUID</th>
                                                <th>CARET</th>
                                                <th>Quant</th>
                                                <th>GROSS</th>
                                                <th>LESS</th>
                                                <th>NET</th>
                                                <th>TUNCH</th>
                                                <th>WSTG.</th>
                                                <th>FINE</th>
                                                <th>ST.CH.</th>
                                                <th>Lbr.</th>
                                                <th>Other</th>
                                                <th>Rate</th>
                                                <th>Disc.</th>
                                                <th>IMAGE</th>
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
                                                    <label style="text-wrap:nowrap;">{{ $stock->name }}</label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border tag item_input" name="tag[]" id="tag_{{ $ski }}" value="{{ $stock->tag }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border rfid item_input" name="rfid[]" id="rfid_{{ $ski }}" value="{{ $stock->rfid }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border huid item_input" name="huid[]" id="huid_{{ $ski }}" value="{{ $stock->huid }}">
                                                </td>
                                                <td>
                                                    <select class="form-control no-border caret item_input px-1 text-center" name="caret[]" id="caret_{{ $ski }}" readonly>
                                                        <option value="">_?</option>
                                                        <option value="18" {{ ($stock->caret=='18')?'selected':'' }}>18K</option>
                                                        <option value="20" {{ ($stock->caret=='20')?'selected':'' }} >20K</option>
                                                        <option value="22" {{ ($stock->caret=='22')?'selected':'' }} >22K</option>
                                                        <option value="24" {{ ($stock->caret=='24')?'selected':'' }} >24K</option>
                                                    </select>
                                                    {{--<input type="text" class="form-control no-border caret item_input" name="caret[]" id="caret_{{ $ski }}" value="{{ $stock->caret }}">--}}
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border count item_input" name="count[]" id="count_{{ $ski }}" value="{{ $stock->count }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border gross item_input" name="gross[]" id="gross_{{ $ski }}" value="{{ $stock->gross }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border less item_input" name="less[]" id="less_{{ $ski }}"  value="{{ $stock->less }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border net item_input" name="net[]" id="net_{{ $ski }}"  value="{{ $stock->net }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border tunch item_input" name="tunch[]" id="tunch_{{ $ski }}"  value="{{ $stock->tunch }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border wstg item_input" name="wstg[]" id="wstg_{{ $ski }}"  value="{{ $stock->wastage }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border fine item_input" name="fine[]" id="fine_{{ $ski }}"  value="{{ $stock->fine }}">
                                                </td>
                                                <td>
                                                    <div class="input-group element_chrg w-100">
                                                        <input type="text" class="form-control no-border chrg item_input" name="chrg[]" id="chrg_{{ $ski }}"  value="{{ $stock->element_charge }}">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-sm add_assos_element px-1 py-0 form-control no-border h-auto m-0" data-target="ele_area_{{ $ski }}"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control no-border lbr item_input" name="lbr[]" id="lbr_{{ $ski }}" value="{{ $stock->labour }}">
                                                    
                                                        <select class="form-control no-border lbrunit item_input px-1 text-center" name="lbrunit[]" id="lbrunit_{{ $ski }}">
                                                            <option value="">_?</option>
                                                            <option value="w" {{ ($stock->labour_unit=='w')?'selected':'' }}>Gm.</option>
                                                            <option value="p" {{ ($stock->labour_unit=='p')?'selected':'' }} >%</option>
                                                        </select>
                                                    </div> 
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border other item_input" name="other[]" id="other_{{ $ski }}" value="{{ $stock->chrgs }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control no-border rate item_input" name="rate[]" id="rate_{{ $ski }}" value="{{ $stock->rate }}">
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control no-border disc item_input" name="disc[]" id="disc_{{ $ski }}" value="{{ $stock->discount }}">
                                                        <select class="form-control no-border discunit item_input px-1 text-center" name="discunit[]" id="discunit_{{ $ski }}">
                                                            <option value="">_?</option>
                                                            <option value="r" {{ ($stock->discount=='r')?'selected':'' }}>Rs.</option>
                                                            <option value="p" {{ ($stock->discount=='p')?'selected':'' }}>%</option>
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
                                                    <input type="text" class="form-control no-border ttl item_input" name="ttl[]" id="ttl_{{ $ski }}" value="{{ $stock->total }}">
                                                </td>
                                            </tr>
                                                <tr class="element_tr" id="ele_tr_{{ $ski }}" style="display:{{ ($stock->elements->count()>0)?'':'none' }};">
                                                    <td colspan="20">
                                                        <table >
                                                            <thead>
                                                                <tr>
                                                                    <th>SN</th>
                                                                    <th>Element/Stone</th>
                                                                    <th>Caret</th>
                                                                    <th>Part</th>
                                                                    <th>Colour</th>
                                                                    <th>Piece</th>
                                                                    <th>Clarity</th>
                                                                    <th>Gross</th>
                                                                    <th>Less</th>
                                                                    <th>Net</th>
                                                                    <th>Tunch</th>
                                                                    <th>Wastage</th>
                                                                    <th>Fine</th>
                                                                    <th>Rate</th>
                                                                    <th>Cost</th>
                                                                    <th><label  class="btn btn-sm btn-danger m-0 p-0 px-1">&cross;</label></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="ele_area_{{ $ski }}">
                                                                
                                                             @if($stock->elements->count()>0)
                                                                @foreach($stock->elements as $ei=>$element) 
                                                                    <tr class="item_ele item_ele_{{ $ski }}">
                                                                        <td>
                                                                            <input type="hidden" name="ele_ids[{{ $ski }}][]" value="{{ $element->id }}">
                                                                            {{ $ei+1 }}
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="ele_name[{{ $ski }}][]" class="form-control no-border ele_name" value="{{ $element->element }}" required>
                                                                        </td>
                                                                        <td>
                                                                            @php 
                                                                                $caret_sel =  $element->caret??false;
                                                                                if($caret_sel){
                                                                                    $caret_sel = "k_".$element->caret;
                                                                                    $$caret_sel = 'selected';
                                                                                }

                                                                            @endphp
                                                                            <select name="ele_caret[{{ $ski }}][]" class="form-control no-border ele_caret">
                                                                                <option value="">__?</option>
                                                                                <option value="18" {{ @$k_18 }}>18K</option>
                                                                                <option value="20" {{ @$k_20 }}>20K</option>
                                                                                <option value="22" {{ @$k_22 }}>22K</option>
                                                                                <option value="24" {{ @$k_24 }}>24K</option>
                                                                            </select>   
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_part" name="ele_part[{{ $ski }}][]" value="{{ $element->part }}">    
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_colour" name="ele_colour[{{ $ski }}][]" value="{{ $element->colour }}">     
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_piece"  name="ele_piece[{{ $ski }}][]" value="{{ $element->piece }}"> 
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_clear"  name="ele_clear[{{ $ski }}][]" value="{{ $element->clarity }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_gross" name="ele_gross[{{ $ski }}][]" value="{{ $element->gross }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_less" name="ele_less[{{ $ski }}][]"  value="{{ $element->less }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_net" name="ele_net[{{ $ski }}][]"  value="{{ $element->net }}" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_tunch"  name="ele_tunch[{{ $ski }}][]"  value="{{ $element->tunch }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_wstg" name="ele_wstg[{{ $ski }}][]"  value="{{ $element->wastage }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_fine" name="ele_fine[{{ $ski }}][]"  value="{{ $element->fine }}" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_rate" name="ele_rate[{{ $ski }}][]"  value="{{ $element->rate }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control no-border ele_cost" name="ele_cost[{{ $ski }}][]"  value="{{ $element->cost }}">
                                                                        </td>
                                                                        <td class="text-danger text-center">
                                                                            <label  class="ele_del_check_label m-0"><input type="checkbox" name="del_ele_id[{{ $ski }}][]" class="form-control ele_del_check" value="{{ $element->id }}" style="display:none;"></label>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="w-100 text-center">
                                    <button type="submit" name="update" value="stock" class="btn btn-sm btn-success">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
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
@endsection

@section('javascript')
@php 
    $js_file = ($selected_stock=='franchise-jewellery')?'franchise':strtolower($selected_stock);
    $edit = true;
@endphp
@include("vendors.stocks.newpage.itemforms.js.{$js_file}calculate",compact('edit'))
@if($edit)
@include("vendors.stocks.newpage.itemforms.js.editelementcalculate")
@endif
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