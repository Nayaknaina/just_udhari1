<div class="modal" tabindex="-1" role="dialog" id="confirmoperation" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border:1px solid #f95600;">
            <div class="modal-header py-1" style="border-bottom:1px solid #f95600;background:#f5e5dc">
                <h5 class="modal-title text-primary" id="confirm-title">{{ $title??'UI Title' }}</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="confirm-title">
                <form action="{{ url()->current() }}" id="mpincheckform" role="form"  method="post">
                    <div class="form-group" >
                        <label for="mpin">M-PIN</label>
                        @foreach($request->toArray() as $key=>$value)
                            <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="password" name="mpin" value="" placeholder="Enter M-PIN Please !" class="form-control">
                        <small class="alert p-0" id="confirmmsg"></small>
                    </div>
                    <div class="form-group text-center pt-3 m-0" style="border-top:1px dashed #f95600;">
                        <button type="submit" name="check" value="mpin" class="btn btn-sm btn-primary">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("#confirmoperation").on('hidden.bs.modal',function(){
        $("#confirmoperation").remove();
    });
    $("#confirmoperation").on('show.bs.modal',function(){
        $('#confirmmsg').removeClass('alert-danger alert-success px-1').empty();
    });
    $('#confirmoperation').modal('show');
    
    $(document).off('submit', '#mpincheckform').on('submit', '#mpincheckform', function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var data = $(this).serialize();
        var redirect = $("#redirect").val();
        var method = $("#redirect").val();
        $.post(path,data,function(response){
            if(response.error){
                $('#confirmmsg').removeClass('alert-success px-1').addClass('alert-danger px-1').html(response.message || 'Invalid MPIN');
            }else{
                if(redirect=='true'){
                    if(method=='post'){
                        $(this).submit();
                    }else{
                        window.location.href = path;
                    }
                }else{
                    $(document).trigger('mpinVerified', [response,window.mpinTriggerElement]);
                }
                $('#confirmoperation').modal('hide');
            }
        },'json');
    });

</script>