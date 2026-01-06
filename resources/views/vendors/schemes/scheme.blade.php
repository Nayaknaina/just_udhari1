@extends('layouts.vendors.app')
@section('css')

    @include('layouts.theme.css.datatable')

@section('content')

    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Groups Dues</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ $scheme->scheme_head }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid --
    </section>-->
@php 
$anchor = ['<a href="'.route('shopscheme.due').'" class="btn btn-sm btn-outline-info"><i class="fa fa-object-group"></i> All Dues</a>'];
$path = ["Scheme Dues"=>route('shopscheme.due')];
$data = new_component_array('newbreadcrumb',"Scheme Dues",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/>  
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-default" >
                        <div class="card-header p-1">
                            <h3 class="card-title text-secondary">
                                <strong>{{ $scheme->scheme_head }} </strong>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="col-12 p-2">
                            <form action="">
                                <div class="row">
                                    <div class="col-6 col-md-2 form-group">
                                        <label for="">Show entries</label>
                                        @include('layouts.theme.datatable.entry')
                                    </div>
                                </div>
                            </form>
                        </div>
                            <div class="table-responsive">
                                <table id="CsTable" class="table table_theme table-bordered table-stripped group-detail-table">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>GROUP</th>
                                            <th class="text-center">ENROLLED</th>
                                            <th class="text-center">PAYABLE</th>
                                            <th class="text-center">PAID</th>
                                            <th class="text-center">DUE</th>
                                            <th class="text-center">VIEW</th>
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
        a.overlay_anchor{
            text-align:center;
            color:#b7aeae;
            height:100%;
            width:100%;
            background:#00000094;
            position: absolute;
            font-size:200%;
            z-index:-1;
        }
        a.overlay_anchor.anchor_visible{
            z-index:1!important;
        }
        a.overlay_anchor:after{

        }
        a.overlay_anchor>li{
            padding:10%;
        }
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
      $("#data-list").html('<tr><td colspan="8" class="text-center"><span><li class="fa fa-spinner fa-spin"></li>  Loading Content...</span></td></tr>');
      var data = {
              "entries": $(".entries").val(),
              "month": $("#month").val(),
              "payment": $("#payment").val(),
              "custo": $("#custo").val(),
              "mob": $("#mob").val(),
          }
      $.get(url,data,function(response){
        $("#data-list").html(response.html);
        $("#pageination").html(response.paginate);
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

  <script>
    // $('.group_block').hover(function(){
    //     const a = $(this).find('a.overlay_anchor');
    //     alert($(a).html());
    //     if($(this).prev('.overlay_anchor').hasClass('anchor_visible')){
    //         $(this).prev('.overlay_anchor').addClass('anchor_visible');
    //     }else{
    //         $(this).prev('.overlay_anchor').removeClass('anchor_visible');

    //     }
    // });
    $(".group_block").mouseover(function () {
        const a = $(this).find('a.overlay_anchor');
        a.addClass('anchor_visible');
    });
    $(".group_block").mouseout(function () {
        const a = $(this).find('a.overlay_anchor');
        a.removeClass('anchor_visible');
    });
  </script>



  @endsection
