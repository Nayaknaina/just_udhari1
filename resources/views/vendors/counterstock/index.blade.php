@extends('layouts.vendors.app')

@section('css')

@include('layouts.theme.css.datatable')

    <style>
        .bill_show{
            color:blue;
            border:1px solid lightgray;
            padding:1px 2px;
        }
        .bill_show:hover{
            color:black;            
        }
    </style>
@endsection

@section('content')

    @php

        //$data = component_array('breadcrumb' , 'Counters Stock',[['title' => 'Counters']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Counter Stock") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
    <!--<div class="card-header">
        <h3 class="card-title">Stock List</h3>
    </div>-->
    <div class="card-body">

    <form action="">

        <div class="row">

            
            <div class="col-12 col-lg-6  form-group">
            <label for=""> Search Stock </label>
            <input type="text" id = "stock" class = "vin_no form-control" placeholder = "Search ...."  oninput="changeEntries()" >
            </div>
            <div class="col-12 col-md-2 form-group">
                <label for="">Counter/Box</label>
                <input type="text" name="place" id="place" placeholder="Enter Label" class="form-control" oninput="changeEntries()" >
            </div>
            <div class="col-12 col-md-2 form-group">
                <label for="">Bill No.</label>
                <input type="text" name="bill" id="bill" placeholder="Enter Bill No." class="form-control" oninput="changeEntries()" >
            </div>
			<div class="col-12 col-lg-2 form-group">
            <label for="">Show entries</label>
            @include('layouts.theme.datatable.entry')
            </div>
        </div>

    </form>
    <div  class="text-center col-12" id="loader"></div>
    <!-- <div class = "table-responsive"> -->
		<table id="CsTable" class="table table_theme table-striped table-bordered text-wrap align-middle dataTable table">
            <thead>
                <tr class = "">
                    <th>S.N.</th>
                    <th>COUNTER</th>
                    <th>BOX</th>
                    <th>ITEM</th>
					<th>CATEGORY</th>
                    <th>Qnt/Wght</th>
                    <th>BILL</th>
                    <th>ACTION</th>
                    <th>&cross;</th>
                </tr>
            </thead>
            <tbody id="data_area">
                
            </tbody>
        </table>
    <!-- </div> -->

    </div>
    <div class="col-12" id="table_data_pagination" >
    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>


    
    <div class="modal" tabindex="-1" id="pop_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header p-2 bg-light">
                <h5 class="modal-title" id="modal_title">Purchase Bill</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><li class="fa fa-times"></li></button>
            </div>
            <div class="modal-body p-2" id="modal_body">
                
            </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')

    <script>

      var route = "{{ route('stockcounters.index') }}";
      const  load_tr = '<tr><td colspan="9" class="text-center"> <span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span> </td></tr>';
      function getresult(url) {
            $("#data_area").html(load_tr);
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "place": $("#place").val(),
                  "bill": $("#bill").val(),
                  "stock": $("#stock").val(),
              },
              success: function (data) {
                $("#data_area").html(data.html);
                $("#table_data_pagination").html(data.paging);
              },
              error: function (data) {
                $("#data_area").html(data.html);
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

    $(document).on('click','.pop_out',function(e){
        e.preventDefault();
        if($(this).hasClass('counter')){
            $('#pop_modal').find('.modal-dialog').addClass('modal-sm').removeClass('modal-lg');
        }else{
            $('#pop_modal').find('.modal-dialog').addClass('modal-lg').removeClass('modal-sm');
        }
        $("#modal_title").empty().text($(this).data('header'));
        $("#modal_body").empty().load($(this).attr('href'));
        $("#pop_modal").modal('show');
    });
    $(document).on('change input','.form-control',function(){
        $(this).removeClass('is-invalid');
    });

    function submitcounterform(self,e){
        e.preventDefault();
        $.post(self.attr('action'),self.serialize(),function(response){
            if(response.errors){
                $.each(response.errors,function(i,v){
                    $('[name="'+i+'"]').addClass('is-invalid');
                    toastr.error(v[0]);
                });
            }else{
                if(response.status){
                    $("#pop_modal").modal('hide');
                    success_sweettoatr(response.msg);
                    location.reload();
                }else{
                    toastr.error(response.msg);
                }
            }
        });
    }
    function deleteplacement(self,e){
        $.get($(this).attr('href'),"",function(){

        });
    }
	
    </script>
	
@include('layouts.theme.js.datatable')
@include('layouts.vendors.js.passwork-popup')
@endsection
