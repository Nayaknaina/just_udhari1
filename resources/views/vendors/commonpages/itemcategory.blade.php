<div class="modal" tabindex="-1" role="dialog" id="item_category_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content  border-primary">
                <div class="modal-header p-2">
                    <h6 class="modal-title">New Item Category</h6>
                    <button type="button" class="close text-danger" id="category_modal_close" data-dismiss="modal" aria-label="Close" onclick="$('#item_category_form').trigger('reset');">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            <form action="{{ route("global.itemtype.save") }}" method="post" id="item_category_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        @csrf       
                        <div class="col-md-12">
                            <input type="hidden" name="from" value="{{ @$from }}">
                            <div class="form-group mb-1">
                                <label for="title" >TITLE <sup class="text-danger"><b>*</b></sup></label>
                                <input type="text" name="title" id="title" class="form-control custo p-1 h-auto" placeholder="Category Title" >
                                <small class="text-danger help-block" id="name_error"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" value="itemcategory" class="btn btn-sm btn-primary" id="category_add_button">Add ?</button>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                </div>
            </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('item_category_modal').addEventListener('hidden.bs.modal,hide.bs.modal', function () {
            resetcategoryform(true);
            document.getElementById('category_add_button').disabled = false;
        });
    </script>
    <script>
        document.getElementById('item_category_form').addEventListener('submit', function (e) {
            e.preventDefault();
            resetcategoryform();
            const formData = new FormData(this);
            const form = document.getElementById('item_category_form');
            const path = form.action;
            fetch(path, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                document.dispatchEvent(new CustomEvent('categoryformsubmit', {detail:data}));
            })
            .catch(err => {
                document.dispatchEvent(new CustomEvent('categoryformsubmit', {detail:err}));
            });
        });

        function resetcategoryform(reset = false){
            let form =  document.getElementById('item_category_form');
            form.querySelectorAll('.help-block').forEach(function(el) {
                el.textContent = ''; // or el.innerHTML = '';
            });
            form.querySelectorAll('.form-control').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            if(reset){
                form.reset();
            }
        }

    </script>