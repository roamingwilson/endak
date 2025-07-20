@extends('layouts.home')
@section('title', 'تسجيل جديد')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="la la-user-plus"></i> تسجيل مستخدم جديد</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label><i class="la la-user"></i> الاسم الأول</label>
                            <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label><i class="la la-user"></i> الاسم الأخير</label>
                            <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label><i class="la la-phone"></i> رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label><i class="la la-envelope"></i> البريد الإلكتروني (اختياري)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label><i class="la la-lock"></i> كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-4">
                            <label><i class="la la-lock"></i> تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block w-100 py-2">
                            <i class="la la-check-circle"></i> تسجيل
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
