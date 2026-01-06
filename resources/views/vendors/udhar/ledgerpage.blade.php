@extends('layouts.vendors.app')

@section('css')

<link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
    @include('layouts.theme.css.datatable')
<style>
    .input-group.h-32px > select,.input-group.h-32px > .input-group-append{
        height:32px;
    }
	.input-group.h-32px{
		gap:0;
	}
	ul.pagination{
		margin-bottom:5px;
		font-size:90%;
	}
</style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Udhar Record',[['title' => 'Udhar Record ']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('udhar.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="'.route('udhar.index').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> List</a>'];
$data = new_component_array('newbreadcrumb',"Udhar Ledger") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
					{{--<div class="card-header">
						<h6 class="card-title col-12 p-0"><x-back-button /> Udhar Record <a href="{{ route('udhar.create') }}" class="btn btn-sm btn-primary" style="float:right;"><li class="fa fa-plus"></li> Add New</a></h6>
					</div>--}}
                        <div class="card-body row pt-2">
                            <div class="col-12 p-0">
                                <div class="row">
                                    <div class="col-12 col-lg-6  form-group mb-1">
                                        <input type="text" id = "keyword" class = "form-control h-32px border-dark" placeholder = "C. name/Number/Mobile (Enter Keyword )" oninput="changeEntries()" >
                                    </div>
                                    <div class="col-md-2 col-12 form-group offset-md-4 mb-1">
                                        <div class="input-group mb-0 h-32px">
                                            @include('layouts.theme.datatable.entry')
                                            <div class="input-group-append">
                                                <label class="input-group-text" id="basic-addon1">Entry</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">  
                                    <table id="CsTable" class="table table_theme table-bordered table-stripped" >
                                        <thead>
                                            <tr class="">
                                                <th >SN.<i class="fa fa-envelope"></i></th>
                                                <!--<th>DAte/Time</th>-->
                                                <th>Name/No.</th>
                                                <th>Contact No.</th>
                                                <th>AMOUNT</th>
                                                <th>GOLD</th>
                                                <th>SILVER</th>
                                                <th><i class="fa fa-eye"></i></th>
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

@section('javascript')

@include('layouts.theme.js.datatable')
<script>
    var route = "{{ route('udhar.ledger') }}";
      const loading_tr = '<tr><td colspan="8" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
      function getresult(url) {
            $("#loader").show();
            $("#data_area").html(loading_tr);
			//$('tfoot').remove();
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "keyword":$("#keyword").val(),
              },
              success: function (data) {
                $("#loader").hide();
                /*$("#data_area").html(data.html);*/
                $(document).find("#data_area").replaceWith(data.html);
                $("#paging_area").html(data.paging);
                //$("#pagination-result").html(data.html);
              },
              error: function (data) {
                $("#loader").hide();
              },
          });
      }

      getresult(url) ;

      $(document).on('click', '.pagination a', function (e) {

              e.preventDefault();
              var pageUrl = $(this).attr('href');
              getresult(pageUrl);

      });

      function changeEntries() {

      getresult(url) ;

      }
	  
	  $(document).on('change','.sms_send_check',function(){
        if($('.sms_send_check:checked').length >0){
            $('#sms_send_btn').removeClass('btn-outline-info').addClass('btn-info');
        }else{
            $('#sms_send_btn').removeClass('btn-info').addClass('btn-outline-info');
        }
      });
	  
	  $(document).on('click','#sms_send_btn',function(e){
        e.preventDefault();
        if($('.sms_send_check:checked').length >0){
            const path = $(this).attr('href');
            var custos = $('.sms_send_check:checked').serialize();
            var csrf = '{{ csrf_token() }}';
            custos+= '&_token='+encodeURIComponent(csrf);
            $.post(path,custos,function(response){
				if(response.success){
					success_sweettoatr(response.success);
					$('.sms_send_check').prop('checked',false);
					$("#sms_send_btn").removeClass('btn-info').addClass('btn-outline-info');
				}else{
					toastr.error(response.errors);
				}
            });
        }else{
            toastr.error("Select The Customer First !");
        }
      });
	  
</script>
@endsection