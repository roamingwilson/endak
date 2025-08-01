@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'إدارة الخدمات المطلوبة': "Manage Service Requests" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'إدارة الخدمات المطلوبة': "Manage Service Requests" }}
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
            <form method="GET" action="{{ route('admin.service_management.services') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'الحالة': "Status" }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ ($lang == 'ar')? 'جميع الحالات': "All Statuses" }}</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مفتوح': "Open" }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'معلق': "Pending" }}</option>
                                <option value="confirm" {{ request('status') == 'confirm' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مؤكد': "Confirmed" }}</option>
                                <option value="close" {{ request('status') == 'close' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مغلق': "Closed" }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'المدينة': "City" }}</label>
                            <input type="text" name="city" class="form-control" value="{{ request('city') }}" placeholder="{{ ($lang == 'ar')? 'ادخل المدينة': "Enter city" }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'من تاريخ': "From Date" }}</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'إلى تاريخ': "To Date" }}</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ ($lang == 'ar')? 'بحث': "Search" }}
                                </button>
                                <a href="{{ route('admin.service_management.services') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ ($lang == 'ar')? 'إعادة تعيين': "Reset" }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- قائمة الخدمات -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ ($lang == 'ar')? 'قائمة الخدمات المطلوبة': "Service Requests List" }}</h3>
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
                            <th>{{ ($lang == 'ar')? 'الخدمة': "Service" }}</th>
                            <th>{{ ($lang == 'ar')? 'المستخدم': "User" }}</th>
                            <th>{{ ($lang == 'ar')? 'المدينة': "City" }}</th>
                            <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                            <th>{{ ($lang == 'ar')? 'التاريخ': "Date" }}</th>
                            <th>{{ ($lang == 'ar')? 'الإجراءات': "Actions" }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                <a href="{{ route('admin.service_management.show_service', $service->id) }}">
                                    {{ ($lang == 'ar')? $service->department->name_ar : $service->department->name_en }}
                                    @if($service->subDepartment)
                                        - {{ ($lang == 'ar')? $service->subDepartment->name_ar : $service->subDepartment->name_en }}
                                    @endif
                                </a>
                            </td>
                            <td>{{ $service->user->first_name ?? 'غير محدد' }}</td>
                            <td>{{ $service->city ?? 'غير محدد' }}</td>
                            <td>
                                @if($service->status == 'open')
                                    <span class="badge badge-warning">{{ ($lang == 'ar')? 'مفتوح': "Open" }}</span>
                                @elseif($service->status == 'pending')
                                    <span class="badge badge-info">{{ ($lang == 'ar')? 'معلق': "Pending" }}</span>
                                @elseif($service->status == 'confirm')
                                    <span class="badge badge-success">{{ ($lang == 'ar')? 'مؤكد': "Confirmed" }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ ($lang == 'ar')? 'مغلق': "Closed" }}</span>
                                @endif
                            </td>
                            <td>{{ $service->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.service_management.show_service', $service->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('admin.service_management.update_service_status', $service->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="open">
                                            <button type="submit" class="dropdown-item">{{ ($lang == 'ar')? 'فتح': "Open" }}</button>
                                        </form>
                                        <form action="{{ route('admin.service_management.update_service_status', $service->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="dropdown-item">{{ ($lang == 'ar')? 'تعليق': "Pending" }}</button>
                                        </form>
                                        <form action="{{ route('admin.service_management.update_service_status', $service->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="confirm">
                                            <button type="submit" class="dropdown-item">{{ ($lang == 'ar')? 'تأكيد': "Confirm" }}</button>
                                        </form>
                                        <form action="{{ route('admin.service_management.update_service_status', $service->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="close">
                                            <button type="submit" class="dropdown-item">{{ ($lang == 'ar')? 'إغلاق': "Close" }}</button>
                                        </form>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('admin.service_management.delete_service', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ ($lang == 'ar')? 'هل أنت متأكد من حذف هذه الخدمة؟': 'Are you sure you want to delete this service?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">{{ ($lang == 'ar')? 'حذف': "Delete" }}</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ ($lang == 'ar')? 'لا توجد خدمات مطلوبة': "No service requests found" }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
