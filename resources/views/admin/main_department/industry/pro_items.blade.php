@extends('layouts.home')

@section('content')
    <h1>إضافة منتج جديد للطلب</h1>
    <form action="{{ route('pro_order_items.store', $orderId) }}" method="POST">
        @csrf
        <label for="inds_product_id">المنتج:</label>
        <select name="inds_product_id" id="inds_product_id">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->title }}</option>
            @endforeach
        </select>
        <label for="quantity">الكمية:</label>
        <input type="number" name="quantity" required min="1">
        <label for="price">السعر:</label>
        <input type="number" name="price" required>
        <button type="submit">إضافة</button>
    </form>
@endsection
