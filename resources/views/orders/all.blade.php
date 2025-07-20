@extends('layouts.front_office.header')
@section('content')
<div class="container my-4">
    <h2>كل الطلبات</h2>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="user_id" class="form-control" placeholder="رقم المستخدم" value="{{ request('user_id') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>معلق</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>نشط</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>مكتمل</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">فلتر</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>المزود</th>
                <th>الحالة</th>
                <th>تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->first_name ?? '-' }}</td>
                    <td>{{ $order->serviceProvider->first_name ?? '-' }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">لا توجد طلبات</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $orders->withQueryString()->links() }}
</div>
@endsection
