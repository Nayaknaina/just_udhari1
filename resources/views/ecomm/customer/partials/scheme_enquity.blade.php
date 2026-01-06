@php 
//print_r($shopscheme->toArray());
@endphp
<form name="sentMessage" id="scheme_enquiry_form" novalidate="novalidate" action="{{ url("{$ecommbaseurl}schemeenquiry")}}">
    @csrf
    <input type="hidden" name="scheme" value="{{ $shopscheme->url_part }}">
    <div class="control-group mb-2">
        <label for="message">MESSAGE</label>
        <textarea name="message" id="message" class="form-control" Placeholder="Your Message Here !" rows="5"></textarea>
        <small class="help-block text-danger" id="message_error"></small>
    </div>
	<div class="alert text-center" id="response_block" style="display:none;"></div>
    <hr>
    <div class="control-group mb-2 text-center"> 
        <button type="submit" name="send" value="enquiry" class="btn btn-sm btn-primary"><li class="fas fa-paper-plane">   Send </button>
    </div>
</form>
<script>
    $("#scheme_enquiry_form").submit(function(e){
        e.preventDefault();
        $('.help-block').empty();
        $('.form-control').removeClass('required');
        const path = $(this).attr('action');
        const formdata = $(this).serialize();
        $.post(path,formdata,function(response){
            if(response.valid){
				$("#response_block").text(response.msg);
                if(response.status){
                    //toastr.success(response.msg);
					$("#response_block").removeClass('alert-danger');
                    $("#response_block").addClass('alert-success');
                    $("#response_block").show();
                    //alert(response.msg);
                    //location.reload();
                    //setTimeout(location.reload(),10000);
					setTimeout(function(){location.reload();}, 1000);
                }else{
                    //toastr.error(response.msg);
					$("#response_block").removeClass('alert-success');
                    $("#response_block").addClass('alert-danger');
                    $("#response_block").show();
                    //alert(response.msg);
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