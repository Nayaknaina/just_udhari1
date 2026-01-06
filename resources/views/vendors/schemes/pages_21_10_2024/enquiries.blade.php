@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php
    //dd($enquiries);
    $data = component_array('breadcrumb' , 'Schemes Enquiries',[['title' => 'Schemes']] ) ;

    @endphp

    <x-page-component :data=$data />

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
          <!-- general form elements -->
            <div class="card ">
              <!-- <div class="card-header">
              <a class = "btn btn-outline-primary" href = "{{route('schemes.create')}}"><i class="fa fa-plus"></i>  Add New </a>
              </div> -->

              <div class="card-body">

                <form action="">

                  <div class="row">

                    <div class="col-6 col-lg-2 form-group">
                      <label for="">Show entries</label>
                      @include('layouts.theme.datatable.entry')
                    </div>

                    <!-- <div class="col-6 col-lg-4  form-group">
                      <label for=""> Branches Name </label>
                    <input type="text" name = "scheme_name" class = "vin_no form-control" placeholder = "Search Schemes Name"  onchange="changeEntries()" >
                    </div> -->

                  </div>

                </form>

                <div  class="table-responsive">
                  <table class="table table-striped table-bordered ">
                    <thead class = "bg-info">
                        <tr>
                          <th>S.N.</th>
                          <th>ENTRY</th>
                          <th>Customer</th>
                          <th>SCHEME <li class="fa fa-link"></li></th>
                          <th>MESSAGE</th>
                          <th>RESPONSE</th>
                          <th>MARK</th>
                        </tr>
                    </thead>
                    <tbody id="data-list">
                      
                    </tbody>
                  </table>
                </div>
                <div id="pageination">
               
                </div>
              </div>
            </div>
            <style>
              td > hr, td >a > hr{
                border-top:1px solid lightgray;
                padding:0;
              }
              .link:hover{
                color:tomato;
              }
              div.dropdown-menu.my_drop.show{
                min-width:auto!important;
              }
            </style>
          </div>
        </div><!-- /.container-fluid -->
      </div><!-- /.container-fluid -->
    </section>
    <div class="modal" tabindex="-1" id="scheme_detail_model">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SCHEME DETAIL</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal" aria-label="Close" onclick="$('#scheme_detail_model').modal('hide');">&cross;</button>
            </div>
            <div class="modal-body p-0" id="scheme_detail_model_body">
                <p>Nothing Here !</p>
            </div>
            </div>
        </div>
    </div>

@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>

    var route = "{{ route('shopbranches.index') }}";

    function getresult(url) {
      $("#data-list").html('<tr><td colspan="7" class="text-center"><span><li class="fa fa-spinner fa-spin"></li>  Loading Content...</span></td></tr>');
      var data = {
              "entries": $(".entries").val(),
          }
      $.get(url,data,function(response){
        $("#data-list").html(response.html);
        $("#pageination").html(response.paginate);
      });
    }

    $(document).on('click', '.pagination a', function (e) {
          e.preventDefault();
          var pageUrl = $(this).attr('href');
          getresult(pageUrl);

      });

    function changeEntries() {

      getresult(url);

    }
    getresult(url);

  </script>
 <script>
    $('.scheme_detail_view').click(function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        $("#scheme_detail_model_body").empty().load(url);
        $("#scheme_detail_model").modal();
    });
    $('.enquiry_status_mark').click(function(e){
      e.preventDefault();
      $.get($(this).attr('href'),"",function(response){
        if(response.status){
          success_sweettoatr(response.msg);
          location.reload();
        }else{
          toastr.error(response.msg);
        }
      });
    });
</script>


  @endsection
