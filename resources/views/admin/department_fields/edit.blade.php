@extends('layouts.dashboard.dashboard')

@section('content')
    <div class="container">
        <h1 class="mb-4" style="color:#1976d2; font-weight:bold;">تعديل الحقل: {{ $field->name_ar }} ({{ $field->name_en }})</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.departments.fields.update', $field->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.department_fields._dynamic_field_form', ['field' => $field])
            <a href="{{ route('admin.departments.fields.index', $field->department_id) }}" class="btn btn-secondary mt-2">
                <i class="fas fa-arrow-right"></i> إلغاء / رجوع
            </a>
        </form>
    </div>
@endsection
