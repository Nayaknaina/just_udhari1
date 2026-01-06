@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Banking/Billing Info',[['title' => 'Banking & Billing Setting']] ) ;

@endphp
<style>
.detail_info{
    border:1px solid lightgray;
    padding:0 2px;
    color:blue;
}
.detail_info:hover{
    color:black;
}
a.switch{
    display:block;
}
.switch{
    height:40px;
    border:1px solid gray;
    border-radius:5px;
    width:20px;
    position:relative;
    overflow:hidden;
    cursor:pointer;
    background:white;
}

.switch > span:before{
    content:"off";
    height:20px;
    width:100%;
    position:absolute;
    bottom:0;
    background:pink;
    font-size: xx-small;
    color:red;
    text-align:center;
}
.switch > span.on:before{
    top:0;
    content:"On";
    background:lightgreen;
    color:green;
}
tr.disabled >td{
    position:relative
}
tr.disabled >td:before{
    content:"";
    width:100%;
    height:100%;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    background:black;
    opacity:0.5;
    z-index:1;
}
</style>
{{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Banking / Billing Info") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <!--<div class="card-header">
                            <h6>Banking / Billing Info</h6>
                        </div>-->
                        @php 
                        $switch = ["off","on"];
                        @endphp
                        <div class="card-body row">
                            
                            <div class="card col-md-6 p-0">
                                <div class="card-header">
                                    <h6>Banking Info</h6>
                                </div>
                                <div class="card-body p-2">
                                    <form action="{{ route('banking.store') }}" method="post" id="bank_set">
                                        @csrf
                                        @method('post')
                                        <div class="row">
                                            <div class="form-group col-md-6 ">
                                                <label for="name">NAME</label>
                                                <input type="text" name="name" id="name" value="" class="form-control" placeholder="Bank name"  >
                                            </div>
                                            <div class="form-group col-md-6 ">
                                                <label for="branch">BRANCH</label>
                                                <input type="text" name="branch" id="branch" value="" class="form-control" placeholder="Bank Branch Name" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 ">
                                                <label for="ac">A/C Number</label>
                                                <input type="text" name="ac" id="ac" value="" class="form-control" placeholder="Account Number" required  oninput="digitonly(event)">
                                            </div>
                                            <div class="form-group col-md-3 ">
                                                <label for="ifsc">IFSC</label>
                                                <input type="text" name="ifsc" id="ifsc" value="" class="form-control" placeholder="IFS Code" required>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="apply">Apply</label>
                                                <select name="apply" class="form-control" id="apply" required>
                                                    <option value="" >Use Case</option>
                                                    <option value="all" selected >All Section</option>
                                                    <option value="sys" >System Use</option>
                                                    <option value="jb" >Just Bill</option>
                                                    <option value="b" >Stock Bill</option>
                                                    <option value="bjb" >Stock/Just Bill</option>
                                                </select>
                                            </div>
                                            <hr class="col-12 m-1 p-0">
                                            <div class="form-group col-12 text-center  p-1">
                                                <button type="submit" name="save" value="bank" class="btn btn-danger">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="col-12 table-responsive p-0">
                                        <table class="table table-bordered table-stripped">
                                            <thead>
                                                <tr class="bg-dark">
                                                    <th><li class="fa fa-cursor"></li></th>
                                                    <th>BANK</th>
                                                    <th>ACCOUNT</th>
                                                    <th>APPLY</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody id="banking-Info">
                                                @if($banking->count()>0)
                                                    @php 
                                                        $apply = ["all"=>"All Section","sys"=>"System Use","jb"=>"Just Bill","b"=>"Stock Bill","bjb"=>"Stock/Just Bill"];
                                                    @endphp
                                                    @foreach($banking as $bk=>$bank) 
                                                        <tr class="{{ ($bank->status==0)?'table-danger':'' }}">
                                                            <td>
                                                                <!-- <label class="switch">
                                                                    <span class=""></span>
                                                                </label> -->
                                                                <a class="switch anchor" href="{{ url("vendors/banking/status/{$bank->id}") }}">
                                                                    <span class="{{ $switch[$bank->status] }}"></span>
                                                                </a>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $bank->name }}
                                                                <hr class="m-1">
                                                                {{ $bank->branch }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $bank->account }}
                                                                <hr class="m-1">
                                                                <b>IFSC : </b>
                                                                {{ $bank->ifsc }}</td>
                                                            <td class="text-center">
                                                                {{ $apply["{$bank->for}"] }}
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ route('banking.edit',$bank->id) }}" class="btn btn-outline-info edit_info  px-1 py-0 m-1" data-head="Change Banking Info"><li class="fa fa-edit" ></li></a>

                                                                <a href="{{ url("vendors/banking/delete/{$bank->id}") }}" class="btn btn-outline-danger delete_info  px-1 py-0 m-1" data-target="BANKING Info"><li class="fa fa-times"></li></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else 
                                                <tr><td colspan="5" class="text-center text-danger">No Detail !</tr>
                                                @endif 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card col-md-6 p-0">
                                <div class="card-header">
                                    <h6>Billing Info</h6>
                                </div>
                                <div class="card-body p-2">
                                <form action="{{ route('banking.save') }}" method="post" id="bill_set">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label for="hsf">HSN Code</label>
                                            <input type="text" name="hsf" id="hsf" value="" class="form-control" placeholder="HSN Code"  oninput="digitonly(event,4)">
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label for="gst">GST %</label>
                                            <input type="text" name="gst" id="gst" value="" class="form-control" placeholder="GST % Value" >
                                        </div>
                                        <div class="form-group col-md-12 ">
                                            <label for="desc">HSN Description</label>
                                            <input type="text" name="desc" id="desc" value="" class="form-control" placeholder="About HSN Code" >
                                        </div>
                                        <hr class="col-12 m-1 p-0">
                                        <div class="form-group col-12 text-center  p-1">
                                            <button type="submit" name="save" value="bank" class="btn btn-danger">Save</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-12 table-responsive p-1">
                                    <table class="table table-bordered table-stripped">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th></th>
                                                <th>HSN Code</th>
                                                <th>Detail</th>
                                                <th>GST %</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="billing-Info">
                                            @if(count($billing)>0)
                                                @foreach($billing as $b_i=>$bill)
                                                    <tr class="{{ ($bill->status==0)?'table-danger':'' }}">
                                                        <td>
                                                            <a class="switch anchor" href="{{ url("vendors/banking/hsnstatus/{$bill->id}") }}">
                                                                <span class="{{ $switch[$bill->status] }}"></span>
                                                            </a>
                                                        </td>
                                                        <td class="text-center"> {{ $bill->hsf }}</td>
                                                        <td class="text-center">{{ $bill->desc??'-----' }}</td>
                                                        <td class="text-center">{{ $bill->gst }} %</td>
                                                        <td class="text-center">
                                                            <a href="{{ url("vendors/banking/edithsn/{$bill->id}") }}" class="btn btn-outline-info edit_info  px-1 py-0 m-1" data-head="Change BILL HSN/GST"><li class="fa fa-edit"></li></a>
                                                            <a href="{{ url("vendors/banking/deletehsn/{$bill->id}") }}" class="btn btn-outline-danger delete_info  px-1 py-0 m-1" data-target="HSN/GST Info"><li class="fa fa-times"></li></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else 
                                            <tr><td colspan="5" class="text-center text-danger">No Info !</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
    <div class="modal fade" id="edit_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_head"></h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="edit_body">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

  @section('javascript')


    <script>
        const loader = '<p class="text-center" id="modal_loader"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></p>';
        $(document).ready(function(){
            $('form').trigger('reset');

            $(document).on('input','input',function(){
                $(this).removeClass('is-invalid');
            });
            $('.anchor').click(function(e){
                e.preventDefault();
                const span = $(this).find('span');
                const tr = $(this).closest('tr');
                $.get($(this).attr('href'),"",function(response){
                    if(response.status){
                        span.toggleClass('on');
                        tr.toggleClass('table-danger');
                    }
                });
            });
            
            // $('.switch').click(function(){
            //     const span = $(this).find('span');
            //     span.toggleClass('on');
            // });
            $('#bank_set,#bill_set').submit(function(e){
                e.preventDefault();
                var formdata = $(this).serialize();
                var action = $(this).attr('action');
                $.post(action,formdata,function(response){
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

            $('.edit_info').click(function(e){
                e.preventDefault();
                $("#edit_body").empty().append(loader);
                $("#modal_head").empty().text($(this).data('head'));
                $("#edit_model").modal('show');
                $("#edit_body").load($(this).attr('href'));
            });
            $('.delete_info').click(function(e){
                e.preventDefault();
                const tr = $(this).closest('tr');
                var q = confirm("Sure to Delete "+$(this).data('target'));
                var self = $(this);
                if(q==true){
                    $.get($(this).attr('href'),"",function(response){
                        if(response.status){
                            tr.addClass('disabled');
                            self.remove();
                        }
                    });
                }
            })
            $('#myModal').on('hidden.bs.modal', function () {
                $("#edit_body").empty();
                $("#modal_head").empty();
            });
            
        });
        function digitonly(event,num=false){
            let inputValue = event.target.value;

            // Allow only digits using regex
            inputValue = inputValue.replace(/[^0-9]/g, '');  // Remove anything that's not a digit

            // Ensure that the input has exactly 10 digits
            if(num){
                if (inputValue.length > num) {
                    inputValue = inputValue.slice(0, 10);  // Trim to 10 digits
                }
            }

            // Update the input field with the valid input
            event.target.value = inputValue;
        }

    </script>


@endsection
