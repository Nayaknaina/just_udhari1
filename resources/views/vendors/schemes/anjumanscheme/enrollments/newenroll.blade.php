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
    #customerlist {
        list-style-type: none;
        padding: 0;
        margin: 0;
        background-color:#efefef;
        border: 1px solid #ccc;
        width: 200px;
        display: none;
        position: absolute;
        z-index: 100;
        max-height:50vh;
        height:auto;
        min-width:auto;
        overflow-x:scroll;
        box-shadow: 1px 2px 3px gray;
        border-radius:10px;
    }
    #customerlist.active{
        display:block;
    }
    #customerlist li {
        padding: 10px;
        cursor: pointer;
        text-wrap:wrap;
    }

    #customerlist li.hover,#customerlist li:hover {
        background-color: #ddd;
    }
</style>
@php 
$anchor = ['<a href="'.route('anjuman.all.enroll').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$data = new_component_array('newbreadcrumb',"New Anjuman Enroll") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-primary mt-1" style="border-top:1px dashed #ff2300;">
                        <form role="form" id="enroll_form" action="{{ route('anjuman.save.enroll.save')}}"> 
                            <div class="card-body pt-2">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12 px-1">
                                        <label form="type">Scheme <sup class="text-danger">*</sup></label>
                                        <select name="scheme" class="form-control text-primary" id="scheme" style="font-weight:bold;">
                                            <option value="">Loading..</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-7 px-1">
                                        <label form="type">Customer <sup class="text-danger">*</sup></label>
                                        <div class="input-group m-0" id="custo_parent_div">
                                            <input type="text" name="custo" id="custo" class="form-control" value="" placeholder="Find Name/Mobile">
                                            <input type="hidden" name="custo_id" id="custo_id" value="">
                                             <div class="input-group-append">
                                                <button class="btn btn-secondary" type="button"  data-toggle="modal" data-target="#custo_modal">
                                                    <span class="fa fa-plus"></span>
                                                </button>
                                            </div>
                                            <ul id="customerlist" class="w-100"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3  px-1">
                                        <label form="type">Enroll Date<sup class="text-danger">*</sup></label>
                                        <input type="date" name="date" id="date" class="form-control text-center" value="{{ date('d/m/Y',strtotime('now')) }}">
                                    </div>
                                    <div class="form-group col-md-2 px-1">
                                        <label form="type">Multi Enroll <sup class="text-danger">*</sup></label>
                                        <input type="number" name="times" id="times" class="form-control text-center" value="1" placeholder="Num" min="1" style="font-weight:bold;">
                                    </div>
                                    <div class="col-12">
                                        <div class="row p-1" style="border:1px dashed gray;box-shadow:1px 2px 3px 3px  lightgray inset;border-radius: 0px 0px 20px 20px;" id="multienroll">
                                            <div class="form-group col-md-6 px-1">
                                                <label form="type">Enrolled Name 1 <sup class="text-danger">*</sup></label>
                                                <input type="text" name="name[]" id="name" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="col-12 p-0" style="border-top:1px solid lightgray;">
                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" name="create" value="scheme" class="btn btn-primary">Create</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('vendors.commonpages.newcustomerfull')

@endsection
@section('javascript')
<script>
    $(document).ready(function(){
        
        $.get("{{ route('anjuman.all.scheme') }}",'mode=default',function(response){
            let option = false;
            if(response.scheme.length > 0){
                option = '<option value="">Select</option>';
                let schemes = response.scheme;
                $.each(schemes,function(i,v){
                    option+='<option value="'+v.id+'">'+v.type.toUpperCase()+'&#10148; '+v.title+'</option>' ;
                });
                
            }else{
                option = '<option value="">No Schemes !</option>';
            }
            $("#scheme").empty().append(option);
        });

        function getcustomer(self){
            const title = self.val();
            if(title!=""){
                $.get("{{ route('customers.search') }}","keyword="+title+"&mode=default",function(response){
                    if(response){
						var li = '';
                        if(response.length > 0){
                            $.each(response,function(i,v){
								let stream = v.id+'~'+v.name+'~'+v.mobile;
                                li+='<li><a href="javascript:void(null);" class="select_customer" data-target="'+stream+'">'+v.name+' - ('+v.mobile+') </a></li>';  
                            });
                        }else{
                           li = '<li>No Record</li>';
                        }
						$("#customerlist").empty().append(li);
						$("#customerlist").addClass('active');
                    }
                });
            }else{
                $('[name="custo_id"]').val('');
                $('[name="udhar_id"]').val('');
                $("#udhar_form").trigger('reset');
                $("#customerlist").removeClass('active');
                $("#bottom_block").hide();
            }
        }

        function positionmenu(container,input){
            const $menu = $(container);
            const $input = $(input);
            const input_height = $input.outerHeight();//Use To Specify Position From Top/Bottom
            const menu_height = $menu.outerHeight();
            const page_height = $(document).height();
            const from_top = $input.offset().top; 
            const from_bottom = $(document).height()-(from_top+input_height);
            switch(menu_height){
                case (menu_height/4<from_bottom):
                    $menu.css({
                        top: input_height + 'px',
                    });
                    break;
                case (menu_height/4 < from_top):
                    $menu.css({
                        bottom: input_height + 'px',
                    });
                    break;
                default :
                    $menu.css({
                        top: input_height + 'px',
                    });
                    break;
            }
        }

        $("#times").on('input',function(e){
            var val = ($(this).val()>0)?$(this).val():1;
            $("#multienroll").empty();
            for(var i=1;i<=val;i++){
                var name = $('#custo').val()??'';
                if(name!=""){
                    name = $(name.split('-'))[0].trim();
                }
                var div = `<div class="form-group col-md-6 px-1">
                                <label form="type">Enrolled Name `+i+` <sup class="text-danger">*</sup></label>
                                <input type="text" name="name[]" id="name" class="form-control" value="`+name+`">
                            </div>`;
                $("#multienroll").append(div);
            }
        })
        $("#times").on('blur',function(){
            if($(this).val()==""){
                $(this).val(1);
            }
        })
        $("#custo").keyup(function(e){
            $("#custi_id").val("");
            $("#customerlist").removeClass('active');
            getcustomer($(this));
            positionmenu("#customerlist",'#custo_parent_div');
        });

        $(document).on('click','.select_customer',function(e){
            var target_data = $(this).data('target');
            var data = target_data.split('~');
            $("#custo_id").val(data[0]);
            $("#custo").val(data[1]+' - ('+data[2]+')');
            var enrol_count = $("#times").val()??1;
            for(var i=1;i<=enrol_count;i++){
                $('input[name="name[]"]').val(data[1]);
            }
            $("#customerlist").removeClass('active');
        });

        $("#custo_plus_form").submit(function(e){
            e.preventDefault();
            $('.help-block').empty();
            $('.custo').removeClass('invalid');
            const path = $(this).attr('action');
            const fd = new FormData(this) ;
            //var formData = new FormData(this); 
            $.ajax({
                url: path,
                type: 'POST',
                data: fd,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                $('.btn').prop("disabled", true);
                $('#loader').removeClass('hidden');
                },
                success: function(response) {
                    $('.btn').prop("disabled", false);
                    $('#loader').addClass('hidden');
                    if(response.success){
                        $("#custo_modal").modal('hide');
                        $("#custo_plus_form").trigger('reset');
                        success_sweettoatr(response.success);
                        if(response.data){
                            $("#custo_id").val(response.data.id);
                            let name = response.data.custo_full_name;
                            let mob = response.data.custo_fone;
                            $('#custo').val(name+' -( '+mob+' )');
                           var enrol_count = $("#times").val()??1;
                            for(var i=1;i<=enrol_count;i++){
                                $('input[name="name[]"]').val(name);
                            }
                        }
                    }else{
                        if(typeof response.errors !='string'){
                            $.each(response.errors,function(i,v){
                                $('[name="'+i+'"]').addClass('is-invalid');
                                $.each(v,function(ind,val){
                                    toastr.error(val);
                                });
                            });
                        }else{
                            toastr.error(response.errors);
                        }
                    }
                },
                error: function(response) {
                    
                }
            });
        });

        $("#enroll_form").submit(function(e){
            e.preventDefault();
            var formdata = $(this).serialize();
            var path = $(this).attr('action');
            $.post(path,formdata,function(response){
                if(response.errors){
                    let error = response.errors;
                    $.each(error ,function(ei,ev){
                       var element_arr =  ei.split('.');
                       var element = "";
                       var message = ev;
                        if(element_arr[1]){
                            element = $('input[name="name[]"]').eq(element_arr[1]);
                        }else{
                            element = $("#"+ei);
                        }
                        element.addClass('is-invalid');
                        toastr.error(message);
                    });
                }else if(response.status){
                    success_sweettoatr(response.msg);
                    window.location.href='{{ route("anjuman.all.enroll") }}';
                }else{
                    toastr.error(response.msg);
                }
            });
        });
    });
</script>
@endsection 