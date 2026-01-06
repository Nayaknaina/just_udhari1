@if($view_type)

@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        $data = component_array('breadcrumb' , 'Schemes Details',[['title' => 'Schemes']] ) ;

    @endphp

    <x-page-component :data=$data />

    <section class="content">
    <div class="container-fluid">

@endif

<div class="card card-primary">
    <div class="card-header p-2">
        <h3>{{ $schemedetail->scheme_head }} <a href="{{route('shopscheme.edit', $schemedetail->id)}}" class="btn btn-warning float-right"><li class="fa fa-edit"></li></a></h3>
        <hr class="m-1">
        <h4 class="card-title col-12">{{ $schemedetail->scheme_sub_head }}</h4>
    </div>
    <div class="card-body p-2">
        <div class="col-12 p-0">
            {!! $schemedetail->scheme_detail_one !!}
        </div>
        @if($schemedetail->scheme_table_one!="")
        <div class="table-responsive">
            @php
                $col_arr = ['thead'=>'th','tbody'=>'td'];
                $table_one = json_decode($schemedetail->scheme_table_one,true);
            @endphp
            <table class="table table-default table-bordered table-striped">
                @foreach($table_one as $head => $tr)
                    {!! "<".$head.">" !!}
                    @foreach($tr as $tri => $tds)
                        <tr>
                            @foreach($tds as $tdi => $td)
                                {!! "<".$col_arr[$head].">" !!}  
                                <span id="text-{{ $tri }}-{{ $tdi }}">{{ $td }}</span>
                                <input type="text" id="input-{{ $tri }}-{{ $tdi }}" value="{{ $td }}" class="form-control" style="display: none;">
                                {!! "</".$col_arr[$head].">" !!}
                            @endforeach
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="editRow({{ $tri }})">Edit</button>
                                <button type="submit" class="btn btn-success btn-sm" id="save-{{ $tri }}" style="display: none;" onclick="saveRow({{ $tri }})">Save</button>
                                <button type="button" class="btn btn-secondary btn-sm" id="cancel-{{ $tri }}" style="display: none;" onclick="cancelEdit({{ $tri }})">Cancel</button>
                            </td>
                        </tr>
                    @endforeach
                    {!! "</".$head.">" !!}
                @endforeach
            </table>
        </div>

        @endif

        @if($schemedetail->scheme_detail_two!="")
        <div class="col-12 p-0">
            {!! $schemedetail->scheme_detail_two !!}
        </div>
        @endif
        @if($schemedetail->scheme_table_two!="")
            <div class="table-responsive">
                @php
                    $col_arr = ['thead'=>'th','tbody'=>'td'];
                    $table_two = json_decode($schemedetail->scheme_table_two,true);
                @endphp
                <table class="table table-default table-bordered table-stripped">
                    @foreach($table_two as $head=>$tr)
                        {!! "<".$head.">" !!}
                        @foreach($tr as $tri=>$tds)
                            <tr>
                            @foreach($tds as $tdi=>$td)
                            {!! "<".$col_arr[$head].">" !!}  {{  $td }} {!! "</".$col_arr[$head].">" !!}
                            @endforeach
                            </tr>
                        @endforeach
                        {!! "</".$head.">" !!}
                    @endforeach
                </table>
            </div>
        @endif
    </div>
</div>

@if($view_type)

        </div>
    </section>

    <script>
        function editRow(rowId) {

            // Show input fields and buttons for the selected row
            let inputs = document.querySelectorAll(`input[id^='input-${rowId}-']`);
            let texts = document.querySelectorAll(`span[id^='text-${rowId}-']`);
            inputs.forEach(input => input.style.display = 'block');
            texts.forEach(text => text.style.display = 'none');

            document.getElementById(`save-${rowId}`).style.display = 'inline-block';
            document.getElementById(`cancel-${rowId}`).style.display = 'inline-block';

        }
        
        function saveRow(rowId) {
            // Handle saving logic here, such as submitting a form via AJAX or a regular form submission
            alert("Save logic for row " + rowId);
        
            // Hide the inputs and show the original text after saving
            cancelEdit(rowId);
        }

        function cancelEdit(rowId) {
           
            // Show input fields and buttons for the selected row
            let inputs = document.querySelectorAll(`input[id^='input-${rowId}-']`);
            let texts = document.querySelectorAll(`span[id^='text-${rowId}-']`);
            inputs.forEach(input => input.style.display = 'none');
            texts.forEach(text => text.style.display = 'block');

            document.getElementById(`save-${rowId}`).style.display = 'none';
            document.getElementById(`cancel-${rowId}`).style.display = 'none';
        
        }

        </script>
@endsection

@else
    @php exit(); @endphp
@endif


