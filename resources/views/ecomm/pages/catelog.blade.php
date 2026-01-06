@extends('ecomm.site')
@section('title', "Myu Shop")
@section('stylesheet')
<style>
  div.category_block {
    transition: all 1s;
  }

  div.categorey_block.collapse_block {
    height: 200px;
    overflow: hidden;
  }

  div.categorey_block.expande_block {
    height: auto;
  }
  #product_load{
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    height:100%;
    background:#04040470;
    /* padding-top:100px; */
    z-index:9999;
  }
  #product_load > .content{
    margin:auto;
    width:max-content;
    color:orange;
    text-align:center;
    font-size:3rem;
    /* padding:45vh 0; */
    /* border:1px solid black; */
    /* background:white; */
}
#shop_social_share{
  font-size:150%;
}
.product_blur{
  opacity:0.3;
  position:relative;
} 
.product_blur::after{
  content:"";
  position:absolute;
  top:0;
  left:0;
  text-align:center;
  color:red;
  width:100%;
  height:100%;
  background: #ffffff59;
  filter:blur(10px);
}

</style>
@endsection
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section p-0 d-md-block d-none">
  <div class="d-flex flex-column align-items-center justify-content-center px-2 py-2" >
    <!--<h1 class="font-weight-semi-bold mb-3">Our Catalogue</h1>-->
	<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Our Catalogue</h3>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Our Catalogue</p>
    </div>
  </div>
</div>

<div class="container-fluid  bg-secondary p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Catalogue</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Catelogue</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->


<!-- Shop Start -->
<div class="container-fluid pt-1 ">
  <div class="row px-xl-5">
    <button class="btn btn-primary w-100 text-left text-white mb-1 filter_toggle_btn d-block d-lg-none d-md-none" onclick="$($(this).attr('data-target')).toggleClass('d-none');" data-target="#filter_side_bar">Filter<i class="fa fa-filter float-right"></i></button>
    <!-- Shop Sidebar Start -->
    @if(count($matter_menu) > 0 || count($collection) > 0 || count($main_menu) > 0)
    <div class="col-lg-3 col-md-12  filter_side_bar d-lg-block d-md-block d-none" id="filter_side_bar">
      <form id="filter_form" action="" method="post">
      @if(count($matter_menu) > 0)
      <!-- Color Start -->
      <div class="border-bottom mb-2 pb-2">
        <h5 class="font-weight-semi-bold mb-4">Material</h5>
          @foreach($matter_menu as $mk=>$matter)
          <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
            <input type="checkbox" name='data[]' class="matter-all custom-control-input" id="{{ $matter->slug }}" value="{{ $matter->slug }}" {{ @${"$matter->slug"} }} >
            <label class="custom-control-label matter_label" for="{{ $matter->slug }}">{{ $matter->name }}</label>
            <span class="badge border font-weight-normal"></span>
          </div>
          @endforeach
      </div>
      @endif
      <!-- Color End -->
      <!-- Price Start -->
      @if(count($collection) > 0)
      <div class="border-bottom mb-2 pb-2">
        <div class="collection_block categorey_block collapse_block">
          <h5 class="font-weight-semi-bold mb-4">Collection</h5>
            <!-- <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
              <input type="checkbox" class="custom-control-input" id="collection-all">
              <label class="custom-control-label collection_label" for="collection-all" id="collection_all">All </label>
              <span class="badge border font-weight-normal"></span>
            </div> -->
            @foreach($collection as $ckey=>$coll)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
              <input type="checkbox" name="data[]" class="collection-all custom-control-input" id="{{ $coll->slug }}" value="{{ $coll->slug }}" {{ @${"$coll->slug"} }} >
              <label class="custom-control-label collection_label" for="{{ $coll->slug }}">{{ $coll->name }}</label>
              <span class="badge border font-weight-normal"></span>
            </div>
            @endforeach
        </div>
        <button class="col-12 btn btn-default cat_block_toggle" id="collection_block">
        <strong>More Collection</strong>
          <i class="fa fa-angle-down"></i>
        </button>
      </div>
      <!-- Price End -->
      @endif


      <!-- Size Start -->
      @if(count($collection) > 0)
      <div class="mb-2">
        <div class="jewellery_block categorey_block collapse_block">
          <h5 class="font-weight-semi-bold mb-4">Jewellery</h5>
            <!-- <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
              <input type="checkbox" class="custom-control-input" id="category-all">
              <label class="custom-control-label category_label" for="category-all" id="category_all">All</label>
              <span class="badge border font-weight-normal"></span>
            </div> -->
            @foreach($main_menu as $mkey=>$menu)
            <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
              <input type="checkbox" name="data[]" class="category-all custom-control-input" id="{{ $menu->slug }}" value="{{ $menu->slug }}"  {{ @${"$menu->slug"} }} >
              <label class="custom-control-label category_label" for="{{ $menu->slug }}">{{ $menu->name }}</label>
              <span class="badge border font-weight-normal"></span>
            </div>
            @endforeach
        </div>
        <button class="col-12 btn btn-default cat_block_toggle" id="jewellery_block">
          <strong>More Jewellery</strong>
          <i class="fa fa-angle-down"></i>
        </button>
      </div>
      @endif
      <!-- Size End -->
    </form>
    </div>
    <!-- Shop Sidebar End -->
    @endif

    <!-- Shop Product Start -->
     
    <div class="col-lg-9 col-md-12 shop_product_block">
      @include("alert")
      <div class="col-12  px-0 py-2">
        <form action="" id="sortlist_form">
          <div class="d-flex align-items-center justify-content-between">
            <div class="input-group shop_keyword_filter">
              <input type="text" class="form-control" placeholder="Search by name" name="term" value="" id="term">
              <div class="input-group-append ">
                  <span class="input-group-text bg-inherit text-primary">
                  <i class="fa fa-search"></i>
                  </span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <hr class="w-100 mt-0  d-block bg-white">
      <div class="col-12 p-0">
        <div class="row" id="shop_data">
        
        </div>
        <div id="product_load" style="display:none;">
          <div class="content">
              <li class="fa fa-spinner fa-spin"></li>
          </div>
        </div>
      </div>
    </div>
    <!-- Shop Product End -->
  </div>
