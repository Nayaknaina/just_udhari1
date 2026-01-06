@extends('ecomm.site')
@section('title', "Emi Pay")
@section('content')

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
            <div class="card col-md-8" style="margin:auto;padding:0;">
                <div class="card-header bg-secondary">
                    <h3 class="card-title text-white">
                    EMI Pay <small style="float:right;"><a href="#" onclick="event.preventDefault();window.history.go(-1);" class="btn btn-sm btn-outline-warning"><i class="fa fa-angle-double-left"></i></a></small>
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
                            <form action="" method="">
                                <div class="input-group col-md-6 p-0 offset-md-3">
                                    <input type="text" name="amnt" value="{{ $enrolldetails->emi_amnt }}" class="form-control text-center h-auto" style="font-size:30px;">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2" style="font-size:30px;"><i class="fa fa-rupee"></i></span>
                                    </div>
                                </div>
                                <br>
                                @if($gateways->count()>0)
                                <div class="form-group form-inline">
                                    @foreach($gateways as $gi=>$gtwy)
                                        <label class="col-md-3  col-4 p-2 m-auto">
                                            <a href="" class="form-control h-auto w-auto p-0"><img src="{{ asset($gtwy->origin->icon)}}" class="img-responsive img-thumbnail"></a>
                                        </label>
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
                <!-- <div class="card-footer">
                    <ul class="row" style="list-style:none;padding:0;">
                        <li class="col-6 text-center"><a href="{{ url("{$ecommbaseurl}register")}}" class="btn btn-default  btn-sm text-primary">Register ?</a></li>    
                        <li class="col-6 text-center"> <a href="{{ url("{$ecommbaseurl}login")}}" class="btn btn-default btn-sm text-success">Login ?</a></li>    
                    </ul>
                   
                </div> -->
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