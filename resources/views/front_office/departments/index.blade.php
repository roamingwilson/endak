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
        .mb-4.text-center {
            color: #ff9800;
            font-weight: bold;
            letter-spacing: 1px;
            font-size: 2rem;
        }
        .row.g-3 {
            margin-top: 2rem;
        }
        .card.shadow-sm.p-2 {
            transition: box-shadow 0.18s, transform 0.18s;
            border-radius: 16px !important;
            box-shadow: 0 2px 10px rgba(255,152,0,0.07);
            background: #f5f5f5 !important;
        }
        .card.shadow-sm.p-2:hover {
            box-shadow: 0 8px 32px rgba(255,152,0,0.18), 0 2px 8px rgba(0,0,0,0.10);
            transform: translateY(-4px) scale(1.04);
        }
        .card .mt-2.small.fw-bold {
            color: #111;
            font-size: 1.08rem;
            font-weight: 700;
            margin-top: 10px !important;
        }
        .col-4.col-md-2.text-center {
            margin-bottom: 24px;
        }
        @media (max-width: 768px) {
            .col-4.col-md-2.text-center { flex: 0 0 50%; max-width: 50%; }
        }
        @media (max-width: 500px) {
            .col-4.col-md-2.text-center { flex: 0 0 50%; max-width: 50%; }
        }
    </style>
@endsection
@section('content')

@php
    $departments = \App\Models\Department::all();
@endphp
<div class="container" style=" padding-top: 100px;">
    <h1 class="mb-4 text-center"> {{ $lang == 'ar' ? "اختيار خدمة" : "Choose Service"}}</h1>

    <div class="row g-3">
        @foreach($departments as $department)
        @if ($department->name_en == 'plastic')
            @continue
        @endif
        <div class="col-4 col-md-2 text-center">
            <a href="{{route('services.show',$department->id)}}" class="text-decoration-none text-dark">
                <div class="card shadow-sm p-2" style="background-color: #f5f5f5; border-radius: 12px;">
                    <img src="{{ asset('storage/' . $department->image) }}" alt="{{ $department->name }}" style="width: 60px; height: 60px; object-fit: contain; margin: auto;">
                    <div class="mt-2 small fw-bold">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}</div>
                </div>
            </a>
        </div>



        @endforeach
    </div>
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
@if (Session::has('info'))
<script>
    swal("Message", "{{ Session::get('info') }}", 'info', {
        button: true,
        button: "Ok",
        timer: 3000,
    })
</script>
@endif
@endsection
@section('script')
    <script src="{{ asset('js/feather-icons/dist/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
@endsection

