@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'مقدمو الخدمات': "Service Providers" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'مقدمو الخدمات': "Service Providers" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- فلاتر البحث -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ ($lang == 'ar')? 'فلاتر البحث': "Search Filters" }}</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.service_management.providers') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'المدينة': "City" }}</label>
                            <input type="text" name="city" class="form-control" value="{{ request('city') }}" placeholder="{{ ($lang == 'ar')? 'ادخل المدينة': "Enter city" }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'القسم': "Department" }}</label>
                            <select name="department_id" class="form-control">
                                <option value="">{{ ($lang == 'ar')? 'جميع الأقسام': "All Departments" }}</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ ($lang == 'ar')? $department->name_ar : $department->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ ($lang == 'ar')? 'بحث': "Search" }}
                                </button>
                                <a href="{{ route('admin.service_management.providers') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ ($lang == 'ar')? 'إعادة تعيين': "Reset" }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- قائمة مقدمي الخدمات -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ ($lang == 'ar')? 'قائمة مقدمي الخدمات': "Service Providers List" }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.service_management.dashboard') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-arrow-left"></i> {{ ($lang == 'ar')? 'العودة للوحة التحكم': "Back to Dashboard" }}
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ ($lang == 'ar')? 'الاسم': "Name" }}</th>
                            <th>{{ ($lang == 'ar')? 'البريد الإلكتروني': "Email" }}</th>
                            <th>{{ ($lang == 'ar')? 'رقم الهاتف': "Phone" }}</th>
                            <th>{{ ($lang == 'ar')? 'المدينة': "City" }}</th>
                            <th>{{ ($lang == 'ar')? 'الأقسام': "Departments" }}</th>
                            <th>{{ ($lang == 'ar')? 'عدد الخدمات': "Services Count" }}</th>
                            <th>{{ ($lang == 'ar')? 'عدد الطلبات': "Orders Count" }}</th>
                            <th>{{ ($lang == 'ar')? 'الإجراءات': "Actions" }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($providers as $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>{{ $provider->first_name ?? 'غير محدد' }}</td>
                            <td>{{ $provider->email ?? 'غير محدد' }}</td>
                            <td>{{ $provider->phone ?? 'غير محدد' }}</td>
                            <td>{{ $provider->city ?? 'غير محدد' }}</td>
                            <td>
                                @if($provider->userDepartments->count() > 0)
                                    @foreach($provider->userDepartments->take(2) as $dept)
                                        <span class="badge badge-info">
                                            {{ ($lang == 'ar')? $dept->commentable->name_ar : $dept->commentable->name_en }}
                                        </span>
                                    @endforeach
                                    @if($provider->userDepartments->count() > 2)
                                        <span class="badge badge-secondary">+{{ $provider->userDepartments->count() - 2 }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ ($lang == 'ar')? 'لا توجد أقسام': "No departments" }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $servicesCount = \App\Models\Services::where('provider_id', $provider->id)->count();
                                @endphp
                                <span class="badge badge-primary">{{ $servicesCount }}</span>
                            </td>
                            <td>
                                @php
                                    $ordersCount = \App\Models\GeneralOrder::where('service_provider_id', $provider->id)->count();
                                @endphp
                                <span class="badge badge-success">{{ $ordersCount }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.service_management.show_provider', $provider->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> {{ ($lang == 'ar')? 'عرض': "View" }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ ($lang == 'ar')? 'لا يوجد مقدمي خدمات': "No service providers found" }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $providers->links() }}
        </div>
    </div>
</div>
@endsection
