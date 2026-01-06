@extends('ecomm.site')
@section('title', "Invalid Access")
@section('content')

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
            <div class="card col-md-4 bg-warning" style="margin:auto;padding:0;">
                <div class="card-header">
                    <h3 class="card-title">Registered Customer Only !</h3>
                </div>
                <div class="card-body"> 
                    Only a registered customer can access this page.
                    <ol>
                        <li>Registered Customer can Login !</li>    
                        <li>non Registered user can Register than login !</li>    
                    </ol> 
                </div>
                <div class="card-footer">
                    <ul class="row" style="list-style:none;padding:0;">
                        <li class="col-6 text-center"><a href="{{ url("{$ecommbaseurl}register")}}" class="btn btn-default  btn-sm text-primary">Register ?</a></li>    
                        <li class="col-6 text-center"> <a href="{{ url("{$ecommbaseurl}login")}}" class="btn btn-default btn-sm text-success">Login ?</a></li>    
                    </ul>
                   
                </div>
            </div>
    </div>
</div>


@endsection