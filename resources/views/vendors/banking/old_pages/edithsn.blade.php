<form action="{{ url("vendors/banking/hsnupdate/{$gstinfo->id}") }}" method="post" id="bill_set_update">
    @csrf
    <div class="row">
        <div class="form-group col-md-6 p-2">
            <label for="hsf">HSN Code</label>
            <input type="text" name="hsf" id="hsf" value="{{ $gstinfo->hsf }}" class="form-control" placeholder="HSF Code"   oninput="digitonly(event)">
        </div>
        <div class="form-group col-md-6 p-2">
            <label for="gst">GST %</label>
            <input type="text" name="gst" id="gst" value="{{ $gstinfo->gst }}" class="form-control" placeholder="GST % Value" >
        </div>
        <div class="form-group col-md-12 p-2">
            <label for="desc">HSF Description</label>
            <input type="text" name="desc" id="desc" value="{{ $gstinfo->desc }}" class="form-control" placeholder="About HSF Code" >
        </div>
        <hr class="col-12 m-1 p-0">
        <div class="form-group col-12 text-center  p-1 mb-0">
            <button type="submit" name="update" value="hsf" class="btn btn-danger">Change</button>
        </div>
    </div>
</form>
<script>
    $("#bill_set_update").submit(function(e){
        e.preventDefault();
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.valid){
                if(response.status){
                    window.location.reload();
                    success_sweettoatr(response.msg);
                }else{
                    toastr.error(msg);
                }
            }else{
                var count = 0;
                $.each(response.errors,function(i,v){
                    var ele = $('[name="'+i+'"]');
                    if(count==0){
                        ele.focus();
                        count++;
                    }
                    $('[name="'+i+'"]').addClass('is-invalid');
                    toastr.error(v[0]);
                });
            }
        });
    });
</script>