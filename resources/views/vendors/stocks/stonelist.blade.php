@extends('layouts.vendors.app')

@section('css')

@include('layouts.theme.css.datatable')
<style>
    .bill_show{
        color:blue;
        border:1px solid lightgray;
        padding:1px 2px;
    }
    .bill_show:hover{
        color:black;            
    }
    td.grms{
        position:relative;
    }
    td.grms:before{
        /* position:absolute;
        content:'Grm';
        right:15px;
        color:gray; */
    }
    td.active.grms:before{
        color:blue;
    }
    input.false{
        font-weight:bold;
        color:blue;
    }
    .placer_label{
      position: relative;
    }
    .placer_label > .inline_check_input{
      position:absolute;
      right:0;
      top:0;
    }
    span.unit{
      position: absolute;
      right:0;
      bottom:0;
      font-weight:normal;
      top: -10%;
    }
    span.unit.active{
      color:blue;
      /*text-shadow: 1px 2px 3px blue;*/
    }
</style>
<style>
/* Glowing Blinking Button */
.placer_button {
  position: relative;
  animation: blink 1.5s infinite alternate;
  border-radius:10px;
}

/* Glowing effect */
@keyframes blink {
  0% {
    box-shadow: 0 0 1px rgb(209, 224, 234), 0 0 2px rgb(185, 205, 219), 0 0 3px rgb(209, 224, 234);
    /* background-color: #3498db; */
  }
  50% {
    box-shadow: 0 0 2px rgb(23, 172, 48), 0 0 4px rgb(22, 215, 64), 0 0 6px rgb(22, 168, 78);
    /* background-color: #f39c12; */
  }
  100% {
    box-shadow: 0 0 1px rgb(209, 224, 234), 0 0 2px rgb(185, 205, 219), 0 0 3px rgb(209, 224, 234);
    /* background-color: #3498db; */
  }
}
</style>
<style>
  label.placer_label>input[type="text"]{
    padding-right: 30%;
  }
  label.placer_label>input[type="text"].selected {
    border: 1px solid green;
    box-shadow: 1px 2px 3px gray;
    background-color: white;
    background: white !important;
  }
  .stock_check.inline_check_input{
    appearance: none;
    width: calc(1.50rem + 2px); 
    height: calc(1.50rem + 2px);
    border: 1px solid pink; 
    border-radius: 4px; 
    background-color: #fff; 
    cursor: pointer;
    transition: background-color 0.2s, border 0.2s; /* Smooth transition */
    margin: 4% 15% 4% 0;
  }
  .stock_check.inline_check_input:before{
    content: '\2714';
    margin:20%;
    color:#d3d3d378;
  }
  .stock_check.inline_check_input:checked{
    border:2px solid #4CAF50;
   /* background-color: #4CAF50; /* Change background color when checked */
    /*border-color: #4CAF50; /* Border color when checked */
  }
  .stock_check.inline_check_input:checked::before {
    transform: translate(-50%, -50%);
    color: #4CAF50; /* Color of the check mark */
    border:#4CAF50;
  }
</style>
<style>
#my-control{
  height:30px;
  border-radius:15px;
  overflow:hidden;
  box-shadow:1px 2px 3px gray;
}
#my-control .form-control{
  height:30px;
  padding:0 5px;
  text-align: center;
  font-size:90%;
}
#my-control.disabled{
  opacity:0.5;
  pointer-events: none; 
  cursor: not-allowed;
}
</style>
@endsection

@section('content')

