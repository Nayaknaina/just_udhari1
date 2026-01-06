@extends('layouts.vendors.app')

@section('stylesheet')
<style>
    a.day_book_btn{
        background:black;
        color:white;
        padding:3px 10px;
        border-radius:5px;
    }
    a.day_book_btn:hover,a.day_book_btn:focus{
        border:1px solid tomato;
        color:tomato;
        background:white;
    }
    a.day_book_btn:active{
        border:1px solid tomato;
        color:tomato;
        background:white;
        font-weight:bold;
    }
    #page_pop{
        border:1px solid; 
        border-radius: 10px;
        position: absolute;
        z-index: 1;
        background: white;
        display: none;
        padding:0;
        height:auto;
        max-height:calc(100vh - 150px);
        overflow-y:scroll;
        top:30px;
        box-shadow:1px 2px 3px 4px #8912125c;
    }
    #page_pop.right_open{
        right:0
    }
    #page_pop.right_open > button#pop_close{
        left:0;
    }
    #page_pop.left_open{
        left:0;
    }
    #page_pop.left_open> button#pop_close{
        right:0;
    }
    #page_pop >button#pop_close{
        position:absolute;
        top:0;
        border-radius:5px;
        z-index:1;
        margin:0;
    }
    table#data_table{
        border-collapse: separate !important;
    }
    table#data_table tbody tr.tr_e>td{
        background:#fefce2!important;
    }
    table#data_table tbody tr.tr_d>td{
         background:#fdd!important;
    }
    table#data_table tbody tr.tr_u>td{
         background:#f0ffeb!important;
    }
    table#data_table tfoot#data_foot tr td.bg-white{
        background:white!important;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
        padding:3px 10px !important;
        font-weight:bold;
    }
    .open_close_summery_area{
        height:auto;
        max-height:calc(100vh - 200px);
    }
</style>
<style>
@media (max-width: 600px) {
    #right_daybook_btn_box{
        order:1;
    }
    #left_daybook_btn_box{
        order:1;
    }
    #right_daybook_btn_box >a,#left_daybook_btn_box >a{
        width:100%;
        display:block;
    }

}
#date_summery_table thead th{
    padding:5px 15px;
    max-width:auto;
    border-bottom:1px dashed gray;
}

#date_summery_table tbody th,#date_summery_table tbody td{
    padding:2px 5px;
    border-right:1px dashed gray;
    white-space: nowrap;
}
#date_summery_table tbody td{
    text-align: center;
}
#date_summery_table tbody th{
    padding:2px 10px 2px 5px;
}
#date_summery_table tbody td:last-child{
    border-right:unset;
}
#date_summery_block{
    width: fit-content;
    position: relative;
}
#date_summery_block_loader{
    width:100%;
    height:100%;
    position: absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background: #ffffffd6;
    align-content: center;
    text-align: center;
    border:1px dashed gray;
    border-radius:10px;
}
#date_summery_block_loader > span{
    color:tomato;
    text-shadow:1px 2px 3px gray;
}
</style>

@endsection 

