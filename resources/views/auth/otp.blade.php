@extends('layouts.home')
@section('title', 'تفعيل الحساب')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="la la-key"></i> تفعيل الحساب عبر كود OTP</h4>
                </div>
                <div class="card-body">
                    @if(session('otp_demo'))
                        <div class="alert alert-info text-center">
                            <i class="la la-info-circle"></i> كود التفعيل (للديمو):
                            <span class="badge bg-primary text-white" style="font-size: 1.2em;">{{ session('otp_demo') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <label><i class="la la-key"></i> أدخل كود التفعيل</label>
                            <input type="text" name="otp" class="form-control text-center" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-success btn-block w-100 py-2">
                            <i class="la la-check"></i> تأكيد الكود
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
