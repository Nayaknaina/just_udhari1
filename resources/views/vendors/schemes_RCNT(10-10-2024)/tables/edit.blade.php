@extends('layouts.vendors.app')

@section('content')
<div class="container">
    <h1>Edit Table</h1>

    <form action="{{ route('table.update', $table->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead class="bg-info">
                <tr>
                    @foreach ($structure as $column)
                        <th>{{ $column['column_name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($rows as $rowIndex => $row)
                    <tr>
                        @foreach ($row as $cellIndex => $cell)
                            <td>
                                @php
                                    $isAmount = $structure[$cellIndex]['is_amount'];
                                    $cellType = $structure[$cellIndex]['column_type'];
                                @endphp

                                @if ($cellType === 'integer')
                                    <input type="number" class="form-control" name="data[{{ $rowIndex }}][{{ $cellIndex }}]" value="{{ $cell }}">
                                @else
                                    <input type="text" class="form-control" name="data[{{ $rowIndex }}][{{ $cellIndex }}]" value="{{ $cell }}">
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success mt-4">Save Changes</button>

    </form>
</div>
@endsection
