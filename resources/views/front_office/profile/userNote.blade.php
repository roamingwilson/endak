@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'تعديل الصفحة الشخصية' : 'Edit Profile' }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2-4.0.3/css/select2.css') }}">
    <style>

    .card {
        background-color: #fff;
        border: 1px solid #e0e0e0;
    }
    .card-header {
        font-weight: bold;
    }


        .profile-cover-container {
            position: relative;
            width: 100%;
            min-height: 400px;
            background-color: #f5f5f5;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-content {
            padding-top: 20px;
        }
        .profile-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 767.98px) {
            .profile-card {
                padding: 15px;
            }
        }@media (max-width: 768px) {
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
<div class="container py-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $lang == 'ar' ? ' ملاحظات المستخدمين ' : 'User`s Feedback' }}</h4>
        </div>
        <div class="card-body">
            <p class="text-muted" style="line-height: 2;">
                هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى...
            </p>
        </div>
    </div>
</div>
@endsection



