@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale');
    $user = auth()->user();
    ?>
    {{ $lang == 'ar' ? 'المشاريع' : 'Projects' }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <style>
        /* تنسيق الـ Container */
.container {
    margin-top: 50px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* تنسيق العنوان */
h2 {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
}

/* تنسيق بطاقة المشروع (Card) */
.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

/* تنسيق قائمة المعلومات */
.list-group-item {
    padding: 10px;
    border: none;
    font-size: 1.1rem;
}

/* تنسيق البطونات */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

/* تنسيق العناصر داخل الـ Section */
.section {
    padding: 50px 0;
    background-color: #f8f9fa;
}

/* تنسيق الـ Cards داخل الـ Section */
.card-custom {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

/* تنسيق الصورة داخل الـ Card */
.card-custom img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-height: 200px;
    border-bottom: 2px solid #f0f0f0;
}

/* تنسيق النصوص داخل الـ Card */
.card-custom .card-body {
    padding: 15px;
    text-align: center;
}

/* تنسيق العناوين داخل الـ Card */
.card-custom .card-body h5 {
    font-size: 1.2rem;
    color: #333;
    font-weight: bold;
    margin-bottom: 10px;
}

/* تنسيق النصوص داخل الـ Card */
.card-custom .card-body p {
    color: #555;
    font-size: 1rem;
    line-height: 1.5;
}

/* تحسين التباعد بين الـ Cards في الشاشات الصغيرة */
@media (max-width: 1024px) {
    .cards-container {
        grid-template-columns: repeat(2, 1fr); /* عمودين في الشاشات المتوسطة */
    }
}

@media (max-width: 768px) {
    .cards-container {
        grid-template-columns: 1fr; /* عمود واحد في الشاشات الصغيرة */
    }
}
@media (max-width: 768px) {
    body {
        padding-top: 90px; /* عشان الـ navbar ما يغطيش الصفحة */
    }
}

@media (min-width: 769px) {
    body {
        padding-top: 70px; /* أو حسب ارتفاع الـ navbar */
    }
}

    </style>
@endsection



@section('content')
@php
    $rating = \App\Models\Rating::where('order_id', $order->id)->first();
@endphp
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2" style="color: #ff9800;"><i class="fas fa-file-alt me-2"></i>{{ $lang == 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}</h2>
    </div>
    <div class="card shadow border-0 rounded-4 p-4 mb-4">
        <h4 class="mb-4 text-primary"><i class="fas fa-info-circle me-2"></i>{{ $lang == 'ar' ? 'معلومات الطلب' : 'Order Info' }}</h4>
        <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item"><i class="fas fa-hashtag text-secondary me-2"></i><strong>{{ $lang == 'ar' ? 'رقم الطلب' : 'Order Number' }}:</strong> {{ $order->id }}</li>
            <li class="list-group-item"><i class="fas fa-cogs text-warning me-2"></i><strong>{{ $lang == 'ar' ? 'الخدمة' : 'Service' }}:</strong> {{ $order->service->type }}</li>
            <li class="list-group-item"><i class="fas fa-clipboard-check text-info me-2"></i><strong>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}:</strong> <span class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $order->status == 'completed' ? ($lang == 'ar' ? 'مكتمل' : 'Completed') : ($lang == 'ar' ? 'قيد التنفيذ' : ucfirst($order->status)) }}</span></li>
            <li class="list-group-item"><i class="fas fa-user text-primary me-2"></i><strong>{{ $lang == 'ar' ? 'المزود' : 'Provider' }}:</strong> {{ $order->user->first_name ?? '' }} {{ $order->user->last_name ?? '' }}</li>
            @if(isset($order->service->departments) && $order->service->departments)
                <li class="list-group-item"><i class="fas fa-building text-success me-2"></i><strong>{{ $lang == 'ar' ? 'القسم الرئيسي' : 'Main Department' }}:</strong> {{ $lang == 'ar' ? $order->service->departments->name_ar : $order->service->departments->name_en }}</li>
            @endif
            @if(isset($order->service->subDepartment) && $order->service->subDepartment)
                <li class="list-group-item"><i class="fas fa-sitemap text-info me-2"></i><strong>{{ $lang == 'ar' ? 'القسم الفرعي' : 'Sub-Department' }}:</strong> {{ $lang == 'ar' ? $order->service->subDepartment->name_ar : $order->service->subDepartment->name_en }}</li>
                            @endif
            @if(!empty($order->service->city) || !empty($order->service->from_city))
                <li class="list-group-item"><i class="fas fa-map-marker-alt text-danger me-2"></i><strong>{{ $lang == 'ar' ? 'المدينة' : 'City' }}:</strong> {{ $order->service->city ?? $order->service->from_city }}</li>
                        @endif
            @if(!empty($order->service->neighborhood) || !empty($order->service->from_neighborhood))
                <li class="list-group-item"><i class="fas fa-map-pin text-danger me-2"></i><strong>{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }}:</strong> {{ $order->service->neighborhood ?? $order->service->from_neighborhood }}</li>
                    @endif
            @if(!empty($order->service->to_city))
                <li class="list-group-item"><i class="fas fa-map-marker-alt text-secondary me-2"></i><strong>{{ $lang == 'ar' ? 'المدينة (إلى)' : 'To City' }}:</strong> {{ $order->service->to_city }}</li>
                    @endif
            @if(!empty($order->service->to_neighborhood))
                <li class="list-group-item"><i class="fas fa-map-pin text-secondary me-2"></i><strong>{{ $lang == 'ar' ? 'الحي (إلى)' : 'To Neighborhood' }}:</strong> {{ $order->service->to_neighborhood }}</li>
                    @endif
            @if(!empty($order->service->location))
                <li class="list-group-item"><i class="fas fa-location-arrow text-info me-2"></i><strong>{{ $lang == 'ar' ? 'الموقع' : 'Location' }}:</strong> {{ $order->service->location }}</li>
                    @endif
            @if(!empty($order->service->price))
                <li class="list-group-item"><i class="fas fa-money-bill-wave text-success me-2"></i><strong>{{ $lang == 'ar' ? 'السعر' : 'Price' }}:</strong> {{ $order->service->price }}</li>
                    @endif
            @if(!empty($order->service->date))
                <li class="list-group-item"><i class="fas fa-calendar-alt text-primary me-2"></i><strong>{{ $lang == 'ar' ? 'التاريخ' : 'Date' }}:</strong> {{ $order->service->date }}</li>
                    @endif
            @if(!empty($order->service->time))
                <li class="list-group-item"><i class="fas fa-clock text-primary me-2"></i><strong>{{ $lang == 'ar' ? 'الوقت' : 'Time' }}:</strong> {{ $order->service->time }}</li>
                    @endif
            @if(!empty($order->service->number_of_days_of_warranty))
                <li class="list-group-item"><i class="fas fa-shield-alt text-success me-2"></i><strong>{{ $lang == 'ar' ? 'عدد أيام الضمان' : 'Warranty Days' }}:</strong> {{ $order->service->number_of_days_of_warranty }}</li>
                    @endif
            @if(!empty($order->service->notes))
                <li class="list-group-item"><i class="fas fa-sticky-note text-secondary me-2"></i><strong>{{ $lang == 'ar' ? 'ملاحظات' : 'Notes' }}:</strong> {{ $order->service->notes }}</li>
                    @endif
        </ul>
        @if(!empty($order->service->custom_fields) && is_array($order->service->custom_fields))
            <h4 class="mt-4 mb-3 text-info"><i class="fas fa-list-alt me-2"></i>{{ $lang == 'ar' ? 'تفاصيل إضافية' : 'Custom Fields' }}</h4>
            <ul class="list-group list-group-flush mb-3">
                @foreach($order->service->custom_fields as $key => $value)
                    @if(is_array($value) && isset($value[0]) && is_array($value[0]))
                        <li class="list-group-item">
                            <strong>{{ $lang == 'ar' ? $key : ucfirst($key) }}:</strong>
                            <ul class="mb-0">
                                @foreach($value as $instance)
                                    <li>
                                        @foreach($instance as $fieldName => $fieldValue)
                                            @if(is_array($fieldValue))
                                                @foreach($fieldValue as $img)
                                                    <img src="{{ asset('storage/' . (is_object($img) && isset($img->path) ? $img->path : $img)) }}" alt="صورة" style="max-width:80px; margin:3px; border-radius:6px;">
                                                @endforeach
                                            @else
                                                <span class="badge bg-light text-dark mx-1">{{ $fieldName }}: {{ $fieldValue }}</span>
                    @endif
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @elseif(is_array($value))
                        <li class="list-group-item">
                            <strong>{{ $lang == 'ar' ? $key : ucfirst($key) }}:</strong>
                            @foreach($value as $img)
                                <img src="{{ asset('storage/' . (is_object($img) && isset($img->path) ? $img->path : $img)) }}" alt="صورة" style="max-width:80px; margin:3px; border-radius:6px;">
                            @endforeach
                        </li>
                    @else
                        <li class="list-group-item">
                            <strong>{{ $lang == 'ar' ? $key : ucfirst($key) }}:</strong> {{ $value }}
                        </li>
                    @endif
                @endforeach
            </ul>
                    @endif
        <div class="d-flex flex-wrap gap-3 mt-4">
            @if ($user->id == $order->user_id)
                @if ($order->status == 'pending')
                    <a href="{{ route('accept_project', $order->id) }}" class="btn btn-primary btn-lg rounded-pill px-4">
                        <i class="fas fa-check-circle me-2"></i>{{ ($lang == 'ar') ? 'أستلام المشروع' : 'Confirm Project' }}
                    </a>
                @elseif($order->status == 'completed' && !$rating)
                    <a href="{{ route('web.add_rate', $order->id) }}" class="btn btn-success btn-lg rounded-pill px-4">
                        <i class="fas fa-star me-2"></i>{{ $lang == 'ar' ? 'تقييم العمل' : 'Rate Work' }}
                    </a>
                @elseif($order->status == 'completed' && $rating)
                    <span class="text-success fw-bold fs-5"><i class="fas fa-check-double me-2"></i>{{ $lang == 'ar' ? 'تم التقييم مسبقًا' : 'Already Rated' }}</span>
                    @endif
                    @endif
        </div>
    </div>
</div>

@endsection




