@extends('layouts.home')

<?php $lang = config('app.locale'); ?>

@section('content')
    <h1>{{ $lang == 'ar' ? 'إضافة منتج جديد للطلب' : 'Add New Product to Order' }}</h1>

    <form action="{{ route('pro_order_items.store', $orderId) }}" method="POST">
        @csrf

        <label for="inds_product_id">
            {{ $lang == 'ar' ? 'اختر المنتج:' : 'Select Product:' }}
        </label>
        <select name="inds_product_id" id="inds_product_id">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->title }}</option>
            @endforeach
        </select>

        <label for="quantity">
            {{ $lang == 'ar' ? 'الكمية:' : 'Quantity:' }}
        </label>
        <input type="number" name="quantity" required min="1">

        <label for="price">
            {{ $lang == 'ar' ? 'السعر:' : 'Price:' }}
        </label>
        <input type="number" name="price" required>

        <button type="submit">
            {{ $lang == 'ar' ? 'إضافة' : 'Add' }}
        </button>
    </form>
@endsection
