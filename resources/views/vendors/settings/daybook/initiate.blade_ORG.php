@extends('layouts.vendors.app')

@section('stylesheet')
@endsection 

@section('content')
    @include('layouts.theme.css.datatable')

    @php 
        $anchor = ['<a href="'.route('shop.detail').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-eye"></i> Today</a>','<a href="'.route('shop.daybook').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-list"></i> List</a>'];
        $path = ["Day Book"=>route('shop.daybook')];
		$data = new_component_array('newbreadcrumb',"Day-Book Feed",$path) 
	@endphp 
    <x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <style>
        .day-book-card-title{
            margin:0;
        }
        #feed_block table th{
            padding:2px 5px;
            text-align: center;
        }
        #feed_block table td{
            padding:0;
        }
        #feed_block table tbody td input,#feed_block table tbody th input{
            border:unset;
            text-align: center;;
        }
        #feed_block table tbody th input.is-invalid,#feed_block table  tbody td input.is-invalid{
            border:1px dashed red;
        }
        #feed_block table tbody th input:focus,#feed_block table tbody td input:focus{
            border-bottom:1px dashed tomato;
        }
    </style>
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('shop.daybook.feed') }}" id="daybook_feed">
                                <div class="container" id="feed_block">
                                    <div class="row">
                                        <div class="col-md-12 p-0 mb-1">
                                            <div class="card card-dark">
                                                <div class="card-header p-1">
                                                    <h6 class="day-book-card-title">GOLD</h6>
                                                </div>
                                                <div class="card-body p-1  table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="bg-light">
                                                                <th class="text-dark" rowspan="2">KARET</th>
                                                                <th class="text-dark" colspan="2" >USUAL</th>
                                                                <th class="text-dark" colspan="2">LOOSE</th>
                                                                <th class="text-dark" colspan="2">BULLION</th>
                                                                <th class="text-dark" colspan="2">OLD</th>
                                                                <th class="text-dark" colspan="2">OTHER</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th> 
                                                                    14K
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    18K
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    20K
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    22K
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    24K
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="gold[other][fine]">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 p-0 mb-1">
                                            <div class="card card-dark">
                                                <div class="card-header p-1">
                                                    <h6 class="day-book-card-title">SILVER</h6>
                                                </div>
                                                <div class="card-body p-1 table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="bg-light">
                                                                <th class="text-dark" rowspan="2">PURITY %</th>
                                                                <th class="text-dark" colspan="2" >USUAL</th>
                                                                <th class="text-dark" colspan="2">LOOSE</th>
                                                                <th class="text-dark" colspan="2">BULLION</th>
                                                                <th class="text-dark" colspan="2">OLD</th>
                                                                <th class="text-dark" colspan="2">OTHER</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                                <th class="text-dark" >NET</th>
                                                                <th class="text-dark" >FINE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th> 
                                                                    <input type="text" class="form-control" name="silver[purity][]">
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    <input type="text" class="form-control" name="silver[purity][]">
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    <input type="text" class="form-control" name="silver[purity][]">
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    <input type="text" class="form-control" name="silver[purity][]">
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][fine]">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> 
                                                                    <input type="text" class="form-control" name="silver[purity][]">
                                                                </th>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[usual][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[loose][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][net]">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[bullion][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[old][fine]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][net]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="silver[other][fine]">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 p-0 mb-1">
                                            <div class="card card-dark">
                                                <div class="card-header p-1">
                                                    <h6 class="day-book-card-title">STONE</h6>
                                                </div>
                                                <div class="card-body p-1 table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="bg-light">
                                                                <th class="text-dark">COUNT</th>
                                                                <th class="text-dark">COST</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control" name="stone[count][]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="stone[cost][]">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 p-0 mb-1">
                                            <div class="card card-dark">
                                                <div class="card-header p-1">
                                                    <h6 class="day-book-card-title">ARTIFICIAL</h6>
                                                </div>
                                                <div class="card-body p-1 table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="bg-light">
                                                                <th class="text-dark">COUNT</th>
                                                                <th class="text-dark">COST</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control" name="art[count][]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="art[cost][]">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 p-0 mb-1">
                                            <div class="card card-dark">
                                                <div class="card-header p-1">
                                                    <h6 class="day-book-card-title">MONEY</h6>
                                                </div>
                                                <div class="card-body p-1">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="bg-light">
                                                                <th class="text-dark">CASH</th>
                                                                <th class="text-dark">BANK</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control" name="money[cash][]">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="money[bank][]">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <hr class="m-0 mb-2">
                                            <button type="submit" name="" value="feed" class="btn btn-sm btn-success">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    @include('layouts.theme.js.datatable')

@endsection