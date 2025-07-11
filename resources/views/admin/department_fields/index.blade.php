@extends('layouts.dashboard.dashboard')
@section('content')
    <div class="container">
        <h1>Fields for {{ $department->name_en }}</h1>
        <a href="{{ route('admin.departments.fields.create', $department->id) }}" class="btn btn-primary">Add New Field</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($department->fields as $field)
                    <tr>
                        <td>{{ $field->name }}</td>
                        <td>{{ $field->type }}</td>
                        <td>{{ $field->is_required ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
