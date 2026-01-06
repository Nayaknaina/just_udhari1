<div class="modal" tabindex="-1" role="dialog" id="custo_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content  border-primary">
                <div class="modal-header p-2">
                    <h6 class="modal-title">New Customer Category</h6>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close" onclick="$('#custo_plus_form').trigger('reset');">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            <form action="{{ route("global.customer.save") }}" method="post" id="custo_plus_form" enctype="multipart/form-data">
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
                                label.radio_label > input[type="radio"]{
                                    display:none;
                                }
                                label.radio_label{
                                    cursor:pointer;
                                    color:gray;
                                }
                                label.radio_label.checked,label.radio_label:hover{
                                    color:blue;
                                    border:1px solid blue;
                                    text-shadow: 1px 2px 3px gray;
                                }
                                label.radio_label:before{
                                    content:"\2713";
                                }
                                label.radio_label.checked:before{
                                    content:"\2714";
                                }
                            </style>                 
                            <div class="form-group">
                                <button data-target="profile_image_placer" class="btn btn-sm btn-outline-danger" style="position:absolute;right:0;display:none;" id="profile_image_clear">x</button>
                                <label class="form-control h-auto" for="profile_image" style="cursor:pointer;">
                                <img src="/assets/images/icon/browse.png" class="img-responsive h-auto form-control" id="profile_image_placer"></label>
                                <input type="file" name="image" id="profile_image" style="display:none;" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group input-group mb-1">
                                <label for="customer" class="form-control input-button checked radio_label" style="border-radius:15px 0 0 15px;">
                                    <input type="radio" name="type" value="c" id="customer" checked > Customer
                                </label>
                                <label for="supplier" class="form-control input-button radio_label" style="border-radius:0 15px 15px 0;">
                                    <input type="radio" name="type" value="s" id="supplier" > Supplier
                                </label>
                            </div>
                            <input type="hidden" name="from" value="{{ @$from }}">
                            <div class="form-group required mb-1">
                                <input type="text" name="name" id="name" class="form-control custo p-1 h-auto" placeholder="Customer Name" required="">
                                <small class="text-danger help-block" id="name_error"></small>
                            </div>
                            <div class="form-group required mb-1">
                                <input type="text" name="fone" id="fone" class="form-control custo p-1 h-auto" placeholder="Customer Mobile Number" required=""  maxlength="10" oninput="this.value=this.value.replace(/\D/g, '').slice(0, 10)">
                                <small class="text-danger help-block" id="fone_error"></small>
                            </div>
                            <div class="form-group mb-1">
                                <input type="text" name="mail" id="mail" class="form-control custo p-1 h-auto" placeholder="Customer E-Mail Address">
                                <small class="text-danger help-block" id="mail_error"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <textarea name="addr" id="addr" class="form-control custo" rows="2" placeholder="Customer Address" required=""></textarea>
                                <small class="text-danger help-block" id="addr_error"></small>
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
    
    document.querySelectorAll('input[name="type"]').forEach(function(input) {
        input.addEventListener('change', function() {
            document.querySelectorAll('.radio_label').forEach(function(label) {
                label.classList.remove('checked');
            });

            const parentLabel = this.closest('label');
            if (parentLabel) {
                parentLabel.classList.add('checked');
            }
        });
    });

    document.getElementById('custo_modal').addEventListener('hidden.bs.modal,hide.bs.modal', function () {
       resetcustoform(true);
    });
</script>
<script>
document.getElementById('custo_plus_form').addEventListener('submit', function (e) {
    e.preventDefault();
    resetcustoform();
    const formData = new FormData(this);
    const form = document.getElementById('custo_plus_form');
    const path = form.action;
    fetch(path, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.dispatchEvent(new CustomEvent('customerformsubmit', {detail:data}));
    })
    .catch(err => {
        document.dispatchEvent(new CustomEvent('customerformsubmit', {detail:err}));
    });
});

function resetcustoform(reset = false){
    let form =  document.getElementById('custo_plus_form');
    form.querySelectorAll('.help-block').forEach(function(el) {
        el.textContent = ''; // or el.innerHTML = '';
    });
    form.querySelectorAll('.form-control').forEach(function(el) {
        el.classList.remove('is-invalid');
    });
    if(reset){
        form.reset()
    }
}

</script>