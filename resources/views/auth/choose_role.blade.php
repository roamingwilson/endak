@extends('layouts.home')
@section('title', 'اختيار نوع الحساب')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="la la-users"></i> اختيار نوع الحساب</h4>
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
                    <form method="POST" action="{{ route('choose.role.save') }}">
                        @csrf
                        <div class="form-group mb-4 text-center">
                            <button type="submit" name="role" value="client" class="btn btn-info btn-lg mx-2 px-4 py-2">
                                <i class="la la-user"></i> عميل
                                <div style="font-size: 0.9em;">يبحث عن خدمات</div>
                            </button>
                            <button type="submit" name="role" value="provider" class="btn btn-warning btn-lg mx-2 px-4 py-2">
                                <i class="la la-briefcase"></i> مزود خدمة
                                <div style="font-size: 0.9em;">يقدم خدمات للآخرين</div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
