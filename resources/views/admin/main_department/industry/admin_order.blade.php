@extends('layouts.dashboard.dashboard')
<?php $lang = config('app.locale'); ?>
@section('content')
<h1 class="page-title">{{ $lang == 'ar' ? 'إدارة الطلبات' : 'Manage Orders' }}</h1>

<div class="orders-container">
    @foreach ($orders as $order)
        <div class="order-card">
            <h3>{{ $lang == 'ar' ? 'رقم الطلب' : 'Order ID' }}: {{ $order->id }}</h3>
            <p>{{ $lang == 'ar' ? 'العميل' : 'Customer' }}: {{ $order->user->first_name . ' ' . $order->user->last_name }}</p>
            <p>{{ $lang == 'ar' ? 'رقم الجوال' : 'Phone' }}:{{ $order->user->phone }}</p>
            <p>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}: {{ $order->status }}</p>

            <a href="{{ route('admin.pro_orders.show', $order->id) }}" class="details-btn">
                {{ $lang == 'ar' ? 'عرض التفاصيل' : 'View Details' }}
            </a>
        </div>
    @endforeach
</div>
@endsection

@section('css')
<style>
.page-title {
    text-align: center;
    font-size: 28px;
    margin-bottom: 30px;
    color: #2c3e50;
}

.orders-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.order-card {
    background: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: 0.3s ease;
}

.order-card:hover {
    transform: translateY(-5px);
}

.order-card h3 {
    color: #2980b9;
}

.order-card p {
    color: #555;
    margin-bottom: 8px;
}

.details-btn {
    display: inline-block;
    background-color: #3498db;
    color: #fff;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s;
}

.details-btn:hover {
    background-color: #2980b9;
}
</style>
@endsection