</div>



<!-- Shop End -->

<div class="modal fade" id="gallery_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header p-1">
        <h6 class="modal-title fs-5" id="catelog-head"></h6>
        <button type="button" class="btn btn-close btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close" onclick="$('#gallery_modal').modal('hide');">&cross;</button>
      </div>
      <div class="modal-body" id="gallery_area">
        
      </div>
    </div>
  </div>
</div>

@endsection
@section('javascript')
<script>
  $(document).ready(function() {
	  var list_request = false;
    $(".cat_block_toggle").click(function(e) {
      e.preventDefault();
      const trgt_blk = $(this).attr('id');
      //$('.categorey_block').removeClass("expande_block").addClass('collapse_block');
      $("." + trgt_blk).toggleClass('collapse_block expande_block');
      //$(".cat_block_toggle").find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
      $(this).find('i').toggleClass('fa-angle-up fa-angle-down');
      var text = $(this).find('strong').text();
      var now_text = text.replace('More','Less')?text.replace('More','Less'):text.replace('Less','More');
      $(this).find('strong').text(now_text);
    });

    $('#matter-all,#collection-all,#category-all').click(function() {
      var block = $(this).attr('id');
      var checked = $(this).prop('checked');
      if(checked==true){
        $('.'+block).prop('checked',true);
      }else{
        $('.'+block).prop('checked',false);
      }
    });
    $(".custom-control-input").click(function(){
      var form_data = $("#filter_form").serializeArray();
      var filter_data = [];
      $(form_data).each(function(i,v){
        filter_data[i] = v.value;
      });
      var data = JSON.stringify(filter_data);
      pagedata(data);
    });
    
    
    $(document).on('keyup change',"input[name='term'],select[name='price']",function(){
      $("#sortlist_form").submit();
    });
    
    $("#sortlist_form").submit(function(e){
      e.preventDefault();
      var form_data = $("#filter_form").serializeArray();
      var filter_data = [];
      $(form_data).each(function(i,v){
        filter_data[i] = v.value;
      });
      var data = JSON.stringify(filter_data);
      var sort_term = [$("input[name='term']").val(),$("select[name='price']").val()];
      var search = JSON.stringify(sort_term);
      pagedata(data,search);
    });
    function pagedata(data="",search="") {
      $("#product_load").show();
      $.get("{{ url("{$ecommbaseurl}cateloge") }}", "data=" + data+"&term="+search,function(response) {
        $("#shop_data").empty().append(response.html);
        $("#product_load").hide()  
      });
    }
    
    var data = [];
      

    //--IF THE SEARCH TERM COMES FROM TOP SEARCh BAR ----//
    @if(isset($_POST['search_term']))
      @if($_POST['search_term']!="")
        $("#term").val("{{$_POST['search_term']}}");
        var sort_term = [$("input[name='term']").val(),$("select[name='price']").val()];
        var search = JSON.stringify(sort_term);
        pagedata(data,search);
      @else
        //--Default Product Load--//
        pagedata(data);
      @endif
    @else
    //--Default Product Load--//
      pagedata(data);
    @endif
    //--END IF THE SEARCH TERM COMES FROM TOP SEARCh BAR ----//
    
    $(document).on('click','.page-link',function(e){
      e.preventDefault();
      var url = $(this).attr('href');
      $.get(url, "data=" + data,function(response) {
          $("#shop_data").empty().append(response.html);
      });
    })
	
	$(document).on('click','.view_gallery',function(e){
      e.preventDefault();
      $("#gallery_area").empty().load($(this).attr('href'));
      $("#catelog-head").empty().text($(this).data('head'));
      $("#gallery_modal").modal();
    });
	
	$(document).on('click','a.addtowishlist',function(e){
        e.preventDefault();
        var self = $(this);
        if(!list_request){
            $("#page_await").show();
            const url = $(this).attr('href');
            const quant = $("#quant").val()??1;
            $.get(url,"quant="+quant,function(response){
              $("#page_await").hide();
              if(response.status){
                $(self).addClass('active');
                const data_arr = (response.data).split("#");
                const type_arr = ["wishlist","kart"];
                $("#"+(type_arr[data_arr[0]])+"_count").text(data_arr[1]);
              }
              alert(response.msg);
            });
          }else{
            alert("Request already Sent, \nPlease wait for Response...!")
          }
      });
	
  });
</script>
@endsection