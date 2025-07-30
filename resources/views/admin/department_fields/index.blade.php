@extends('layouts.dashboard.dashboard')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>الحقول المخصصة للقسم: {{ $department->name_ar ?? $department->name_en }}</h1>
            <a href="{{ route('admin.departments.fields.create', $department->id) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة حقل جديد
            </a>
        </div>

        @if($department->fields->count() > 0)
            <!-- الحقول المجمعة -->
            @php
                $groupedFields = $department->fields->groupBy('input_group');
                $groupedFields = $groupedFields->filter(function($fields, $group) { return $group; });
            @endphp

            @if($groupedFields->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-layer-group"></i> الحقول المجمعة</h5>
                    </div>
                    <div class="card-body">
                        @foreach($groupedFields as $group => $fields)
                            @php
                                $repeatable = $fields->first()->is_repeatable ?? false;
                            @endphp
                            <div class="border rounded p-3 mb-3" style="background: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-object-group"></i>
                                        مجموعة: <strong>{{ $group }}</strong>
                                        @if($repeatable)
                                            <span class="badge bg-success ms-2">قابلة للتكرار</span>
                                        @else
                                            <span class="badge bg-secondary ms-2">غير قابلة للتكرار</span>
                                        @endif
                                    </h6>
                                    <span class="text-muted">عدد الحقول: {{ $fields->count() }}</span>
                                </div>
                                <div class="row">
                                    @foreach($fields as $field)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="border rounded p-2" style="background: white;">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>{{ $field->name_ar ?? $field->name_en }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            النوع: {{ $field->type }}
                                                            @if($field->is_required)
                                                                <span class="text-danger">* مطلوب</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                                                                            <a href="{{ route('admin.departments.fields.edit', $field->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.departments.fields.destroy', $field->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الحقل؟')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- الحقول العادية (غير مجمعة) -->
            @php
                $regularFields = $department->fields->where('input_group', null);
            @endphp

            @if($regularFields->count() > 0)
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> الحقول العادية</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الحقل</th>
                                        <th>النوع</th>
                                        <th>مطلوب</th>
                                        <th>الوصف</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($regularFields as $field)
                                        <tr>
                                            <td>
                                                <strong>{{ $field->name_ar ?? $field->name_en }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $field->name }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $field->type }}</span>
                                                @if($field->type === 'select' && is_array($field->options))
                                                    <br>
                                                    <small class="text-muted">{{ count($field->options) }} خيارات</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($field->is_required)
                                                    <span class="badge bg-danger">مطلوب</span>
                                                @else
                                                    <span class="badge bg-secondary">اختياري</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($field->description)
                                                    {{ $field->description }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.departments.fields.edit', $field->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> تعديل
                                                    </a>
                                                    <form action="{{ route('admin.departments.fields.destroy', $field->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الحقل؟')">
                                                            <i class="fas fa-trash"></i> حذف
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i>
                لا توجد حقول مخصصة لهذا القسم بعد.
                <br>
                <a href="{{ route('admin.departments.fields.create', $department->id) }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> إضافة أول حقل
                </a>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('admin.departments') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> العودة للأقسام
            </a>
        </div>
    </div>
@endsection
