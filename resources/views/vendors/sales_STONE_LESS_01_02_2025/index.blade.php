@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

@php

$data = component_array('breadcrumb' , 'Bill List',[['title' => 'Billings']] ) ;

@endphp
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
</style>
<x-page-component :data=$data />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                <!-- general form elements -->
                    <div class="card ">
                        <div class="card-header">
                        <a class = "btn btn-outline-primary" href = "{{route('sells.create')}}"><i class="fa fa-plus"></i>  Create New </a>
                        </div>

                        <div class="card-body">

                            <form action="">

                                <div class="row">

                                    <div class="col-6 col-lg-2 form-group">
                                        <label for="">Show entries</label>
                                        @include('layouts.theme.datatable.entry')
                                    </div>
                                    <div class="col-6 col-lg-2 form-group">
                                        <label for=""> Bill No. </label>
                                        <input type="text" id = "bill" class = "form-control" placeholder = "Search Bill No.s" onchange="changeEntries()" >
                                    </div>
                                    <div class="col-12 col-lg-4  form-group">
                                        <label for=""> Customer Name/Mobile </label>
                                        <input type="text" id = "customer" class = "form-control" placeholder = "Search customer Name/Mobile" onchange="changeEntries()" >
                                    </div>

                                    <div class="col-lg-4 col-12 ">

                                        @include('layouts.vendors.content.date-range')

                                    </div>
                                </div>

                            </form>

                    
                            <div class="table-responsive">
                                <table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
                                    <thead class = "bg-info">
                                        <tr> <th style="border:1px solid;">S.N.</th>
                                        <th style="border:1px solid;width:10%">Customer</th>
                                        <th style="border:1px solid;width:10%">Bill</th>
                                        <th style="border:1px solid;width:10%">Count</th>
                                        <th style="border:1px solid;width:10%">Sub Total</th>
                                        <th style="border:1px solid;width:10%">GST</th>
                                        <th style="border:1px solid;width:10%">DISCOUNT</th>
                                        <th style="border:1px solid;width:10%">Payable</th>
                                        <th style="border:1px solid;width:10%">Payment</th>
                                        <th style="border:1px solid;width:10%">Remains</th>
                                        <th style="border:1px solid;width:10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_area">

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12" id="paging">
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
    <div class="modal" tabindex="-1" role="dialog" id="info_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="info_head"></h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0" id="info_body">
                </div>
            </div>
        </div>
    </div>

@endsection

  @section('javascript')


  <script>

    var route = "{{ route('sells.index') }}";
    const  load_tr = '<tr><td colspan="11" class="text-center"> <span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span> </td></tr>';
    function getresult(url) {
        $("#data_area").html(load_tr);
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "customer": $("#customer").val(),
                "bill": $("#bill").val(),
            },
            success: function (data) {
                $("#data_area").html(data.html) ;
                $("#paging").html(data.paging) ;
            },
            error: function () {},
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

    $(document).on('click','.view_custo,.view_pays',function(e){
        e.preventDefault();
        const head = $(this).data('head');
        $("#info_head").empty().text(head);
        $('#info_body').empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></p>');
        $('#info_body').load($(this).attr('href'));
        $("#info_modal").modal('show');
    });

</script>

@include('layouts.vendors.js.date-range')

@include('layouts.theme.js.datatable')


@include('layouts.vendors.js.passwork-popup')

@endsection
