@extends('layouts.home')
@section('title', 'تفعيل الحساب')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="la la-phone"></i> تفعيل الحساب برقم الهاتف</h4>
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-info text-center">
                            {{ session('message') }}
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
                    <form method="POST" action="{{ route('postActivatePhone') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <label><i class="la la-phone"></i> رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control text-center" required autofocus placeholder="أدخل رقم هاتفك">
                        </div>
                        <button type="submit" class="btn btn-success btn-block w-100 py-2">
                            <i class="la la-check"></i> إرسال رمز التفعيل
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
