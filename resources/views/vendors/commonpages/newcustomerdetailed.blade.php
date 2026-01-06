<div class="modal" tabindex="-1" role="dialog" id="custo_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content  border-primary">
                <div class="modal-header p-2">
                    <h6 class="modal-title">New Customer</h6>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close" onclick="$('#custo_plus_form').trigger('reset');">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            <form action="{{ route("customers.save.new") }}" method="post" id="custo_plus_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            @csrf     
                            <style>
                                .form-group{
                                    position:relative;
                                }
                                .required:after{
                                    position:absolute;
                                    color:red;
                                    content:"*";
                                    right:5px;
                                    top:0;
                                }
                            </style>                 
                            <div class="form-group">
                                <button data-target="profile_image_placer" class="btn btn-sm btn-outline-danger" style="position:absolute;right:0;display:none;" id="profile_image_clear">x</button>
                                <!-- <a href="profile_image_placer" class="btn btn-sm btn-outline-danger" style="position:absolute;right:0;display:none;" id="profile_image_clear">X</a> -->
                                <label class="form-control h-auto" for="profile_image" style="cursor:pointer;">
                                <img src="/assets/images/icon/browse.png" class="img-responsive h-auto form-control" id="profile_image_placer"></label>
                                <input type="file" name="image" id="profile_image" style="display:none;" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <input type="hidden" name="from" value="{{ @$from }}">
                            <div class="form-group required">
                                <input type="text" name="name" id="name" class="form-control custo p-1 h-auto" placeholder="Customer Name" required="">
                                <small class="text-danger help-block" id="name_error"></small>
                            </div>
                            <div class="form-group required">
                                <input type="text" name="fone" id="fone" class="form-control custo p-1 h-auto" placeholder="Customer Mobile Number" required=""  maxlength="10" oninput="this.value=this.value.replace(/\D/g, '').slice(0, 10)">
                                <small class="text-danger help-block" id="fone_error"></small>
                            </div>
                            <div class="form-group">
                                <input type="text" name="mail" id="mail" class="form-control custo p-1 h-auto" placeholder="Customer E-Mail Address">
                                <small class="text-danger help-block" id="mail_error"></small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 p-1">
                                    <div class="form-group mb-1 required">
                                        <select class="form-control" name="state" id="state" required>
                                        @if($states->count()>0)
                                            <option value="">Select State</option>
                                            @foreach($states as $si=>$state)
                                                <option value="{{ $state->code }}">{{ $state->name." / ".$state->code }}</option>
                                            @endforeach
                                        @else 
                                            <option value="">No State/Code</option>
                                        @endif
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <select class="form-control" name="dist" id="dist">
                                            <option value="">Select Disrtict</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <input type="text" name="area_title" id="area_title" class="form-control custo_addr" placeholder="Enter Area Name">
                                    </div>
                                </div>
                                <div class="col-md-6 p-1">
                                    <div class="form-group required">
                                        <textarea name="addr" id="addr" class="form-control custo" rows="4" placeholder="Customer Address" required=""></textarea>
                                        <small class="text-danger help-block" id="addr_error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" value="custo" class="btn btn-primary">Add ?</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader instance
            
            reader.onload = function(e) {
                const imgElement = document.getElementById('profile_image_placer');
                imgElement.src = e.target.result;  // Set the image source to the file data
                imgElement.style.display = 'block'; // Make the image visible
                document.getElementById('profile_image_clear').style.display = 'block';
                document.getElementById('profile_image_upload').style.display = 'block';
                $("#"+id+"_clear").show();
        //         $("#"+id+"_upload").show();
            };
            
            reader.readAsDataURL(file); // Read the file as a Data URL
        }
    });

    document.getElementById('profile_image_clear').addEventListener('click', function(event) {
        event.preventDefault();
        this.style.display = 'none';
        var targetElement = document.getElementById(this.getAttribute('data-target'));
        targetElement.setAttribute('src', '/assets/images/icon/browse.png');
        //this.getAttribute('data-target').style.display = 'none';
        //this.getAttribute('data-target').setAttribute('src','/assets/images/icon/browse.png');
    });
</script>