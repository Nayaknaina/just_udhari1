@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')
<style>
.detail_info{
    border:1px solid lightgray;
    padding:0 2px;
    color:blue;
}
.detail_info:hover{
    color:black;
}
ul.gst_list{
    list-style:none;
    padding:0;
}
tr.disabled > td{
    position:relative!important;
}
tr.disabled>td::after{
    content: "";
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 100%;
    background: #fff8f8d1;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
}
</style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Purchase List',[['title' => 'Purchase']] ) ;

@endphp

{{--<x-page-component :data=$data />--}} 
@php 
$anchor = ['<a href="'.route('purchases.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>'];
$data = new_component_array('newbreadcrumb',"Purchases")  
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    {{--<div class="card-header">
    <a class = "btn btn-outline-primary" href = "{{route('purchases.create')}}"><i class="fa fa-plus"></i>  Add New </a>
    </div>--}}
    <div class="card-body p-1">
        <form action="">
            <div class="row">
               
                <div class="col-12 col-lg-2  form-group">
                    <label for=""> Bill No. </label>
                    <input type="text" id = "bill_no" class = "form-control" placeholder = "Search Bills" oninput="changeEntries()" >
                </div>
                <div class="col-12 col-lg-4  form-group">
                    <label for=""> Suppliers Name </label>
                    <input type="text" id = "supplier_name" class = "form-control" placeholder = "Search Supplier Name" oninput="changeEntries()" >
                </div>
                <div class="col-lg-4 col-12 text-center">

                    @include('layouts.vendors.content.date-range')

                </div>
				 <div class="col-12 col-lg-2 form-group text-center">
                    <label for="">Show entries</label>
                    @include('layouts.theme.datatable.entry')
                </div>
            </div>
        </form>

        <div class="text-center col-12" id="loader">
            <span class="p-1" style="background:lightgray;">
                <li class="fa fa-spinner fa-spin"></li> Loading Content..
            </span>
        </div>
        <div id = "pagination-result"></div>
        
    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>
<div class="modal" tabindex="-1" id="bill_modal">
  <div class="modal-dialog modal-lg" id="bill_modal_content">
    <div class="modal-content">
      <div class="modal-header p-2 bg-light">
        <h5 class="modal-title" id="bill_modal_title">Purchase Bill</h5>
        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><li class="fa fa-times"></li></button>
      </div>
      <div class="modal-body p-2" id="bill_modal_body">
        
      </div>
    </div>
  </div>
</div>

@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>

    var route = "{{ route('suppliers.index') }}";

    function getresult(url) {
        $("#loader").show();
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "bill": $("#bill_no").val(),
                "supplier": $("#supplier_name").val(),
                "range":$("#reportrange").val(),
            },
            success: function (data) {
                $("#loader").hide();
                $("#pagination-result").html(data.html) ;
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
</script>
<script>
    $(document).on('click','.bill_show',function(e){
        e.preventDefault();
        $("#bill_modal_content").removeClass('modal-sm').addClass('modal-lg');
        $("#bill_modal_title").empty().text('Purchase Bill');
        $("#bill_modal_body").empty().load($(this).attr('href'));
        $("#bill_modal").modal();
    });

    $(document).on('click','.bill_pay',function(e){
        e.preventDefault();
        $("#bill_modal_content").removeClass('modal-lg').addClass('modal-sm');
        $("#bill_modal_title").empty().text('Bill Transaction');
        $("#bill_modal_body").empty().load($(this).attr('href'));
        $("#bill_modal").modal();
    });
</script>

<script>

var target_element = "";
    $('#blockingmodal').on('show.bs.modal', function (event) {
        var element = event.relatedTarget;
        $("#mpincheckform").trigger('reset');
        const ind = $('tbody').index($(element).closest('tr'));
        var container = $('tbody>tr').eq(ind);
        target_element = container;
        $("#mpincheckform").attr('action',$(element).data('url'));
    });
    $("#mpincheckform").submit(function(e){
        e.preventDefault();
        var target = $("#target_container").val();
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.status){
                toastr.success(response.msg);
                $("#blockingmodal").modal('hide');
                target_element.addClass('disabled');
                target_element = "";
            }else{
                toastr.error(response.msg);
            }
        }).done(function(response) {

        }).fail(function(jqXHR, status,errormessage) {
            var response = jqXHR.responseJSON;
            $(response.errors).each(function(i,arr){
                $.each(arr,function(ik,ival){
                    $("[name='"+ik+"']").addClass('is-invalid');
                    toastr.error(ival);
                });
            });
            $("#mpincheckform").trigger('reset');
        });
    });
	
	cleardate(changeEntries);
</script>
@include('layouts.vendors.js.date-range')

@include('layouts.vendors.js.passwork-popup')

@endsection
