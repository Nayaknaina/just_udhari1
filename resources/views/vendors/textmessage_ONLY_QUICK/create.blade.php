  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Text Message APi',[['title' => 'Text Message APi']] ) ;
   
  @endphp

  <x-page-component :data=$data />
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                        <h3 class="card-title"><x-back-button />  New {{ ucfirst($new) }}</h3>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                    <form id = "submitForm" method="POST" action="{{ route('textmsgeapi.store')}}" class = "myForm" enctype="" autocomplete="off">
                        @csrf
                        @method('post')
                       @switch($new)
                            @case("url")
                                <div class="col-6 m-auto" style="border-bottom:1px solid lightgray;">
                                    <div class="form-group">
                                        <label for="url">
                                            API Url
                                        </label>
                                        <input type="text" class="form-control" name="url" placeholder="http://www.xyz.ex">
                                    </div>
                                    <div class="form-group">
                                        <label for="key">
                                            API Key
                                        </label>
                                        <textarea  class="form-control" name="key" placeholder="Alpha numeric Value ( Received From DLT Pltform )"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="url">
                                            Route
                                        </label>
                                        <select name="route" class="form-control" >
                                            <option value="q">Q (Quick Send)</option>
                                            <option value="dlt">DLT (DLT Route)</option>
                                        </select>
                                    </div>
                                </div>
                                @break
                            @case("tamplate")
                                <div class="col-6 m-auto" style="border-bottom:1px solid lightgray;">
                                    <div class="form-group">
                                        <label for="url">HEAD</label>
                                        <input type="text" class="form-control" name="head" placeholder="The Header You Get From DLT Portal">
                                    </div>
                                    <div class="form-group">
                                        <label for="url">BODY</label>
                                        <textarea  class="form-control" rows="3" name="body" placeholder="The Message You Created at DLT Portal"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="url">VARIABLES <small class="text-danger">Content that will replace in Message</small></label>
                                        <input type="text" class="form-control" name="variable" placeholder="NAME|MOBILE|XYZ|......">
                                        <small class="text-info">Must be in Sequence & Separated By '|'</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="url">DETAIL</label>
                                        <input type="text" class="form-control" name="detail" placeholder="Purpose of the Message">
                                    </div>
                                </div>
                                @break
                            @default
                                <div class="col-12 text-center text-danger">Invalid Action !</div>
                                @break
                        @endswitch
                        <div class="col-12 form-group text-center pt-2">
                            <input type="hidden" name="store" value="{{ strtolower($new) }}">
                            <button type="submit" name="save" value="data" class="btn btn-danger">Save ?</button>
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

