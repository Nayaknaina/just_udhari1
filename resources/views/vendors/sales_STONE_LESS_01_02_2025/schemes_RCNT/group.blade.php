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
              <li class="breadcrumb-item"> <a href="#">{{ $group_data["scheme"] }}</a> </li>
              <li class="breadcrumb-item active">{{ $group_data["name"] }}</li>
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
                                <strong>{{ $group_data["name"] }}</strong> ( {{ $group_data["scheme"] }} )
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Name</th>
                                            <th class="text-center">Mobile</th>
                                            <th class="text-center">Mail</th>
                                            <th class="text-center">EMI COVERED</th>
                                            <th class="text-center">PAID</th>
                                            <th class="text-center">EMI REMAINS</v>
                                            <th class="text-center">UNPAID</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $kist = ["a"=>2,"b"=>'4',"c"=>1,"d"=>3,"e"=>5,"f"=>3,"g"=>4,"h"=>2];
                                            $paid = [
                                                    "a"=>"10000",
                                                    "b"=>"20000",
                                                    "c"=>"5000",
                                                    "d"=>"15000",
                                                    "e"=>"25000",
                                                    "f"=>"15000",
                                                    "g"=>"20000",
                                                    "h"=>"10000",
                                                    ];
                                            $kist_remains = ["a"=>8,"b"=>6,"c"=>9,"d"=>7,"e"=>5,"f"=>7,"g"=>6,"h"=>8];
                                            $unpaid = [
                                                    "a"=>"40000",
                                                    "b"=>"30000",
                                                    "c"=>"45000",
                                                    "d"=>"35000",
                                                    "e"=>"25000",
                                                    "f"=>"35000",
                                                    "g"=>"30000",
                                                    "h"=>"40000",
                                                    ];
                                        @endphp
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
                                                <td class="text-center">{{ rand(1101101110,9999999999) }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $num = rand(10,90);
                                                        $string = strtolower(str_replace(" ","_",$name[$i]));
                                                    @endphp
                                                    {{ $string.$num."@gmail.com" }}
                                                </td>
                                                <td class="text-center">{{ $kist["{$id}"] }}</td>
                                                <td class="text-center">{{ $paid["{$id}"] }}</td>
                                                <td class="text-center">{{ $kist_remains["{$id}"] }}</td>
                                                <td class="text-center">{{ $unpaid["{$id}"] }}</td>
                                            </tr>
                                        @endfor
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