@php 
$anchor = ['<a href="'.route('stocks.create').'"  class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> stock</a>','<a href="'.route('stocks.home').'" class="btn btn-sm btn-outline-info"><i class="fa fa-object-group"></i> Stocks</a>'];
$path = ["Stocks"=>route('stocks.home')];
$data = new_component_array('newbreadcrumb',"Stone Stock",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    <div class="card-body p-1">

    <form action="{{ route('stocks.place') }}" method="post" id="counter_place_form">
        @csrf
        <div class="row">
          <input type="hidden" name="stock" id="metal", value="{{ $metal }}">
            <div class="col-12 col-lg-2  form-group">
                <label for=""> Bill No. </label>
                <input type="text" id = "bill_no" class = "form-control" placeholder = "Search Bills" oninput="changeEntries()" >
            </div>
                
            <div class="col-md-3  form-group">
              <label for=""> Type </label>
              <select name="type" class="form-control" id="type" oninput="changeEntries()">
                <option value="">Select</option>
                @php $stones = stonecategory()  @endphp
                @if($stones->count() > 0 )
                  @foreach($stones as $si=>$stone)
                    <option value="{{ $stone->id }}" {{ (isset($type) && $type==$stone->id)?'selected':'' }}>{{ $stone->name }}</option>
                  @endforeach
                @else 
                <option value="">No Stone Types !</option>
                @endif 
              </select>
            </div>
            <div class="col-md-3  form-group">
              <label for=""> Search Stock </label>
              <input type="text" id = "stocks" class = "vin_no form-control" placeholder = "Search ...."  oninput="changeEntries()" >
            </div>
            <div class="col-md-2  form-group">
              <label for="">Show entries</label>
              @include('layouts.theme.datatable.entry')
            </div>
            <div class="col-md-2  form-group text-center">
                <button class="btn btn-sm btn-default placer_button" data-toggle="" data-target="" type="button" id="placer_button">Place to Counter</button>
				<div class="input-group mt-2 disabled" id="my-control">
                  <select name="tagprint" class="form-control">
                    <option value="">TAG ?</option>
                    <option value="qrcode">QRCODE</option>
                    <option value="barcode">BARCODE</option>
                  </select>
                  <div class="input-group-append">
                    <a href="{{ route('stocks.printtag') }}" class="form-control btn btn-sm btn-info m-0" name="print" value="tag" id="print_tag" style="align-content:center;"><b>Print</b></a>
                  </div>
                </div>
            </div>
          </div>
         
          <table id="CsTable" class="table table_theme table-striped table-bordered text-wrap">
            <thead>
                <tr> 
                  <th>S.N.</th>
                  <th>Bill</th>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Caret</th>
                  <th>Amount</th>
                  <th>To Place</th>
					@if(!in_array($metal,['loose','artificial']))
				  <th>TAG</th>
					@endif
                  <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="data_area">
            </tbody>
          </div>
        </table>
        <div class="col-12" id="paging_area">
        </div>

          <div class="modal" tabindex="-1" role="dialog" id="placement_model">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Counter Placement</h5>
                  <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="col-12 text-danger p-0">
                      <b><u>NOTE</u> : </b>
                      <small class="help-text">Enter New Name or Choose existing withh "&#x27A5;"</small>
                  </div>
                  <div class="form-group">
                    <label for="">Counter Name/Label</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <a href="{{ route('stock.counters') }}" class="btn btn-outline-secondary m-0 place_resource"  type="button" style="padding:0 5px;">
                              <span style="font-size:180%;">&#x27A5;</span>
                          </a>
                      </div>
                      <input type="text" class="form-control" placeholder="New Counter Name" id="counter" name="counter">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Box Name/Label</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <a href="{{ route('stock.boxes') }}" class="btn btn-outline-secondary m-0 place_resource" type="button" style="padding:0 5px;">
                              <span style="font-size:180%;">&#x27A5;</span>
                          </a>
                      </div>
                      <input type="text" class="form-control" placeholder="New Box Name" id="box" name="box">
                    </div>
                  </div>
                </div>
                <div class="modal-footer text-center">
                  <button type="submit" class="btn btn-secondary" >Place</button>
                </div>
              </div>
            </div>
          </div>


      </form>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>

<div class="modal" tabindex="-1" id="place_resource" style="background:#00000042">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header p-2 bg-light">
            <h5 class="modal-title">List</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><li class="fa fa-times"></li></button>
        </div>
        <div class="modal-body p-2" id="place_modal_body">
        </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" id="bill_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header p-2 bg-light">
        <h5 class="modal-title">Purchase Bill</h5>
        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><li class="fa fa-times"></li></button>
      </div>
      <div class="modal-body p-2" id="bill_modal_body">
        
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="tag_code_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius:15px;overflow:hidden;">
      <div class="modal-body p-0">
        
      </div>
    </div>
  </div>
</div>
@endsection
@section('javascript')

    <script>


      var route = "{{ route('stocks.index') }}";
      const loading_tr = '<tr><td colspan="8" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
      function getresult(url) {
            $("#loader").show();
            $("#data_area").html(loading_tr)
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "bill": $("#bill_no").val(),
                  "type":$("#type").val(),
                  "Stock_name": $("#stocks").val(),
                  "stock":$("#metal").val(),
              },
              success: function (data) {
                $("#loader").hide();
                $("#data_area").html(data.html);
                $("#paging_area").html(data.paging);
                //$("#pagination-result").html(data.html);
              },
              error: function (data) {
                $("#loader").hide();
              },
          });
      }

      getresult(url) ;

      $(document).on('click', '.pagination a', function (e) {

              e.preventDefault();
              var pageUrl = $(this).attr('href');
              getresult(pageUrl);

      });

      function changeEntries() {

      getresult(url) ;

      }


      $(document).on('click','.stock_check',function(){
        var self_ind = $('label>.stock_check').index($(this));
        var type_field = $('input[name="item_type[]"]').eq(self_ind);
        var qnt_field = $('input[name="quantity[]"]').eq(self_ind);
        var unit_span = $('span.unit').eq(self_ind);
        var read = ($(this).is(':checked'))?false:true;
        type_field.prop('disabled',read);
        qnt_field.prop('disabled',read);
        if(!read){
          $(this).closest('label').find('input[type="text"]').addClass('selected');
        }else{
          $(this).closest('label').find('input[type="text"]').removeClass('selected');
        }
        (!read)?unit_span.addClass('active'):unit_span.removeClass('active');
        if(type_field.val()!='genuine'){
          qnt_field.select();
        }
        var is_check = $('.stock_check:checked').length;
        if(is_check>0){
          if( $("#placer_button").data('toogle')=="" || $("#placer_button").data('target')==""){
            $("#placer_button").attr({
                'data-toggle': 'modal',
                'data-target': '#placement_model'
            });
            $("#placer_button").removeClass('btn-disabled').addClass('btn-success');
          }
        }else{
          $("#placer_button").removeAttr('data-toggle');
          $("#placer_button").removeAttr('data-target');
          $("#placer_button").removeClass('btn-success').addClass('btn-disabled');
        }
      });

      // $(document).on('click','.stock_check',function(){
      //   const td_num = "{{ ($metal =='artificial')?'8':'9'  }}";
      //   const tr = $(this).parent('label').parent('td').parent('tr');
      //   const ind = tr.index();
      //   //const td = tr.find('td').eq(7);
      //   const td = tr.find('td').eq(td_num);
      //   var read = ($(this).is(':checked'))?false:true;
      //   var type_field = td.find('input[name="item_type[]"]');
      //   var qnt_field = td.find('input[name="quantity[]"]');
      //   type_field.prop('disabled',read);
      //   qnt_field.prop('disabled',read);
      //   if(type_field.val()!='genuine'){
      //     qnt_field.select();
      //   }
      //   qnt_field.toggleClass('true false');
      //   (!read)?td.addClass('active'):td.removeClass('active');
      //   var is_check = $('.stock_check:checked').length;
      //   if(is_check>0){
      //     if( $("#placer_button").data('toogle')=="" || $("#placer_button").data('target')==""){
      //       $("#placer_button").attr({
      //           'data-toggle': 'modal',
      //           'data-target': '#placement_model'
      //       });
      //       $("#placer_button").removeClass('btn-disabled').addClass('btn-success');
      //     }
      //   }else{
      //     $("#placer_button").removeAttr('data-toggle');
      //     $("#placer_button").removeAttr('data-target');
      //     $("#placer_button").removeClass('btn-success').addClass('btn-disabled');
      //   }
      // });


      $('.place_resource').click(function(e){
            e.preventDefault();
            //$("#place_modal_body").load($(this).attr('href'));
            $("#place_modal_body").empty().load($(this).attr('href'),"",function(){
                //$("#place_resource").modal();
            });
            $("#place_resource").modal();
        });

        $(document).on('click','input.input_apply',function(){
          var ttrgt = $(this).data('target');
            $("#"+ttrgt).val($(this).val());
        });

        $("#counter_place_form").submit(function(e){
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(),function(response){
              if(response.valid){
                if(response.status){
                  $("#placer_button").removeAttr('data-toggle');
                  $("#placer_button").removeAttr('data-target');
                  $("#placer_button").removeClass('btn-success').addClass('btn-disabled');
                  $("#placement_model").modal('hide');
                  success_sweettoatr(response.msg);
                  location.reload();
                  // $(document).find('.stock_check').each(function(i,v){
                  //   if($(this).is(':checked')){
                  //     $(this).remove();
                  //   }
                  // });
                }else{
                  toastr.error(response.msg) ;
                }
              }else{
                $.each(response.errors, function(field, messages) {
                  $('[name="' + field + '"]').addClass('is-invalid') ;
                  toastr.error(messages) ;
                });
              }
            });
        });
        
        $(document).on('click','.bill_show',function(e){
            e.preventDefault();
            $("#bill_modal_body").empty().load($(this).attr('href'));
            $("#bill_modal").modal();
        });
		
		
		$(document).on('change','.tag_check',function(){
			let checkedCount = $('.tag_check:checked').length;
			if(checkedCount > 0){
			  $("#my-control").removeClass('disabled');
			}else{
			  $('[name="tagprint"]').val("");
			  $("#my-control").addClass('disabled');
			}
		});

		$("#print_tag").click(function(e){
			e.preventDefault();
			if($('[name="tagprint"]').val()!=""){
			  var path = $(this).attr('href');
			  var data = $('[name="tag[]"]').serialize();
			  var stock = $('[name="stock"]').val();
			  var code = $('[name="tagprint"]').serialize();
			  var type = $('[name="type"]').serialize();
			  //$('#tag_code_modal').modal();
			  $("#tag_code_modal").find('.modal-body').load(path,data+"&stock="+stock+"&"+type+"&"+code,function(){
				$('#tag_code_modal').modal();
			  });
			}else{
			  toastr.error("Please select the Tag Typw ?");
			  $("[name='tagprint']").focus();
			}
		});
    
    </script>

@include('layouts.theme.js.datatable')
@include('layouts.vendors.js.passwork-popup')

@endsection

