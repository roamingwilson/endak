@extends('layouts.dashboard.dashboard')
@section('content')
<div class="container">
    <h2>إضافة رقم واتساب مرسل</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.whatsapp_senders.store') }}">
        @csrf
        <div class="form-group">
            <label>رقم الواتساب</label>
            <input type="text" name="number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>التوكن</label>
            <input type="text" name="token" class="form-control" required>
        </div>
        <div class="form-group">
            <label>معرف الـ Instance</label>
            <input type="text" name="instance_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label>القسم/الأقسام</label>
            <select name="departments[]" class="form-control" multiple required>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name_ar }}</option>
                @endforeach
            </select>
            <small>يمكنك اختيار أكثر من قسم بالضغط على Ctrl أو Cmd</small>
        </div>
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
    <hr>
    <h3>جميع أرقام الإرسال</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الواتساب</th>
                <th>التوكن</th>
                <th>الأقسام المرتبطة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($senders as $index => $sender)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sender->number }}</td>
                    <td>{{ $sender->token }}</td>
                    <td>
                        @if($sender->departments && $sender->departments->count())
                            @foreach($sender->departments as $dep)
                                <span class="badge badge-info">{{ $dep->name_ar }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.whatsapp_senders.edit', $sender->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('admin.whatsapp_senders.delete', $sender->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
