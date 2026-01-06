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
                        <table class="table table-bordered table-stripped">
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Name</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Mail</th>
                                    <th class="text-center">SCHEMES</th>
                                </tr>
                            </thead>
                            <tbody>
                            @for($i=0;$i<=10;$i++)
                                @php
                                    //$name = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,10);
                                    $name = [
                                        "Niru Singh",
                                        "Mohit",
                                        "Raju Sriniwas",
                                        "Prashan Mandela",
                                        "Vivek Choudhary",
                                        "Somnaath Bhagat",
                                        "Rohit ",
                                        "Mohit",
                                        "Prashan Mandela",
                                        "Niru Singh",
                                        "Raju Sriniwas",
                                        ];
                                @endphp
                              <tr>
                                  <td>{{ $i+1 }}</td>
                                  <td>{{ $name[$i] }}</td>
                                  <td>{{ rand(1101101110,9999999999) }}</td>
                                  <td>
                                    @php
                                        $num = rand(10,90);
                                        $string = strtolower(str_replace(" ","_",$name[$i]));
                                    @endphp
                                    {{ $string.$num."@gmail.com" }}
                                  </td>
                                  <td>
                                    <ol>
                                      <li><a href="{{ route("shopscheme.emipay",1)}}">Labh Lakshmi Yojnas</a></li>
                                      <li><a href="">Subh Lakshmi Yojnas</a></li>
                                    </ol>
                                  </td>
                              </tr>
                              @endfor
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>


  </script>



  @endsection
