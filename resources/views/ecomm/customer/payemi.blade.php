@extends('ecomm.site')
@section('title', "Emi Pay")
@section('content')
@php 
//dd($enrolldetails);
@endphp

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
            <div class="card col-md-8" style="margin:auto;padding:0;">
                <div class="card-header bg-secondary">
                    <h3 class="card-title text-white">
                    EMI Pay <!-- <small style="float:right;"><a href="#" onclick="event.preventDefault();window.history.go(-1);" class="btn btn-sm btn-outline-warning"><i class="fa fa-angle-double-left"></i></a></small> -->
                    <small style="float:right;"><a href="{{ url("{$ecommbaseurl}schemes") }}" onclick="" class="btn btn-sm btn-outline-warning"><i class="fa fa-angle-double-left"></i></a></small>
                </h3>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-md-6 col-12" style="border:1px dotted lightgray;">
                            <ul style="list-style:none;" class="row p-0">
                                <li class="col-12 text-center mb-2"><h3 class="text-info">SCHEME</h3><br><span style="font-size:20px;"><b>{{ $enrolldetails->schemes->scheme_head }}</b><hr class="m-1">{{ $enrolldetails->schemes->scheme_sub_head }}</span></li>    
                                <li class="col-6 text-center form-control h-auto mb-2"><strong>GROUP</strong><br><span>{{ $enrolldetails->groups->group_name }}</span></li>    
                                <li class="col-6 text-center form-control h-auto mb-2"><strong>ID</strong><br><span>{{ $enrolldetails->assign_id }}</span></li>    
                                <li class="col-6 text-center form-control h-auto mb-2"><strong>NAME</strong><br><span>{{ $enrolldetails->customer_name }}</span></li>    
                                <li class="col-6 text-center form-control h-auto mb-2"><strong>EMI</strong><br><span>{{ $enrolldetails->emi_amnt }} Rs.</span></li>    
                            </ul> 
                        </div>
                        <div class="col-md-6 col-12 p-1" style="border:1px dotted lightgray;">
                            <form action="{{ url("{$ecommbaseurl}payemi") }}" method="post" id="emi_pay_form">
                                @csrf
                                <input type="hidden" name="enroll"   value="{{ $enrolldetails->id }}">
                                <input type="hidden" name="amnt"   value="{{ $enrolldetails->emi_amnt }}">
								<input type="hidden" name="pay"   value="{{ $enrolldetails->emi_amnt }}">
                                <div class="input-group col-md-6 p-0 offset-md-3">
                                    <label type="text" class="form-control text-center h-auto readonly" style="font-size:30px;">{{ $enrolldetails->emi_amnt }}</label>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2" style="font-size:30px;"><i class="fa fa-rupee"></i></span>
                                    </div>
                                </div>
                                <br>
								@php 
									$gateway_count = $gateways->count();
								@endphp
                                @if($gateway_count>0)
                                <div class="form-group form-inline text-center">
                                    @foreach($gateways as $gi=>$gtwy)
                                    <div class="col-md-3 col-4  m-auto text-center">
                                        <label class="h-auto gateway_label form-control w-auto p-0 {{ ($gateway_count==1)?'active':'' }}" for="{{ $gtwy->id }}" style="position:relative;cursor:pointer;">
                                            <input type="checkbox" id="{{ $gtwy->id }}" name="gateway" value="{{ $gtwy->id }}" style="display:none;" class="gateway_check" {{ ($gateway_count==1)?'checked':'' }}>
                                            <img src="{{ asset($gtwy->origin->icon)}}" class="img-responsive img-thumbnail">
                                        </label>
                                        <span class="text-center w-100" style="color:black;">{{ $gtwy->custom_name??$gtwy->gateway_name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                <div class="form-group  text-center mb-3">
                                    <button type="submit" name="do" value="pay" class="btn btn-success">Pay ?</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer" id="emi_pay_bottom">                   
                </div>
            </div>
    </div>
</div>

<div id="pay_loader">
    <p class="text-center">
        <span><i class="fa fa-spinner fa-spin"></i> Processing..</span><br><br>
        <span id="aware">Please Do not Press<b><br> Cancel or Back Button</b><br>While Processing !</span>
    </p>
</div>
<style>
    label.active:before{
        content:"\2714";
        position:absolute;
        width:100%;
        height:100%;
        text-align:center;
        vertical-align:middle;
        background:#00000057;
        color:#0a0;
        text-shadow:1px 2px 3px white;
        font-size:200%;
        /* backdrop-filter: blur(5px); */
    } 
    ul > li >h3:before{
        content:"\269C";
    }
    ul > li >h3:after{
        content:"\269C";
    }
    .fa-rupee:before{
        content: "\f156";
    }
    #pay_loader{
        position: fixed;
        bottom:0;
        width:100%;
        height:100%;
        background:#000000c9;
        overflow:hidden!important;
        z-index:9999;
        display:none;
    }
    #pay_loader>p{
        color:#fdd199;
        margin-top:10%;
        font-size:25px;
    }
    
    #pay_loader>p>span#aware{
        /* background:white; */
        color:white;
        /* text-shadow:2px 1px 5px gray; */
        font-size:initial;
    }
</style>

@endsection
@section('javascript')
<script>
    $('.gateway_label').click(function(e){
        if($(this).find('input[type="checkbox"]').is(':checked')){
            $('.gateway_check').prop('checked',false);
            $('.gateway_label').removeClass('active');
            $(this).find('input[type="checkbox"]').prop('checked',true);
            $(this).addClass('active');
        }else{
            $(this).removeClass('active');
        }
    });
	
	$("#emi_pay_form").submit(function(e){
		if($('input[name="gateway"]').is(':checked')){
			$("#pay_loader").show();
		}else{
			alert("Please Select The Payment Gateway First !");
			return false;
		}
    });
	
    /*$("#emi_pay_form").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = $(this).serialize();
        if($('input[name="gateway"]').is(':checked')){
            $("#pay_loader").show();
            $.post(path,formdata,function(response){
                if(response.valid){
                    if(response.html){
                        //alert(response.success);
                        $("#emi_pay_bottom").empty().append(response.html)
                        $(document).find("#txn_form").submit();
                        //toastr.success(response.success);
                    }else{
                        alert(response.error);
                        $("#pay_loader").hide();
                        //toastr.error(response.error);
                    }
                }else{
                    if(response.errors){
                        $.each(response.errors,function(i,v){
                            alert(v[0]);
                        });
                        $("#pay_loader").hide();
                    }
                }
            });
        }else{
            alert("Please Select The Payment Gateway First !");
        }
    })*/
</script>
@endsection