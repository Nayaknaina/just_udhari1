@extends('layouts.vendors.app')

@section('content')

<style>
#emi_value.disabled{
    opacity:0.3;
    cursor:not-allowed;
    pointer-events: none;
}
sup.text-danger{
    font-weight:bold!important;
}
</style>
@php 
$anchor = ['<a href="'.route('anjuman.new.scheme').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="'.route('anjuman.all.scheme').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$data = new_component_array('newbreadcrumb',"Change Anjuman Scheme") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-primary mt-1" style="border-top:1px dashed #ff2300;">
                        <form role="form" id="scheme_form" action="{{ route('anjuman.update.scheme')}}"> 
                            <div class="card-body pt-2">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="form-group col-md-2 px-1">
                                        <input type="hidden" id="id" name="id" value="">
                                        <label form="type">Type <sup class="text-danger">*</sup></label>
                                        <select name="type" class="form-control text-primary" id="type" style="font-weight:bold;">
                                            <option value="">Select</option>
                                            <option value="gold">Gold</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-10 px-1">
                                        <label form="type">Title <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control" id="title" name="title" value="">
                                    </div>
                                    <div class="form-group col-md-12 px-1">
                                        <label form="type">Detail</label>
                                        <textarea name="detail" id="detail" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group col-md-3 px-1">
                                        <label form="type">Validity <sup class="text-danger">*</sup></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" id="validity" name="validity" placeholder="">
                                            <span class="input-group-text"> <b>Month</b></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 px-1">
                                        <label form="type">Start Date <sup class="text-danger">*</sup></label>
                                        <input type="date" name="start" id="start" value="{{ date('d-m-Y',strtotime('Now')) }}" class="form-control" >
                                    </div>
                                    <div class="form-group col-md-3 px-1">
                                        <label form="type">Emi Fix ? <sup class="text-danger">*</sup></label>
                                        <div class="input-group">
                                            <label for="emi_yes"class="form-control" style="border-radius: 20px 0px 0px 20px;">
                                                <input type="radio" name="emi" value="yes" id="emi_yes" class="emi" > Yes
                                            </label>
                                            <label for="emi_no" class="form-control" style="border-radius: 0px 20px 20px 0px;">
                                                <input type="radio" name="emi" value="no" id="emi_no" class="emi" >  No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 disabled px-1" id="emi_value">
                                        <label form="type">Emi Value ? <sup class="text-danger" id="emi_val_req" style="display:none;">*</sup></label>
                                        <div class="input-group">
                                            <input type="text" id="quant" name="quant" class="form-control" value="" >
                                            <span class="input-group-text"> 
                                                <b id="unit" >Unit</b>
                                                <b id="gr_unit" style="display:none;">Grm</b>
                                                <b id="rs_unit" style="display:none;">Rs.</b>
                                            </span>
                                        </div>
                                    </div>
                                    <hr class="col-12 p-0" style="border-top:1px solid lightgray;">
                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" name="create" value="scheme" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('javascript')
<script>
    $(document).ready(function(){
        
        $("#type").change(function(){
            let type = $(this).val();
            if(type==""){
                $('#unit').show();
                $('#gr_unit').hide();
                $('#rs_unit').hide();
            }else{
                $('#unit').hide();
                if(type=='gold'){
                    $('#gr_unit').show();
                    $('#rs_unit').hide();
                }else{
                    $('#gr_unit').hide();
                    $('#rs_unit').show();
                }
            }
        });

        $('.emi').change(function(){
            let emi_fix_set = $(this).val();
            if(emi_fix_set=='yes'){
                $("#emi_val_req").show();
                $("#emi_value").removeClass('disabled');
            }else{
                $("#emi_val_req").hide();
                $("#quant").val("");
                $("#emi_value").addClass('disabled');
            }
        });

        $("#scheme_form").submit(function(e){
            e.preventDefault();
            var formdata = $(this).serialize();
            var path = $(this).attr('action');
            $.post(path,formdata,function(response){
                if(response.errors){
                    let error = response.errors;
                    $.each(error ,function(ei,ev){
                        $('#'+ei).addClass('is-invalid');
                        toastr.error(ev);
                    });
                }else if(response.status){
                    success_sweettoatr(response.msg);
                    window.location.href='{{ route("anjuman.all.scheme") }}';
                }else {
                    toastr.error(response.msg);
                }
            });
        });

        $.get(url,'',function(response){
            if(!response.anjumanScheme){
                let div = '<div class="text-center alert alert-outline-danger m-0">Nothing to Edit !</div>';
                $("#scheme_form").empty().append(div);
            }else{
                let scheme = response.anjumanScheme;
                $("#id").val(scheme.id);
                $("#type").val(scheme.type);
                $("#type").trigger('change');
                $("#title").val(scheme.title);
                $("#detail").val(scheme.detail);
                $("#validity").val(scheme.validity);
                $("#start").val(scheme.start);
                if(scheme.fix_emi==1){
                    $("#emi_yes").click();
                }else{
                    $("#emi_no").click();
                }
                $('#quant').val(scheme.emi_quant);
                $("#id").val(scheme.id);
                $("#id").val(scheme.id);
                $("#id").val(scheme.id);
            }
        });
    });
</script>
@endsection 