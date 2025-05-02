@extends('layouts.home')
<?php $lang = config('app.locale'); ?>
@section('content')
    <div class="orders-container">
        <h1 class="orders-title">   {{ $lang == 'en' ? 'Orders'  : ' الطلبات ' }} </h1>

        @foreach ($orders as $order)
            <div class="order-item card shadow-lg mb-4">
                <div class="card-body">
                    <h3 class="order-id">   {{ $lang == 'en' ? ' Order`s Number'  : '  رقم الطلب  ' }}    : {{ $order->id }}</h3>
                    <p class="order-status"> {{ $lang == 'en' ? 'Status'  : ' الحالة ' }}: <span class="status {{ strtolower($order->status) }}">{{ $order->status }}</span></p>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">     {{ $lang == 'en' ? ' details'  : '  عرض التفاصيل  ' }}   </a>
                </div>
            </div>
        @endforeach
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

</style>
@endsection
