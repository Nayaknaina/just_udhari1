@extends('layouts.vendors.app')

@section('content')
    @php
        $data = component_array('breadcrumb' , 'Stock',[['title' => 'Stock']] ) ;
    @endphp
    <x-page-component :data=$data />
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row">
                <div class = "col-12">

                </div>
            </div>
        </div>
    </section>
@endsection('content')