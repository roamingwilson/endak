@extends('layouts.dashboard.dashboard')

@section('content')
    <div class="container">
        <h1>Add Field to {{ $department->name_en }}</h1>
        <form action="{{ route('admin.departments.fields.store', $department->id) }}" method="POST">
            @include('admin.department_fields._form', ['field' => null])
        </form>
    </div>
@endsection
