@extends('layouts.vendors.app')

@section('content')

<div class="container mt-5">
    <h3>Table Structure and Data</h3>
    @php
        // Decode JSON structure and rows
        $structure = json_decode($table->structure, true);
        $rows = json_decode($table->rows, true);
    @endphp

    @if ($structure && $rows)
        <table class="table table-bordered">
            <thead class="bg-info" >
                <tr>
                    @foreach ($structure as $column)
                        <th>{{ $column['column_name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($row as $column1)
                            <td>{{ $column1 }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No data available.</p>
    @endif

    <a href="{{ route('table.edit', $table->id) }}" class="btn btn-primary mt-4">Edit Table</a>
    <a href="{{ route('table.index') }}" class="btn btn-secondary mt-4">Back to List</a>
</div>

@endsection


