<form action="{{ route('banking.update',$banking->id,) }}" method="post" id="bank_set_update">
    @csrf
    @method('put')
    <div class="row">
        <div class="form-group col-md-6 p-2">
            <label for="edit_name">NAME</label>
            <input type="text" name="edit_name" id="edit_name" value="{{ $banking->name }}" class="form-control" placeholder="Bank name"  >
        </div>
        <div class="form-group col-md-6 p-2">
            <label for="edit_branch">BRANCH</label>
            <input type="text" name="edit_branch" id="edit_branch" value="{{ $banking->branch }}" class="form-control" placeholder="Bank Branch Name" >
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 p-2">
            <label for="edit_ac">A/C Number</label>
            <input type="text" name="edit_ac" id="edit_ac" value="{{ $banking->account }}" class="form-control" placeholder="Account Number" required  oninput="digitonly(event)">
        </div>
        <div class="form-group col-md-3 p-2">
            <label for="edit_ifsc">IFSC</label>
            <input type="text" name="edit_ifsc" id="edit_ifsc" value="{{ $banking->ifsc }}" class="form-control" placeholder="IFS Code" required >
        </div>
        <div class="form-group col-md-3 p-2">
            @php 
                $sel_apply = $banking->for;
                $$sel_apply = 'selected';
            @endphp 
            <label for="edit_apply">Apply</label>
            <select name="edit_apply" class="form-control" id="edit_apply" required>
                <option value="" >Use Case</option>
                <option value="all" {{ @$all }}>All Section</option>
                <option value="sys" {{ @$sys }}>System Use</option>
                <option value="jb" {{ @$jb }}>Just Bill</option>
                <option value="b" {{ @$b }}>Stock Bill</option>
                <option value="bjb" {{ @$bjb }}>Stock/Just Bill</option>
            </select>
        </div>
        <hr class="col-12 m-1 p-0">
        <div class="form-group col-12 text-center  p-1 mb-0">
            <button type="submit" name="save" value="bank" class="btn btn-danger">Change</button>
        </div>
    </div>
</form>
<script>
    $("#bank_set_update").submit(function(e){
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