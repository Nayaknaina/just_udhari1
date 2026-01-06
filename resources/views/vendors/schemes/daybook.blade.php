@extends('layouts.vendors.app')


@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-3">
            <h1>Day Books</h1>
          </div>
          <div class="col-md-9">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"> <a href="#">Schemes</a> </li>
              <li class="breadcrumb-item active">Day Book</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid --
    </section>-->
	
@php 
$anchor = ['<a href="'.route('shopscheme.daybooksummery').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> Summery</a>'];
$path = ["Scheme Day Book Summery" =>route('shopscheme.daybooksummery')];
$data = new_component_array('newbreadcrumb',"Scheme Day Book",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor /> 
	
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card  card-primary" >
					{{--<div class="card-header">
                            <h3 class="card-title ">
                            <x-back-button />
                                <strong>Day Book <small>( Scheme )</small></strong>
                            </h3>
                        </div>--}}
                        <div class="card-body p-0">
                            <div class="col-12 p-2">
                                <form action="" class="m-0">
                                    <div class="row">
                                        <div class="col-12  text-center">
											<h3 class="text-secondary" style="text-shadow:2px 2px 3px gray;font-weight:bold;">{{ date("d-M-Y",strtotime($date)) }}
											</h3>
                                        </div>
                                        <div class="col-12 col-md-3  form-group">
                                            <div class="input-group my-1">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">Show entries</span>
                                                </div>
                                                    @include('layouts.theme.datatable.entry')
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <ul class="row txn_summery total my-1 text-center m-auto">
                                                <li class="col-md-3 col-6 card text-secondary">
                                                    <strong>TOTAL</strong> 
                                                    <hr>
                                                    <span id="total">0</span>
                                                </li>
                                                <li class="col-md-3 col-6 card text-danger">
                                                    <strong>BONUS</strong>
                                                    <hr>
                                                    <span id="bonus">0</span>
                                                </li>
                                                <li class="col-md-3 col-6 card text-success">
                                                    <strong>CASH</strong> 
                                                    <hr>
                                                    <span id="cash">0</span>
                                                </li>
                                                <li class="col-md-3 col-6 card text-info">
                                                    <strong>ONLINE</strong>
                                                    <hr>
                                                    <span  id="bank">0</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <style>
                                .txn_summery{
                                    list-style:none;
                                    padding:0;
                                }
                                .txn_summery > li{
                                    padding:2px;
                                }
                                .txn_summery > li >hr{
                                    margin:0;padding:0;
                                }
                               .txn_summery > li > strong,
                                .txn_summery > li > span{
                                    width:100%;
                                    display: block;
                               }
							    .txn_summery > li > span:after{
                                    content:" Rs.";
                               }
                               .txn_summery >li:nth-child(1)>hr{
                                    border-top:1px dashed violet;
                               }
                               .txn_summery >li:nth-child(2)>hr{
                                    border-top:1px dashed pink;
                               }
                               .txn_summery >li:nth-child(3)>hr{
                                    border-top:1px dashed lightgreen;
                               }
                               .txn_summery >li:nth-child(4)>hr{
                                    border-top:1px dashed #95b0f2;
                               }
                            </style>
                            <div class="table-responsive">
                                <table id="CsTable"class="table table_theme table-bordered table-stripped group-detail-table dataTable">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>DATE</th>
                                            <th class="text-center">Amnt IN</th>
                                            <th class="text-center">Amnt OUT</th>
                                            <th class="text-center">Balance</th>
                                            <th class="text-center">Holder</th>
											<th class="text-center">Scheme</th>
                                            <th class="text-center">Group</th>
                                            <th class="text-center">Customer</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center"><li class="fa fa-id-card"></li></th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-list">
                                       
                                    </tbody>
                                </table>
                            </div>
                            <div >
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div id="pageination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <style>
        
        .title_block{
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .title_value > .total::marker{
            content:"\0023  ";

        }
        .title_value > .received::marker{
            content:"\2713  ";
        }
        .title_value > .remains::marker{
            content:"\2717  ";
        }
        .title_value >li::marker{
            font-weight:bold;
            border:1px solid gray;
            padding:2px;
        }
        .title_value{
            border-left:1px solid lightgray;
        }
        table.group-detail-table > thead >tr>th{
            color:white;
            background:#66778c;
        } 
    </style>
@endsection

  @section('javascript')
     
  @include('layouts.theme.js.datatable')

  <script>

    var route = "{{ route('shopbranches.index') }}";
    
    function getresult(url) {
      $("#data-list").html('<tr><td colspan="11" class="text-center"><span><li class="fa fa-spinner fa-spin"></li>  Loading Content...</span></td></tr>');
      var data = {
              "entries": $(".entries").val(),
			  'date':"{{ $date }}",
          }
      $.get(url,data,function(response){
        $("#data-list").html(response.html);
        $("#pageination").html(response.paginate);
		if(response.sum){
            $.each(response.sum,function(i,v){
                $("#"+i).empty().text(v);
            })
        }
      });
    }

    $(document).on('click', '.pagination a', function (e) {
          e.preventDefault();
          var pageUrl = $(this).attr('href');
          getresult(pageUrl);

      });

    function changeEntries() {

      getresult(url);

    }
    getresult(url);

  </script>




  @endsection
