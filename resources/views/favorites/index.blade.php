@extends('layouts.home')

@section('title')
    {{ __('general.favorites') }}
    <?php $lang = config('app.locale'); ?>
@endsection

@section('style')
    <style>
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
<div class="container mt-4">
    <h1 class="mb-4 text-center">
        {{ $lang == 'ar' ? 'الأقسام المفضلة' : 'Favorite Departments' }}
    </h1>

    @if($favorites->count())
    <div class="row g-3">
        @foreach($favorites as $department)
            <div class="col-4 col-md-2 text-center">
                <a href="{{ $department->name_en == 'plastic' ? route('indsproducts.index') : route('services.show', $department->id) }}"
                   class="text-decoration-none text-dark">
                    <div class="card shadow-sm p-2" style="background-color: #f5f5f5; border-radius: 12px;">
                        <img src="{{ asset('storage/' . $department->image) }}" alt="{{ $department->name }}"
                             style="width: 60px; height: 60px; object-fit: contain; margin: auto;">
                        <div class="mt-2 small fw-bold">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    @else
        <div class="alert alert-info text-center mt-5">
            {{ $lang == 'ar' ? 'لا توجد أقسام مفضلة حالياً' : 'No favorite departments yet.' }}
        </div>
    @endif
</div>
@endsection
