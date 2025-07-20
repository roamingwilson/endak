@extends('layouts.dashboard.dashboard')
@section('title', 'إضافة قسم فرعي')
@section('content')
<div class="container mt-4">
    <h2>إضافة قسم فرعي جديد</h2>
    <form action="{{ route('admin.sub_departments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>القسم الرئيسي</label>
            <select name="department_id" class="form-control" required>
                <option value="">اختر القسم الرئيسي</option>
                @foreach($departments as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>اسم القسم بالعربي</label>
            <input type="text" name="name_ar" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>اسم القسم بالإنجليزي</label>
            <input type="text" name="name_en" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>صورة</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label>الوصف بالعربي</label>
            <textarea name="description_ar" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>الوصف بالإنجليزي</label>
            <textarea name="description_en" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>الحالة</label>
            <select name="status" class="form-control">
                <option value="1">مفعل</option>
                <option value="0">غير مفعل</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
