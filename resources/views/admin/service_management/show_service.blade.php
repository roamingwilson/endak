@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'تفاصيل الخدمة': "Service Details" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'تفاصيل الخدمة': "Service Details" }}
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- تفاصيل الخدمة -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'تفاصيل الخدمة': "Service Details" }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.service_management.services') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ ($lang == 'ar')? 'العودة': "Back" }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'رقم الخدمة': "Service ID" }}</th>
                                    <td>#{{ $service->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'القسم': "Department" }}</th>
                                    <td>{{ ($lang == 'ar')? $service->department->name_ar : $service->department->name_en }}</td>
                                </tr>
                                @if($service->subDepartment)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'القسم الفرعي': "Sub Department" }}</th>
                                    <td>{{ ($lang == 'ar')? $service->subDepartment->name_ar : $service->subDepartment->name_en }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'المدينة': "City" }}</th>
                                    <td>{{ $service->city ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحي': "Neighborhood" }}</th>
                                    <td>{{ $service->neighborhood ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
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
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'تاريخ الإنشاء': "Created Date" }}</th>
                                    <td>{{ $service->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'المستخدم': "User" }}</th>
                                    <td>{{ $service->user->first_name ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'مقدم الخدمة': "Service Provider" }}</th>
                                    <td>{{ $service->provider->first_name ?? 'غير محدد' }}</td>
                                </tr>
                                @if($service->price)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'السعر': "Price" }}</th>
                                    <td>{{ $service->price }} ريال</td>
                                </tr>
                                @endif
                                @if($service->quantity)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الكمية': "Quantity" }}</th>
                                    <td>{{ $service->quantity }}</td>
                                </tr>
                                @endif
                                @if($service->date)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'التاريخ المطلوب': "Required Date" }}</th>
                                    <td>{{ $service->date }}</td>
                                </tr>
                                @endif
                                @if($service->time)
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الوقت المطلوب': "Required Time" }}</th>
                                    <td>{{ $service->time }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($service->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>{{ ($lang == 'ar')? 'الملاحظات': "Notes" }}</h5>
                            <p>{{ $service->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- تفاصيل إضافية حسب نوع الخدمة -->
                    @if($service->type == 'maintenance')
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>{{ ($lang == 'ar')? 'تفاصيل الصيانة': "Maintenance Details" }}</h5>
                            <div class="row">
                                @if($service->model)
                                <div class="col-md-3">
                                    <strong>{{ ($lang == 'ar')? 'الموديل': "Model" }}:</strong> {{ $service->model }}
                                </div>
                                @endif
                                @if($service->year)
                                <div class="col-md-3">
                                    <strong>{{ ($lang == 'ar')? 'السنة': "Year" }}:</strong> {{ $service->year }}
                                </div>
                                @endif
                                @if($service->brand)
                                <div class="col-md-3">
                                    <strong>{{ ($lang == 'ar')? 'العلامة التجارية': "Brand" }}:</strong> {{ $service->brand }}
                                </div>
                                @endif
                                @if($service->car_type)
                                <div class="col-md-3">
                                    <strong>{{ ($lang == 'ar')? 'نوع السيارة': "Car Type" }}:</strong> {{ $service->car_type }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($service->type == 'transport')
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>{{ ($lang == 'ar')? 'تفاصيل النقل': "Transport Details" }}</h5>
                            <div class="row">
                                @if($service->from_city)
                                <div class="col-md-6">
                                    <strong>{{ ($lang == 'ar')? 'من مدينة': "From City" }}:</strong> {{ $service->from_city }}
                                </div>
                                @endif
                                @if($service->to_city)
                                <div class="col-md-6">
                                    <strong>{{ ($lang == 'ar')? 'إلى مدينة': "To City" }}:</strong> {{ $service->to_city }}
                                </div>
                                @endif
                                @if($service->from_neighborhood)
                                <div class="col-md-6">
                                    <strong>{{ ($lang == 'ar')? 'من حي': "From Neighborhood" }}:</strong> {{ $service->from_neighborhood }}
                                </div>
                                @endif
                                @if($service->to_neighborhood)
                                <div class="col-md-6">
                                    <strong>{{ ($lang == 'ar')? 'إلى حي': "To Neighborhood" }}:</strong> {{ $service->to_neighborhood }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- إجراءات سريعة -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'إجراءات سريعة': "Quick Actions" }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.service_management.update_service_status', $service->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>{{ ($lang == 'ar')? 'تغيير الحالة': "Change Status" }}</label>
                            <select name="status" class="form-control">
                                <option value="open" {{ $service->status == 'open' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مفتوح': "Open" }}</option>
                                <option value="pending" {{ $service->status == 'pending' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'معلق': "Pending" }}</option>
                                <option value="confirm" {{ $service->status == 'confirm' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مؤكد': "Confirmed" }}</option>
                                <option value="close" {{ $service->status == 'close' ? 'selected' : '' }}>{{ ($lang == 'ar')? 'مغلق': "Closed" }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ ($lang == 'ar')? 'تحديث الحالة': "Update Status" }}
                        </button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.service_management.delete_service', $service->id) }}" method="POST" onsubmit="return confirm('{{ ($lang == 'ar')? 'هل أنت متأكد من حذف هذه الخدمة؟': 'Are you sure you want to delete this service?' }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> {{ ($lang == 'ar')? 'حذف الخدمة': "Delete Service" }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- الطلبات المرتبطة -->
            @if($orders->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ ($lang == 'ar')? 'الطلبات المرتبطة': "Related Orders" }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ ($lang == 'ar')? 'الطلب': "Order" }}</th>
                                    <th>{{ ($lang == 'ar')? 'مقدم الخدمة': "Provider" }}</th>
                                    <th>{{ ($lang == 'ar')? 'الحالة': "Status" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
