<div id="msg_block" class="pb-2">{!! @$msg !!}</div>
<form method="post" action="{{ route('setting.mpin','verify') }}" id="mpin_change_form">
    @csrf
    <div class="form-group mpin_form_group mt-3 pb-3">
        <label for="otp_field"  class="stick_label">OTP </label>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="OTP Here" id="otp_field" name="otp">
            <div class="input-group-append">
                <span class="input-group-text"><i  id="otp-timer"  style="color:blue;">0</i>&nbsp;sec.</span>
            </div>
        </div>
        <small class="text-danger error_block" id="otp_error"></small>
    </div>
    <div class="form-group mpin_form_group  mt-3 pb-2">
        <label for="mpin_field" class="stick_label">New MPIN</label>
        <input type="text" class="form-control" placeholder="MPIN Here(4 Digits)" id="new_mpin_field" name="new">
        <small class="text-danger error_block" id="new_error"></small>
    </div>
    <div class="form-group mpin_form_group  mt-3 pb-2">
        <label for="mpin_field" class="stick_label">Confirm MPIN</label>
        <input type="text" class="form-control" placeholder="Confirm MPIN" id="re_mpin_field" name="confirm">
        <small class="text-danger error_block" id="confirm_error"></small>
    </div>
    <hr class="col-12 m-0 p-0">
    <div class="form-group m-0 p-2">
        <ul style="list-style:none;" class="p-0 row">
            <li class="col-5 text-left" id="send_otp">
                <a href="{{ route('setting.mpin',['otp','resend']) }}" class="text-info" id="resend_otp_button"><u>Resend Otp ?</u></a>
            </li>
            <li  class="col-7 text-right">
                <button type="submit" class="btn btn-sm btn-success" name="set" value="otp"><i class="fa fa-thumbs-up"></i> Varify & Change</button>
            </li>
        </ul>
    </div>
</form>
<div id="mpin_loader" class="text-center" >
    <span style="margin:auto;"><i class="fa fa-spinner fa-spin"></i>Processing...</span>
</div>
<style>
    #mpin_loader{
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        right:0;
        background: #0006;
        color:white;
        z-index:1;
        padding:45% 0;  
        display:none;      
    }
    .mpin_form_group{
        position:relative;
    }
    label.stick_label{
        position:absolute;
        top:-30%;
        background:white;
        left:2%;
    }
    li#send_otp{
        position:relative;
    }
    li#send_otp:before{
        position:absolute;
        content:" ";
        width:100%;
        height:100%;
        top:0;
        left:0;
        z-index:1;
        background:#ffffffb0;
    }li#send_otp.active:before{
        content:unset;
    }
</style>
<script>
$("#mpin_change_form").submit(function(e){
    e.preventDefault();
    $('.error_block').empty();
    $('input[type="text"]').removeClass('is-invalid');
    $("#mpin_loader").show();
    $.post($(this).attr('action'),$(this).serialize(),function(response){
        if(response.errors){
            if(typeof response.errors !='string'){
                $.each(response.errors,function(i,v){
                    $('[name="'+i+'"]').addClass('is-invalid');
                    $.each(v,function(ind,val){
                        $("small#"+i+"_error").html(val);
                        //toastr.error(val);
                    });
                });
            }else{
                toastr.error(response.errors);
            }
            if(response.expire){
                $("li#send_otp").addClass('active');
            }
        }else{
            toastr.success(response.success);
            $("#mpin_modal").modal('hide');
        }
        $("#mpin_loader").hide();
    });
    
});

//var timerInterval = false;
var otpExpirationTime =  60; // seconds

// Function to update the timer
function updateTimer() {

    // Format the timer (mm:ss)
    $('#otp-timer').text((otpExpirationTime < 10 ? '0' : '') + otpExpirationTime);

    // Decrease the remaining time
    if (otpExpirationTime > 0) {
        otpExpirationTime--;
    } else {
        clearInterval(timerInterval);
        $("li#send_otp").addClass('active');
        toastr.error('OTP expired !');
        // Optionally, you can disable the OTP input here or trigger other actions
    }
}

var timerInterval = setInterval(updateTimer, 1000);

// Call the function immediately to display the initial timer
updateTimer();

$("a#resend_otp_button").click(function(e){
    e.preventDefault();
    $("#mpin_loader").show();
    $.get($(this).attr('href'),"",function(response){
        $('.error_block').empty();
        $('input[type="text"]').removeClass('is-invalid');
        $('#msg_block').html(response.msg);
        $("#mpin_change_form").trigger('reset');
        $("#mpin_loader").hide();
        if(response.status){
            $('li#send_otp').removeClass('active');
            otpExpirationTime = 60;
            timerInterval = setInterval(updateTimer, 1000); // Start a new interval
            updateTimer();
        }
    });
})

</script>
<!--
<script>
    $(document).ready(function () {
        // Set the OTP expiration time (e.g., 5 minutes)
        var otpExpirationTime = 5 * 60; // 5 minutes in seconds

        // Function to update the timer
        function updateTimer() {
            var minutes = Math.floor(otpExpirationTime / 60);
            var seconds = otpExpirationTime % 60;

            // Format the timer (mm:ss)
            $('#otp-timer').text(minutes + ':' + (seconds < 10 ? '0' : '') + seconds);

            // Decrease the remaining time
            if (otpExpirationTime > 0) {
                otpExpirationTime--;
            } else {
                clearInterval(timerInterval);
                $('#otp-timer').text('OTP expired!');
                // Optionally, you can disable the OTP input here or trigger other actions
            }
        }

        // Update the timer every second
        var timerInterval = setInterval(updateTimer, 1000);

        // Call the function immediately to display the initial timer
        updateTimer();
    });
</script>
-->