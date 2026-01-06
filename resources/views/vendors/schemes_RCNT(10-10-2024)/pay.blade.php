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
                                    <th>Name</th>
                                    <th class="text-center">MOBILE</th>
                                    <th class="text-center">MAIL</th>
                                    <th class="text-center">GROUP</th>
                                    <th class="text-center">EMI</th>
                                    <th class="text-center">SCHEMES</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($customers as $ci=>$custo)
                              <tr>
                                  <td>{{ $ci+1 }}</td>
                                  <td>{{ $custo->customer_name }}</td>
                                  <td>{{ $custo->info->custo_fone }}</td>
                                  <td>
                                  {{ $custo->info->custo_mail }}
                                  </td>
                                  <td class="text-center">
                                    {{ $custo->groups->group_name }}
                                  </td>
                                  <td>
                                      <b>{{ $custo->emi_amnt }}</b>
                                  </td>
                                  <td>
                                      {{ $custo->schemes->scheme_head }}
                                  </td>
                                  <td class="text-center">
                                      <a href="{{ route("shopscheme.emipay",$custo->id)}}" class="btn btn-outline-success">
                                        <li class="fa fa-rupee"></li> PAY
                                      </a>
                                  </td>
                                  
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
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


  </script>



  @endsection
