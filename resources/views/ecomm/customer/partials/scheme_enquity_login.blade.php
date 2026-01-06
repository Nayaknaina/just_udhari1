@php 
//print_r($shopscheme->toArray());
@endphp
<form name="sentMessage" id="scheme_enquiry_login" novalidate="novalidate" action="{{ url("{$ecommbaseurl}login")}}" method="post">
    @csrf
    <input type="hidden" name="scheme" valuue="{{ $shopscheme->url_part }}">
    <div class="control-group mb-2">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Username (Mobile Number)" required="required" data-validation-required-message="Please enter your username" maxlength="10"  minlength="10"/>
        <small class="help-block text-danger username_error" id="username_error"></small>
    </div>
    <div class="control-group  mb-2">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password"   placeholder="Password" required="required" data-validation-required-message="Please enter your password" />
        <small class="help-block text-danger" id="password_error"></small>
    </div>
    <div class="control-group text-right">
        <button class="btn btn-primary py-2 px-4" type="submit" id="sendMessageButton">Login</button>
    </div>
</form>

<hr>
<a href="{{ url("{$ecommbaseurl}register") }}"> If you don't have a account Register here </a> 
<script>
    $('.form-control').focus(function(){
       const self =  $(this).attr('id');
       $("#"+self).removeClass('required');
       $("#"+self+"_error").empty();
    })
    $("#scheme_enquiry_login").submit(function(e){
        e.preventDefault();
        $('.help-block').empty();
        $('.form-control').removeClass('required');
        const path = $(this).attr('action');
        const formdata = $(this).serialize();
        $.post(path,formdata,function(response){
            if(response.valid){
                if(response.status){
                    //toastr.success(response.msg);
                    $('.modal-body').empty().append('<h6 class="text-center">'+response.msg+'</h6><p class="text-center"><span><i class="fa fa-spinner fa-spin"></i> Loading Content..</span></p>')
                    $('.modal-body').load("{{ url("{$ecommbaseurl}schemeenquiry/{$shopscheme->url_part}") }}","",function(response){});
                }else{
                    //toastr.error(response.msg);
                    alert(response.msg);
                }
            }else{
                $.each(response.errors,function(i,v){
                    $("#"+i).addClass('required');
                    $("#"+i+"_error").empty().append(v);
                });
            }
        });
    });
</script>