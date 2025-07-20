ف@extends('layouts.home')
@section('title', 'كل الخدمات المطلوبة')
@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">كل الخدمات المطلوبة</h2>
    <form method="get" class="mb-4 text-center">
        <label for="department_id" class="me-2">فلترة حسب القسم:</label>
        <select name="department_id" id="department_id" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
            <option value="">كل الأقسام</option>
            @foreach($departments as $dep)
                <option value="{{ $dep->id }}" {{ request('department_id') == $dep->id ? 'selected' : '' }}>
                    {{ app()->getLocale() == 'ar' ? $dep->name_ar : $dep->name_en }}
                </option>
            @endforeach
        </select>
        <label for="city" class="me-2 ms-4">المدينة:</label>
        <select name="city" id="city" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
            <option value="">كل المدن</option>
            @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                    {{ $city }}
                </option>
            @endforeach
        </select>
    </form>
    <div class="row">
        @forelse($services as $service)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" style="color:#1976d2; font-weight:bold;">
                            {{ $service->department->name_ar ?? $service->department->name_en ?? '-' }}
                        </h5>
                        <div class="mb-2 text-muted" style="font-size:0.95em;">
                            <i class="fas fa-map-marker-alt"></i> {{ $service->city ?? '-' }}
                        </div>
                        <div class="mb-2 text-muted" style="font-size:0.95em;">
                            <i class="fas fa-clock"></i> {{ $service->created_at ? $service->created_at->diffForHumans() : '-' }}
                        </div>
                        <a href="{{ route('show_myservice', $service->id) }}" class="btn btn-outline-primary mt-auto mb-2">
                            تفاصيل الخدمة
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
                            <a href="{{ route('show_myservice', $service->id) }}#offer-form" class="btn btn-success">قدم عرض</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">
                لا توجد خدمات متاحة حالياً.
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-3">
        {!! $services->links() !!}
    </div>
</div>
@endsection
