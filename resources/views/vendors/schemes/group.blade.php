@extends('layouts.vendors.app')

@section('content')
@include('layouts.theme.css.datatable')
    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-md-3">
            <h1>Customers Dues</h1>
          </div>
          <div class="col-md-9">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"> <a href="#">{{ $group->schemes->scheme_head }}</a> </li>
              <li class="breadcrumb-item active">{{ $group->group_name }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid --
    </section>-->
@php 
$anchor = ['<a href="'.route('shopscheme.due').'" class="btn btn-sm btn-outline-info"><i class="fa fa-object-group"></i> All Dues</a>'];
$path = ["Scheme Dues"=>route('shopscheme.due')];
$bread_data = new_component_array('newbreadcrumb',"Scheme Group Dues",$path) 
@endphp 
<x-new-bread-crumb :data=$bread_data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card  card-default" >
                        <div class="card-header p-1">
                            <h3 class="card-title text-secondary">
                                <strong>{{ $group->group_name }} <small><i> {{ $group->schemes->scheme_head }} </i></small></strong>
                            </h3>
                        </div>
                        <div class="card-body p-1">
                            <div class="col-12 p-0">
                            <form action="">
                                <div class="row">
									@if($group->schemes->scheme_date_fix=='1')
									@php 
										$start_month_num = date('m',strtotime($group->schemes->scheme_date));
										//echo $start_month_num."<br>";
										$start_month_name = date('M',strtotime($group->schemes->scheme_date));
										//echo $start_month_name."<br>";
										$curr_month_num = date('m',strtotime('now'));
										//echo $curr_month_num."<br>";
										$curr_emi_num = ($curr_month_num > $start_month_num)?(($curr_month_num-$start_month_num)+1):(($curr_month_num < $start_month_num)?(12-($start_month_num -$curr_month_num)+1):1);
										//echo $curr_emi_num."<br>";
										
									@endphp
									
                                    <div class="col-6 col-md-2 form-group">
                                        <label for="">Month</label>
                                        <select name="month" id="month" class="form-control" onchange="changeEntries()">
                                            <option value="" {{ (isset($data) && $data=='all')?'selected':'' }}>ALL</option>
                                            @for($i=0;$i<$group->schemes->scheme_validity;$i++)
                                                <option value="{{ $i+1 }}" {{ (!isset($data))?(($i+1 == $curr_emi_num)?'selected':''):'' }}>{{ date('M',strtotime("{$start_month_name}+ $i Month")) }}</option>
                                            @endfor
                                        </select>
                                    </div>
									@else 
										@php  $sup_arr = ["st",'nd','rd']; @endphp
                                    <div class="col-6 col-md-2 form-group">
                                    <label for="">Emi Num.</label>
                                    <select name="month" id="month" class="form-control" onchange="changeEntries()">
                                        <option value="" {{ (isset($data) && $data=='all')?'selected':'' }}>ALL</option>
                                        @for($i=1;$i<=$group->schemes->scheme_validity;$i++)
                                        <option value="{{ $i }}">{{  $i }}{{ $sup_arr[$i-1]??'th' }}</option>
                                        @endfor
                                    </select>
                                    </div>
									@endif
                                    <div class="col-12 col-md-2 form-group">
                                        <label for="">Payment</label>
                                        <select name="payment" id="payment" class="form-control" onchange="changeEntries()">
                                            <option value="" {{ (isset($data) && $data=='all')?'selected':'' }}>ALL</option>
                                            <option value="1">Paid</option>
                                            <option value="0" {{ (!isset($data))?'selected':'' }} >Un-Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="">Customer</label>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="text" name="custo" id="custo" class="form-control" placeholder="Name" oninput="changeEntries()">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="mob" id="mob" class="form-control" placeholder="Mobile" oninput="changeEntries()">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-2 form-group">
                                        <label for="">Show entries</label>
                                        @include('layouts.theme.datatable.entry')
                                    </div>
                                </div>
                                
                            </form>
                        </div>
						
                                <table id="CsTable" class="table table_theme table-bordered table-stripped group-detail-table dataTable">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Name</th>
                                            <th class="text-center">Contact</th>
                                            <th class="text-center">EMI CHOOSED</th>
                                            <th class="text-center">EMI COVERED</th>
                                            <th class="text-center">PAID</th>
                                            <th class="text-center">DUE</th>
                                            <th class="text-center">TXNs</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-list">
                                       
                                    </tbody>
                                </table>
                            <div>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-0">
                            <div id="pageination"></div>
                        </div>
                    </div>
                </div> 
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="modal" tabindex="-1" role="dialog" id="view_txn_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
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
	var url = url.split('?')[0] ;
    function getresult(url) {
		$('#CsTable').DataTable().destroy();
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
			$('#CsTable').DataTable();
			$("#pageination").html(response.paginate);
		  });
		/*$('#data-list').DataTable().destroy();
        $("#data-list").html(response.html);
		$('#data-list').DataTable();
        $("#pageination").html(response.paginate);
      });*/
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
