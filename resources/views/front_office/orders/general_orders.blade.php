@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale');
    $user = auth()->user();
    ?>
    {{ $lang == 'ar' ? 'المشاريع' : 'Projects' }}
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
<style>
    .order-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
    }

    .order-header {
        font-weight: bold;
        font-size: 1.4rem;
        margin-bottom: 10px;
        color: #333;
    }

    .order-info {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 1rem;
    }

    .order-info div {
        flex: 1 1 45%;
        background: #f9f9f9;
        padding: 10px;
        border-radius: 8px;
    }

    .order-actions {
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .order-actions a,
    .order-actions form button {
        flex: 1;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
        font-weight: bold;
        border: none;
        color: white;
        text-decoration: none;
    }

    .order-actions a {
        background-color: #3490dc; /* Blue */
    }

    .order-actions form button {
        background-color: #e3342f; /* Red */
    }

    @media (max-width: 768px) {
        .order-info div {
            flex: 1 1 100%;
        }
    }        @media (max-width: 768px) {
    body {
        padding-top: 90px; /* عشان الـ navbar ما يغطيش الصفحة */
    }
}

@media (min-width: 769px) {
    body {
        padding-top: 70px; /* أو حسب ارتفاع الـ navbar */
    }
}
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="mb-4 text-center"  style=" margin-top: 50px;"> {{ $lang == 'ar' ? '  طلبات جديدة ' : ' new order' }} :{{ $user->fullname }}</h1>

    @if(!$orders)
        <p class="text-center">{{ $lang == 'ar' ? 'لا يوجد طلبات جديدة ' : 'No new order' }}</p>
    @else
        <div class="row">



            @foreach($orders as $order)


            @if ($orders && $order->status !== 'completed')


                <div class="col-md-6">
                    <div class="order-card">
                        <div class="order-header">

                            {{ $order->service->type  }}
                        </div>
                        <div class="order-info">
                            <div><strong>{{ $lang == 'ar' ? '  الحالة  ' : 'status' }}:</strong>
                                <span class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $order->status == 'completed' ? __('order.complete') : __('order.pending') }}
                                </span>
                            </div>
                            <div><strong>{{ $lang == 'ar' ? '  المزود  ' : 'Provider' }}:</strong> {{ $order->user->fullname }}</div>
                            {{-- <div><strong>{{ __('order.service') }}:</strong> {{ $order->orderable->name ?? '-' }}</div> --}}
                        </div>
                        <div class="order-actions">
                            <a href="{{ route('general_orders.show', $order->id) }}">{{ $lang == 'ar' ? 'تفاصيل' : 'details' }}</a>


                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        <div class="  mb-4" >
            <a href="{{route('pre_order.customer')}}" class=" btn btn-success ">{{ $lang == 'ar' ? 'الطلبات السابقة' : 'previous Orders' }}
                </a>
            </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection






