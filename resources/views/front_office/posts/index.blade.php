@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'منشوراتي' : 'Posts' }}
@endsection
@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: #ff9800;"><i class="fas fa-tasks me-2"></i>{{ $lang == 'ar' ? 'طلباتي' : 'My Services' }}</h2>
            <p class="text-muted">{{ $lang == 'ar' ? 'جميع طلباتك وخدماتك في مكان واحد' : 'All your service requests in one place.' }}</p>
        </div>
        <div class="row justify-content-center">
            @forelse ($services as $service)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow border-0 rounded-4 h-100 service-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-shrink-0">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fs-5 p-2 rounded-circle"><i class="fas fa-briefcase"></i></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title fw-bold mb-0 text-primary">{{ $lang == 'ar' ? $service->departments->name_ar : $service->departments->name_en }}</h5>
                                    <small class="text-muted">{{ $service->created_at->format('Y-m-d') }}</small>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-3 mt-2">
                                <li><i class="fas fa-layer-group text-warning me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'القسم' : 'Department' }}:</span> {{ $lang == 'ar' ? $service->departments->name_ar : $service->departments->name_en }}</li>
                                @if (isset($service->subDepartment) && $service->subDepartment)
                                    <li><i class="fas fa-sitemap text-success me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'القسم الفرعي' : 'Sub-Department' }}:</span> {{ $lang == 'ar' ? $service->subDepartment->name_ar : $service->subDepartment->name_en }}</li>
                                @endif
                                @if ($service->equip_type)
                                    <li><i class="fas fa-cogs text-info me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'نوع المعدة' : 'Equipment type' }}:</span> {{ $service->equip_type }}</li>
                                @endif
                                @if (!empty($service->city) || !empty($service->from_city))
                                    <li><i class="fas fa-map-marker-alt text-danger me-1"></i> <span class="fw-semibold">{{ $lang == 'ar' ? 'المدينة' : 'City' }}:</span> {{ $service->city ?? $service->from_city }}</li>
                                @endif
                            </ul>
                            <div class="mb-3">
                                <span class="badge px-3 py-2 rounded-pill {{ $service->status == 'completed' ? 'bg-success' : ($service->status == 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $lang == 'ar' ? 'الحالة' : 'Status' }}: {{ $lang == 'ar' ? ($service->status == 'completed' ? 'مكتمل' : ($service->status == 'pending' ? 'قيد الانتظار' : $service->status)) : ucfirst($service->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <a href="{{ route('show_myservice', $service->id) }}" class="btn btn-warning btn-sm rounded-pill px-4 shadow-sm">
                                    <i class="fe fe-eye"></i> {{ $lang == 'ar' ? 'عرض التفاصيل' : 'Details' }}
                                </a>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $service->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center py-5 rounded-4 shadow-sm">
                        <i class="fas fa-folder-open fa-2x mb-3 text-warning"></i><br>
                        <span class="fs-5">{{ $lang == 'ar' ? 'لا توجد خدمات حالياً' : 'No services yet.' }}</span>
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
        .service-card {
            transition: box-shadow 0.2s;
        }
        .service-card:hover {
            box-shadow: 0 8px 24px rgba(255, 152, 0, 0.15), 0 1.5px 6px rgba(0,0,0,0.07);
        }
        @media (max-width: 768px) {
            body {
                padding-top: 90px;
            }
        }
        @media (min-width: 769px) {
            body {
                padding-top: 70px;
            }
        }
    </style>
@endsection
