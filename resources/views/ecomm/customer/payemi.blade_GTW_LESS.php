@extends('ecomm.site')
@section('title', "Emi Pay")
@section('content')

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
            <div class="card col-md-4" style="margin:auto;padding:0;">
                <div class="card-header bg-secondary">
                    <h3 class="card-title text-white">
                    EMI Pay <small style="float:right;"><a href="#" onclick="event.preventDefault();window.history.go(-1);" class="btn btn-sm btn-outline-warning"><i class="fa fa-angle-double-left"></i></a></small>
                </h3>
                </div>
                <div class="card-body"> 
                    <ul style="list-style:none;" class="row p-0">
                        <li class="col-12 text-center mb-2"><h3 class="text-info">SCHEME</h3><br><span style="font-size:20px;"><b>{{ $enrolldetails->schemes->scheme_head }}</b><hr class="m-1">{{ $enrolldetails->schemes->scheme_sub_head }}</span></li>    
                        <li class="col-6 text-center form-control h-auto mb-2"><strong>GROUP</strong><br><span>{{ $enrolldetails->groups->group_name }}</span></li>    
                        <li class="col-6 text-center form-control h-auto mb-2"><strong>ID</strong><br><span>{{ $enrolldetails->assign_id }}</span></li>    
                        <li class="col-6 text-center form-control h-auto mb-2"><strong>NAME</strong><br><span>{{ $enrolldetails->customer_name }}</span></li>    
                        <li class="col-6 text-center form-control h-auto mb-2"><strong>EMI</strong><br><span>{{ $enrolldetails->emi_amnt }} Rs.</span></li>    
                    </ul> 
                    <div class="col-12 text-center">
                        <form action="" method="">
                            <div class="input-group col-md-10 p-0 offset-md-1">
                                <input type="text" name="amnt" value="{{ $enrolldetails->emi_amnt }}" class="form-control text-center" style="font-size:50px;">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2" style="font-size:50px;"><i class="fa fa-rupee"></i></span>
                                </div>
                            </div>
                            <br>
                            <div class="form-group  mb-3">
                                <button type="submit" name="do" value="pay" class="btn btn-success">Pay ?</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="card-footer">
                    <ul class="row" style="list-style:none;padding:0;">
                        <li class="col-6 text-center"><a href="{{ url("{$ecommbaseurl}register")}}" class="btn btn-default  btn-sm text-primary">Register ?</a></li>    
                        <li class="col-6 text-center"> <a href="{{ url("{$ecommbaseurl}login")}}" class="btn btn-default btn-sm text-success">Login ?</a></li>    
                    </ul>
                   
                </div> -->
            </div>
    </div>
</div>
<style>
    ul > li >h3:before{
        content:"\269C";
    }
    ul > li >h3:after{
        content:"\269C";
    }
    /* ul > li >h3:before{
        content:"\269F";
    }
    ul > li >h3:after{
        content:"\269E";
    } */
    /* ul > li >h3:before{
        content:"\2720";
    }
    ul > li >h3:after{
        content:"\2720";
    } */
    .fa-rupee:before{
        content: "\f156";
    }
</style>

@endsection