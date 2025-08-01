@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'إحصائيات الخدمات': "Service Statistics" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'إحصائيات الخدمات': "Service Statistics" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- إحصائيات عامة -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $serviceStats->sum('count') }}</h3>
                    <p>{{ ($lang == 'ar')? 'إجمالي الخدمات': "Total Services" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $orderStats->sum('count') }}</h3>
                    <p>{{ ($lang == 'ar')? 'إجمالي الطلبات': "Total Orders" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $serviceStats->where('status', 'open')->first()->count ?? 0 }}</h3>
                    <p>{{ ($lang == 'ar')? 'الخدمات المفتوحة': "Open Services" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $orderStats->where('status', 'pending')->first()->count ?? 0 }}</h3>
                    <p>{{ ($lang == 'ar')? 'الطلبات المعلقة': "Pending Orders" }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- إحصائيات الخدمات حسب الحالة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الخدمات حسب الحالة': "Services by Status" }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <th>{{ ($lang == 'ar')? 'العدد': "Count" }}</th>
                                    <th>{{ ($lang == 'ar')? 'النسبة المئوية': "Percentage" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceStats as $stat)
                                <tr>
                                    <td>
                                        @if($stat->status == 'open')
                                            <span class="badge badge-warning">{{ ($lang == 'ar')? 'مفتوح': "Open" }}</span>
                                        @elseif($stat->status == 'pending')
                                            <span class="badge badge-info">{{ ($lang == 'ar')? 'معلق': "Pending" }}</span>
                                        @elseif($stat->status == 'confirm')
                                            <span class="badge badge-success">{{ ($lang == 'ar')? 'مؤكد': "Confirmed" }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ($lang == 'ar')? 'مغلق': "Closed" }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $stat->count }}</td>
                                    <td>{{ number_format(($stat->count / $serviceStats->sum('count')) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات الطلبات حسب الحالة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الطلبات حسب الحالة': "Orders by Status" }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                    <th>{{ ($lang == 'ar')? 'العدد': "Count" }}</th>
                                    <th>{{ ($lang == 'ar')? 'النسبة المئوية': "Percentage" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderStats as $stat)
                                <tr>
                                    <td>
                                        @if($stat->status == 'pending')
                                            <span class="badge badge-warning">{{ ($lang == 'ar')? 'معلق': "Pending" }}</span>
                                        @elseif($stat->status == 'completed')
                                            <span class="badge badge-success">{{ ($lang == 'ar')? 'مكتمل': "Completed" }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ($lang == 'ar')? 'ملغي': "Cancelled" }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $stat->count }}</td>
                                    <td>{{ number_format(($stat->count / $orderStats->sum('count')) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- إحصائيات الخدمات حسب القسم -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الخدمات حسب القسم': "Services by Department" }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'القسم': "Department" }}</th>
                                    <th>{{ ($lang == 'ar')? 'العدد': "Count" }}</th>
                                    <th>{{ ($lang == 'ar')? 'النسبة المئوية': "Percentage" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentStats as $stat)
                                <tr>
                                    <td>
                                        @if($stat->department)
                                            {{ ($lang == 'ar')? $stat->department->name_ar : $stat->department->name_en }}
                                        @else
                                            <span class="text-muted">{{ ($lang == 'ar')? 'قسم محذوف': "Deleted Department" }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $stat->count }}</td>
                                    <td>{{ number_format(($stat->count / $departmentStats->sum('count')) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات الخدمات حسب المدينة -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الخدمات حسب المدينة': "Services by City" }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'المدينة': "City" }}</th>
                                    <th>{{ ($lang == 'ar')? 'العدد': "Count" }}</th>
                                    <th>{{ ($lang == 'ar')? 'النسبة المئوية': "Percentage" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cityStats as $stat)
                                <tr>
                                    <td>{{ $stat->city }}</td>
                                    <td>{{ $stat->count }}</td>
                                    <td>{{ number_format(($stat->count / $cityStats->sum('count')) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- روابط سريعة -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'روابط سريعة': "Quick Links" }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.dashboard') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-tachometer-alt"></i> {{ ($lang == 'ar')? 'لوحة التحكم': "Dashboard" }}
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.services') }}" class="btn btn-info btn-block">
                                <i class="fas fa-tools"></i> {{ ($lang == 'ar')? 'إدارة الخدمات': "Manage Services" }}
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.orders') }}" class="btn btn-success btn-block">
                                <i class="fas fa-shopping-cart"></i> {{ ($lang == 'ar')? 'إدارة الطلبات': "Manage Orders" }}
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.service_management.providers') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-users"></i> {{ ($lang == 'ar')? 'مقدمو الخدمات': "Service Providers" }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
