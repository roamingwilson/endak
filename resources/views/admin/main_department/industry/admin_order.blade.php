@extends('layouts.dashboard.dashboard')

@section('content')
    <h1>إدارة الطلبات</h1>
    @foreach ($orders as $order)
        <div class="order-item">
            <h3>رقم الطلب: {{ $order->id }}</h3>
            <p>العميل: {{ $order->user->name }}</p>
            <p>الحالة: {{ $order->status }}</p>
            <form action="{{ route('admin.pro_orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('POST')
                <select name="status">
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
                <button type="submit">تغيير الحالة</button>
            </form>
            <form action="{{ route('admin.pro_orders.destroy', $order->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">حذف الطلب</button>
            </form>
        </div>
    @endforeach
@endsection
@section('css')
<style>
    h1 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 32px;
        color: #333;
    }

    .orders-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .order-item {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: 0.3s ease;
    }

    .order-item:hover {
        transform: translateY(-5px);
    }

    .order-item h3 {
        margin-bottom: 10px;
        color: #007BFF;
    }

    .order-item p {
        margin-bottom: 8px;
        color: #555;
    }

    .order-item form {
        margin-top: 10px;
    }

    .order-item select,
    .order-item button {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .order-item button {
        background-color: #28a745;
        color: white;
        cursor: pointer;
        border: none;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .order-item button:hover {
        background-color: #218838;
    }

    .order-item form:last-of-type button {
        background-color: #dc3545;
    }

    .order-item form:last-of-type button:hover {
        background-color: #c82333;
    }
</style>
@endsection
