@extends('layouts.vendors.app')

@section('content')
<div class="container">
    <h1>Tables</h1>
    <a href="{{ route('table.create') }}" class="btn btn-primary mb-3">Create New Table</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tables as $table)
                <tr>
                    <td>{{ $table->id }}</td>
                    <td>{{ $table->created_at }}</td>
                    <td>
                        <a href="{{ route('table.show', $table->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('table.edit', $table->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('table.destroy', $table->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