@section('content')
    
    @php 
		$anchor = ['<a href="'.route('shop.daybook').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-list"></i> List</a>','<a href="'.route('shop.daybook.feed').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Feed</a>'];
		$data = new_component_array('newbreadcrumb',"Day Book") 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 col-6 text-right pt-1" id="left_daybook_btn_box">
                                    <a href="{{ route('shop.summery.target','open') }}" class="day_book_btn text-center day_book_action_btn left_open">Opening</a>
                                </div>
                                <div class="col-md-4 col-12 text-center ">
                                    <div class="form-inline">
                                        <div class="input-group w-100">
                                            <input type="date" name="date" id="date" class="form-control text-center" value="{{ @$date }}" max="{{ date('Y-m-d',strtotime('now')) }}">
                                            <select name="source" class="myselect form-control input-append text-center" id="source">
                                                <option value="">Source</option>
                                                <option value="create">Create Stock</option>
                                                <option value="import">Import Stock</option>
                                                <option value="sell">Sell</option>
                                                <option value="purchase">Purchase</option>
                                                <option value="udhar">Udhar</option>
                                                <option value="scheme">Scheme</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6  text-left  pt-1" id="right_daybook_btn_box">
                                    <a href="{{ route('shop.summery.target','close') }}" class="day_book_btn  text-center day_book_action_btn right_open">Closing</a>
                                </div>
                                <div class="col-12 mt-1 pt-1 order-2">
                                    <hr class="m-0" style="border-top:1px solid lightgray;">
                                    <div class="table-responsive py-2 w-100" id="date_summery_block" >
                                        <table class="m-auto" id="date_summery_table">
                                            <thead>
                                                <tr>
                                                    <th><a href="{{ route('shop.summery.target') }}" class="btn btn-outline-dark btn-sm p-1 w-100 fa fa-eye day_book_action_btn"></a></th>
                                                    <th>GOLD</th>
                                                    <th>SILVER</th>
                                                    <th>STONE</th>
                                                    <th>ARTIFICIAL</th>
                                                    <!--<th>FRANCHISE</th>-->
                                                    <th>MONEY</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>OPENING</th>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                </tr>
                                                <tr>
                                                    <th>TRANSACTION</th>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                </tr>
                                                <tr>
                                                    <th>CLOSING</th>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                    <td class="summery_data"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <div id="date_summery_block_loader" class="day_book_data_loader">
                                            <span><i class="fa fa-spinner fa-spin"></i> Loading Summery !</span>    
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4 bordered border-primary right_open" id="page_pop" >
                                    <button class="btn btn-sm btn-danger" id="pop_close" onclick="$('#page_pop').hide();">
                                        &cross;
                                    </button>
                                    <div class="day_pop_content_area pb-2" id="day_pop_content_area">

                                    </div>
                                    <div class="text-center text-danger" id="summery_loader">
                                        <span><i class="fa fa-spinner fa-spin text-primary"></i> loading..</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card" style="box-shadow:1px -2px 3px 1px lightgray inset;">
                                <div class="car-header py-1">
                                    <h6 class="card-title text-primary px-2">Source Wise</h6>
                                    <a href="{{ route('shop.source.summery') }}" class="btn btn-sm btn-outline-dark fa fa-caret-down min" style="float:right;" id="source_day_book"></a>
                                </div>
                                <div class="card-body p-0" id="source_wise_daybook">
                                </div>
                                <span class="text-secondary m-auto" id="source_summery_loader" style="display:none;"><i class="fa fa-spinner fa-spin"></i> loading..</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive pb-3">
                                <table id="data_table" class="table table_theme m-0">
                                    <thead id="data_thead">
                                        <!-- First row: main groups -->
                                        <tr class="text-center">
                                            <th rowspan="2">SN.</th>
                                            <th rowspan="2">TIME</th>
                                            <th rowspan="2">C NAME/NO.</th>
                                            <th rowspan="2">SOURCE</th>
                                            <th colspan="4">AMOUNT</th>
                                            <th colspan="5">GOLD<small class="text-dark"><b>(Net)</b></small></th>
                                            <th colspan="5">SILVER<small class="text-dark"><b>(Net)</b></small></th>
                                            <th colspan="3">STONE<small class="text-dark"><b>(Pc.)</b></small></th>
                                            <th colspan="3">ARTIFICIAL<small class="text-dark"><b>(Pc.)</b></small></th>
                                            <th colspan="3">FRANCHISE<small class="text-dark"><b>(Pc.)</b></small></th>
                                        </tr>

                                        <!-- Second row: opening/closing sub columns -->
                                        <tr class="text-center">
                                            <th>TYPE</th>
                                            <th>IN(+)</th>
                                            <th>OUT(-)</th>
                                            <th>BALANCE</th>

                                            <th>TYPE</th>
                                            <th>K</th>
                                            <th>IN(+)</th>
                                            <th>OUT(-)</th>
                                            <th>BALANCE</th>

                                            <th>TYPE</th>
                                            <th>%</th>
                                            <th>IN(+)</th>
                                            <th>OUT(-)</th>
                                            <th>BALANCE</th>

                                            <th>IN(+)</th>
                                            <th>OUT(-)</th>
                                            <th>BALANCE</th>

                                            <th>IN(+)</th>
                                            <th>OUT(-)</th>
                                            <th>BALANCE</th>

                                            <th>IN(+)</th>
                                            <th>OUT(-)</th>
                                            <th>BALANCE</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="col-12 text-center day_book_data_loader" id="data_loader">
                                    <span class="text-primary"><i class="fa fa-spinner fa-spin"></i> Loading Content...</span>
                                </div>
                            </div>
                            <div id="paging_area">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 

