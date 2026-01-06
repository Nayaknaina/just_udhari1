@extends('layouts.vendors.app')
@include('layouts.theme.css.datatable')
@section('content')
@php 
$data = new_component_array('newbreadcrumb',"Scheme Due List") 
@endphp 
<x-new-bread-crumb :data=$data /> 
	 <style>
        .remains_month{
            list-style:none;
            padding:2px;
        }
        ul.remains_month > li > span{
            float:right;
        }
		ul.remains_month > li{
			margin:2px 0;
		}
    </style>
   
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card  card-primary" >
                        <div class="card-body p-0">
                            <div class="col-12 p-2">
                            <form action="" class="m-0">
                                <div class="row">
                                    
                                    <div class="col-12 col-md-4 form-group">
                                        <label for="">Customer</label>
                                            <input type="text" name="custo" id="custo" class="form-control" placeholder="Name" oninput="changeEntries()">
                                    </div>
                                    <div class="col-12 col-md-4 form-group">
                                        <label for="">Schemes</label>
                                        <select name="scheme" class="form-control" id="scheme" onChange="loadgroup();changeEntries();">
                                            @if($schemes->count()>0)
                                            <option value="">Select</option>
                                            @foreach($schemes as $schk=>$scheme)
                                            <option value="{{ $scheme->id }}">{{ $scheme->scheme_head }}</option>
                                            @endforeach
                                            @else 
                                            <option value="">No Data !</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2 form-group">
                                        <label for="">Groups</label>
                                        <select name="group" class="form-control" id="group" onChange="changeEntries();">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
									
									<div class="col-6 col-md-1 form-group p-0">
                                        <label for="">Export</label>
                                        <div class="input-group">
                                            <a href="{{ route('shopscheme.due.export',['pdf']) }}" class="btn btn-sm btn-outline-danger export m-0 form-control" target="_blank" id="pdf_export" title="More than 100 Record Can't Export !" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-file-pdf"></i>Pdf </a>
                                            <a href="{{ route('shopscheme.due.export',['excel']) }}" class="btn btn-sm btn-outline-success export m-0 form-control" target="_blank" id="exl_export"> <i class="fa fa-file-excel"></i>Xcel </a>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-1 form-group">
                                        <label for="">entries</label>
                                        @include('layouts.theme.datatable.entry')
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                            <div class="table-responsive">
                                <table id="CsTable" class="table_theme table table-bordered table-stripped group-detail-table ">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Customer</th>
                                            <th class="text-center">Contact</th>
                                            <th class="text-center">Scheme</th>
                                            <th class="text-center">Group</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-list">
                                       
                                    </tbody>
                                </table>
								<div class="col-12 text-center" id="loader" style="display:none;">
                                    <i><span class="fa fa-spinner fa-spin"></span> Loading Content ...</i>
                                </div>
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
@endsection

  @section('javascript')
     
  @include('layouts.theme.js.datatable')

  <script>

    var route = "{{ route('shopbranches.index') }}";
    //var url = url.split('?')[0] ;
    function getresult(url) {
	  $("#loader").show();
	  $('#CsTable').DataTable().destroy();
	  $("#data-list").empty();
	  $("#pageination").empty();
      /*$("#data-list").html('<tr><td colspan="8" class="text-center"><span><li class="fa fa-spinner fa-spin"></li>  Loading Content...</span></td></tr>');*/
      var data = {
              "entries": $(".entries").val(),
              "scheme": $("#scheme").val(),
              "group": $("#group").val(),
              "custo": $("#custo").val(),
          }
      $.get(url,data,function(response){
		$("#loader").hide();
        $("#data-list").html(response.html);
		(response.total_page>100)?$("#pdf_export").addClass('disabled'):$("#pdf_export").removeClass('disabled');
        $('#CsTable').DataTable();
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

	function loadgroup(){
        var scheme = $("#scheme").val()??false;
        if(scheme){
            $.get("{{ route('shopschemes.getgroup') }}","scheme_id="+scheme,function(response){
                var option = '<option   value="">Loading....</option>';
				$("#group").empty().append(option);
                if(response[0].length>0){
                    option = '<option value="">Select Group !</option>';
                    $.each(response[0],function(i,v){
                         option+=`<option value="${v.id}">${v.group_name}</option>`;
                    });
                }else{
                    option = `<option value="">No Group !</option>`;
                }
                $("#group").empty().append(option);
            });
        }
    }

	$(".export").click(function(e){
        e.preventDefault();
        var data = {
            "scheme": $("#scheme").val(),
            "group": $("#group").val(),
            "custo": $("#custo").val(),
        }
        var queryString  = $.param(data); 
        const url = $(this).attr('href')+'?'+queryString;
        window.open(url, '_blank');
        //window.open($(this).attr('href')+'?'+$.param(data),'_blank');
        // $.get($(this).attr('href')+'?'+queryString,'',function(response){
        //     var printWindow = window.open(response);
        //     printWindow.onload = function () {
        //         printWindow.focus();
        //         printWindow.print();
        //     };
        // });
    });

  </script>




  @endsection
