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
$anchor = ['<a href="'.route('anjuman.new.enroll').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="'.route('anjuman.all.enroll').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$data = new_component_array('newbreadcrumb',"Change Anjuman Enroll") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-primary" style="border-top:1px dashed #ff2300;">
                        <form role="form" id="enroll_form" action="{{ route('anjuman.update.enroll')}}"> 
                            <div class="card-body pt-2">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12 px-1">
                                        <input type="hidden" value="" id="id" name="id">
                                        <label form="type">Scheme <sup class="text-danger">*</sup></label>
                                        <select name="scheme" class="form-control text-primary" id="scheme" style="font-weight:bold;">
                                            <option value="">Loading..</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8 px-1">
                                        <label form="type">Enrolled Name <sup class="text-danger">*</sup></label>
                                        <input type="text" name="custo" id="custo" class="form-control" value="" placeholder="Find Name/Mobile">
                                        <input type="hidden" name="custo_id" id="custo_id" value="">
                                    </div>
                                    <div class="form-group col-md-4  px-1">
                                        <label form="type">Enroll Date <sup class="text-danger">*</sup></label>
                                        <input type="date" name="date" id="date" class="form-control text-center" value="{{ date('d/m/Y',strtotime('now')) }}">
                                    </div>
                                    <div class="col-12 py-1 m-0">
                                        <b id="origin_custo" class="text-info"></b>
                                    </div>
                                    <hr class="col-12 p-0" style="border-top:1px solid lightgray;">
                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" name="create" value="scheme" class="btn btn-primary">Change</button>
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

        
        $.get(url,'',function(response){
            if(!response.enrolled){
                let div = '<div class="text-center alert alert-outline-danger m-0">Nothing to Edit !</div>';
                $("#scheme_form").empty().append(div);
            }else{
                let enroll = response.enrolled;
                $("#id").val(enroll.id);
                $("#scheme").val(enroll.scheme_id);
                $("#custo_id").val(enroll.custo_id);
                if(response.disabled){
                    $("#scheme").prop('readonly',response.disabled);
                }
                $("#custo").val(enroll.custo_name);
                $("#date").val(enroll.enroll_date);
                let custo_strm = response.origin.custo_full_name+" - ("+response.origin.custo_fone+')';
                $('#origin_custo').empty().append("ORIGIN => "+custo_strm);
            }
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