@extends('layouts.dashboard.dashboard')
@section('title', 'تكرار قسم فرعي')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-copy"></i> تكرار قسم فرعي: {{ $subDepartment->name_ar }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>ملاحظة:</strong> سيتم نسخ جميع بيانات القسم الفرعي مع إمكانية تغيير الاسم والوصف.
                        @if($subDepartment->fields->count() > 0)
                            <br>سيتم نسخ {{ $subDepartment->fields->count() }} حقل مرتبط بالقسم.
                        @endif
                    </div>

                    <form action="{{ route('admin.sub_departments.duplicate.store', $subDepartment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">القسم الرئيسي</label>
                                    <select name="department_id" class="form-control @error('department_id') is-invalid @enderror" required>
                                        <option value="">اختر القسم الرئيسي</option>
                                        @foreach($departments as $dep)
                                            <option value="{{ $dep->id }}" {{ $dep->id == $subDepartment->department_id ? 'selected' : '' }}>
                                                {{ $dep->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الحالة</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ $subDepartment->status ? 'selected' : '' }}>مفعل</option>
                                        <option value="0" {{ !$subDepartment->status ? 'selected' : '' }}>غير مفعل</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">اسم القسم بالعربي <span class="text-danger">*</span></label>
                                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                                           value="{{ old('name_ar', $subDepartment->name_ar . ' (نسخة)') }}" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">اسم القسم بالإنجليزي <span class="text-danger">*</span></label>
                                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror"
                                           value="{{ old('name_en', $subDepartment->name_en . ' (Copy)') }}" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">صورة جديدة (اختياري)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @if($subDepartment->image)
                                <small class="form-text text-muted">
                                    الصورة الحالية: {{ basename($subDepartment->image) }}
                                    <br>اترك الحقل فارغاً لنسخ الصورة الحالية
                                </small>
                            @endif
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الوصف بالعربي</label>
                                    <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3">{{ old('description_ar', $subDepartment->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">الوصف بالإنجليزي</label>
                                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en', $subDepartment->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($subDepartment->fields->count() > 0)
                            <div class="mb-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>الحقول المرتبطة:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($subDepartment->fields as $field)
                                            <li>{{ $field->name_ar ?? $field->name_en }} ({{ $field->type }})</li>
                                        @endforeach
                                    </ul>
                                    <small class="text-muted">سيتم نسخ جميع هذه الحقول مع القسم الفرعي الجديد</small>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.sub_departments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-copy"></i> تكرار القسم الفرعي
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
