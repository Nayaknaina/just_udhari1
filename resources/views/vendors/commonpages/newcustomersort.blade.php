<div class="modal" tabindex="-1" role="dialog" id="custo_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">New Customer</h6>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            <form action="{{ route("udhar.newcustomer") }}" method="post" id="custo_plus_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                        <input type="hidden" name="_token" value="EW5tKlvF2pCzBlBBwNJz63BkyoQ0Yfci6ql2UOhI" autocomplete="off">                        <div class="form-group">
                            <a href="profile_image_placer" class="btn btn-sm btn-outline-danger" style="position:absolute;right:0;display:none;" id="profile_image_clear">X</a>
                            <label class="form-control h-auto" for="profile_image" style="cursor:pointer;">
                            <img src="/assets/images/icon/browse.png" class="img-responsive h-auto form-control" id="profile_image_placer"></label>
                            <input type="file" name="image" id="profile_image" style="display:none;" accept="image/*">
                        </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name">Name<sup class="text-danger">*</sup></label>
                                <input type="text" name="name" id="name" class="form-control custo" placeholder="Customer Name" required="">
                                <small class="text-danger help-block" id="name_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="fone">Mobile<sup class="text-danger">*</sup></label>
                                <input type="text" name="fone" id="fone" class="form-control custo" placeholder="Customer Mobile Number" required="">
                                <small class="text-danger help-block" id="fone_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="mail">E-Mail</label>
                                <input type="text" name="mail" id="mail" class="form-control custo" placeholder="Customer E-Mail Address">
                                <small class="text-danger help-block" id="mail_error"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="addr">Address<sup class="text-danger">*</sup></label>
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