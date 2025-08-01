@extends('layouts.dashboard.dashboard')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> الحقول المخصصة للقسم: {{ $department->name_ar ?? $department->name_en }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.departments.fields.create', $department->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة حقل جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                                                        <span class="badge bg-success ms-2">
                                                            <i class="fas fa-repeat"></i> قابلة للتكرار
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary ms-2">
                                                            <i class="fas fa-times"></i> غير قابلة للتكرار
                                                        </span>
                                                    @endif
                                                </h6>
                                                <span class="text-muted">
                                                    <i class="fas fa-hashtag"></i> عدد الحقول: {{ $fields->count() }}
                                                </span>
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
                                                                        <i class="fas fa-key"></i> {{ $field->name }}
                                                                    </small>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-tag"></i> النوع: {{ $field->type }}
                                                                        @if($field->is_required)
                                                                            <span class="text-danger">* مطلوب</span>
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('admin.departments.fields.edit', $field->id) }}"
                                                                       class="btn btn-outline-warning btn-sm"
                                                                       title="تعديل">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('admin.departments.fields.destroy', $field->id) }}"
                                                                          method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                                class="btn btn-outline-danger btn-sm"
                                                                                onclick="return confirm('هل أنت متأكد من حذف هذا الحقل؟')"
                                                                                title="حذف">
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
                                        <table class="table table-striped table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th><i class="fas fa-font"></i> اسم الحقل</th>
                                                    <th><i class="fas fa-tag"></i> النوع</th>
                                                    <th><i class="fas fa-exclamation-triangle"></i> مطلوب</th>
                                                    <th><i class="fas fa-sticky-note"></i> الوصف</th>
                                                    <th><i class="fas fa-cogs"></i> الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($regularFields as $field)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $field->name_ar ?? $field->name_en }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-key"></i> {{ $field->name }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-tag"></i> {{ $field->type }}
                                                            </span>
                                                            @if($field->type === 'select' && is_array($field->options))
                                                                <br>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-list"></i> {{ count($field->options) }} خيارات
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($field->is_required)
                                                                <span class="badge bg-danger">
                                                                    <i class="fas fa-exclamation-triangle"></i> مطلوب
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">
                                                                    <i class="fas fa-check"></i> اختياري
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($field->description)
                                                                <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                                      title="{{ $field->description }}">
                                                                    {{ $field->description }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('admin.departments.fields.edit', $field->id) }}"
                                                                   class="btn btn-outline-warning btn-sm"
                                                                   title="تعديل">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('admin.departments.fields.destroy', $field->id) }}"
                                                                      method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="btn btn-outline-danger btn-sm"
                                                                            onclick="return confirm('هل أنت متأكد من حذف هذا الحقل؟')"
                                                                            title="حذف">
                                                                        <i class="fas fa-trash"></i>
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
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>لا توجد حقول مخصصة لهذا القسم بعد.</h5>
                            <p class="mb-3">ابدأ بإضافة الحقول المخصصة لتخصيص نموذج الطلب لهذا القسم.</p>
                            <a href="{{ route('admin.departments.fields.create', $department->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة أول حقل
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.departments') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> العودة للأقسام
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
