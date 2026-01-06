@extends('layouts.vendors.app')

@section('stylesheet')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Schemes Pay',[['title' => 'Schemes Pay']] ) ;
    
    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Scheme Payments") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12"> 
                  <div class="card">
                    <!--<div class="card-header">
                      <h3 class="card-title  text-primary">
                        Find Customer To Pay
                      </h3>
                    </div>-->
                    <div class="card-body p-0">
                      <div class="col-12 p-2">
                        <form name="" action="" role="" id="" >
						
							<div class="container p-0"> 
								<div class="row">
									<div class="col-md-6 col-12">
										<label for="" class="mb-1">Customer 
											<small><label class="text-success mb-0"><input type="checkbox" name="winner" value="yes" class="" id="winner" onChange="changeEntries()"> Winner</label></small>
											<small><label class="text-danger mb-0"><input type="checkbox" name="withdraw" value="yes" class="" id="withdraw" onChange="changeEntries()"> Withdraw</label></small>
										</label>
										<div class="container">
											<div class="row">
												 <input type="text" name="custo" id="custo" class="form-control col-md-8 col-12" placeholder="Name" oninput="changeEntries()">
												 <input type="text" name="mob" id="mob" class="form-control col-md-4 col-12" placeholder="Mobile" oninput="changeEntries()">
											</div>
										</div>
									</div>
									<div class=" col-md-2  col-12">
										<label for="" class="mb-1">Assigned ID</label>
										<input type="text" name="assign" class="form-control" id="assign" oninput="changeEntries()">
									</div>
									<div class="col-md-4 col-12">
										<label for="" class="mb-1">EnrollMent</label>
										<div class="input-group">
											<input type="date" name="start" id="start" class="form-control date_range text-center" placeholder="start Date" onchange="changeEntries()">
											<input type="date" name="end" id="end" class="form-control date_range  text-center" placeholder="Launch Date" onchange="changeEntries()"  style="border-radius:0 5px 5px 0!important;">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12 col-md-7">
									  <label for="" class="mb-1"> Scheme </label>
									  <select name="scheme" id="scheme" class="form-control" onchange="changeEntries();getgroups();">
										<option value="">Select</option>
										@foreach($active_schemes as $sk=>$scheme)
										<option value="{{ $scheme->id }}">{{ $scheme->scheme_head }} ( {{ $scheme->scheme_sub_head }} )</option>
										@endforeach
									  </select>
									</div>
									<div class="col-8 col-md-3">
										<label for="" class="mb-1"> Group </label>
										<select name="group" id="group" class="form-control" onchange="changeEntries()">
											<option value="">Select</option>
										</select>
									</div>
									<div class="col-4 col-md-2">
										<label for="" class="mb-1">Entries</label>
										@include('layouts.theme.datatable.entry')
									</div>
								</div>
							</div>
							
							{{--
                          <div class="row">
						  
							
                            <div class="col-12 col-md-9">
                              <label for="">
								Customer 
								<small><label class="text-info"><input type="checkbox" name="winner" value="yes" class="" id="winner" onChange="changeEntries()"> Winner</label></small>
								</label>
                              <div class="row">
                                <div class="col-md-7 input-group">
									
                                  <input type="text" name="custo" id="custo" class="form-control" placeholder="Name" oninput="changeEntries()">
                                </div>
                                <div class="col-md-5">
                                  <input type="text" name="mob" id="mob" class="form-control" placeholder="Mobile" oninput="changeEntries()">
                                </div>
                              </div>
                            </div>
						  
                            <div class="col-md-3">
                              <label for="">Assigned ID</label>
                              <input type="text" name="assign" class="form-control" id="assign" oninput="changeEntries()">
                            </div>
							
                            <div class="col-md-5">
                              <label for="">Scheme</label>
                              <select name="scheme" id="scheme" class="form-control" onchange="changeEntries();getgroups();">
                                <option value="">Select</option>
                                @foreach($active_schemes as $sk=>$scheme)
                                <option value="{{ $scheme->id }}">{{ $scheme->scheme_head }} ( {{ $scheme->scheme_sub_head }} )</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-2">
                              <label for="">Group</label>
                              <select name="group" id="group" class="form-control" onchange="changeEntries()">
                                <option value="">Select</option>
                              </select>
                            </div>
                           
                            <div class="col-12 col-md-5">
                              <div class="row">
                                <div class="col-12">
                                  <label for="">Enrollment</label>
                                </div>
                                <div class="col-12 col-md-6  form-group">
                                  <input type="date" name="start" id="start" class="form-control date_range text-center" placeholder="start Date" onchange="changeEntries()">
                                </div>
                              
                                <div class="col-12 col-md-6  form-group">
                                  <input type="date" name="end" id="end" class="form-control date_range  text-center" placeholder="Launch Date" onchange="changeEntries()">
                                </div>
                              </div>
                            </div>
                            <div class="col-12 col-lg-2 form-group">
                                <label for="">Show entries</label>
                                @include('layouts.theme.datatable.entry')
                            </div>
                          </div>--}}
                        </form>
                      </div>
                      <div class="table-responsive" id="table_content">
                        <table id="CsTable" class="table table_theme table-bordered scheme-custo-table dataTable">
						  <thead>
							  <tr>
								  <th>S.N.</th>
								  <th>CUSTOMER</th>
								  <th class="text-center">ENROLL</th>
								  <th class="text-center">PAYMENT</th>
								  <th>ACTION</th>
							  </tr>
						  </thead>
						  <tbody id="data-list">
						  </tbody>
						</table>
                      </div>
                    </div>
                    <div class="card-footer bg-white py-0" id="pagination">
                    </div>
                  </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <style>
      table.scheme-custo-table > thead >tr>th{
        color:white;
		background:#66778c;
      }	  
	  span.winner-crown{
		  font-weight: bold;
		  font-size: 200%;
		  color: #ff4800;
	  }
    </style>

