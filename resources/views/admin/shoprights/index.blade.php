@extends('layouts.admin.app')

@section('content')

    @php

    $data = component_array('breadcrumb' , 'Shop Rights List' ,['title' => 'Shop Rights'] ) ; 

    @endphp

    <x-page-component :data=$data />

  <section class="content">
    <div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
    <div class="card ">
    <div class="card-body">
    <form method="POST" action="">
    @csrf
    @method('post')

    @php
        // exit();
    @endphp

    <div class="row justify-content-center">
  
    <div class="col-lg-12">
      <div class="form-group">

       <h3 class = "text-bold text-center "> {{ $product->title }} </h3> 
       <hr>

      </div>
    </div>

    <div class="col-lg-12 form-group">
    <div class = "row align-items-center">
  
     <div class="col-lg-12">
        <div class="card p-3 bg-info text-bold ">
        <div class="row">
        <div class="col-lg-1"> # </div>
        <div class="col-lg-3"> Permission Head </div>
        <div class="col-lg-8"> Permission </div>
        </div>
        </div>
      </div>

    @foreach($permissions as $permission)

      <div class = "card-body">
          <div class = "row">
              <div class="col-lg-1"> <label> <input type = "checkbox" class = "form-checkbox main-head " id = "main{{ $permission->id }}" >  All </label> </div>
              <div class="col-lg-3">

                  <label class="inline-flex items-center mt-3">
                  <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" @if(in_array($permission->id, $shoprights)) checked @endif  value = "{{$permission->id}}" >
                  <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                  </label>

              </div>
  
              <div class="col-lg-8">

              @if($permission->children)

              <div class="row">

                @foreach ($permission->children as $child)

                    <div class="col-lg-3">
                    <div class="card p-2 m-2 text-center">
                    <label class="inline -flex items-center mt-3">
                    <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" @if(in_array($child->id, $shoprights)) checked @endif value = "{{$child->id}}"> <span class="ml-2 text-gray-500">{{ $child->name }}</span>
                    </label>
                    </div>
                    </div>

                @endforeach
    
              </div>
  
              @endif
  
              </div>
          </div>
    </div>
  
    <div class="col-lg-12 m-2" style="border: 1px dotted rgb(0,0,0,.3)"></div>
  
    @endforeach

    </div>
    </div>
  
    <div class="col-lg-4">
    <button type="submit" class="btn btn-success">Update</button>

    <input type = "hidden" name = "shop_id" value="{{ $shop_id }}" >
    <input type = "hidden" name = "product_id" value="{{ $product->id }}" >

    </div>
  
    </div>
  
    </form>
  
    </div>
    </div>
  
    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>
  
    @endsection

    @section('javascript')

      <script>

          document.addEventListener('DOMContentLoaded', function() {
              const mainHeads = document.querySelectorAll('.main-head') ;
              const subHeads = document.querySelectorAll('.sub-head') ;
  
                  mainHeads.forEach(mainHead => {
                  mainHead.addEventListener('change', function() {
                      const subHeadCheckboxes = document.querySelectorAll(`.sub-head[data-main="${mainHead.id}"]`);
                      subHeadCheckboxes.forEach(subHead => {
                          subHead.checked = mainHead.checked;
                      });
                  });
              });

              function updateMainHeads() {
                  subHeads.forEach(subHead => {
                      const mainHeadId = subHead.getAttribute('data-main');
                      const mainHead = document.getElementById(mainHeadId);
                      const relatedSubHeads = document.querySelectorAll(`.sub-head[data-main="${mainHeadId}"]`);
                      mainHead.checked = Array.from(relatedSubHeads).every(sub => sub.checked);
                  });
              }

              subHeads.forEach(subHead => {
                  subHead.addEventListener('change', function() {
  
                      const mainHeadId = subHead.getAttribute('data-main');
                      const mainHead = document.getElementById(mainHeadId);
                      const relatedSubHeads = document.querySelectorAll(`.sub-head[data-main="${mainHeadId}"]`);
                      mainHead.checked = Array.from(relatedSubHeads).every(sub => sub.checked) ;
  
                  });
              });
  
              updateMainHeads() ;
  
          });
  
      </script>
  
    @endsection
  