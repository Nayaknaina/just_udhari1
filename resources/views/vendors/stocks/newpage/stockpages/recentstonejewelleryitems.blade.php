@if($stock_data->count()>0)
    <style>
        tbody#item_data_area > tr>td{
            font-size:12px;
        }
        table.element_table{
            width:100%;
        }
        table.element_table > thead>tr>th,table.element_table > tbody>tr>td{
            padding:2px 5px;
        }
        table.element_table > thead>tr>th{
            border:1px solid lightgray;
        }
    </style>
    <div class="row">
        <div class="col-md-6 form-inline">
            <div class="input-group m-1">
                <div class="input-group-text p-1">
                    <label for="stock_type" class="m-0">STOCK </label>
                </div>
                <label class="form-control">{{ $entry_data->stock_type }}</label>
            </div>
            <div class="input-group m-1">
                <div class="input-group-text p-1">
                    @php $entry_arr = ['manu'=>'Manual','tag'=>'With Tag']; @endphp
                    <label for="stock_type" class="m-0">ENTRY  </label>
                </div>
                <label class="form-control">{{ $entry_arr[$entry_data->entry_mode] }}</label>
            </div>
        </div>
        <div class="col-md-6 form-inline mb-1">
            <div class="input-group" style="margin-left:auto;">
                <label class="form-control w-auto">ENTRY : {{ $entry_data->entry_num }}</label>
                <label class="form-control w-auto">DATE : {{ date('d-M-Y',strtotime($entry_data->entry_date)) }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table_theme">
                <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>TAG</th>  
                        <th>HUID</th>
                        <th>CARET</th>
                        <th>PIECE</th>
                        <th>GROSS</th>
                        <th>LESS</th>
                        <th>NET</th>
                        <th>TUNCH</th>
                        <th>WASTAGE</th>
                        <th>FINE</th>
                        <th>ST.CH.</th>
                        <th>RATE</th>
                        <th>LABOUR</th>
                        <!--<th width="50px">ON</th>-->
                        <th>OTHER</th>
                        <th>DISC.</th>
                        <!--<th width="50px">ON</th>-->
                        <th>IMAGE</th>
                        <th>RFID</th>  
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody class="billing item_tbody" id="item_data_area">
                    @php 
                       $count = $ttl_pc = $ttl_gross = $ttl_net = $ttl_fine = $ttl_stn = $ttl_other = $ttl_ttl = 0;
                    @endphp
                    @foreach($stock_data as $stock_k=>$stock)
                    <tr>
                        <td>{{ $stock->name }}</td>
                        <td class="text-center">{{ $stock->tag }}</td> 
                        <td class="text-center">{{ $stock->huid }}</td> 
                        <td class="text-center">{{ $stock->caret?$stock->caret."K":'' }}</td> 
                        <td class="text-center">{{ @$stock->count }}</td> 
                        <td class="text-center">{{ $stock->gross }}</td> 
                        <td class="text-center">{{ $stock->less }}</td> 
                        <td class="text-center">{{ $stock->net }}</td> 
                        <td class="text-center">{{ $stock->tunch }}</td> 
                        <td class="text-center">{{ $stock->wastage }}</td> 
                        <td class="text-center">{{ $stock->fine }}</td> 
                        <td class="text-center">{{ $stock->element_charge }}</td> 
                        <td class="text-center">{{ $stock->rate }}</td> 
                        @php $chrg_unit = ["w"=>'Gm',"p"=>"%","r"=>"Rs"] @endphp
                        <td class="text-center">{{ $stock->labour }} {{ $chrg_unit[$stock->labour_unit] }}</td> 
                        <td class="text-center">{{ $stock->charge }}</td> 
                        <td class="text-center">{{ $stock->discount }}{{ $chrg_unit[$stock->discount_unit] }}</td> 
                        <td class="text-center">
                            @if($stock->image)
                                <button type="button" data-image="{{ asset($stock->image) }}" class="btn btn-sm btn-outline-info item_image_prev px-1 py-0" >&check; YES</button>
                            @else 
                                <i>-</i>
                            @endif
                        </td> 
                        <td class="text-center">{{ $stock->rfid }}</td>   
                        <td class="text-center">{{ $stock->total  }}</td> 
                    </tr>
                    @if($stock->elements->count() > 0 )
                        <tr>
                            <td></td>
                            <td colspan="17">
                                <table class="element_table">
                                    <thead>
                                        <tr>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stock->elements as $stke=>$element)
                                            <tr>
                                                <td>{{ $element->element }}</td>
                                                <td>{{ $element->caret }}</td>
                                                <td>{{ $element->part }}</td>
                                                <td>{{ $element->colour }}</td>
                                                <td>{{ $element->piece }}</td>
                                                <td>{{ $element->clarity }}</td>
                                                <td>{{ $element->gross }}</td>
                                                <td>{{ $element->less }}</td>
                                                <td>{{ $element->net }}</td>
                                                <td>{{ $element->tunch }}</td>
                                                <td>{{ $element->wastage }}</td>
                                                <td>{{ $element->fine }}</td>
                                                <td>{{ $element->rate }}</td>
                                                <td>{{ $element->cost }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td></td>
                        </tr>
                        @php 
                            $count++;
                            $ttl_pc += $stock->count??0;
                            $ttl_gross += $stock->gross??0;
                            $ttl_net += $stock->net??0;
                            $ttl_fine += $stock->fine??0;
                            $ttl_stn += $stock->element_charge??0;
                            $ttl_other += $stock->charge??0;
                            $ttl_ttl += $stock->total??0;;
                        @endphp
                    @endif
                    @endforeach
                    @if($count < 6)

                    @endif
                </tbody>
                <tfoot>
                    <tr>
                    <td ><label class="form-control m-0 text-center">{{ $count }}</label></td>
                    <td colspan="3"></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_pc }}</label></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_gross }}</label></td>
                    <td ></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_net }}</label></td>
                    <td colspan="2"></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_fine }}</label></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_stn }}</label></td>
                    <td colspan="2"></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_other }}</label></td>
                    <td colspan="3"></td>
                    <td><label class="form-control m-0 text-center">{{ @$ttl_ttl }}</label></td>
                    </tr>
                </tfoot>
            </div>
        </div>
    </div>
@else 
<p class="text-center text-warning"><b>No Recent Stock !</b></p>
@endif