@extends('layouts.vendors.app')

@section('content')

    @php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Import Stock",$path) 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 
   
   {{--<x-page-component :data=$data />--}}
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-default">
                <div class="card-body p-2">
                    <div class="row">
                        <h6 class="col-12">&ldquo;The CSV File Must Have The Following Column Name(Top Most Row In CSV)&rdquo;</h3>
                        <div class="col-md-12">
                            <style>
                                #csv_column{
                                    list-style:inside decimal;;
                                    display: flex;
                                    flex-wrap: wrap;
                                }
                                #csv_column > li{
                                    margin: 5px auto;
                                    border:1px solid gray;
                                    padding:2px;
                                    border-radius:10px;
                                }
                                #csv_column > li > hr{
                                    margin:2px 0;
                                    padding:0;
                                    border-bottom:1px dashed gray;
                                }
                                #csv_column > li::marker{
                                    font-weight:bold;
                                    border:1px solid gray;
                                    color:blue;
                                }
                            </style>
                            <ol class="p-0 text-center" id="csv_column">
                                <li >
                                    <strong>NAME</strong>
                                    <hr> 
                                    <small>Item Name/Title</small></li>
                                <li >
                                    <strong>TAG</strong>
                                    <hr> 
                                    <small> Tag Code</small>
                                </li>
                                <li >
                                    <strong>HUID</strong>
                                    <hr> 
                                    <small> Huid</small>
                                </li>
                                <li >
                                    <strong>KARET</strong>
                                    <hr> 
                                    <small> KARET</small>
                                </li>
                                <li >
                                    <strong>PIECE</strong>
                                    <hr> 
                                    <small> Piece/Quantity/No. Of Items</small>
                                </li>
                                <li >
                                    <strong>GROSS</strong>
                                    <hr> 
                                    <small> Gross Weight</small>
                                </li>
                                <li >
                                    <strong>LESS</strong>
                                    <hr> 
                                    <small> Less Weight</small>
                                </li>
                                <li >
                                    <strong>NET</strong>
                                    <hr> 
                                    <small> Net Weight</small>
                                </li>
                                <li >
                                    <strong>TUNCH</strong>
                                    <hr> 
                                    <small> Tunch Value</small>
                                </li>
                                <li >
                                    <strong>WASTAGE</strong>
                                    <hr> 
                                    <small> Wastage VAlue</small>
                                </li>
                                <li >
                                    <strong>FINE</strong>
                                    <hr> 
                                    <small> Fine Weight</small>
                                </li>
                                <li >
                                    <strong>ADDON_COST</strong>
                                    <hr> 
                                    <small> Additionam Metal/Stone Cost</small>
                                </li>
                                <li >
                                    <strong>RATE</strong>
                                    <hr> 
                                    <small> Rate</small>
                                </li>
                                <li >
                                    <strong>LABOUR</strong>
                                    <hr> 
                                    <small> Labour Charge</small>
                                </li>
                                <li >
                                    <strong>LABOUR_UNIT</strong>
                                    <hr> 
                                    <small> Labour Charge Unit(Gm/%)</small>
                                </li>
                                <li >
                                    <strong>OTHER</strong>
                                    <hr> 
                                    <small> Any Other Extra Charges</small>
                                </li>
                                <li >
                                    <strong>DISCOUNT</strong>
                                    <hr> 
                                    <small> Discount Value</small>
                                </li>
                                <li >
                                    <strong>DISCOUNT_UNIT</strong>
                                    <hr> 
                                    <small> Discount Unit(Rs/%)</small>
                                </li>
                                <li >
                                    <strong>TOTAL</strong>
                                    <hr> 
                                    <small> Item Total Cost</small>
                                </li>
                            </ol>
                            <p style="font-weight:bold;"><small class="text-danger"><strong><b><u>NOTE</u> : </b> There Can be Less Column,The Column Name Can be Capital/Small Letters & Orderless But Can't Misspell.</strong></small>
							<a href="{{ route('stock.new.csv.sample') }}"  target="_blank" class="btn btn-sm btn-outline-info p-0 p-1" id="csv_sample_download"><i class="fa fa-download p-0 px-1"></i> .CSV Sample</a>
							</p>
                        </div>
                        <div class="col-md-12 m-auto">
                            <form id="inmort_form" enctype="multipart/form-data" method="post" action="{{ route('stock.new.inventory.import') }}">
                                <div class="row">
                                    @csrf
                                    <div class="col-md-2 col-6 my-2">
                                        <div class="input-group">
                                            <label for="stock" class="input-group-text p-0 px-2">Stock</label>
                                            <select class="form-control text-center" id="stock" name="stock">
                                                <option value="">___?</option>
                                                <option value="gold">Gold</option>
                                                <option value="silver">Silver</option>
                                                <option value="stone">Stone</option>
                                                <option value="artificial">Artificial</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 my-2">
                                        <div class="input-group">
                                            <label for="stock" class="input-group-text p-0 px-2">Type</label>
                                            <select class="form-control text-center" id="stock_type" name="stock_type">
                                                <option value="">___?</option>
                                                <option value="both">Both</option>
                                                <option value="loose">loose</option>
                                                <option value="tag">Tag</option>
                                            </select>
                                        </div>
                                    </div>
									<div class="col-md-2 col-12 my-2">
                                        <div class="input-group">
                                            <label for="group" class="input-group-text p-0 px-2">Group</label>
                                            <select class="form-control text-center my_select" id="group" name="group">
                                                @if($groups->count() > 0)
                                                    @foreach($groups as $jk=>$jstock)
                                                        @if($jstock->count() > 0)
                                                            <option value="" class="group_option {{ strtolower($jk) }}" style="display:none;">___?</option>
                                                            @foreach($jstock as $jsk=>$jwlry)
                                                            <option value="{{ $jwlry->id }}" class="group_option {{ strtolower($jk) }}" style="display:none">{{ $jwlry->item_group_name }}  ({{ $jwlry->cat_name }}/{{ $jwlry->coll_name }}}</small></span> </option>
                                                            @endforeach
                                                        @else 
                                                            <option value="">No Data</option>
                                                        @endif
                                                    @endforeach
                                                @else 
                                                    <option value="">No Data</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-9 my-2">
                                        <!--<input type="file" class="form-control p-0" id="csv_file" name="csv_file" value="" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">-->
                                        <input type="file" class="form-control p-0" id="csv_file" name="csv_file" value="" accept=".csv">
                                    </div>
                                    <div class="col-md-2 col-3 text-center my-2">
                                        <button type="submit" name="do" value="import" class="btn btn-sm btn-info w-100">Go</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class = "row">
                <div class="col-12">
                </div>
            </div> -->
        </div>
    </section>

@endsection

@section('javascript')

    <script>
	
	$("#stock").change(function(){
        $('#group').find('option.group_option').hide();
		$('#group').val("");
        $('#group').find(`option.${$(this).val()}`).show();
    });
	
    $("#inmort_form").submit(function(e){
         e.preventDefault();
         const path = $(this).attr('action');
         const formdata = new FormData(this);
        $.ajax({
            url: path,   // The URL to send the request to
            type: "POST",               // Request type: GET or POST
            data:formdata, 
            contentType: false, // important for file upload
            processData: false, // prevent jQuery from processing data          // Expected data type (json, html, text, script)
            success: function(response) {
               $("#process_wait").hide();
                // What happens if request succeeds
                if(response.status){
                    success_sweettoatr(response.msg)
                    location.href = response.next;
                }else if(response.errors){
                    $.each(response.errors,function(i,v){
                        $('#'+i).addClass('is-invalid');
                        toastr.error(v);
                    })
                }else{ 
                    toastr.error(response.msg);
                }
                //console.log("Response:", response);
            },
            error: function(xhr, status, error) {
                $("#process_wait").hide();
                // What happens if request fails
                console.error("Error:", error);
            }
        });
     });
    </script>
@endsection