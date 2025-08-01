@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'تفاصيل مقدم الخدمة': "Service Provider Details" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'تفاصيل مقدم الخدمة': "Service Provider Details" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- معلومات مقدم الخدمة -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'معلومات مقدم الخدمة': "Service Provider Information" }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.service_management.providers') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ ($lang == 'ar')? 'العودة': "Back" }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'رقم المستخدم': "User ID" }}</th>
                                    <td>#{{ $provider->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الاسم الأول': "First Name" }}</th>
                                    <td>{{ $provider->first_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الاسم الأخير': "Last Name" }}</th>
                                    <td>{{ $provider->last_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'البريد الإلكتروني': "Email" }}</th>
                                    <td>{{ $provider->email ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'رقم الهاتف': "Phone" }}</th>
                                    <td>{{ $provider->phone ?? 'غير محدد' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'المدينة': "City" }}</th>
                                    <td>{{ $provider->city ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'المحافظة': "Governorate" }}</th>
                                    <td>{{ $provider->governement ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'تاريخ التسجيل': "Registration Date" }}</th>
                                    <td>{{ $provider->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'آخر تحديث': "Last Updated" }}</th>
                                    <td>{{ $provider->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <td>
                                        @if($provider->status == 1)
                                            <span class="badge badge-success">{{ ($lang == 'ar')? 'نشط': "Active" }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ ($lang == 'ar')? 'غير نشط': "Inactive" }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- الأقسام المرتبطة -->
                    @if($provider->userDepartments->count() > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>{{ ($lang == 'ar')? 'الأقسام المرتبطة': "Associated Departments" }}</h5>
                            <div class="row">
                                @foreach($provider->userDepartments as $dept)
                                <div class="col-md-4 mb-2">
                                    <span class="badge badge-info p-2">
                                        {{ ($lang == 'ar')? $dept->commentable->name_ar : $dept->commentable->name_en }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- إحصائيات سريعة -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'إحصائيات سريعة': "Quick Statistics" }}</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="info-box bg-info">
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ ($lang == 'ar')? 'الخدمات': "Services" }}</span>
                                    <span class="info-box-number">{{ $provider->services->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-success">
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ ($lang == 'ar')? 'الطلبات': "Orders" }}</span>
                                    <span class="info-box-number">{{ $provider->orders->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الخدمات المقدمة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الخدمات المقدمة': "Provided Services" }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الخدمة': "Service" }}</th>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <th>{{ ($lang == 'ar')? 'التاريخ': "Date" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($provider->services as $service)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.service_management.show_service', $service->id) }}">
                                            {{ ($lang == 'ar')? $service->department->name_ar : $service->department->name_en }}
                                        </a>
                                    </td>
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
                                    <td>{{ $service->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ ($lang == 'ar')? 'لا توجد خدمات': "No services found" }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- الطلبات المقدمة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الطلبات المقدمة': "Provided Orders" }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الطلب': "Order" }}</th>
                                    <th>{{ ($lang == 'ar')? 'العميل': "Customer" }}</th>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($provider->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.service_management.show_order', $order->id) }}">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>{{ $order->user->first_name ?? 'غير محدد' }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning">{{ ($lang == 'ar')? 'معلق': "Pending" }}</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-success">{{ ($lang == 'ar')? 'مكتمل': "Completed" }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ($lang == 'ar')? 'ملغي': "Cancelled" }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ ($lang == 'ar')? 'لا توجد طلبات': "No orders found" }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
