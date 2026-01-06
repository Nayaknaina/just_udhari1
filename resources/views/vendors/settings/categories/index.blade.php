@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        //$data = component_array('breadcrumb' , ''.category_label($id).' List',[['title' => 'Categories']] ) ;
		$path_arr = ["E-Comm"=>"","Not Listed Product"=>"","Edit"=>""];
        $title = (category_label($id) != 'Category')?category_label($id)." Category":category_label($id);
        $data = new_component_array('newbreadcrumb' , "Product ".$title) ;
    @endphp
	<x-new-bread-crumb :data=$data />
    {{--<x-page-component :data=$data />--}}

    <section class="content">
    <div class="container-fluid">
    <div class="row justify-content-center ">

    <div class="col-md-6">
    <div class="card ">
    <div class="card-body p-2">

    <form action="">

    <div class="row">

		<div class="col-12 col-lg-8  form-group">
			<label for=""> Search Name </label>
			<input type="text" class = "name form-control" placeholder = "Search Name"  oninput="changeEntries()">
		</div>
		<div class="col-12 col-lg-4 form-group">
			<label for="">Show entries</label>
			@include('layouts.theme.datatable.entry')
		</div>
    </div>

    </form>

    <div id = "pagination-result"></div>

    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>

@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>

    function getresult(url) {

        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "name": $(".name").val(),
            },
            success: function (data) {
                $("#pagination-result").html(data.html);
            },
            error: function () {},
        });
    }

    getresult(url) ;

    $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href') ;
            getresult(pageUrl);

        });

    function changeEntries() {

        getresult(url) ;

    }

  </script>



  @endsection
