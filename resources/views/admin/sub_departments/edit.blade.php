@extends('layouts.dashboard.dashboard')
@section('title', 'تعديل قسم فرعي')
@section('content')
<div class="container mt-4">
    <h2>تعديل قسم فرعي</h2>
    <form action="{{ route('admin.sub_departments.update', $subDepartment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>القسم الرئيسي</label>
            <select name="department_id" class="form-control" required>
                <option value="">اختر القسم الرئيسي</option>
                @foreach($departments as $dep)
                    <option value="{{ $dep->id }}" @if($subDepartment->department_id == $dep->id) selected @endif>{{ $dep->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>اسم القسم بالعربي</label>
            <input type="text" name="name_ar" class="form-control" value="{{ $subDepartment->name_ar }}" required>
        </div>
        <div class="mb-3">
            <label>اسم القسم بالإنجليزي</label>
            <input type="text" name="name_en" class="form-control" value="{{ $subDepartment->name_en }}" required>
        </div>
        <div class="mb-3">
            <label>صورة</label>
            <input type="file" name="image" class="form-control">
            @if($subDepartment->image)
                <img src="{{ asset('storage/' . $subDepartment->image) }}" alt="صورة القسم" width="100">
            @endif
        </div>
        <div class="mb-3">
            <label>الوصف بالعربي</label>
            <textarea name="description_ar" class="form-control">{{ $subDepartment->description_ar }}</textarea>
        </div>
        <div class="mb-3">
            <label>الوصف بالإنجليزي</label>
            <textarea name="description_en" class="form-control">{{ $subDepartment->description_en }}</textarea>
        </div>
        <div class="mb-3">
            <label>الحالة</label>
            <select name="status" class="form-control">
                <option value="1" @if($subDepartment->status) selected @endif>مفعل</option>
                <option value="0" @if(!$subDepartment->status) selected @endif>غير مفعل</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">تحديث</button>
    </form>
</div>
@endsection
