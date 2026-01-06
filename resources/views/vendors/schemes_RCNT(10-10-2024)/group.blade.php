@extends('layouts.vendors.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Group</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"> <a href="#">{{ $group->schemes->scheme_head }}</a> </li>
              <li class="breadcrumb-item active">{{ $group->group_name }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-primary" >
                        <div class="card-header">
                            <h3 class="card-title">
                                <strong>{{ $group->group_name }} <small>( {{ $group->schemes->scheme_head }})</small></strong>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped group-detail-table">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Name</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Mail</th>
                                            <th class="text-center">EMI CHOOSED</th>
                                            <th class="text-center">EMI COVERED</th>
                                            <th class="text-center">PAID</th>
                                            <th class="text-center">EMI REMAINS</v>
                                            <th class="text-center">UNPAID</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($customers as $ck=>$custo)
                                            <tr>
                                                <td>{{ $ck+1 }}</td>
                                                <td>
                                                    <b>{{ @$custo->customer_name }}</b>
                                                    <hr class="m-2 p-0">
                                                    ( {{ @$custo->info->custo_full_name }} )
                                                    </td>
                                                <td class="text-center">
                                                    {{ @$custo->info->custo_fone }}
                                                </td>
                                                <td class="text-center">
                                                    {{ @$custo->info->custo_mail }}
                                                </td>
												 <td class="text-center text-info">
                                                    {{ @$custo->emi_amnt }} Rs.
                                                </td>
                                                @php 
                                                    $emi_sum = $custo->emipaid->sum('emi_amnt');
                                                    $emi_count = $custo->emipaid->count('emi_num');
                                                @endphp
                                                <td class="text-center">
                                                {{ $emi_count }}
                                                </td>
                                                <td class="text-center text-success">
                                                    {{ $emi_sum }} Rs.
                                                </td>
                                                <td class="text-center">
                                                    {{ $custo->schemes->scheme_validity-$emi_count }}
                                                </td>
                                                <td class="text-center text-danger">
                                                    {{ ($custo->schemes->scheme_validity*$custo->emi_amnt)- $emi_sum }} Rs.
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <style>
        a.overlay_anchor{
            text-align:center;
            color:#b7aeae;
            height:100%;
            width:100%;
            background:#00000094;
            position: absolute;
            font-size:200%;
            z-index:-1;
        }
        a.overlay_anchor.anchor_visible{
            z-index:1!important;
        }
        a.overlay_anchor:after{

        }
        a.overlay_anchor>li{
            padding:10%;
        }
        .title_block{
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .title_value > .total::marker{
            content:"\0023  ";

        }
        .title_value > .received::marker{
            content:"\2713  ";
        }
        .title_value > .remains::marker{
            content:"\2717  ";
        }
        .title_value >li::marker{
            font-weight:bold;
            border:1px solid gray;
            padding:2px;
        }
        .title_value{
            border-left:1px solid lightgray;
        }
        table.group-detail-table > thead >tr>th{
            color:white;
            background:#66778c;
        } 
    </style>
@endsection

  @section('javascript')


  <script>
    // $('.group_block').hover(function(){
    //     const a = $(this).find('a.overlay_anchor');
    //     alert($(a).html());
    //     if($(this).prev('.overlay_anchor').hasClass('anchor_visible')){
    //         $(this).prev('.overlay_anchor').addClass('anchor_visible');
    //     }else{
    //         $(this).prev('.overlay_anchor').removeClass('anchor_visible');

    //     }
    // });
    $(".group_block").mouseover(function () {
        const a = $(this).find('a.overlay_anchor');
        a.addClass('anchor_visible');
    });
    $(".group_block").mouseout(function () {
        const a = $(this).find('a.overlay_anchor');
        a.removeClass('anchor_visible');
    });
  </script>



  @endsection
