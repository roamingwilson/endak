@extends('layouts.dashboard.dashboard')

@section('content')
    <div class="container">
        <h1>Edit Field</h1>
        <form action="{{ route('admin.fields.update', $field->id) }}" method="POST">
            @method('PUT')
            @include('admin.department_fields._form')
        </form>
    </div>
@endsection