@section('javascript')
    <script>
        var target = true;
        $('.day_book_action_btn').click(function(e){
            e.preventDefault();
            const date = $('#date').val()??false;
            const id = $(this).hasClass('left_open')?'left_open':'right_open';
            if(date){
                $("#page_pop").removeClass('right_open left_open').addClass(id).show();
                $("#summery_loader").show();
                $("#day_pop_content_area").empty().load($(this).attr('href')+`?date=${date}`,"",function(){
                    $("#summery_loader").hide();
                });
            }else{
                toastr.error('Invalid Date !');
                $('#date').focus();
            }
        });

        $("#source_day_book").click(function(e){
            e.preventDefault();
            const self = $(this);
            if(self.hasClass('max')){
                $("#source_wise_daybook").empty();
                //self.removeClass('max').addClass('min');
                //self.removeClass('fa-caret-up').addClass('fa-caret-down');
                self.toggleClass('fa-caret-up fa-caret-down max min');
            }else{
                $("#source_summery_loader").show();
                const date = $('#date').val()??false;
                if(date){
                    $("#source_wise_daybook").empty().load($(this).attr('href')+`?date=${date}`,"",function(){
                        $("#source_summery_loader").hide();
                        self.toggleClass('fa-caret-up fa-caret-down max min');
                    });
                }
            }
        });
        
        $("#date,#source").change(function(){
            $("#page_pop").hide();
            $("#day_pop_content_area").empty();
            target = true;
            getresult();
        });

        function getresult(url) {
			$("#paging_area").empty();
            $('#data_table').find('tbody,tfoot').remove();  // remove old tbody
            $("#data_loader").show();
            if(target){
                $('#date_summery_block_loader').show();
                $("#source_wise_daybook").empty();
                $("#source_day_book").removeClass('fa-caret-up max').addClass('fa-caret-down min');
            }
			//$('#CsTable').find('tfoot').remove();  // 
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    "entries": $("#entries").val()??null,
                    "date":$('#date').val()??false,
                    "source":$('#source').val()??false,
                    "place":target,
                },
                success: function (data) {
                    $("#data_loader").hide();
					$('.data_area').remove();
                    $(data.html).insertAfter('thead#data_thead');
                    if(target){
                        if(data.summery){
                            $("#date_summery_table").find('tbody').replaceWith(data.summery);
                        }else{
                            $('.summery_data').text('');
                        }
                        $('#date_summery_block_loader').hide();
                    }
                    $("#paging_area").html(data.paging);
                    //$('#CsTable').DataTable();
                    target = false;
                },
                error: function (data) {
                    $("#data_loader").hide();
                },
            });
        }
        
        $('.day_book_data_loader').show();
        getresult(url) ;
        $('.day_book_data_loader').hide();

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            target = false;
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);
        });

        function changeEntries() {

            getresult(url) ;

        }

        function printdaybook(){
            const day_date = $("#date").val(); 
            const day_source = $("#source").val(); 
            const target_url = `{{ route('shop.detail.export') }}?date=${day_date}&source=${day_source}`;
            window.open(target_url, "_blank");
        }
    </script>
@endsection