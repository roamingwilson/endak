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
    }

            @media (max-width: 768px) {
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
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2" style="color: #ff9800;"><i class="fas fa-history me-2"></i>{{ $lang == 'ar' ? 'الطلبات السابقة' : 'Previous Orders' }}</h2>
        <p class="text-muted">{{ $lang == 'ar' ? 'كل طلباتك المكتملة في مكان واحد' : 'All your completed orders in one place.' }}</p>
        <h5 class="mt-3 text-primary">{{ $user->fullname }}</h5>
    </div>
    @if(!$orders || $orders->isEmpty())
        <div class="alert alert-warning text-center py-5 rounded-4 shadow-sm">
            <i class="fas fa-folder-open fa-2x mb-3 text-warning"></i><br>
            <span class="fs-5">{{ $lang == 'ar' ? 'لا يوجد طلبات سابقة' : 'No previous orders' }}</span>
        </div>
    @else
        <div class="row justify-content-center">
            @foreach($orders as $order)
                @if ($order->status == 'completed')
                    <div class="col-md-6 col-lg-5 mb-4">
                        <div class="order-card shadow border-0 rounded-4 h-100">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-success bg-opacity-10 text-success fs-5 p-2 rounded-circle me-2"><i class="fas fa-check-circle"></i></span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $order->service->type ?? '-' }}</h5>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $order->created_at->format('Y-m-d') }}</small>
                                </div>
                            </div>
                            <div class="order-info mb-3">
                                <div><strong>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}:</strong>
                                    <span class="badge px-3 py-2 rounded-pill bg-success">{{ __('order.complete') }}</span>
                        </div>
                                <div><strong>{{ $lang == 'ar' ? 'المزود' : 'Provider' }}:</strong> {{ $order->user->fullname }}</div>
                                @if(isset($order->service->departments) && $order->service->departments)
                                    <div><strong>{{ $lang == 'ar' ? 'القسم الرئيسي' : 'Main Department' }}:</strong> {{ $lang == 'ar' ? $order->service->departments->name_ar : $order->service->departments->name_en }}</div>
                                @endif
                                @if(isset($order->service->subDepartment) && $order->service->subDepartment)
                                    <div><strong>{{ $lang == 'ar' ? 'القسم الفرعي' : 'Sub-Department' }}:</strong> {{ $lang == 'ar' ? $order->service->subDepartment->name_ar : $order->service->subDepartment->name_en }}</div>
                                @endif
                                @if(!empty($order->service->city) || !empty($order->service->from_city))
                                    <div><strong>{{ $lang == 'ar' ? 'المدينة' : 'City' }}:</strong> {{ $order->service->city ?? $order->service->from_city }}</div>
                                @endif
                                @if(!empty($order->service->neighborhood) || !empty($order->service->from_neighborhood))
                                    <div><strong>{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }}:</strong> {{ $order->service->neighborhood ?? $order->service->from_neighborhood }}</div>
                                @endif
                                @if(!empty($order->service->location))
                                    <div><strong>{{ $lang == 'ar' ? 'الموقع' : 'Location' }}:</strong> {{ $order->service->location }}</div>
                                @endif
                                @if(!empty($order->service->price))
                                    <div><strong>{{ $lang == 'ar' ? 'السعر' : 'Price' }}:</strong> {{ $order->service->price }}</div>
                                @endif
                                @if(!empty($order->service->date))
                                    <div><strong>{{ $lang == 'ar' ? 'التاريخ' : 'Date' }}:</strong> {{ $order->service->date }}</div>
                                @endif
                                @if(!empty($order->service->time))
                                    <div><strong>{{ $lang == 'ar' ? 'الوقت' : 'Time' }}:</strong> {{ $order->service->time }}</div>
                                @endif
                                @if(!empty($order->service->number_of_days_of_warranty))
                                    <div><strong>{{ $lang == 'ar' ? 'عدد أيام الضمان' : 'Warranty Days' }}:</strong> {{ $order->service->number_of_days_of_warranty }}</div>
                                @endif
                                @if(!empty($order->service->notes))
                                    <div><strong>{{ $lang == 'ar' ? 'ملاحظات' : 'Notes' }}:</strong> {{ $order->service->notes }}</div>
                                @endif
                            </div>
                            <div class="order-actions d-flex gap-2">
                                <a href="{{ route('general_orders.show', $order->id) }}" class="btn btn-warning btn-sm rounded-pill px-4 shadow-sm">
                                    <i class="fe fe-eye"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                </a>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@if (Session::has('success'))
    <script>
        swal("نجاح", "{{ Session::get('success') }}", 'success', {
            button: true,
            button: "{{ app()->getLocale() == 'ar' ? 'حسناً' : 'Ok' }}",
            timer: 3000,
        })
    </script>
@endif

@if (Session::has('error'))
    <script>
        swal("خطأ", "{{ Session::get('error') }}", 'error', {
            button: true,
            button: "{{ app()->getLocale() == 'ar' ? 'حسناً' : 'Ok' }}",
            timer: 3000,
        })
    </script>
@endif
@endsection






