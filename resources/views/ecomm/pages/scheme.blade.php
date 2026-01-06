@extends('ecomm.site')
@section('title', "Schemes")
@section('content')
@php 
//dd($schemes);
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section p-0 d-md-block d-none">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
        <!--<h1 class="font-weight-semi-bold text-uppercase mb-3">Our Scheme</h1>-->
		<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Our Scheme</h3>
        <div class="d-inline-flex">
            <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Scheme</p>
        </div>
    </div>
</div>



<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Our Scheme</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Scheme</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->

<div class="container offer pt-5">
    <div class="row px-xl-4">

      	@if($schemes->count()>0)
          @foreach ($schemes as $scheme )
            <div class=" col-md-4 pb-4">
              <div class="scheme-card">
                  <div class="scheme-content">
                      <h4>{{ $scheme->scheme_head }}</h4>
                      <h2>{{ $scheme->scheme_sub_head }}</h2>
                      
                  </div>                  
                  <div class="scheme-btn-group">
                     @php $scheme_url = url("{$ecommbaseurl}schemeenquiry/{$scheme->url_part}"); @endphp
                      <a href="javascript:void(null);" class="explore load_rule" data-target="#exampleModal" data-toggle="modal" onclick="$('#scheme_enquiry').empty().load('{{ $scheme_url }}')">Get Scheme Started</a>
                      <a href="{{ route('schemes',$scheme->url_part) }}" class="explore load_rule">Detail</a>

                      @if(!empty($scheme->scheme_rules))
                          @php 
                              $rule_url = url("{$ecommbaseurl}schemerule/{$scheme->url_part}"); 
                          @endphp
                          <a href="javascript:void(0);" 
                            class="explore load_rule" 
                            data-target="#rules_model" 
                            data-toggle="modal" 
                            onclick="$('#rule_block').empty().load('{{ $rule_url }}')">
                            Rule & Regulation
                          </a>
                      @endif
                  </div>



              </div>
          </div>
          @endforeach
        @else
          <div class="offset-md-4 alert col-md-4 alert-warning text-center">No Scheme Yet !</div>
        @endif
    </div>
</div>

{{--  
<!-- Offer Start -->
<div class="container offer pt-5">
    <div class="row px-xl-5">
	
	@if($schemes->count()>0)
        @foreach ($schemes as $scheme )

            <div class="col-md-6 pb-4">
                <div class="position-relative text-center text-md-right schemes-box ">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset($scheme->scheme_img) }}" alt="" class="img-responsive offer_new_img">
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative" style="z-index: 1;">
                                <h4 class="mb-4 font-weight-semi-bold">{{ $scheme->scheme_head }}</h4>
                                <h5 class="text-uppercase text-primary ">{{ $scheme->scheme_sub_head }}</h5>
                            </div>
                        </div>
						<div class="col-md-12">
							@php $scheme_url = url("{$ecommbaseurl}schemeenquiry/{$scheme->url_part}"); @endphp
                            <a href = "javascript:void(null);" class="btn btn-outline-info py-md-2 px-md-3" data-target="#exampleModal" data-toggle="modal" onclick="$('#scheme_enquiry').empty().load('{{ $scheme_url }}')"><b>Get Scheme Started</b></a>
                            <a href = "{{ route('schemes',$scheme->url_part) }}" class="btn btn-outline-primary py-md-2 px-md-3">Detail</a>
							@if(!empty($scheme->scheme_rules))
                            @php $rule_url = url("{$ecommbaseurl}schemerule/{$scheme->url_part}"); @endphp
                            <a href = "javascript:void(null);" class="btn btn-outline-success py-md-2 px-md-3 mt-1 load_rule" data-target="#rules_model" data-toggle="modal" data-href="" onclick="$('#rule_block').empty().load('{{ $rule_url }}')">Rule & Regultion</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
		@else 
            <div class="offset-md-4 alert col-md-4 alert-warning text-center">No Scheme Yet !</div>
        @endif
    </div>
</div>
--}}
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Scheme Enquiry </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="scheme_enquiry">
            
        </div>
        <!-- <div class="modal-footer">
         
        </div> -->
      </div>
    </div>
</div>
<div class="modal fade" id="rules_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rules_model_Head"> Rules & Regulationss </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="rule_block">
        </div>
        <!-- <div class="modal-footer">
         
        </div> -->
      </div>
    </div>
  </div>
<style>

    .form-control.required{
        border:1px dotted red;
    }

</style>

@endsection

@section('javascript')

<script>

 $("#exampleModal").on('show.bs.modal', function () {
    $(".modal-body").empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i> Loading Content..</span></p>') ;
  });
  $("#rules_model").on('show.bs.modal', function () {
    $("#rule_block").empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i> Loading Content..</span></p>');
  });

</script>

@endsection