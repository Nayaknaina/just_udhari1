@extends('layouts.vendors.app')

@section('content')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection
@php 
$anchor = ['<a href="'.route('anjuman.new.enroll').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>'];
$data = new_component_array('newbreadcrumb',"All Anjuman Enrolls") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
                                    <thead >
                                        <tr>
                                            <th>SN</th>
                                            <th>Customer</th>
                                            <th>Scheme</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataarea">
                                        <tr>
                                            <td colspan="7" class="text-center text-primary">
                                                <span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagingarea">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $(document).ready(function(){
            function getresult(url) {
                $("#loader").show();
                $.ajax({
                    url: url , // Updated route URL
                    type: "GET",
                    data: {
                    },
                    success: function (data) {
                    $("#loader").hide();
                    $("#dataarea").html(data.html);
                    $("#pagingarea").html(data.paging);
                    },
                    error: function () {},
                });
            }

            getresult(url);

            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                var pageUrl = $(this).attr('href');
                getresult(pageUrl);
            });

            function changeEntries() {
                getresult(url);
            }
        });
    </script>
    @include('layouts.theme.js.datatable')
    @include('layouts.vendors.js.passwork-popup')
@endsection