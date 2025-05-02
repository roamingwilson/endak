@extends('layouts.dashboard.dashboard')
<?php $lang = config('app.locale'); ?>
@section('content')

<h2 class="page-title">{{ $lang == 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}</h2>

<div class="order-summary">
    <p><strong>{{ $lang == 'ar' ? 'رقم الطلب' : 'Order ID' }}:</strong> {{ $order->id }}</p>
    <p><strong>{{ $lang == 'ar' ? 'اسم العميل' : 'Customer' }}:</strong>{{ $order->user->first_name . ' ' . $order->user->last_name }}</p>
    <p><strong>{{ $lang == 'ar' ? 'رقم الجوال' : 'Phone' }}:</strong>{{ $order->user->phone }}</p>
    <p><strong>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}:</strong> {{ $order->status }}</p>
</div>

<hr>

<h3>{{ $lang == 'ar' ? 'المنتجات داخل الطلب' : 'Order Items' }}</h3>
<div class="products-grid">
    @foreach ($order->items as $item)
        <div class="product-card">
            <h4>{{ $lang == 'ar' ? 'اسم المنتج' : 'Product name' }}: {{ $item->product->title ?? '—' }}</h4>
            <p>{{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}: {{ $item->quantity }}</p>
            <p>{{ $lang == 'ar' ? 'السعر' : 'Price' }}: {{ $item->price }} {{ $lang == 'ar' ? 'جنيه' : 'EGP' }}</p>
        </div>
    @endforeach
</div>

<form action="{{ route('admin.pro_orders.updateStatus', $order->id) }}" method="POST" class="status-form">
    @csrf
    <label for="status">{{ $lang == 'ar' ? 'تغيير الحالة:' : 'Change Status:' }}</label>
    <select name="status" required>
        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ $lang == 'ar' ? 'مكتمل' : 'Completed' }}</option>
        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ $lang == 'ar' ? 'معلق' : 'Pending' }}</option>
        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ $lang == 'ar' ? 'ملغي' : 'Cancelled' }}</option>
    </select>

    <button type="submit">{{ $lang == 'ar' ? 'تحديث الحالة' : 'Update Status' }}</button>

    @if ($order->status !== 'cancelled')
        <button type="submit" name="status" value="cancelled" style="margin-top: 10px; background-color: var(--danger);">
            {{ $lang == 'ar' ? 'إلغاء الطلب' : 'Cancel Order' }}
        </button>
    @endif
</form>

@endsection

@section('css')
<style>
:root {
    --primary: #4f46e5;
    --bg-light: #f9fafb;
    --text-dark: #111827;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
}

body {
    font-family: 'Cairo', sans-serif;
    background-color: var(--bg-light);
}

.page-title {
    font-size: 30px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 40px;
    color: var(--primary);
}

.order-summary {
    background-color: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    border: 1px solid var(--border-color);
}

.order-summary p {
    font-size: 16px;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.product-card {
    background-color: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.04);
    border: 1px solid var(--border-color);
    transition: transform 0.2s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card h4 {
    color: var(--primary);
    margin-bottom: 12px;
    font-size: 18px;
}

.product-card p {
    color: var(--text-muted);
    font-size: 15px;
}

.status-form {
    max-width: 400px;
    background-color: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    margin: 0 auto;
}

.status-form label {
    display: block;
    font-size: 16px;
    margin-bottom: 10px;
    color: var(--text-dark);
}

.status-form select,
.status-form button {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border-radius: 8px;
    margin-top: 10px;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.status-form button {
    background-color: var(--success);
    color: white;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.status-form button:hover {
    background-color: #059669;
}
</style>
@endsection

