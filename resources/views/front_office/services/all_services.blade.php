ف@extends('layouts.home')
@section('title', 'كل الخدمات المطلوبة')
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2" style="color: #1976d2;"><i class="fas fa-tasks me-2"></i>{{ app()->getLocale() == 'ar' ? 'كل الخدمات المطلوبة' : 'All Requested Services' }}</h2>
        <p class="text-muted">{{ app()->getLocale() == 'ar' ? 'تصفح جميع الخدمات المطلوبة وقم بتقديم عروضك المناسبة.' : 'Browse all requested services and submit your offers.' }}</p>
    </div>
    <form method="get" class="row g-3 align-items-center justify-content-center mb-4 bg-light p-3 rounded-3 shadow-sm">
        <div class="col-auto">
            <label for="department_id" class="form-label fw-semibold me-2"><i class="fas fa-th-large"></i> {{ app()->getLocale() == 'ar' ? 'القسم' : 'Department' }}</label>
            <select name="department_id" id="department_id" onchange="this.form.submit()" class="form-select d-inline-block w-auto rounded-pill">
                <option value="">{{ app()->getLocale() == 'ar' ? 'كل الأقسام' : 'All Departments' }}</option>
                @foreach($departments as $dep)
                    <option value="{{ $dep->id }}" {{ request('department_id') == $dep->id ? 'selected' : '' }}>
                        {{ app()->getLocale() == 'ar' ? $dep->name_ar : $dep->name_en }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label for="city" class="form-label fw-semibold me-2"><i class="fas fa-map-marker-alt"></i> {{ app()->getLocale() == 'ar' ? 'المدينة' : 'City' }}</label>
            <select name="city" id="city" onchange="this.form.submit()" class="form-select d-inline-block w-auto rounded-pill">
                <option value="">{{ app()->getLocale() == 'ar' ? 'كل المدن' : 'All Cities' }}</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="row">
        @forelse($services as $service)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow border-0 rounded-4 service-card">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary fs-5 p-2 rounded-circle"><i class="fas fa-briefcase"></i></span>
                        </div>
                        <h5 class="card-title fw-bold mb-2" style="color:#1976d2;">
                            {{ $service->department->name_ar ?? $service->department->name_en ?? '-' }}
                        </h5>
                        <ul class="list-unstyled mb-3">
                            @if(isset($service->subDepartment) && $service->subDepartment)
                                <li class="mb-1"><i class="fas fa-sitemap text-success me-1"></i> <span class="fw-semibold">{{ app()->getLocale() == 'ar' ? 'القسم الفرعي' : 'Sub-Department' }}:</span> {{ app()->getLocale() == 'ar' ? $service->subDepartment->name_ar : $service->subDepartment->name_en }}</li>
                            @endif
                            @if(!empty($service->city))
                                <li class="mb-1"><i class="fas fa-map-marker-alt text-danger me-1"></i> <span class="fw-semibold">{{ app()->getLocale() == 'ar' ? 'المدينة' : 'City' }}:</span> {{ $service->city }}</li>
                            @endif
                            <li class="mb-1"><i class="fas fa-clock text-warning me-1"></i> <span class="fw-semibold">{{ app()->getLocale() == 'ar' ? 'منذ' : 'Since' }}:</span> {{ $service->created_at ? $service->created_at->diffForHumans() : '-' }}</li>
                        </ul>
                        <div class="mt-auto d-flex flex-column gap-2">
                            <a href="{{ route('show_myservice', $service->id) }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fe fe-eye"></i> {{ app()->getLocale() == 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}
                            </a>
                            @php
                                $canOffer = false;
                                if(auth()->check() && auth()->user()->role_id == 3) {
                                    $allowedMain = auth()->user()->getAllDepartments()['main']->pluck('id')->toArray();
                                    $allowedSub = auth()->user()->getAllDepartments()['sub']->pluck('id')->toArray();
                                    $serviceDepartmentId = $service->department_id ?? null;
                                    $serviceSubDepartmentId = $service->sub_department_id ?? null;
                                    if ($serviceSubDepartmentId) {
                                        $canOffer = in_array($serviceSubDepartmentId, $allowedSub);
                                    } else {
                                        $canOffer = in_array($serviceDepartmentId, $allowedMain);
                                    }
                                }
                            @endphp
                            @if($canOffer)
                                <a href="{{ route('show_myservice', $service->id) }}#offer-form" class="btn btn-success rounded-pill"><i class="fas fa-paper-plane"></i> {{ app()->getLocale() == 'ar' ? 'قدم عرض' : 'Submit Offer' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">
                <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                {{ app()->getLocale() == 'ar' ? 'لا توجد خدمات متاحة حالياً.' : 'No services available at the moment.' }}
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {!! $services->links() !!}
    </div>
</div>
@endsection
