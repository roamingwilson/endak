@extends('layouts.dashboard.dashboard')
@section('title', 'الأقسام الفرعية')
@section('content')
<div class="container mt-4">
    <h2>جميع الأقسام الفرعية</h2>
    <a href="{{ route('admin.sub_departments.create') }}" class="btn btn-primary mb-3">إضافة قسم فرعي جديد</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم بالعربي</th>
                <th>الاسم بالإنجليزي</th>
                <th>القسم الرئيسي</th>
                <th>الحالة</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subDepartments as $sub)
            <tr>
                <td>{{ $sub->id }}</td>
                <td>{{ $sub->name_ar }}</td>
                <td>{{ $sub->name_en }}</td>
                <td>{{ $sub->department ? $sub->department->name_ar : '-' }}</td>
                <td>{{ $sub->status ? 'مفعل' : 'غير مفعل' }}</td>
                <td>
                    <a href="{{ route('admin.sub_departments.edit', $sub->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('admin.sub_departments.destroy', $sub->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>{{ $subDepartments->links() }}</div>
</div>
@endsection
