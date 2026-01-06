@extends('ecomm.site')
@section('title', "My Password")
@section('content')
@php 
    @$$activemenu = 'active';
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">My Password</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 col-12 customer_info_block pt-5">
            <!-- <div class="col-lg-6" style="margin:auto;">
                <div class="card border-secondary mb-5">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Create Password</label>
                            <input class="form-control" type="text" placeholder="John">
                        </div>
                        <div class="form-group">
                            <a href="">Send Otp ?</a>
                        </div>
                        <div class="form-group">
                            <label>Enter OTP</label>
                            <input class="form-control" type="text" placeholder="Doe">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-block btn-primary font-weight-bold my-3 py-3">Change Password</button>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-6" style="margin:auto;">
                <div class="card border-secondary mb-5">
                    <div class="card-body">
                        <form method="post" action="{{ url("{$ecommbaseurl}password")}}" id="password_form" role="form">
                            @csrf
                            <div class="form-group">
                                <label for="current" class="required">Current Password</label>
                                <input class="form-control" id="current" type="text" placeholder="Current Password"  name="current">
                                <small class="help-block text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="create" class="required">Create Password</label>
                                <input class="form-control" id="create" type="text" placeholder="Create Password"  name="create">
                                <small class="help-block text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="confirm" class="required">Confirm Password</label>
                                <input class="form-control" id="confirm" type="text" placeholder="Confirm Password"  name="confirm">
                                <small class="help-block text-danger"></small>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-block btn-primary font-weight-bold my-3 py-3" name="change" value="password">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('javascript')
<script>
     $("#page_await").show();
    $(document).ready(function(){
        $("#page_await").hide();
        $("#password_form").submit(function(e){
            e.preventDefault();
            if(formvalidate()){
                $("#page_await").show();
                var url = $(this).attr('action');
                var data = $(this).serialize();
                $.post(url,data,function(response){
                    if(response.valid){
                        alert(response.msg);
                        if(response.status){
                            window.location.href = "{{ url("{$ecommbaseurl}logout")}}";
                        }
                    }else{
                        alert("Invalid data provided !");
                    }
                }).fail(function(response){
        
                }).done(function(response){
                    $("#page_await").hide();
                })
            }else{
                $("#page_await").hide(); 
            }
        });
    
        function formvalidate(){
            var no_blank = true; 
            $("#password_form > div > .form-control").each(function(i,v){
                if($(this).val()==""){
                    $(this).addClass('invalid');
                    var upper = $(this).attr('id').toUpperCase();
                    $(this).next('.help-block').text(upper+' Password Is Required !');
                    no_blank = false;
                }
            })
            return no_blank;
        }
        $('.form-control').focus(function(){
            $(this).removeClass('invalid');
            $(this).next('.help-block').text("");
        });
    })
</script>
@endsection