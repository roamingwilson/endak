@extends('layouts.home')
@section('title')
    {{ __('general.home') }}
    <?php $lang = config('app.locale'); ?>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <style>
        .stars-card {
            min-height: 20px;
        }

        .stars-card svg {
            margin-right: 3px;
            color: #818894;
        }

        .stars-card svg.active {
            color: #ffc600;
            fill: #ffc600;
        }

        .stars-card i.active svg {
            color: #ffc600;
            fill: #ffc600;
        }

        .myform {
            max-width: 500px;
            margin: 0 auto;
        }

        .input-group {
            width: 100%;
        }

        .form-control {
            height: 60px;
            font-size: 1.2rem;
            padding: 15px;
            border-radius: 5px 0 0 5px;
            border: 1px solid #ced4da;
        }

        .btn-primary {
            height: 60px;
            font-size: 1.2rem;
            border-radius: 0 5px 5px 0;
        }

        .card-custom {
            width: 100%;
            height: 200px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: rgba(169, 169, 169, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card-custom img {
            width: 80%;
            height: auto;
            object-fit: contain;
            max-height: 150px;
        }

        .card-custom .card-body {
            text-align: center;
            padding: 10px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection
@section('content')
<div class="container py-5">
    <!-- تعريف عن الموقع -->
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3" style="color: #ff9800;">{{ $lang == 'ar' ? 'مرحباً بك في منصة عندك' : 'Welcome to Endak Platform' }}</h1>
        <p class="lead mx-auto" style="max-width: 700px;">
            {{ $lang == 'ar' ? 'منصتك الذكية لربط العملاء بمزودي الخدمات والمنتجات في جميع المجالات. سهولة في البحث، أمان في التعامل، وراحة في التواصل.' : 'Your smart platform to connect customers with service and product providers in all fields. Easy search, secure transactions, and seamless communication.' }}
        </p>
    </div>
    <!-- رسائل إدارية -->
    @if(Session::has('admin_message') || !empty($adminMessage))
        <div class="alert alert-info text-center mb-4">
            <i class="fas fa-info-circle me-2"></i>
            {{ Session::get('admin_message') ?? $adminMessage }}
        </div>
    @endif
    <!-- تعريف الأدوار -->
    <div class="row justify-content-center align-items-stretch g-4">
        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-3x mb-3 text-primary"></i>
                    <h4 class="fw-bold mb-2">{{ $lang == 'ar' ? 'العميل' : 'Customer' }}</h4>
                    <p class="mb-0">
                        {{ $lang == 'ar' ? 'يمكنك كعميل استكشاف الخدمات والمنتجات، طلب عروض الأسعار، التواصل مع مزودي الخدمة، وإتمام عمليات الشراء بسهولة وأمان.' : 'As a customer, you can explore services and products, request quotes, communicate with providers, and complete purchases easily and securely.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase fa-3x mb-3 text-warning"></i>
                    <h4 class="fw-bold mb-2">{{ $lang == 'ar' ? 'مزود الخدمة' : 'Service Provider' }}</h4>
                    <p class="mb-0">
                        {{ $lang == 'ar' ? 'يمكنك كمزود خدمة أو تاجر عرض خدماتك ومنتجاتك، استقبال الطلبات، التفاعل مع العملاء، وزيادة فرصك في الوصول لعملاء جدد.' : 'As a provider or merchant, you can showcase your services and products, receive orders, interact with customers, and increase your reach.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- يمكنك إضافة أي محتوى إضافي هنا -->
</div>
@if (Session::has('success'))
<script>
    swal("Message", "{{ Session::get('success') }}", 'success', {
        button: true,
        button: "Ok",
        timer: 3000,
    })
</script>
@endif
{{-- @if (Session::has('info'))
<script>
    swal("Message", "{{ Session::get('info') }}", 'info', {
        button: true,
        button: "Ok",
        timer: 3000,
    })
</script> --}}
{{-- @endif --}}
@endsection
@section('script')
    <script src="{{ asset('js/feather-icons/dist/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
@endsection
