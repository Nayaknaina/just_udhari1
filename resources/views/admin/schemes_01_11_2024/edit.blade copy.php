@extends('layouts.admin.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>New Scheme</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Scheme</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title"> <x-back-button /> Create </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('schemes.update',$scheme->id)}}" class = "myForm" enctype="multipart/form-data">

              @csrf

              @method('put')

              <div class="row">

                  <div class="col-lg-12">
                      <label for="heading"> Heading <span class = "text-danger"> * </span> </label>
                      <input type="text" name="heading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:30px;" id="heading" value="{{ $scheme->scheme_head }}">
                  </div>
                  <div class="col-lg-12">
                      <label for="subheading"> Sub Heading <span class = "text-danger"> * </span> </label>
                      <input type="text" name="subheading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:20px;" id="subheading" value="{{ $scheme->scheme_sub_head }}">
                  </div>
                  <div class="col-lg-3 form-group ">
                      <label for="valid"> Validity <span class = "text-danger"> * </span></label>
                      <div class="input-group">
                          <input type="text" name="valid" id="valid" class="form-control form-group text-center"  placeholder="Number Only" value="{{ $scheme->scheme_validity }}">
                          <span class="input-addon">
                              <select name="valid_scale" class="form-control">
                                @php 
                                    $valid_scale_select = $scheme->scheme_validity_scale??'m';
                                    $$valid_scale_select = 'selected';
                                @endphp
                                  <option value="d" {{ @$d }}>Day</option>
                                  <option value="m" {{ @$m }} >Month</option>
                                  <option value="y" {{ @$y }} >Year</option>
                              </select>
                          </span>
                      </div>
                  </div>
                  <div class="col-lg-3 form-group">
                      <label for="emi">EMI <span class = "text-danger"> * </span></label>
                      <input type="text" name="emi" id="emi" class="form-control form-group text-center"  placeholder="Amount" value="{{ $scheme->scheme_emi }}">
                  </div>
                  <div class="col-lg-3 form-groups">
                      <label for="interest">Interest <span class = "text-danger"> * </span><small class="text-primary">( On EMI Pay )</small></label>
                      @php 
                            $checked_arr = ["no","yes"];
                            $checked_val = $checked_arr[$scheme->scheme_interest]??'no';
                            $$checked_val = 'checked';
                      @endphp
                      <div class="form-inline">
                          <label for="interest_no" class="form-control col-6 text-center"><input type="radio" name="interest" value="0" id="interest_no" {{ @$no }} onClick="if($(this).is(':checked')){ $('#interest_block').hide(); }"> NO</label>
                          <label for="interest_yes"  class="form-control col-6 text-center"><input type="radio" name="interest" value="1" id="interest_yes" {{ @$yes }} onClick="if($(this).is(':checked')){ $('#interest_block').show(); }"> Yes</label>
                      </div>
                  </div>
                  <div class="col-lg-3 form-group" style="display:{{ ($checked_val=='no')?'none':'' }};" id="interest_block">
                      <label for=""> Interest Value <span class = "text-danger"> * </span></label>
                      <div class="input-group">
                          <input type="text" name="interest_value" class="form-control form-group text-center"  placeholder="Number Only" value="{{ $scheme->scheme_interest_value }}">
                          @php 
                            $intrst_select = $scheme->scheme_interest_scale??'amnt';
                            $$intrst_select = 'selected';
                          @endphp
                          <span class="input-addon">
                              <select name="interest_scale" class="form-control">
                                  <option value="amnt" {{ @$amnt }} >Rs.</option>
                                  <option value="perc" {{ @$perc }} >%</option>
                              </select>
                          </span>
                      </div>
                  </div>
                  <!-- <div class="col-lg-6">
                      <label for=""> Thumbnail Image </label>
                      <input type="file" name="thumbnail_image" class="form-control form-group"  >
                  </div>

                  <div class="col-lg-6">
                      <label for=""> Banner Image </label>
                      <input type="file" name="banner_image1" class="form-control form-group"  >
                  </div> -->

                  <div class="col-lg-12 form-group">
                      <label for="detail">Detail One <span class = "text-danger"> * </span> </label>
                      <textarea name="detail_one" id="detail_one" class="form-control  ckeditor" placeholder="Enter description">{{ $scheme->scheme_detail_one }}</textarea>
                  </div>
                  <style>
                      .cke_notification_warning{
                          display:none;
                      }
                      th>input{
                          font-weight:bold!important;
                      }
                      th{
                          position:relative;
                      }
                  </style>
                  <div class="col-12 form-group">
                      <label for="detail">Table One Detail </label>
                      <div class="table-responsive">
                          <table class="table table-bordered">
                        @if($scheme->scheme_table_one!="")
                                @php 
                                    $col_arr = ['thead'=>'th','tbody'=>'td'];
                                    $table_one = json_decode($scheme->scheme_table_one,true);
                                @endphp
                                    @foreach($table_one as $head=>$tr)
                                        {!! "<".$head.">" !!} 
                                        @foreach($tr as $tri=>$tds)
                                            <tr class="{{ ($head=='thead')?'table-secondary':'tr_reference' }}">
                                                @if(($head=='thead'))
                                                <th>
                                                    <a href="javascript:void(null);" class="btn btn-sm btn-dark  add_column text-warning">
                                                        <li class="fa fa-plus-circle">
                                                            <span class="fa fa-caret-right"></span>
                                                        </li>
                                                    </a>
                                                </th>
                                                @else
                                                <td>
                                                    @if($tri==0)
                                                    <a href="javascript:void(null);" class="btn btn-sm btn-secondary add_row text-warning" >
                                                        <li class="fa fa-plus-circle w-100">
                                                        <span class="fa fa-caret-down w-100"></span>
                                                        </li>
                                                        
                                                    </a>
                                                    @else
                                                    <a href="javascript:void(null);" class="btn btn-sm btn-outline-danger delete_row">
                                                        <span class="num_span">{{ $tri+1 }}</span> 
                                                        <li class="fa fa-times-circle"></li>
                                                    </a>
                                                    @endif
                                                </td>
                                                @endif
                                            @foreach($tds as $tdi=>$td)
                                                @php 
                                                    $th_class = ($head=='thead' && $tdi==0)?'class="th_reference"':'';
                                                @endphp
                                            {!! "<".$col_arr[$head]." ".$th_class.">" !!}  
                                            <input type="text" name="tableone[{{ $head }}][]" class="form-control" value="{{  $td }}"  >
                                            {!! "</".$col_arr[$head].">" !!} 
                                            @endforeach
                                            </tr>
                                        @endforeach
                                        {!! "</".$head.">" !!} 
                                    @endforeach
                        @else
                              <thead>
                                  <tr class="table-secondary">
                                      <th>
                                          <a href="javascript:void(null);" class="btn btn-sm btn-dark  add_column text-warning">
                                              <li class="fa fa-plus-circle">
                                                  <span class="fa fa-caret-right"></span>
                                              </li>
                                          </a>
                                      </th>
                                      <th class="th_reference">
                                          <input type="text" name="tableone[thead][]" class="form-control" value=""  >
                                      </th>
                                      <th><input type="text" name="tableone[thead][]" class="form-control" value="" ></th>
                                      <th>
                                          <input type="text" name="tableone[thead][]" class="form-control" value="" >
                                      </th>
                                      <th>
                                          <input type="text" name="tableone[thead][]" class="form-control" value="" >
                                      </th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr class="tr_reference">
                                      <td>
                                      <a href="javascript:void(null);" class="btn btn-sm btn-secondary add_row text-warning" >
                                              <li class="fa fa-plus-circle w-100">
                                              <span class="fa fa-caret-down w-100"></span>
                                              </li>
                                              
                                          </a>
                                      </td>
                                      <td><input type="text" name="tableone[tbody][]" class="form-control" value="" ></td>
                                      <td><input type="text" name="tableone[tbody][]" class="form-control" value=""></td>
                                      <td><input type="text" name="tableone[tbody][]" class="form-control" value="" ></td>
                                      <td><input type="text" name="tableone[tbody][]" class="form-control" value="" ></td>
                                  </tr>
                              </tbody>
                            @endif
                          </table>
                      </div>
                  </div>
                  <div class="col-lg-12 form-group">
                      <label for="detail">Detail Two  </label>
                      <textarea name="detail_two" id="detail_two" class="form-control  ckeditor" placeholder="Enter description">{{ $scheme->scheme_detail_two }}</textarea>
                  </div>
                  <div class="col-12 form-group">
                      <label for="detail">Table Two Detail </label>
                      <div class="table-responsive">
                          <table class="table table-bordered">
                          @if($scheme->scheme_table_two!="")
                                @php 
                                    $col_arr = ['thead'=>'th','tbody'=>'td'];
                                    $table_two = json_decode($scheme->scheme_table_two,true);
                                @endphp
                                @foreach($table_two as $head=>$tr)
                                    {!! "<".$head.">" !!} 
                                    @foreach($tr as $tri=>$tds)
                                        <tr class="{{ ($head=='thead')?'table-secondary':'tr_reference' }}">
                                            @if(($head=='thead'))
                                            <th>
                                                <a href="javascript:void(null);" class="btn btn-sm btn-dark  add_column text-warning">
                                                    <li class="fa fa-plus-circle">
                                                        <span class="fa fa-caret-right"></span>
                                                    </li>
                                                </a>
                                            </th>
                                            @else
                                            <td>
                                                @if($tri==0)
                                                <a href="javascript:void(null);" class="btn btn-sm btn-secondary add_row text-warning" >
                                                    <li class="fa fa-plus-circle w-100">
                                                    <span class="fa fa-caret-down w-100"></span>
                                                    </li>
                                                    
                                                </a>
                                                @else
                                                <a href="javascript:void(null);" class="btn btn-sm btn-outline-danger delete_row">
                                                    <span class="num_span">{{ $tri+1 }}</span> 
                                                    <li class="fa fa-times-circle"></li>
                                                </a>
                                                @endif
                                            </td>
                                            @endif
                                        @foreach($tds as $tdi=>$td)
                                            @php 
                                                $th_class = ($head=='thead' && $tdi==0)?'class="th_reference"':'';
                                            @endphp
                                        {!! "<".$col_arr[$head]." ".$th_class.">" !!}  
                                        <input type="text" name="tabletwo[{{ $head }}][]" class="form-control" value="{{  $td }}"  >
                                        {!! "</".$col_arr[$head].">" !!} 
                                        @endforeach
                                        </tr>
                                    @endforeach
                                    {!! "</".$head.">" !!} 
                                @endforeach
                          @else 
                            <thead>
                                <tr class="table-secondary">
                                    <th>
                                        <a href="javascript:void(null);" class="btn btn-sm btn-dark  add_column text-warning">
                                            <li class="fa fa-plus-circle">
                                                <span class="fa fa-caret-right"></span>
                                            </li>
                                        </a>
                                    </th>
                                    <th class="th_reference">
                                        <input type="text" name="tabletwo[thead][]" class="form-control" value=""  >
                                    </th>
                                    <th><input type="text" name="tabletwo[thead][]" class="form-control" value="" ></th>
                                    <th>
                                        <input type="text" name="tabletwo[thead][]" class="form-control" value="" >
                                    </th>
                                    <th>
                                        <input type="text" name="tabletwo[thead][]" class="form-control" value="" >
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr_reference">
                                    <td>
                                    <a href="javascript:void(null);" class="btn btn-sm btn-secondary add_row text-warning" >
                                            <li class="fa fa-plus-circle w-100">
                                            <span class="fa fa-caret-down w-100"></span>
                                            </li>
                                            
                                        </a>
                                    </td>
                                    <td><input type="text" name="tabletwo[tbody][]" class="form-control" value="" ></td>
                                    <td><input type="text" name="tabletwo[tbody][]" class="form-control" value=""></td>
                                    <td><input type="text" name="tabletwo[tbody][]" class="form-control" value="" ></td>
                                    <td><input type="text" name="tabletwo[tbody][]" class="form-control" value="" ></td>
                                </tr>
                            </tbody>

                          @endif
                          </table>
                      </div>
                  </div>
                  <div class="col-lg-12">
                      <label for=""> Meta Title  <span class = "text-danger"> * </span> </label>
                      <input type = "text" name="meta_title" class="form-control form-group" placeholder=" Meta Title"  >
                  </div>

                  <div class="col-lg-12">
                      <label for="">Meta Description  <span class = "text-danger"> * </span> </label>
                      <textarea name="meta_description" class="form-control form-group" placeholder="Enter Meta Description"></textarea>
                  </div>

              </div>

              <div class="row">
                  <div class="col-12 text-center my-3 ">
                      <button type = "submit" class="btn btn-danger"> Submit </button> 
                  </div>
              </div>

          </form>

          </div>
          </div>

          </div>
          <!-- /.row -->
      </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
</section>

@endsection

@section('javascript')

@include('layouts.common.ckeditor')

  <script>

      $(document).ready(function() {
          
          $(".add_column").click(function(e){
              e.preventDefault();
              const tr = $(this).parent('th').parent('tr');
              const th = tr.find('th.th_reference');
              const index = th.index();
              var copy_th = th.clone();
              const tbody_tr = tr.parent('thead').next('tbody').find('tr');
              copy_th.find('input').val("");
              copy_th.removeClass('th_reference');
              copy_th.append('<a href="javascript:void(null);" class="btn btn-xs btn-outline-danger delete_thead" style="position:absolute;top:0;right:0;">&cross;</a>');
              tr.append(copy_th);
              $(tbody_tr).each(function(){
                  var tbody_td = $(this).find('td').eq(index).clone();
                  tbody_td.find('input').val("");
                  $(this).append(tbody_td);
              });
          });
          $(".add_row").click(function(e){
              e.preventDefault();
              var tr = $(this).parent('td').parent('tr');
              var copy_tr = tr.clone();
              const tbody  = tr.parent('tbody');
              $(copy_tr).each(function(){
                  $(this).find('td > input').val("");
              })
              copy_tr.removeClass('tr_reference');
              const tr_num = (tbody.find('tr').length)+1;
              const minus_a = '<a href="javascript:void(null);" class="btn btn-sm btn-outline-danger delete_row"><span class="num_span">'+tr_num+' </span> <li class="fa fa-times-circle"></li></a>'
              copy_tr.find('td:first').empty().append(minus_a)
              tbody.append(copy_tr);
          });
          $(document).on('click','a.delete_row',function(){
              const tr = $(this).parent('td').parent('tr');
              const tbody = tr.parent('tbody');
              var index = tr.index();
              var all_tr = tbody.find('tr').eq(index).nextAll()
              tr.remove();
              //console.log($(tbody.find('tr').eq(index).next()).html());
              $(all_tr).each(function(){
                  const new_index = ($(this).index())+1;
                  $(this).find('td > a > span.num_span').empty().text(new_index);
              });
          });
          $(document).on('click','.delete_thead',function(){
              const th = $(this).parent('th');
              const tbody_tr = th.parent('tr').parent('thead').next('tbody').find('tr');
              const index = th.index();
              if(th.remove()){
                  $(tbody_tr).each(function(){
                      //var tbody_td = $(this).find('td').eq(index).remove();
                      $(this).find('td').eq(index).remove();
                      // tbody_td.find('input').val("");
                      // $(this).append(tbody_td);
                  })
              }
          })
          $('#submitForm').submit(function(e) {
              e.preventDefault() ; // Prevent default form submission
              var formAction = $(this).attr('action');
              var detail_one = CKEDITOR.instances['detail_one'].getData();
              var detail_two = CKEDITOR.instances['detail_two'].getData();
              var formData = new FormData(this) ;
              formData.append('detail_one', detail_one);
              formData.append('detail_two', detail_two);

          // Send AJAX request

              $.ajax({
                  url: formAction,
                  type: 'POST',
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  beforeSend: function() {
                      $('.btn').prop("disabled", true) ;
                      $('#loader').removeClass('hidden') ;
                  },
                  success: function(response) {
                      // Handle successful update
                      toastr.success(response.success);
                      window.open("{{route('schemes.index')}}", '_self');
                  },
                  error: function(response) {

                      $('input').removeClass('is-invalid');
                      $('.btn-outline-danger').prop("disabled", false);
                      $('.btn').prop("disabled", false);
                      $('#loader').addClass('hidden');

                  var errors = response.responseJSON.errors ;

                      if (response.status === 422) {

                          $.each(errors, function(field, messages) {
                          var $field = $('[name="' + field + '"]');
                          toastr.error(messages[0]) ;
                          $field.addClass('is-invalid') ;
                          });

                      } else {

                          toastr.error(errors) ;

                      }
                  }
              });
          });
      });

</script>

@endsection

