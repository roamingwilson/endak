@extends('layouts.home')

@section('title', $lang == 'ar' ? 'عرض إعدادات الحساب' : 'View Account Settings')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary mb-3">
                    <i class="fas fa-cog me-2"></i>
                    {{ $lang == 'ar' ? 'إعدادات الحساب' : 'Account Settings' }}
                </h2>
                <p class="text-muted">
                    {{ $lang == 'ar' ? 'عرض إعدادات المدن والأقسام الخاصة بك' : 'View your cities and departments settings' }}
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Settings Display Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $lang == 'ar' ? 'المدن والأقسام' : 'Cities & Departments' }}
                    </h5>
                    <a href="{{ route('user.settings.account') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i>
                        {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                    </a>
                </div>
                <div class="card-body p-4">

                    <!-- Cities Section -->
                    @if($user->role_id == 3)
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $lang == 'ar' ? 'المدن التي تعمل فيها' : 'Cities You Work In' }}
                        </h6>

                        @if($selectedCities->count() > 0)
                            <div class="row">
                                @foreach($selectedCities as $city)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="city-badge p-2 rounded border">
                                            <i class="fas fa-city me-2 text-primary"></i>
                                            <span class="fw-medium">{{ $lang == 'ar' ? $city->governement->name_ar : $city->governement->name_en }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $lang == 'ar' ? 'لم يتم اختيار أي مدن للعمل فيها' : 'No cities selected for work' }}
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-city me-2"></i>
                            {{ $lang == 'ar' ? 'المدينة' : 'City' }}
                        </h6>

                        @if($user->governementObj)
                            <div class="city-badge p-3 rounded border">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                <span class="fw-medium">{{ $lang == 'ar' ? $user->governementObj->name_ar : $user->governementObj->name_en }}</span>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $lang == 'ar' ? 'لم يتم تحديد المدينة' : 'City not set' }}
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- Departments Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-th-large me-2"></i>
                            {{ $lang == 'ar' ? 'الأقسام المختارة' : 'Selected Departments' }}
                        </h6>

                        @if($userDepartments->count() > 0)
                            @php
                                $mainDepartments = $userDepartments->where('commentable_type', 'App\Models\Department');
                                $subDepartments = $userDepartments->where('commentable_type', 'App\Models\SubDepartment');
                            @endphp

                            <!-- الأقسام الرئيسية -->
                            @if($mainDepartments->count() > 0)
                                <div class="mb-3">
                                    <h6 class="fw-bold text-warning">
                                        <i class="fas fa-folder me-2"></i>
                                        {{ $lang == 'ar' ? 'الأقسام الرئيسية:' : 'Main Departments:' }}
                                    </h6>
                                    <div class="departments-display">
                                        @foreach($mainDepartments as $userDept)
                                            @if($userDept->commentable)
                                                <div class="department-item mb-2 p-2 rounded border">
                                                    <i class="fas fa-folder me-2 text-warning"></i>
                                                    <span class="fw-bold">{{ $lang == 'ar' ? $userDept->commentable->name_ar : $userDept->commentable->name_en }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- الأقسام الفرعية -->
                            @if($subDepartments->count() > 0)
                                <div class="mb-3">
                                    <h6 class="fw-bold text-info">
                                        <i class="fas fa-folder-open me-2"></i>
                                        {{ $lang == 'ar' ? 'الأقسام الفرعية:' : 'Sub Departments:' }}
                                    </h6>
                                    <div class="departments-display">
                                        @foreach($subDepartments as $userDept)
                                            @if($userDept->commentable)
                                                <div class="department-item mb-2 p-2 rounded border">
                                                    <i class="fas fa-folder-open me-2 text-info"></i>
                                                    <span>{{ $lang == 'ar' ? $userDept->commentable->name_ar : $userDept->commentable->name_en }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $lang == 'ar' ? 'لم يتم اختيار أي أقسام' : 'No departments selected' }}
                            </div>
                        @endif
                    </div>

                    <!-- Statistics Section -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="stat-card bg-light p-3 rounded text-center">
                                <i class="fas fa-city fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $selectedCities->count() }}</h5>
                                <small class="text-muted">{{ $lang == 'ar' ? 'مدينة مختارة' : 'Selected Cities' }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card bg-light p-3 rounded text-center">
                                <i class="fas fa-th-large fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">{{ $userDepartments->count() }}</h5>
                                <small class="text-muted">{{ $lang == 'ar' ? 'قسم مختار' : 'Selected Departments' }}</small>
                                <div class="small text-muted mt-1">
                                    @php
                                        $mainCount = $userDepartments->where('commentable_type', 'App\Models\Department')->count();
                                        $subCount = $userDepartments->where('commentable_type', 'App\Models\SubDepartment')->count();
                                    @endphp
                                    {{ $mainCount }} {{ $lang == 'ar' ? 'رئيسي' : 'main' }}, {{ $subCount }} {{ $lang == 'ar' ? 'فرعي' : 'sub' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('user.settings.account') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>
                    {{ $lang == 'ar' ? 'تعديل الإعدادات' : 'Edit Settings' }}
                </a>
                <a href="{{ route('user.settings.profile') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-user me-2"></i>
                    {{ $lang == 'ar' ? 'الملف الشخصي' : 'Profile Settings' }}
                </a>
                <a href="{{ route('web.profile', auth()->id()) }}" class="btn btn-outline-info">
                    <i class="fas fa-home me-2"></i>
                    {{ $lang == 'ar' ? 'الملف الشخصي' : 'Profile' }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .city-badge {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: all 0.3s ease;
    }

    .city-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .department-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: all 0.3s ease;
    }

    .department-item:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        transform: translateX(5px);
    }

    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

        /* Dark mode support */
    body.dark-theme .city-badge {
        background: linear-gradient(135deg, var(--dark-bg-secondary) 0%, var(--dark-bg-tertiary) 100%);
        border-color: var(--dark-border);
    }

    body.dark-theme .department-item {
        background: linear-gradient(135deg, var(--dark-bg-secondary) 0%, var(--dark-bg-tertiary) 100%);
        border-color: var(--dark-border);
    }

    body.dark-theme .department-item:hover {
        background: linear-gradient(135deg, var(--dark-bg-tertiary) 0%, var(--dark-border) 100%);
    }

    body.dark-theme .stat-card {
        background-color: var(--dark-bg-secondary) !important;
        border-color: var(--dark-border);
    }

    /* Updated text colors for light and dark themes */
    body.light-theme .text-primary {
        color: var(--light-text-accent) !important;
    }
    body.light-theme .text-success {
        color: var(--light-text-success) !important;
    }
    body.light-theme .text-danger {
        color: var(--light-text-danger) !important;
    }
    body.light-theme .text-warning {
        color: var(--light-text-warning) !important;
    }
    body.light-theme .text-info {
        color: var(--light-text-info) !important;
    }

    body.dark-theme .text-primary {
        color: var(--dark-text-accent) !important;
    }
    body.dark-theme .text-success {
        color: var(--dark-text-success) !important;
    }
    body.dark-theme .text-danger {
        color: var(--dark-text-danger) !important;
    }
    body.dark-theme .text-warning {
        color: var(--dark-text-warning) !important;
    }
    body.dark-theme .text-info {
        color: var(--dark-text-info) !important;
    }
</style>
@endsection
