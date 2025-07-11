@extends('layouts.dashboard.dashboard')

@section('content')
    <div class="container">
        <h1>Departments</h1>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->name_en }}</td>
                        <td>
                            <a href="{{ route('admin.departments.fields.index', $department->id) }}"
                                class="btn btn-sm btn-info">Manage Fields</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
