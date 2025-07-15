@extends('layouts.dashboard.dashboard')
@section('content')
<div class="container">
    <h2>تعديل رقم واتساب مرسل</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.whatsapp_senders.update', $sender->id) }}">
        @csrf
        <div class="form-group">
            <label>رقم الواتساب</label>
            <input type="text" name="number" class="form-control" value="{{ $sender->number }}" required>
        </div>
        <div class="form-group">
            <label>التوكن</label>
            <input type="text" name="token" class="form-control" value="{{ $sender->token }}" required>
        </div>
        <div class="form-group">
            <label>معرف الـ Instance</label>
            <input type="text" name="instance_id" class="form-control" value="{{ $sender->instance_id }}" required>
        </div>
        <div class="form-group">
            <label>القسم/الأقسام</label>
            <select name="departments[]" class="form-control" multiple required>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $sender->departments->contains($department->id) ? 'selected' : '' }}>{{ $department->name_ar }}</option>
                @endforeach
            </select>
            <small>يمكنك اختيار أكثر من قسم بالضغط على Ctrl أو Cmd</small>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('admin.whatsapp_senders.create') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
