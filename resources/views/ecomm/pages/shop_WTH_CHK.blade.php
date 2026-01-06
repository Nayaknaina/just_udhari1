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
<style>
    .product_detail_layer{
        position:absolute;
        background:#00000080;
        top:0;
        bottom:0;
        left:0;
        right:0;
        display: none;
        justify-content: center;
        align-items: center;
        /*background: rgba(0, 0, 0, 0.5); /* Optional background for contrast */
    }
    .product_detail_layer.active{
        display:flex;
    }
</style>
@endsection
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-1">
  <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
    <h1 class="font-weight-semi-bold text-uppercase mb-3">Our Shop</h1>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Shop</p>
    </div>
  </div>
</div>
<!-- Page Header End -->

@php 
$$item = 'checked';
@endphp
<!-- Shop Start -->
<div class="container-fluid pt-1 mt-5">
  <div class="row px-xl-5">
    <button class="btn btn-primary w-100 text-left text-white mb-1 filter_toggle_btn d-block d-lg-none d-md-none" onclick="$($(this).attr('data-target')).toggleClass('d-none');" data-target="#filter_side_bar">Filter<i class="fa fa-filter float-right"></i></button>
    <!-- Shop Sidebar Start -->
    @if(count($matter_menu) > 0 || count($collection) > 0 || count($main_menu) > 0)
    <div class="col-lg-3 col-md-12  filter_side_bar d-lg-block d-md-block d-none" id="filter_side_bar">
      <form id="filter_form" action="" method="post">
      @if(count($matter_menu) > 0)
      <!-- Color Start -->
      <div class="border-bottom mb-2 pb-2">
        <h5 class="font-weight-semi-bold mb-4">Matterial</h5>
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
      <div class="col-12 pb-3 pt-2">
        <form action="" id="sortlist_form">
          <div class="d-flex align-items-center justify-content-between">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search by name" name="term" value="" id="term">
              <div class="input-group-append ">
                  <span class="input-group-text bg-inherit text-primary">
                  <i class="fa fa-search"></i>
                  </span>
              </div>
            </div>
            <div class="dropdown ml-4">
              <select name="price" class="form-control " id="triggerId" >
                <option value="">Sort Price</option>
                <option value="ASC">Low-High</option>
                <option value="DESC">High-Low</option>
              </select>
              <!-- <button class="btn border dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Sort Price </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                <a class="dropdown-item" href="#">Latest</a>--
                <a class="dropdown-item" href="">Low-High</a>
                <a class="dropdown-item" href="#">Hight-Low</a>
              </div> -->
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
@endsection
@section('javascript')
<script>
  $(document).ready(function() {
    $(".cat_block_toggle").click(function(e) {
      e.preventDefault();
      const trgt_blk = $(this).attr('id');
      //$('.categorey_block').removeClass("expande_block").addClass('collapse_block');
      $("." + trgt_blk).toggleClass('collapse_block expande_block');
      //$(".cat_block_toggle").find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
      $(this).find('i').toggleClass('fa-angle-up fa-angle-down');
      var text = $(this).find('strong').text();
      var now_text = text.replace('More','Less')?text.replace('More','Less'):text.replace('Less','More');
      //alert(now_text);
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
    // function submit_search(){
    //   $("#sortlist_form").submit();
    // }
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
      $.get("{{ url("{$ecommbaseurl}shopdata") }}", "data=" + data+"&term="+search,function(response) {
        $("#shop_data").empty().append(response.html);
        $("#product_load").hide()  
      });
    }
    @if($item) 
      var arrayData = ["{{ $item }}"];
      var data = JSON.stringify(arrayData);
    @else
      var data = [];
    @endif 

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
	  $("#product_load").show();
      var url = $(this).attr('href');
      $.get(url, "data=" + data,function(response) {
		  $("#product_load").hide();
          $("#shop_data").empty().append(response.html);
      });
    })
  });
</script>
<script>
    $(document).ready(function(){
		var list_request = false;
		$(document).on("click","a.addtokart",function(e){
			e.preventDefault();
			if(!list_request){
				list_request = true;
				$("#product_load").show();
				const url = $(this).attr('href');
				$.get(url,"",function(response){
				$("#product_load").hide();
				list_request = false;
				alert(response.msg);
				if(response.status){
					const data_arr = (response.data).split("#");
					const type_arr = ["wishlist","kart"];
					$("#"+(type_arr[data_arr[0]])+"_count").text(data_arr[1]);
				}
				});
			}else{
				alert("Request already Sent, \nPlease wait for Response...!")
			}
		});
    });
    $(document).find('.product-img').mouseenter(function(){
        $(this).find('span.product_detail_layer').addClass('active');
    });
    $(document).find('.product-img').mouseleave(function(){
        $(this).find('span.product_detail_layer').removeClass('active');
    });
</script>
@endsection