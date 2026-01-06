@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

@php

$data = component_array('breadcrumb' , 'Udhar Record',[['title' => 'Udhar Record ']] ) ;

@endphp

<x-page-component :data=$data />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h6 class="card-title col-12 p-0"><x-back-button /> Udhar Record  <a href="{{ route('udhar.create') }}" class="btn btn-sm btn-primary" style="float:right;"><li class="fa fa-plus"></li> Add New</a></h6>
                        </div>
                        <div class="card-body row">
                            <div class="col-12 p-0">
                                <div class="row">
                                    <div class="col-12 col-lg-8  form-group">
                                        <label for="">Head Search</label>
                                        <input type="text" id = "keyword" class = "form-control" placeholder = "Head Search (Enter Keyword )" oninput="changeEntries()" >
                                    </div>
                                    <div class="col-md-2  form-group">
                                        <label for="">Show entries</label>
                                        @include('layouts.theme.datatable.entry')
                                    </div>
                                </div>
                                <div class="table-responsive">  
                                    <table class="table table_theme table-bordered table-stripped DataTable" id="CsTable">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th colspan="3" ></th>
                                                <th colspan="4">Amount</th>
                                                <th colspan="4">Gold</th>
                                                <th colspan="4">Silver</th>
                                                <th colspan="2"></th>
                                            </tr>
                                            <tr class="bg-dark">
                                                <th >SN.</th>
                                                <th>DAte/Time</th>
                                                <th>C.No/C.Name/Source</th>
                                                <th>Old Amnt</th>
                                                <th>Amnt In</th>
                                                <th>Amnt Out</th>
                                                <th>Final Amnt</th>
                                                <th>Old Gold</th>
                                                <th>Gold In</th>
                                                <th>Gold Out</th>
                                                <th>Final Gold</th>
                                                <th>Old Silver</th>
                                                <th>Silver In</th>
                                                <th>Silver Out</th>
                                                <th>Final Silver</th>
                                                <th >Conversion</th>
                                                <th >Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_area">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12" id="paging_area">
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
@endsection