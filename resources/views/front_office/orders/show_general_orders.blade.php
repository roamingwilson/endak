@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale');
    $user = auth()->user();
    ?>
    {{ $lang == 'ar' ? 'المشاريع' : 'Projects' }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <style>
        /* تنسيق الـ Container */
.container {
    margin-top: 50px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* تنسيق العنوان */
h2 {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
}

/* تنسيق بطاقة المشروع (Card) */
.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

/* تنسيق قائمة المعلومات */
.list-group-item {
    padding: 10px;
    border: none;
    font-size: 1.1rem;
}

/* تنسيق البطونات */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

/* تنسيق العناصر داخل الـ Section */
.section {
    padding: 50px 0;
    background-color: #f8f9fa;
}

/* تنسيق الـ Cards داخل الـ Section */
.card-custom {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

/* تنسيق الصورة داخل الـ Card */
.card-custom img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-height: 200px;
    border-bottom: 2px solid #f0f0f0;
}

/* تنسيق النصوص داخل الـ Card */
.card-custom .card-body {
    padding: 15px;
    text-align: center;
}

/* تنسيق العناوين داخل الـ Card */
.card-custom .card-body h5 {
    font-size: 1.2rem;
    color: #333;
    font-weight: bold;
    margin-bottom: 10px;
}

/* تنسيق النصوص داخل الـ Card */
.card-custom .card-body p {
    color: #555;
    font-size: 1rem;
    line-height: 1.5;
}

/* تحسين التباعد بين الـ Cards في الشاشات الصغيرة */
@media (max-width: 1024px) {
    .cards-container {
        grid-template-columns: repeat(2, 1fr); /* عمودين في الشاشات المتوسطة */
    }
}

@media (max-width: 768px) {
    .cards-container {
        grid-template-columns: 1fr; /* عمود واحد في الشاشات الصغيرة */
    }
}

    </style>
@endsection



@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">{{ ($lang == 'ar') ? 'أستلام المشروع' : 'Confirm Project' }}</h2>

    <div class="card shadow border-0 rounded p-4">
        {{-- <h4>{{ ($lang == 'ar') ? 'معلومات الطلب' : 'Order Info' }}</h4> --}}
        <ul class="list-group list-group-flush">
            <h4>{{ $lang == 'ar' ? '  معلومات الطلب  ' : 'Order Info' }}</h4>

            <li class="list-group-item"><strong>{{ ($lang == 'ar') ? 'رقم الطلب' : 'Order  Number' }}:</strong> {{ $order->id }}</li>
            <li class="list-group-item"><strong>{{ ($lang == 'ar') ? ' الخدمة' : 'Order  Number' }}:</strong> {{ $order->service->type}}</li>

            <li class="list-group-item"><strong>{{ $lang == 'ar' ? '  الحالة  ' : 'status' }}:</strong> {{ ($lang == 'ar') ? 'مكتمل' : $order->status }}</li>



            <li class="list-group-item"><strong>{{ $lang == 'ar' ? '  المزود  ' : 'Provider' }}:</strong> {{ $order->user->first_name ?? '' }} {{ $order->user->last_name ?? '' }}</li>
        </ul>


    </div>
</div>
<?php $offer = $service;
$rating = App\Models\Rating::where('order_id' , $order->id)->first();
?>
<section class="section d-flex align-items-center justify-content-center">
<div class="container">
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">


            <div class="d-flex flex-wrap justify-content-between">
                <div class="border mb-4 p-4 br-5" style="flex: 1 1 calc(33.333% - 1rem); margin: 0.5rem;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mt-0 mr-3">
                            {{ $order->user->first_name . ' ' . $order->user->last_name }}
                        </h5>
                        <div>
                            @if ($user->id == $order->user_id)
                            @if ($order->status == 'pending')
                                <a href="{{ route('accept_project', $order->id) }}"
                                    class="btn btn-primary">{{ ($lang == 'ar') ? 'أستلام المشروع' : 'Confirm Project' }}</a>
                            @elseif($order->status == 'completed' && !$rating)
                                <a href="{{ route('web.add_rate', $order->id) }}" class="btn btn-primary">
                                    {{ $lang == 'ar' ? 'تقييم العمل' : 'Rate Work' }}
                                </a>
                            @elseif($order->status == 'completed' && $rating)
                                <span class="text-success">{{ $lang == 'ar' ? 'تم التقييم مسبقًا' : 'Already Rated' }}</span>
                            @endif
                        @endif
                        </div>
                    </div>

                    @if (isset($offer->price))
                        <p>{{ __('general.price') . ' : ' . $offer->price }}</p>
                    @endif
                    @if (isset($offer->body))
                        <p>{{ 'Body : ' . $offer->body }}</p>
                    @endif
                    @if (isset($offer->date))
                        <p>{{ __('general.date') . ' : ' . $offer->date }}</p>
                    @endif
                    @if (isset($offer->time))
                        <p>{{ __('general.time') . ' : ' . $offer->time }}</p>
                    @endif
                    @if (isset($offer->city))
                        <p>{{ (($lang == 'ar') ? 'المدينة' : 'City') . ' : ' . $offer->city }}
                        </p>
                    @endif
                    @if (isset($offer->neighborhood))
                        <p>{{ (($lang == 'ar') ? 'الحي' : 'Neighborhood') . ' : ' . $offer->neighborhood }}
                        </p>
                    @endif
                    @if (isset($offer->to_city))
                        <p>{{ (($lang == 'ar') ? 'المدينة' : 'City') . ' : ' . $offer->city }}
                        </p>
                    @endif
                    @if (isset($offer->to_neighborhood))
                        <p>{{ (($lang == 'ar') ? 'الحي' : 'Neighborhood') . ' : ' . $offer->neighborhood }}
                        </p>
                    @endif
                    @if (isset($offer->from_city))
                        <p>{{ (($lang == 'ar') ? 'المدينة' : 'City') . ' : ' . $offer->city }}
                        </p>
                    @endif
                    @if (isset($offer->from_neighborhood))
                        <p>{{ (($lang == 'ar') ? 'الحي' : 'Neighborhood') . ' : ' . $offer->neighborhood }}
                        </p>
                    @endif
                    @if (isset($offer->location))
                        <p>{{ (($lang == 'ar') ? 'الموقع' : 'Location') . ' : ' . $offer->location }}
                        </p>
                    @endif
                    @if (isset($offer->day))
                        <p>{{ __('general.day') . ' : ' . $offer->day }}</p>
                    @endif
                    @if (isset($offer->number_of_days_of_warranty))
                        <p>{{ (($lang == 'ar') ? 'عدد ايام الضمان' : 'Number of Days of Warranty') . ' : ' . $offer->number_of_days_of_warranty }}
                        </p>
                    @endif
                    @if (isset($offer->notes))
                        <p>{{ (($lang == 'ar') ? 'ملاحظات عن العمل المطلوب' : 'Notes') . ' : ' . $offer->notes }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>






</div>

</section>
@endsection




