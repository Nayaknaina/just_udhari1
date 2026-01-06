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
                            <tbody>
							@if(!empty($customers->toArray()))
                              @foreach($customers as $ci=>$custo)
                              <tr>
                                  <td>{{ $ci+1 }}</td>
                                  <td>{{ $custo->customer_name }}<hr class="m-1">
                                  {{ @$custo->info->custo_fone }}<hr class="m-1">
                                  {{ @$custo->info->custo_mail }}
                                  </td>
                                  <td class="text-center">
                                    {{ $custo->schemes->scheme_head }}<hr class="m-1">
                                    <b>GROUP : </b>{{ $custo->groups->group_name }}<hr class="m-1">
                                    <b>EMI : </b>{{ $custo->emi_amnt }}
                                  </td>
                                  @php
                                    $payable = $custo->emi_amnt*$custo->schemes->scheme_validity; 
                                    $paid = $custo->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt');
                                    $bonus = $custo->emipaid->sum('bonus_amnt');
                                  @endphp
                                  <td>
                                     <b>Payable : </b> {{ $payable }}<hr class="m-1">
                                     <b>Paid : </b> {{ $paid }}<hr class="m-1">
                                     <b>Bonus : </b> {{ $bonus }}
                                  </td>
                                  <td class="text-center">
                                      @php 
                                      $action_lbl = '<li class="fa fa-rupee"></li> PAY';
                                      $btn_class = 'btn-outline-success';
                                      if(($paid=$payable) && ($custo->emipaid->max('emi_num')==$custo->schemes->scheme_validity)){
                                        $action_lbl = '<li class="fa fa-eye"></li> View';
                                        $btn_class = 'btn-outline-info';
                                      }
                                      @endphp
                                      <a href="{{ route("shopscheme.emipay",$custo->id)}}" class="btn {{ $btn_class }}">
                                        {!! $action_lbl !!}
                                      </a>
                                  </td>
                                  
                              </tr>
                              @endforeach
							  @else 
								  <tr><td colspan="5" class="text-center text-danger">No Enrollments Yet !</td></tr>
							  @endif
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
