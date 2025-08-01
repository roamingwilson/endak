@extends('layouts.dashboard.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> تعديل الحقل: {{ $field->name_ar }} ({{ $field->name_en }})
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.departments.fields.index', $field->department_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> العودة للحقول
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> خطأ في التحقق!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.departments.fields.update', $field->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="department_id" value="{{ $field->department_id }}">

                        @include('admin.department_fields._dynamic_field_form', ['field' => $field])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
