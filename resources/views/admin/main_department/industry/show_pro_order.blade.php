@extends('layouts.home')
@section('title')
    {{ __('orders') }}
    <?php $lang = config('app.locale'); ?>
@endsection
@section('content')
<div class="container my-5">
    <div class="order-details">
        <h3 class="order-title">{{ $lang == 'ar' ? 'تفاصيل الطلب رقم' : 'Order Details' }} #{{ $order->id }}</h3>

        @foreach($order->items as $item)
        <div class="order-item card mb-4 shadow-lg">
            <div class="card-body">
                <h5 class="product-title">{{ $lang == 'ar' ? 'اسم المنتج' : 'Product Name' }}: {{ $item->product->title }}</h5>
                <p class="item-quantity">{{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}: <span>{{ $item->quantity }}</span></p>
                <p class="item-price">{{ $lang == 'ar' ? 'السعر' : 'Price' }}: <span>{{ $item->price }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</span></p>
            </div>
        </div>
        @endforeach

        <div class="order-total">
            <h4>{{ $lang == 'ar' ? 'الإجمالي' : 'Total' }}: <span>{{ $order->total }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</span></h4>
        </div>

    </div>
</div>
@endsection
@section('style')

<style>
    .order-details {
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.order-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
    border-bottom: 2px solid #0d6efd;
    padding-bottom: 10px;
}

.order-item {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.order-item:hover {
    transform: translateY(-5px);
}

.product-title {
    font-size: 20px;
    font-weight: 600;
    color: #007bff;
    margin-bottom: 10px;
}

.item-quantity, .item-price {
    font-size: 16px;
    color: #555;
}

.item-quantity span, .item-price span {
    font-weight: bold;
}

.order-total {
    text-align: center;
    margin-top: 30px;
}

.order-total h4 {
    font-size: 22px;
    color: #333;
}

.order-total span {
    font-weight: bold;
    color: #e91e63; /* Bold color for total */
}

.card-body {
    padding: 20px;
}

.card-body p {
    margin-bottom: 10px;
}

</style>
@endsection
