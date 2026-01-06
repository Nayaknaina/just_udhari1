@extends('ecomm.site')
@section('title', "My Profile")

@section('content')
@php 
//dd($user);
    @$$activemenu = 'active';
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase mb-3 " style="margin:auto;">My Profile</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none  dashboard_lg_control">  
            @include('ecomm.customer.sidebar')

        </div>
        <div class="col-md-9 col-12">
            <form action="{{ url("{$ecommbaseurl}profile")}}" method="post"  role="form" class="profile_form" id="profile_form">
                <div class="row">
                    <div class="col-md-3">
                        @csrf
                        <div class="form-group">
                            <label form="image">Photo</label>
                            <div class="col-12 text-center" onClick="$('#image').click();" id="img_prev_container">
                            <img src="{{ asset($user->custo_img) }}" class="img-responsive" style="width:100%;height:inherit;" id="img_preview">
                            </div>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>
                        <!-- <div class="form-group">
                            <label form="name" class="required">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label form="fone" class="required">Mobile</label>
                            <input type="text" name="fone" id="fone" class="form-control" placeholder="Enter mobile Number (10 Digit only )">
                        </div>
                        <div class="form-group">
                            <label form="email">E-Mail</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter E-Mail">
                        </div> -->
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="name" class="required">Full Name <small class="help-text text-danger" ></small></label>
                                <input type="text" name="name" id="name" class="form-control required" placeholder="Enter Name" value="{{ $user->custo_full_name}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fone" class="required">Mobile <small class="help-text text-danger" ></small></label>
                                <input type="text" name="fone" id="fone" class="form-control required" placeholder="Enter mobile Number (10 Digit only )" value="{{ $user->custo_fone }}" minlength="10" maxlength="10">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">E-Mail <small class="help-text text-danger" ></small></label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter E-Mail"  value="{{ $user->custo_mail}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state" >State <small class="help-text text-danger" ></small></label>
                                <select name="state" id="state" class="form-control" >
                                    @php  
                                        $match_state_id = ($user->state_id!="")?$user->state_id:$shop_branch->state;
                                        $states = states();
                                    @endphp
                                    @if(!empty($states))
                                    <option value="">Select</option>
                                        @foreach($states as $sk=>$state)
                                        @php 
                                            
                                            $state_selected = ($match_state_id == $state->code)?'selected':""; 
                                        @endphp
                                        <option value="{{ $state->code}}" {{ $state_selected }}>{{ $state->name }}</option>
                                        @endforeach
                                    @else
                                    <option value="">No Data</option>

                                    @endif
                                </select>
                                <!-- <input type="text" name="state" id="state" class="form-control" placeholder="Enter Name"  value="{{ $user->custo_full_name}}"> -->
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dist" >District <small class="help-text text-danger" ></small></label>
                                <select name="dist" id="dist" class="form-control" >
                                    @php
                                        $match_dist = ($user->dist_id!="")?$user->dist_id:$shop_branch->district;
                                        $districts = districts($match_state_id);
                                        @endphp
                                    @if(!empty($districts))
                                    <option value="">Select</option>
                                        @foreach($districts as $dk=>$dist)
                                        @php 
                                            $dist_select = ($match_dist==$dist->code)?'selected':"";
                                        @endphp
                                        <option value="{{ $dist->code}}" {{ $dist_select }}>{{ $dist->name }}</option>
                                        @endforeach
                                    @else
                                    <option value="">No Data</option>

                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="teh" >Tehsil <small class="help-text text-danger" ></small></label>
                                <input type="text" name="teh" id="teh" class="form-control" placeholder="Enter Name" value="{{ $user->teh_name}}">
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label form="name" class="required">Area</label>
                                <input type="text" name="area" id="area" class="form-control" placeholder="Enter Name">
                            </div> -->
                            <!-- <div class="form-group col-md-6">
                                <label form="name" class="required">Sub Area</label>
                                <input type="text" name="subarea" id="area" class="form-control" placeholder="Enter Name">
                            </div> -->
                            <div class="form-group col-md-6">
                                <label for="pin" >PinCode <small class="help-text text-danger" ></small></label>
                                <input type="text" name="pin" id="pin" class="form-control" placeholder="Enter Pincode (6 Digit Only )"  value="{{ $user->pin_code}}">
                            </div>
                            <div class="form-group col-12">
                                <label for="addr" >Address <small class="help-text text-danger" ></small></label>
                                <textarea name="addr" id="addr" class="form-control" placeholder="Full Address" >{{ $user->custo_address}}</textarea>
                            </div>
                            <hr>
                            <div class="form-group col-12 text-right">
                                <button type="submit" name="save" value="profile" class="btn btn-md btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('javascript')
    <script>
        $("#page_await").show();
        $(document).ready(function(){
            $("page_await").hide();
            $("#image").change(function(){
                var file = $(this).get(0).files[0];
                if(file){
                    var reader = new FileReader();
                    reader.onload = function(){
                        $("#img_preview").attr("src", reader.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
            $("#state").change(function(){
                const state_code = $(this).val();
                if(state!=""){
                    const data = {state:state_code};
                    $.get("{{ url("{$ecommbaseurl}get-districts"); }}",data,function(response){
                        if(response.length > 0){
                            option='<option value="">Select District !</option>';
                            $.each(response,function(i,v){
                                option+='<option value="'+(v.code)+'">'+(v.name)+'</option>';
                            });
                        }else{
                            option='<option value="">No District !</option>';
                        }
                        $("#dist").empty().append(option);
                    });
                }
            });
            $("#profile_form").submit(function(e){
                e.preventDefault();
                if(formvalidate()){
                    var action = $(this).attr('action');
                    var formData = new FormData(this); 
                    //console.log(formData);
                    $.ajax({
                        type:'POST',
                        url:action,
                        data:formData,
                        contentType:false,
                        cache:false,
                        processData:false,
                        beforeSend:function(){

                        },
                        success:function(response){
                            if(response.valid){
                                alert(response.msg);
                                if(response.status){

                                }
                            }else{
                                alert("Invalid data !");
                            }
                        },
                        error:function(){
                            
                        }
                    });
                }
            });
            function formvalidate(){
                var no_blank = true; 
                $(".form-control.required").each(function(i,v){
                    if($(this).val()==""){
                        $(this).addClass('invalid');
                        var upper = $(this).attr('id').toUpperCase();
                        $(this).prev('label').find('.help-text').text("Required !");
                        no_blank = false;
                    }
                })
                return no_blank;
            }
            $('.form-control').focus(function(){
                $(this).removeClass('invalid');
                $(this).prev('label').find('.help-text').text("");
            });
        })
    </script>
@endsection