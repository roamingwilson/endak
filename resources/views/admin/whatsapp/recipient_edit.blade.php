@extends('layouts.dashboard.dashboard')
@section('content')
<div class="container">
    <h2>تعديل رقم واتساب مستلم</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.whatsapp_recipients.update', $recipient->id) }}">
        @csrf
        <div class="form-group">
            <label>رقم العميل</label>
            <input type="text" name="number" class="form-control" value="{{ $recipient->number }}" required>
        </div>
        <div class="form-group">
            <label>القسم</label>
            <select name="department_id" class="form-control" required>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $recipient->department_id == $department->id ? 'selected' : '' }}>{{ $department->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('admin.whatsapp_recipients.create') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
