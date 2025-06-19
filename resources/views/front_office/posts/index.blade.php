@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'منشوراتي' : 'Posts' }}
@endsection
@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-4">{{ $lang == 'ar' ? ' طلباتي' : 'My services' }}</h2>

        <div class="row justify-content-center">
            @forelse ($services as $service)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0 rounded" style="direction: rtl; text-align: right;">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                {{ $lang == 'ar' ? $service->departments->name_ar : $service->departments->name_en }}</h5>

                            <p class="card-text text-muted">
                                <strong>{{ $lang == 'ar' ? 'القسم' : ' Department' }} :</strong>
                                {{ $lang == 'ar' ? $service->departments->name_ar : $service->departments->name_en }}
                            </p>
                            @if ($service->equip_type)
                                <p class="card-text text-muted">
                                    <strong>{{ $lang == 'ar' ? ' نوغ المعدة' : 'Equipment type' }} :</strong>
                                    {{ $service->equip_type }}
                                </p>
                            @endif
                            {{-- <p class="card-text text-muted">
                                <strong>{{ $lang == 'ar' ? ' المدينة' : 'City' }} :</strong>
                                {{ $service->city ?? $service->from_city }}
                            </p> --}}
                            {{-- <p class="card-text text-muted">
                                <strong>{{ $lang == 'ar' ? ' الحي' : 'Nighbouhood' }} :</strong>
                                {{ $service->neighborhood ?? $service->from_neighborhood }}
                            </p> --}}

                            <p class="card-text">
                                <span class="badge bg-light text-dark">
                                    {{ $lang == 'ar' ? ' الحالة' : 'Status' }}: {{ ucfirst($service->status) }}
                                </span>
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('show_myservice', $service->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fe fe-eye"></i> {{ $lang == 'ar' ? ' عرض التفاصيل' : 'Details' }}
                                </a>
                                <small class="text-muted">{{ $service->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        {{ $lang == 'ar' ? 'لا توجد خدمات حالياً ' : 'No sevices' }}
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $services->links() }}
        </div>
    </div>
@endsection
@section('style')
    <style>
        @media (max-width: 768px) {
            body {
                padding-top: 90px;
                /* عشان الـ navbar ما يغطيش الصفحة */
            }
        }

        @media (min-width: 769px) {
            body {
                padding-top: 70px;
                /* أو حسب ارتفاع الـ navbar */
            }
        }
    </style>
@endsection
