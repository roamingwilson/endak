@extends('layouts.dashboard.dashboard')

<?php $lang = config('app.locale'); ?>

@section('content')

<h2 class="page-title">{{ $lang == 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}</h2>

<div class="order-summary">
    <p><strong>{{ $lang == 'ar' ? 'رقم الطلب' : 'Order ID' }}:</strong> {{ $order->id }}</p>
    <p><strong>{{ $lang == 'ar' ? 'اسم العميل' : 'Customer' }}:</strong> {{ $order->user->first_name . ' ' . $order->user->last_name }}</p>
    <p><strong>{{ $lang == 'ar' ? 'رقم الجوال' : 'Phone' }}:</strong> {{ $order->user->phone }}</p>

    <p><strong>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}:</strong>
        @if($order->status == 'pending')
        {{ $lang == 'ar' ? 'معلق' : 'Pending' }}
        @elseif ($order->status == 'completed')
        {{ $lang == 'ar' ? 'مكتمل' : 'Completed' }}
        @else
        {{ $lang == 'ar' ? 'ملغي' : 'Cancelled' }}
        @endif




    </p>
    <p><strong>{{ $lang == 'ar' ? 'الاجمالي' : 'Total' }}:</strong> {{ $order->total }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</p>
</div>
<div class="order-total">
    <h4>: <span>{{ $order->total }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</span></h4>
</div>
<hr>
<div class="page-title">
    <h3>{{ $lang == 'ar' ? 'المنتجات داخل الطلب' : 'Order Items' }}</h3>

</div>



<div class="table-wrapper">
    <table class="order-items-table">
        <thead>
            <tr>
                <th>{{ $lang == 'ar' ? 'صورة المنتج' : 'Image' }}</th>
                <th>{{ $lang == 'ar' ? 'اسم المنتج' : 'Product Name' }}</th>
                <th>{{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}</th>
                <th>{{ $lang == 'ar' ? 'السعر' : 'Price' }}</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        @if ($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="Product Image" class="product-img">
                        @else
                            <span class="no-img">—</span>
                        @endif
                    </td>
                    <td>{{ $item->product->title ?? '—' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }} {{ $lang == 'ar' ? 'جنيه' : 'EGP' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>




@endsection

@section('css')
<style>
    <style>
.table-title {
    font-size: 22px;
    color: var(--primary);
    font-weight: bold;
    margin-bottom: 15px;
    text-align: center;
}

.table-wrapper {
    overflow-x: auto;
    background-color: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 40px;
    border: 1px solid var(--border-color);
}

.order-items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
    color: var(--text-dark);
    text-align: center;
}

.order-items-table thead {
    background-color: var(--primary);
    color: white;
}

.order-items-table th,
.order-items-table td {
    padding: 14px 12px;
    border: 1px solid var(--border-color);
}

.order-items-table tbody tr:hover {
    background-color: var(--bg-light);
    transition: background-color 0.3s ease;
}

.product-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.no-img {
    color: var(--text-muted);
    font-style: italic;
}


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

.table-container {
    overflow-x: auto;
    margin-bottom: 30px;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.order-table th,
.order-table td {
    padding: 14px 18px;
    text-align: {{ $lang == 'ar' ? 'right' : 'left' }};
    border-bottom: 1px solid var(--border-color);
    font-size: 15px;
}

.order-table th {
    background-color: var(--bg-light);
    color: var(--text-dark);
    font-weight: 600;
}

.order-table tr:hover {
    background-color: #f3f4f6;
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
