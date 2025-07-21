@extends('layouts.home')

<?php $lang = config('app.locale'); ?>

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block mb-2" style="font-size:2.2rem;color:#ff9800;"><i class="fas fa-plus-circle"></i></span>
                        <h3 class="fw-bold mb-1">{{ $lang == 'ar' ? 'إضافة منتج جديد للطلب' : 'Add New Product to Order' }}</h3>
                        <p class="text-muted mb-0">{{ $lang == 'ar' ? 'اختر المنتج وأدخل الكمية والسعر لإضافته للطلب.' : 'Select the product, enter quantity and price to add it to the order.' }}</p>
                    </div>
                    <form action="{{ route('pro_order_items.store', $orderId) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="inds_product_id" class="form-label fw-semibold"><i class="fas fa-box-open text-primary me-1"></i> {{ $lang == 'ar' ? 'اختر المنتج' : 'Select Product' }}</label>
                            <select name="inds_product_id" id="inds_product_id" class="form-select rounded-pill">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-semibold"><i class="fas fa-sort-numeric-up text-success me-1"></i> {{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}</label>
                            <input type="number" name="quantity" id="quantity" required min="1" class="form-control rounded-pill">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="form-label fw-semibold"><i class="fas fa-money-bill-wave text-warning me-1"></i> {{ $lang == 'ar' ? 'السعر' : 'Price' }}</label>
                            <input type="number" name="price" id="price" required class="form-control rounded-pill">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold">
                                <i class="fas fa-plus"></i> {{ $lang == 'ar' ? 'إضافة' : 'Add' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

