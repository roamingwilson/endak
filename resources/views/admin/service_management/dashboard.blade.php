@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'لوحة تحكم إدارة الخدمات': "Service Management Dashboard" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'لوحة تحكم إدارة الخدمات': "Service Management Dashboard" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- إحصائيات سريعة -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_services'] }}</h3>
                    <p>{{ ($lang == 'ar')? 'إجمالي الخدمات': "Total Services" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('admin.service_management.services') }}" class="small-box-footer">
                    {{ ($lang == 'ar')? 'عرض التفاصيل': "View Details" }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['open_services'] }}</h3>
                    <p>{{ ($lang == 'ar')? 'الخدمات المفتوحة': "Open Services" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('admin.service_management.services', ['status' => 'open']) }}" class="small-box-footer">
                    {{ ($lang == 'ar')? 'عرض التفاصيل': "View Details" }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['total_orders'] }}</h3>
                    <p>{{ ($lang == 'ar')? 'إجمالي الطلبات': "Total Orders" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.service_management.orders') }}" class="small-box-footer">
                    {{ ($lang == 'ar')? 'عرض التفاصيل': "View Details" }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['pending_orders'] }}</h3>
                    <p>{{ ($lang == 'ar')? 'الطلبات المعلقة': "Pending Orders" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <a href="{{ route('admin.service_management.orders', ['status' => 'pending']) }}" class="small-box-footer">
                    {{ ($lang == 'ar')? 'عرض التفاصيل': "View Details" }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- روابط سريعة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'روابط سريعة': "Quick Links" }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.services') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-tools"></i> {{ ($lang == 'ar')? 'إدارة الخدمات': "Manage Services" }}
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.orders') }}" class="btn btn-success btn-block">
                                <i class="fas fa-shopping-cart"></i> {{ ($lang == 'ar')? 'إدارة الطلبات': "Manage Orders" }}
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.providers') }}" class="btn btn-info btn-block">
                                <i class="fas fa-users"></i> {{ ($lang == 'ar')? 'مقدمو الخدمات': "Service Providers" }}
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.statistics') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-chart-bar"></i> {{ ($lang == 'ar')? 'الإحصائيات': "Statistics" }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- أحدث الخدمات -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'أحدث الخدمات': "Latest Services" }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.service_management.services') }}" class="btn btn-sm btn-primary">
                            {{ ($lang == 'ar')? 'عرض الكل': "View All" }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الخدمة': "Service" }}</th>
                                    <th>{{ ($lang == 'ar')? 'المستخدم': "User" }}</th>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <th>{{ ($lang == 'ar')? 'التاريخ': "Date" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_services as $service)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.service_management.show_service', $service->id) }}">
                                            {{ ($lang == 'ar')? $service->department->name_ar : $service->department->name_en }}
                                        </a>
                                    </td>
                                    <td>{{ $service->user->first_name ?? 'غير محدد' }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- أحدث الطلبات -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'أحدث الطلبات': "Latest Orders" }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.service_management.orders') }}" class="btn btn-sm btn-primary">
                            {{ ($lang == 'ar')? 'عرض الكل': "View All" }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الطلب': "Order" }}</th>
                                    <th>{{ ($lang == 'ar')? 'مقدم الخدمة': "Provider" }}</th>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <th>{{ ($lang == 'ar')? 'التاريخ': "Date" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.service_management.show_order', $order->id) }}">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>{{ $order->service_provider->first_name ?? 'غير محدد' }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning">{{ ($lang == 'ar')? 'معلق': "Pending" }}</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-success">{{ ($lang == 'ar')? 'مكتمل': "Completed" }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ($lang == 'ar')? 'ملغي': "Cancelled" }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
