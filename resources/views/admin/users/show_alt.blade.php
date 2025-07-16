@extends('layouts.dashboard.dashboard')
@section('title')
    {{ __('user.user') }} (عرض بديل)
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>بيانات المستخدم (عرض بديل)</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>الاسم الأول</th>
                    <td>{{ $userAlt->first_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الاسم الأخير</th>
                    <td>{{ $userAlt->last_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الدولة</th>
                    <td>{{ $userAlt->countryObj?->name_ar ?? '-' }}</td>
                </tr>
                <tr>
                    <th>المحافظة</th>
                    <td>{{ $userAlt->governementObj?->name_ar ?? '-' }}</td>
                </tr>
                <tr>
                    <th>رقم الهاتف</th>
                    <td>{{ $userAlt->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>الدور</th>
                    <td>{{ $userAlt->role_name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
