@extends('layouts.vendors.app')
@section('content')
@include('layouts.theme.css.datatable')
    @php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import'').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Import Stock",$path) 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <x-page-component :data=$data />
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    <div class="row">
                        @php 
                            $selected_stock = "";
                            if(isset($type)){
                                $selected_stock = strtolower($type);
                                $$selected_stock = 'selected';
                            }
                        @endphp
                        <div class="form-inline col-md-6">
                            <div class="input-group m-1">
                                <label for="stock" class="input-group-text p-1">Stock</label>
                                <select name="stock" id="stock" class="form-control w-auto" onchange="changeEntries();">
                                    <option value="Gold" {{ @$gold }}>Gold</option>
                                    <option value="Silver" {{ @$silver }}>Silver</option>
                                    {{--<option value="Artificial" {{ @$artificial }}>Artificial</option>--}}
                                </select>
                            </div>
                            @php 
                            $selected_stock_type = "";
                                if(isset($type)){
                                    $selected_stock_type = strtolower($sub);
                                    $$selected_stock_type = 'selected';
                                }
                            @endphp
                            <div class="input-group m-1">
                                <label for="stock_type" class="input-group-text p-1">Type</label>
                                <select name="stock_type" id="stock_type" class="form-control w-auto" onchange="changeEntries();">
                                    <option value="" >Both</option>
                                    <option value="Tag" {{ @$tag }}>Tag</option>
                                    <option value="Loose" {{ @$loose }}>loose</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-inline col-md-6">
                            <div class="input-group" style="margin-left:auto;">
                                <a href="{{ route('stock.new.inventory.import') }}" class="btn btn-sm btn-success m-auto px-2 py-1">
                                    <i class="fa fa-download"></i> Excel
                                </a>
                                <a href="{{ route('stock.new.inventory.export',['pdf']) }}" class="btn btn-sm btn-outline-danger m-auto px-2 py-1 export">
                                    <i class="fa fa-upload"></i> Pdf
                                </a>
                                <a href="{{ route('stock.new.inventory.export',['excel']) }}" class="btn btn-sm btn-outline-success  m-auto px-2 py-1 export">
                                    <i class="fa fa-upload"></i> Excel
                                </a>
                            </div>
                            <div class="input-group m-1" >
                                <label for="entries" class="input-group-text p-1">Entry</label>
                                <select name="entries" id="entries" class="form-control w-auto" onchange="changeEntries();">
                                    <option value="10" >10</option>
                                    <option value="50" selected >50</option>
                                    <option value="100" >100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="CsTable" class="table_theme table table-bordered">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>