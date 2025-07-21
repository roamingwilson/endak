@extends('layouts.home')
<?php $lang = config('app.locale'); ?>
@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0" style="color:#ff9800;"><i class="fas fa-clipboard-list me-2"></i>{{ $lang == 'en' ? 'Orders' : 'الطلبات' }}</h2>
            <span class="badge bg-primary fs-6">{{ count($orders) }} {{ $lang == 'en' ? 'Orders' : 'طلب' }}</span>
        </div>
        @if(count($orders) > 0)
            <div class="row g-4">
                @foreach ($orders as $order)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-lg border-0 rounded-4 h-100 order-item">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3 gap-3">
                                    <span class="badge bg-warning bg-opacity-10 text-warning fs-4 p-2 rounded-circle"><i class="fas fa-receipt"></i></span>
                                    <div>
                                        <h5 class="fw-bold mb-1" style="color:#007bff;">{{ $lang == 'en' ? 'Order #' : 'طلب رقم ' }}{{ $order->id }}</h5>
                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '-' }}</small>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-3">
                                    @if(isset($order->product))
                                        <li class="mb-2 d-flex align-items-center gap-2">
                                            <img src="{{ $order->product->image ? asset('storage/'.$order->product->image) : asset('images/default-product.png') }}" style="width:38px;height:38px;border-radius:8px;object-fit:cover;box-shadow:0 2px 8px #eee;">
                                            <span class="fw-semibold">{{ $order->product->title }}</span>
                                        </li>
                                        <li><i class="fas fa-tag text-info me-1"></i> <span class="fw-semibold">{{ $lang == 'en' ? 'Price:' : 'السعر:' }}</span> {{ $order->product->price }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }}</li>
                                        <li><i class="fas fa-boxes text-success me-1"></i> <span class="fw-semibold">{{ $lang == 'en' ? 'Quantity:' : 'الكمية:' }}</span> {{ $order->quantity ?? 1 }}</li>
                                        <li><i class="fas fa-money-bill-wave text-warning me-1"></i> <span class="fw-semibold">{{ $lang == 'en' ? 'Total:' : 'الإجمالي:' }}</span> {{ ($order->product->price * ($order->quantity ?? 1)) }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }}</li>
                                    @endif
                                    <li><i class="fas fa-info-circle text-secondary me-1"></i> <span class="fw-semibold">{{ $lang == 'en' ? 'Status:' : 'الحالة:' }}</span> <span class="status {{ strtolower($order->status) }}">{{ $order->status }}</span></li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mt-auto gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                        <i class="fe fe-eye"></i> {{ $lang == 'en' ? 'Details' : 'عرض التفاصيل' }}
                                    </a>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card shadow border-0 rounded-4 p-5 my-5 text-center">
                <div class="py-4">
                    <i class="fas fa-box-open fa-3x text-warning mb-3"></i>
                    <h4 class="fw-bold mb-2" style="color:#007bff;">{{ $lang == 'en' ? 'No orders yet.' : 'لا توجد طلبات بعد.' }}</h4>
                    <p class="text-muted mb-0">{{ $lang == 'en' ? 'Start shopping and place your first order.' : 'ابدأ التسوق واطلب منتجاتك الآن.' }}</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('style')
<style>
.orders-container {
    padding: 30px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.orders-title {
    font-size: 28px;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #0d6efd;
    padding-bottom: 10px;
}

.order-item {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.order-item:hover {
    transform: translateY(-5px);
}

.order-id {
    font-size: 22px;
    color: #007bff;
    margin-bottom: 10px;
}

.order-status {
    font-size: 18px;
    color: #333;
}

.order-status .status {
    font-weight: bold;
    color: #28a745; /* Green color for 'completed' or positive statuses */
}

.order-status .status.pending {
    color: #ffc107; /* Yellow color for pending orders */
}

.order-status .status.cancelled {
    color: #dc3545; /* Red color for cancelled orders */
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    border-radius: 30px;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    color: white;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
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
