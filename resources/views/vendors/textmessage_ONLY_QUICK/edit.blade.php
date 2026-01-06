@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Text Message APi',[['title' => 'Text Message APi']] ) ;
 
@endphp
<style>
    a.socket{
        height:20px;
        width:40px;
        border:1px solid lightgray;
        float:right;
        margin:5px 0;
        position:relative;
    }
    span.switch:after{
        position:absolute;
        width:50%;
        height:100%;
        font-size:10px;
        text-align:center;
    }
    span.switch.on:after{
        border:1px solid green;
        background:lightgreen;
        color:green;
        content:'On';
        right:0;
    }
    span.switch.off:after{
        border:1px solid red;
        background:pink;
        color:red;
        content:'Off';
        left:0;
    }
</style>
<x-page-component :data=$data />
  <section class = "content">
      <div class = "container-fluid">
          <div class = "row justify-content-center">
              <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card card-primary">
                      <div class="card-header">
                      <h3 class="card-title"><x-back-button />  Change {{ ucfirst($edit) }}</h3>
                      </div>
                  </div>
                  <div class="card-body bg-white">
                  <form id = "submitForm" method="POST" action="{{ route('textmsgeapi.update',$edit_data->id)}}" class = "myForm" autocomplete="off">
                      @csrf
                      @method('put')
                     @switch($edit)
                          @case("url")
                              <div class="col-6 m-auto" style="border-bottom:1px solid lightgray;">
                                @php 
                                    $status_arr = ['off','on'];
                                @endphp
                                  <div class="form-group">
                                      <label for="url" class="col-12">
                                        API Url
                                        </label>
                                      <input type="text" class="form-control" name="url" placeholder="http://www.xyz.ex" value="{{ $edit_data->url }}">
                                  </div>
                                  <div class="form-group">
                                        <label for="key">
                                            API Key
                                        </label>
                                        <textarea type="text" class="form-control" name="key" placeholder="Alpha numeric Value ( Received From DLT Pltform )" >{{ $edit_data->api_key }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="url">
                                            Route
                                        </label>
                                        @php 
                                            $route = $edit_data->route;
                                            $$route = 'selected';
                                        @endphp 
                                        <select name="route" class="form-control" >
                                            <option value="q" {{ @$q }} >Q (Quick Send)</option>
                                            <option value="dlt" {{ @$dlt }} >DLT (DLT Route)</option>
                                        </select>
                                    </div>
                              </div>
                              @break
                          @case("tamplate")
                              <div class="col-6 m-auto" style="border-bottom:1px solid lightgray;">
                                  <div class="form-group">
                                      <label for="url">HEAD</label>
                                      <input type="text" class="form-control" name="head" placeholder="The Header You Get From DLT Portal" value="{{ $edit_data->head }}">
                                  </div>
                                  <div class="form-group">
                                      <label for="url">BODY</label>
                                      <textarea  class="form-control" rows="3" name="body" placeholder="The Message You Created at DLT Portal">{{ $edit_data->body }}</textarea>
                                  </div>
                                  <div class="form-group">
                                      <label for="url">VARIABLES <small class="text-danger">Content that will replace in Message</small></label>
                                      @php 
                                        $variables = implode("|",json_decode($edit_data->variables,true))
                                      @endphp
                                      <input type="text" class="form-control" name="variable" placeholder="NAME|MOBILE|XYZ|......" value="{{ $variables }}">
                                      <small class="text-info">Must be in Sequence & Separated By '|'</small>
                                  </div>
                                  <div class="form-group">
                                      <label for="url">DETAIL</label>
                                      <input type="text" class="form-control" name="detail" placeholder="Purpose of the Message" value="{{ $edit_data->detail }}">
                                  </div>
                              </div>
                              @break
                          @default
                              <div class="col-12 text-center text-danger">Invalid Action !</div>
                              @break
                      @endswitch
                      <div class="col-12 form-group text-center pt-2">
                          <input type="hidden" name="update" value="{{ strtolower($edit) }}">
                          <button type="submit" name="save" value="data" class="btn btn-danger">Change ?</button>
                      </div>
                  </form>
                  </div>
              </div>
          </div>
      </div><!-- /.container-fluid -->
      
  </section>
  
@endsection

@section('javascript')

<script>

    $(document).ready(function() {
        $("#submitForm").trigger('reset');
      
        $('#submitForm').submit(function(e) {
            $(document).find("#toast-container").remove();
            $("input").removeClass('is-invalid');
            e.preventDefault(); // Prevent default form submission
            var formAction = $(this).attr('action') ;
            var formData = $(this).serialize() ;
            $.post(formAction,formData,function(response){
                if(!response.valid){
                    if(response.errors){
                        var num = 0;
                        var focus = "";
                        $.each(response.errors,function(i,v){
                            if(num == 0){
                                focus = $('[name="'+i+'"]');
                            }
                            $('[name="'+i+'"]').addClass("is-invalid");
                            toastr.error(v[0]);
                            num++;
                            focus.focus();
                        });                    
                    }else{
                        toastr.error(response.msg);
                    }
                }else{
                    if(response.status){
                        success_sweettoatr(response.msg);
                        window.location.href = "{{ route('textmsgeapi.index') }}";
                    }else{
                        toastr.error(response.msg);
                    }
                }
            });
        });
    });

  </script>

@endsection

