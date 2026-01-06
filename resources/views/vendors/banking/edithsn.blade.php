<form action="{{ url("vendors/banking/hsnupdate/{$gstinfo->id}") }}" method="post" id="bill_set_update">
    @csrf
    <div class="col-12 p-2">
        @php 
            $sel_cat = strtolower($gstinfo->category);
            $type = strtolower(str_replace(" ","_",$gstinfo->type));
           $$sel_cat =  $$type = 'selected';
        @endphp
        <style>
            #edit_hsn_gst select>option.{{ $sel_cat }}{
                display:block;
            }
        </style>
        <div class="row" id="edit_hsn_gst">
            <div class="form-group col-md-12 p-0">
                <label for="cat">Category</label>
                <select name="cat" id="cat" class="form-control">
                    <option value="">Select</option>
                    <option value="gold" id="gold" {{ @$gold }}>Gold</option>
                    <option value="silver" id="silver" {{ @$silver }}>Silver</option>
                    <option value="diamond" id="diamond" {{ @$diamond }}>Diamond</option>
                    <option value="stone" id="stone" {{ @$stone }}>Stone</option>
                    <option value="artificial" id="artificial" {{ @$artificial }}>Artificial</option>
                    <option value="default" id="default" {{ @$default }}>Default</option>
                </select>
            </div>
            <div class="form-group col-md-12 p-0">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="">Select</option>
                    <option value="jewellery" class="type_option gold silver artificial" {{ @$jewellery }} >Jewellery</option>
                    <option value="bullion" class="type_option gold silver" {{ @$bullion }}>Bullion</option>
                    <option value="loose" class="type_option gold silver diamond" {{ @$loose }}>Loose</option>
                    <option value="precious" class="type_option stone" {{ @$precious }}>Precious</option>
                    <option value="semi precious" class="type_option stone" {{ @$semi_precious }}>Semi Precious</option>
                    <option value="common" class="type_option default" {{ @$common }}>Common</option>
                </select>
            </div>
            <div class="form-group col-md-6 p-0">
                <label for="hsf">HSN</label>
                <input type="text" name="hsn" id="hsn" value="{{ $gstinfo->hsn }}" class="form-control" placeholder="HSF Code"   oninput="digitonly(event)">
            </div>
            <div class="form-group col-md-6 p-0">
                <label for="gst">GST %</label>
                <input type="text" name="gst" id="gst" value="{{ $gstinfo->gst }}" class="form-control" placeholder="GST % Value" >
            </div>
            <hr class="col-12 m-1 p-0">
            <div class="form-group col-12 text-center  p-1 mb-0">
                <button type="submit" name="update" value="hsf" class="btn btn-danger">Change</button>
            </div>
        </div>
    </div>
</form>
<script>
    $("#edit_hsn_gst #cat").change(function(){
        $('#edit_hsn_gst').find('option.type_option').hide();
        $("#edit_hsn_gst").find('#type').val('');
        const target = $(this).find('option:selected').attr('id');
        $('#edit_hsn_gst').find(`option.${target}`).show();
    });

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