@extends('layouts.vendors.app')

@section('content')

    @include('layouts.theme.css.datatable')

<style>
	#month_title{
        font-size:normal;
        text-shadow: 1px 2px 3px #ff7800
    }
    .txns{
        list-style:none;
        padding:0;
    }
    .txns >  li{
        padding:0;
        margin:0;
        text-align:left;
    }
    .txns >  li >b{
        float:right;
    }
</style>
@php 
$anchor = ['<a href="'.route('anjuman.dashboard').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-home"></i> Dashboard</a>','<a href="'.route('anjuman.enroll',$id).'" class="btn btn-sm btn-outline-primary"> &plus; Enroll</a>'];
$data = new_component_array('newbreadcrumb',"Anjuman Scheme DUE") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-12" > 
                    <div class="card card-default mt-1" style="border-top:1px dashed #ff2300;"> 
                        <div class="card-header text-center p-0">
                            <!--<h4 id="title" class="text-primary col-12">
                                <span id="scheme_title"></span>
                                <span id="month_title" class="m-auto text-secondary">Till Month</span>
                            </h4>-->
                       </div>
					   <div class="card-header p-0">
                            <h4 id="title" class="text-primary ">
                                <span id="scheme_title" class="p-1"></span>
                                <span id="month_title" class="m-auto text-secondary p-1">Till Month</span>
                                <style> 
                                    .status-list {
                                        display: flex;
                                        flex-wrap: wrap;
                                        /* justify-content: center; */
                                        list-style: none;
                                        padding: 2px;
                                        margin: 0;
                                        gap: 2px; /* optional: space between items */

                                        font-size:small;
                                        float:right;
                                    }

                                    .status-list li {
                                        /* display: flex; */
                                        align-items: center;
                                        gap: 0.5rem;
                                        margin:auto;
                                        text-align: center;
                                        border-radius: 10px;
                                        padding:5px 10px;
                                        position: relative;
                                    }
                                    .status-list li > hr{
                                        border-top:1px dashed gray;
                                    }
                                    .status-list li.text-success{
                                        border:1px solid green;
                                    }
                                    .status-list li.text-danger{
                                        border:1px solid red;
                                    }
                                    .status-list li.text-info{
                                        border:1px solid #2196f3;
                                    }
                                    li>b.gm:after{
                                        content:" Gm.";
                                        right:0;
                                    }
                                    li>b.rs:after{
                                        content:" ₹.";
                                        right:0;
                                    }
                                </style>
                                <ul class="status-list">
                                    <li class="text-success">
                                        <span>DEPOSIT </span>
                                        <hr class="m-0">
                                        <b class="gm" id="scheme_deposite_sum">0</b>
                                    </li>
                                    <li class="text-danger">
                                        <span>WITHDRAW </span>
                                        <hr class="m-0">
                                        <b class="gm" id="scheme_withdraw_sum">0</b>
                                    </li>
                                    <li class="text-info">
                                        <span>DUE </span>
                                        <hr class="m-0">
                                        <b class="gm" id="scheme_due_sum">0</b>
                                    </li>
                                </ul>
                            </h4>
                       </div>
                       <div class="card-body p-0" >
                            <div class="form-inline">
                                <div class="form-group col-md-4 p-0">
                                    <select name="scheme" class="form-control w-100" id="scheme" style="font-weight:bold;">
                                        <option value="">SCHEMES</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-1 p-0">
                                    <select name="month" class="form-control w-100 text-center" id="month" style="font-weight:bold;">
                                        <option value="">MONTH</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-5 p-0">
                                    <input type="text" class="form-control w-100" placeholder="Find Customer Name/Mobile" name="custo" id="custo">
                                </div>
								<div class="input-group col-md-2 p-0">
                                    <select name="entries" class="form-control text-center" id="entries" >
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <label class="input-group-text">
                                        Entries
                                    </label>
                                </div>
                            </div>
                           <div class="table-responsive">
                                <table id="CsTable" class="table table_theme align-middle dataTable">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Customer</th>
											<th>DEPOSITE</th>
                                            <th>WITHDRAW</th>
                                            <!--<th>TXN</th>-->
                                            <th>DUE </th>
                                            <th>DUE MONTH</th>
                                            <th>PAYMENT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataarea">

                                    </tbody>
									<tfoot>
                                        <tr>
                                            <th colspan="2" class="text-center">TOTAL</th>
                                            <th class="text-center text-success" id="ttl_deposite"></th>
                                            <th class="text-center text-danger" id="ttl_withdraw"></th>
                                            <th class="text-center text-warning" id="ttl_due"></th>
                                            <th colspan="2" class="text-center"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                           </div> 
                           <div id="pagination" class="col-12">
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
    $(document).ready(function(){
        var unit = 'NA';
        var emi = 'NA';
        var payable = 'NA';
        var first = 0;
        var last = 0;
        var per_page = $("#entries").val();
		var mnth_arr = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $.get("{{ route('anjuman.due',$id) }}","scheme=true",function(response){
            var  tr =`<tr><td colspan="7" class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i> Loading Content !</td></tr>`;
            if(response.scheme){
                unit = (response.scheme.type=='gold')?'Gm.':'Rs.';
                emi = (response.scheme.fix_emi==1)?response.scheme.emi_quant:false;
                
                const schm_start =  new Date(response.scheme.start);
                const curr_date = new Date();

                first = schm_start.getMonth() + 1; // getMonth() returns 0-11
                
                last = curr_date.getMonth() + 1;
                const digit_diff = last-first;
                const actual_diff = (digit_diff<0)?(12 + digit_diff)+1:digit_diff+1;
                payable = (emi)?response.scheme.emi_quant*actual_diff:'No Fix';
				$('#scheme_title').html(response.scheme.title+' - ');
                $("#month_title").html('till '+mnth_arr[last-1].toUpperCase());
				getschemelist(response.scheme.id);
            }
        });
        
		function getschemelist(select=false){
             $.get("{{ route('anjuman.scheme') }}",{mode:'default'},function(response){
                if(response.scheme.length > 0){
                    var option = '';
                    var month_arr = '';
                        $.each(response.scheme,function(i,v){
                            let start_date = new Date( v.start);
                            let month = start_date.getMonth();
                            option+='<option value="'+v.id+'" data-start="'+month+'">'+v.type.toUpperCase()+' - '+v.title+'</option>';
                        });
                        $("#scheme").html(option);
                        if(select){
                            $("#scheme").val(select);
                            getmonthlist();
                        }
                }else{
                    $("#scheme").html('<option value="">Scheme Not Found !</option>');
                }
            });
        }

        function getmonthlist(){
            let month = $("#scheme").find(":selected").data('start');
            let curr_date = new Date();
            let curr = curr_date.getMonth();
            var option = '';
            for(var $i=curr;$i>=month;$i--){
                option += '<option value="'+$i+'">'+(mnth_arr[$i].toUpperCase())+'</option>';
            }
            $("#month").html(option);
        }
		
        function gettxns(response){
			var tr = '';
			if ($.fn.DataTable.isDataTable('#CsTable')) {
				$('#CsTable').DataTable().destroy();
			}
			var ttl_dp = 0;
			var ttl_wd = 0;
			var ttl_du = 0;
			var paging = '';
			var now_last = $("#month option:selected").val()??false;
            last = (now_last)?(+now_last + 1):last;
            if(response.enrolls.data.length > 0){
				paging = response.paging;
                $.each(response.enrolls.data,function(i,v){
					var widthdraw = v.withdraw??0;
                    if(v.activetxns){
                        var deposite = 0;
                        //var widthdraw = 0;
                        var ttl = 0;
                        var pre_num = 0;
                        var num = 0;
                        var part_arr ={};
                        $.each(v.activetxns,function(txni,txnv){
                            if(txnv.txn_status==1){
                                deposite+= txnv.txn_quant;
                                if(emi){
                                    if(pre_num!=txnv.emi_num){
                                        pre_num = txnv.emi_num;
                                        ttl = txnv.txn_quant;
                                    }else{
                                        ttl+=txnv.txn_quant;
                                    }
                                    if(ttl<emi){
                                        let index = mnth_arr[first + (txnv.emi_num - 2)];
                                       part_arr[index] = emi-ttl; 
                                    }
                                }
                            }else{
                                widthdraw+= txnv.txn_quant;
                            }
                        });
                    }
					
                    ttl_dp+=deposite;
                    ttl_wd+=widthdraw;
					
                    tr+=`<tr class="text-center">
                        <td>`+((((per_page * response.enrolls.current_page) - per_page) + 1) + i)+`</td>
                        <td>`+v.custo_name+`
                            <hr class="m-1">
                            (<small><b>`+(v.customer.custo_full_name)+` - `+(v.customer.custo_fone)+`</b></small>)
                        </td>
						 <td class="text-success">
                            <b>`+deposite+` `+unit+`</b>
                        </td>
                        <td class="text-danger">
                            <b>`+widthdraw+` `+unit+`</b>
                        </td>`;
						
                        const digit_diff = last-first;
                        const actual_diff = (digit_diff<0)?(12 + digit_diff)+1:digit_diff+1;
                        payable = (emi)?emi*actual_diff:'No Fix';
                        let due = (emi)?(payable -deposite):false;
						
						if(due){
                            ttl_du+=due;
                        }
						
                        due = (emi)?((due)?parseFloat(due.toFixed(3))+' '+unit:due):'No Fix !';
                        //due = (due)?due+' '+unit:due;
                        tr+=`<td class="text-primary">
                            <b>`+due+`</b>
                        </td>
                        <td>`;
                        if(emi){
                            if(Object.keys(part_arr).length > 0){
                                tr+=`<ul class="txns p-0 text-info">`;
                                $.each(part_arr,function(duei,duev){
                                    tr+=`<li>`+duei+` : <b>`+duev+` `+unit+`</b></li>`
                                });
                                tr+=`</ul>`;
                            }else if(due){
                                tr+=`<ul class="txns p-0 text-info">`;
                                for(var i = first;i<=last;i++){
                                    nw_mnth = mnth_arr[i-1];
                                    tr+=`<li>`+nw_mnth+` : <b>`+emi+` `+unit+`</b></li>`;
                                }
                                tr+=`</ul>`;
                            }else{
                                tr+=`<i>No DUE !</i>`;
                            }
                        }else{
                        tr+=`No Fix !`;
                        }
                        tr+=`</td>
                        <td>
                            <a href="{{ route('anjuman.payment',$id) }}/`+v.id+`" class="btn btn-sm btn-outline-success">₹ Pay</a>
                        </td>
                    </tr>`;
                });
				
				
				let sum = response.sums;
                let deposite_sum = sum.deposit??0;
                let withdraw_sum = sum.withdraw??0;
                let due_sum = response.due;
                $("#scheme_deposite_sum").html(deposite_sum);
                $("#scheme_withdraw_sum").html(withdraw_sum);
                //$("#scheme_due_sum").html('<i class="fa fa-spinner fa-spin"></i>');
                $("#scheme_due_sum").html(due_sum);
				$("ul.status-list > li > b").removeClass().addClass(response.type);
				
				$('#pagination').html(paging);
				$("#dataarea").html(tr);
				$("#ttl_deposite").html((ttl_dp)?ttl_dp+' '+unit:ttl_dp);
                $("#ttl_withdraw").html((ttl_wd)?ttl_wd+' '+unit:ttl_wd);
                $("#ttl_due").html((ttl_du)?parseFloat(ttl_du.toFixed(3))+' '+unit:ttl_du);
				$('#CsTable').DataTable();
				
            }else{
				tr = `<tr><td colspan="7" class="text-center text-danger">No Enrollments !</td></tr>`;
				$('#pagination').html(paging);
				$("#dataarea").html(tr);
				$("#ttl_deposite").html(ttl_dp);
                $("#ttl_withdraw").html(ttl_wd);
                $("#ttl_due").html(ttl_du);
				
            }
        }

        function getresult(url) {
            $("#loader").show();
			$("#dataarea").empty().append('<tr><td colspan="7" class="text-center"><span class="text-primary"><i class="fa fa-spinner fa-spin"></i> Loading Content..</span></td></tr>');
			per_page = $("#entries").val();
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    'entries':per_page,
					'scheme_name':$('#scheme').val()??false,
                    'month':$('#month').val()??false,
                    'custo':$("#custo").val()??false
                },
                success: function (response) {
					$("#loader").hide();
					gettxns(response);
					// $("#dataarea").html(data.html);
					// $("#pagingarea").html(data.paging);
                },
                error: function () {
					
				},
            });
        }

        getresult(url);

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);
        });

        function changeEntries() {
            getresult(url);
        }
		
		$('#scheme').on('input',function(){
            getmonthlist();
            changeEntries();
			applychangedtitle();
        });
        $('#month,#custo').on('input',function(){
            changeEntries();
			if($(this).attr('id')=='month'){
				applychangedtitle();
			}
        });
		
		$("#entries").change(function(){
            changeEntries();
        });
		
		function applychangedtitle(){
            let schm_text = $("#scheme option:selected").text();
            let schm_nm = schm_text.split('-');
            var month = $("#month option:selected").text();
            $("#scheme_title").html(schm_nm[1]);
            $("#month_title").html('-till '+month);
        }
    });
</script>
    @include('layouts.theme.js.datatable')
    @include('layouts.vendors.js.passwork-popup')
@endsection