@endsection


@section('javascript')


  <script>
    function check_date_range(){
      var valid = true;
        if($("#start").val()!="" && $("#end").val()!=""){
          if($('#end') < $("#start")){
            valid = false;
          }
        }else{
          if(($("#start").val()!="" && $("#end").val()=="") || ($("#start").val()=="" && $("#end").val()!="")){
              valid = false;
          }
        }
      return valid;
    }

    function getgroups(){
      if($("#scheme").val()!=""){
        $("#group").empty().append('<option value="">Loading..</option>');
        $.get("{{ route("shopschemes.getgroup") }}",{ scheme_id: $("#scheme").val() },function(response){
          if(response[0].length > 0){
            $("#group").empty().append('<option value="">Select</option>');
            $.each(response[0], function(index, group){
              $("#group").append('<option value="'+group.id+'">'+group.group_name+'</option>');
            });
          }else{
            $("#group").empty().append('<option value="">No Data !</option>');
          }
        });
      }else{
        $("#group").empty().append('<option value="">Select</option>');
      }
    }

    function getresult(url) {
      if(check_date_range()){
        $("#data-list").html('<tr><td colspan="5" class="text-center"><span><li class="fa fa-spinner fa-spin"></li>  Loading Content...</span></td></tr>');
          var data = {
                "entries": $(".entries").val(),
                "scheme": $("#scheme").val()??"",
                "group": $("#group").val()??'',
                "start": $("#start").val(),
                "end": $("#end").val(),
                "custo": $("#custo").val(),
                "mob": $("#mob").val(),
                "assign": $("#assign").val(),
            }
			if($("#winner").prop('checked')==true){
				data+="&winner=yes";
			}
			if($("#withdraw").prop('checked')==true){
				data+="&withdraw=yes";
			}
          $.get(url,data,function(response){
			$('#table_content').html(response.html);
			//$('#CsTable').DataTable().destroy();
            //$("#data-list").html(response.html);
            $("#pagination").html(response.paginate);
			//$("#CsTable").DataTable();
          });
      }else{
        toastr.error("Please recheck the Date Range");
      }
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

@include('layouts.theme.js.datatable')
  @endsection
