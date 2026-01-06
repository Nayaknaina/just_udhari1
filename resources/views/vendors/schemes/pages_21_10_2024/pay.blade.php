@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    $data = component_array('breadcrumb' , 'Schemes Pay',[['title' => 'Schemes Pay']] ) ;
    
    @endphp

    <x-page-component :data=$data />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12"> 
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">
                        Find Customer To Pay
                      </h3>
                    </div>
                    <div class="card-body p-0">
                      <div class="col-12 p-2">
                        <form name="" action="" role="" id="" >
                          <div class="col-md-6">
                            <input type="text" name="customer" placeholder="Enter Keyword Name/mobile/E-mail" class="form-control col-12">
                            <!-- <button type="submit" name="find" value="customer">Find</button> -->
                          </div>
                        </form>
                      </div>
                      <div class="table-responsive">
                          <table class="table table-bordered scheme-custo-table">
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>INFO</th>
                                    <th class="text-center">ENROLL</th>
                                    <th class="text-center">PAYMENT</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="data-list">
                              
                            </tbody>
                          </table>
                        </div>
                        <div id="pagination">
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <style>
      table.scheme-custo-table > thead >tr>th{
        color:white;
      background:#66778c;
      }  
    </style>

@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>
function getresult(url) {
      $("#data-list").html('<tr><td colspan="5" class="text-center"><span><li class="fa fa-spinner fa-spin"></li>  Loading Content...</span></td></tr>');
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



  @endsection
