@extends('layouts.home')
@section('title')
    {{ __('orders') }}
    <?php $lang = config('app.locale'); ?>
@endsection
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="{{ route('pro_order_items.create', $order->id) }}" class="btn btn-success rounded-pill mb-4">
                <i class="fas fa-plus"></i> {{ $lang == 'ar' ? 'إضافة منتج للطلب' : 'Add Product to Order' }}
            </a>
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body py-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h3 class="fw-bold mb-0" style="color:#ff9800;"><i class="fas fa-file-invoice me-2"></i>{{ $lang == 'ar' ? 'تفاصيل الطلب رقم' : 'Order Details' }} #{{ $order->id }}</h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('product_orders.edit', $order->id) }}" class="btn btn-info rounded-pill px-3">
                                <i class="fas fa-edit"></i> {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                            </a>
                            <form action="{{ route('product_orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger rounded-pill px-3" onclick="return confirm('هل أنت متأكد من حذف الطلب؟')">
                                    <i class="fas fa-trash"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                </button>
                            </form>
                        </div>
                        <span class="badge bg-primary fs-6"><i class="far fa-calendar-alt me-1"></i>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-semibold">{{ $lang == 'ar' ? 'الحالة:' : 'Status:' }}</span>
                        <span class="badge px-3 py-2 rounded-pill {{ strtolower($order->status) == 'completed' ? 'bg-success' : (strtolower($order->status) == 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ $order->status }}</span>
                    </div>
                </div>
            </div>
            @php
                $total = 0;
            @endphp
            @foreach($order->items as $item)
                @php $total += $item->price * $item->quantity; @endphp
                <div class="card mb-4 shadow-sm border-0 rounded-4 order-item position-relative">
                    <div class="card-body d-flex flex-column flex-md-row align-items-center gap-4">
                        <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : asset('images/default-product.png') }}" style="width:70px;height:70px;border-radius:12px;object-fit:cover;box-shadow:0 2px 8px #eee;">
                        <div class="flex-grow-1 w-100">
                            <h5 class="fw-bold mb-2 text-primary d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-box-open me-1"></i>{{ $lang == 'ar' ? 'اسم المنتج' : 'Product Name' }}: {{ $item->product->title }}</span>
                                <form action="{{ route('pro_order_items.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill ms-2" onclick="return confirm('هل أنت متأكد من حذف المنتج من الطلب؟')">
                                        <i class="fas fa-trash"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                    </button>
                                </form>
                            </h5>
                            <div class="row g-2">
                                <div class="col-6 col-md-4">
                                    <span class="text-muted"><i class="fas fa-sort-numeric-up me-1"></i>{{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}:</span>
                                    <span class="fw-bold">{{ $item->quantity }}</span>
                                </div>
                                <div class="col-6 col-md-4">
                                    <span class="text-muted"><i class="fas fa-money-bill-wave me-1"></i>{{ $lang == 'ar' ? 'السعر' : 'Price' }}:</span>
                                    <span class="fw-bold">{{ $item->price }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</span>
                                </div>
                                <div class="col-12 col-md-4">
                                    <span class="text-muted"><i class="fas fa-calculator me-1"></i>{{ $lang == 'ar' ? 'الإجمالي الفرعي' : 'Subtotal' }}:</span>
                                    <span class="fw-bold text-success">{{ $item->price * $item->quantity }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</span>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
        @endforeach
            <div class="card shadow border-0 rounded-4 p-4 mt-4 order-total">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <h4 class="fw-bold mb-0" style="color:#e91e63;"><i class="fas fa-coins me-2"></i>{{ $lang == 'ar' ? 'الإجمالي الكلي' : 'Total' }}</h4>
                    <span class="fs-4 fw-bold text-success">{{ $total }} {{ $lang == 'ar' ? 'ريال' : 'SAR' }}</span>
                </div>
            </div>
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
} @media (max-width: 768px) {
    body {
        padding-top: 90px; /* عشان الـ navbar ما يغطيش الصفحة */
    }
}

@media (min-width: 769px) {
    body {
        padding-top: 70px;
         /* أو حسب ارتفاع الـ navbar */
    }
}

</style>
@endsection
