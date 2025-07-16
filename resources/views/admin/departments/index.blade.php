@extends('layouts.dashboard.dashboard')

@section('content')
    <div class="container">
        <h1>Departments</h1>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->name_en }}</td>
                        <td>
                            <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-sm btn-primary">عرض</a>
                            <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                            <a href="{{ route('admin.departments.fields.index', $department->id) }}" class="btn btn-sm btn-info">إدارة الحقول</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
