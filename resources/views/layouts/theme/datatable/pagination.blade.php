@php

// Type 1  For mobile View and 2  for desktop view

$total = $paginator->total();
$currentPage = $paginator->currentPage();
$perPage = $paginator->perPage();

$from = ($currentPage - 1) * $perPage + 1;
$to = min($currentPage * $perPage, $total);

@endphp

<style>

    .pagination .page-link {

    color: #071533 ;
    border-radius: 20px;
    margin-right: 5px;

    }

    .page-link {

        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        /* color: #007bff  !important; */
        /* background-color: #fff  !important; */
        border: 1px solid #011325  !important ;

    }

    .pagination .page-item.active .page-link {

        background-color: #060f1e;
        border-color: #2450a6;
        color: #fff !important ;

    }

	.dataTables_scrollBody {
		max-height: 300px !important;
		height:auto!important;
		overflow-y: auto !important;
	}

    @media screen and (max-width: 768px) {

        .page-link {
            position: relative;
            display: block;
            padding: 0.2rem 0.45rem;
            margin-left: -1px;
            line-height: 1.25;
            /* color: #007bff !important; */
            /* background-color: #fff !important; */
            border: 1px solid #011325 !important;

        }

        .my_page.justify-content-end {
            -ms-flex-pack: end!important;
            justify-content: center !important;
        }

        .my_page.justify-content-start {
            -ms-flex-pack: start!important;
            justify-content: center !important;
        }

        .pagination {

        margin-top: 10px;
        margin-bottom: 5px;

        }
    }

</style>

@if($total>0)

<div class = "row mt-2 " style="border-top: 1px solid rgb(0,0,0,.3); padding-top: 5px; border-bottom: 1px solid rgb(0,0,0,.3);">

<div class="col-lg-6 my_page d-flex  @if($type==2) justify-content-start @else justify-content-start @endif ">
    {{ ("Showing {$from} to {$to} of {$total} entries") }}
</div>

<div class="col-lg-6 my_page d-flex  @if($type==2) justify-content-end @else justify-content-end @endif ">

@if($total!=1)
    {{ $paginator->links('layouts.theme.datatable.all-pages') }}
@endif

</div>

</div>

@endif

@if($type==2)

<script>
	
	$('#CsTable').DataTable().clear().destroy();
    $(function () {

    $('#CsTable').DataTable({
		destroy: true,
        fixedHeader: true,
        scrollX: true,
        /*scrollY: 400,*/
        "searching": false ,
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false ,
        dom: 'Bfrtip',
        buttons: [
            'excel'
        ]

    });
    });

</script>

@else

<script>
	
    $(function () {
		
    $('#CsTable').DataTable({
		destroy: true,
        fixedHeader: false,
        scrollX: true,
        scrollY: 400,
        searching: false,
        paging: false,  // Updated from "bPaginate" to "paging"
        lengthChange: true,  // Updated from "bLengthChange" to "lengthChange"
        filtering: true,  // Updated from "bFilter" to "filtering"
        info: false,  // Updated from "bInfo" to "info"
        autoWidth: false,  // Updated from "bAutoWidth" to "autoWidth"
        responsive: false  // Enable responsiveness // Enable responsiveness
        });
    });

</script>

@endif
