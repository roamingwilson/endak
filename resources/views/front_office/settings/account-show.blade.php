@extends('layouts.home')

@section('title', $lang == 'ar' ? 'إعدادات الحساب' : 'Account Settings')

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
                    {{ $lang == 'ar' ? 'إدارة المدينة والأقسام التي تقدم فيها خدماتك' : 'Manage your city and departments where you provide services' }}
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

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Settings Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $lang == 'ar' ? 'المدينة والأقسام' : 'City & Departments' }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.settings.account.update') }}" method="POST" id="accountSettingsForm">
                        @csrf

                        <!-- Cities Selection (for Service Providers) -->
                        @if($user->role_id == 3)
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                {{ $lang == 'ar' ? 'المدن التي تعمل فيها' : 'Cities You Work In' }}
                                <small class="text-muted">({{ $lang == 'ar' ? 'يمكنك اختيار عدة مدن' : 'You can select multiple cities' }})</small>
                            </label>
                            
                            <div class="cities-container">
                                <div class="row">
                                    @foreach($governorates as $governorate)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input city-checkbox" 
                                                       type="checkbox" 
                                                       name="cities[]" 
                                                       value="{{ $governorate->id }}" 
                                                       id="city_{{ $governorate->id }}"
                                                       {{ $selectedCities->where('governement_id', $governorate->id)->count() > 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="city_{{ $governorate->id }}">
                                                    <i class="fas fa-city me-2 text-info"></i>
                                                    {{ $lang == 'ar' ? $governorate->name_ar : $governorate->name_en }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            @error('cities')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <!-- Departments Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-th-large me-2 text-primary"></i>
                                {{ $lang == 'ar' ? 'الأقسام' : 'Departments' }}
                                <small class="text-muted">({{ $lang == 'ar' ? 'حد أقصى 3 أقسام' : 'Max 3 departments' }})</small>
                            </label>

                            <div class="departments-container">
                                @foreach($departments as $department)
                                    <div class="department-card mb-3 p-3 border rounded">
                                        <div class="form-check">
                                            <input class="form-check-input department-checkbox"
                                                   type="checkbox"
                                                   name="departments[]"
                                                   value="main-{{ $department->id }}"
                                                   id="dept_{{ $department->id }}"
                                                   {{ $userDepartments->where('commentable_id', $department->id)->where('commentable_type', 'App\Models\Department')->count() > 0 ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="dept_{{ $department->id }}">
                                                <i class="fas fa-folder me-2 text-warning"></i>
                                                {{ $lang == 'ar' ? $department->name_ar : $department->name_en }}
                                            </label>
                                        </div>

                                        <!-- Sub Departments -->
                                        @if($department->sub_departments->count() > 0)
                                            <div class="sub-departments ms-4 mt-2">
                                                @foreach($department->sub_departments as $subDepartment)
                                                    <div class="form-check">
                                                        <input class="form-check-input sub-department-checkbox"
                                                               type="checkbox"
                                                               name="departments[]"
                                                               value="sub-{{ $subDepartment->id }}"
                                                               id="sub_dept_{{ $subDepartment->id }}"
                                                               data-parent="{{ $department->id }}"
                                                               {{ $userDepartments->where('commentable_id', $subDepartment->id)->where('commentable_type', 'App\Models\SubDepartment')->count() > 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sub_dept_{{ $subDepartment->id }}">
                                                            <i class="fas fa-folder-open me-2 text-info"></i>
                                                            {{ $lang == 'ar' ? $subDepartment->name_ar : $subDepartment->name_en }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @error('departments')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.settings.profile') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-user me-2"></i>
                                {{ $lang == 'ar' ? 'الملف الشخصي' : 'Profile Settings' }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                {{ $lang == 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Settings Summary -->
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        {{ $lang == 'ar' ? 'الإعدادات الحالية' : 'Current Settings' }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">{{ $lang == 'ar' ? 'المدينة:' : 'City:' }}</h6>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                {{ $user->governementObj ? ($lang == 'ar' ? $user->governementObj->name_ar : $user->governementObj->name_en) : ($lang == 'ar' ? 'غير محدد' : 'Not Set') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">{{ $lang == 'ar' ? 'عدد الأقسام:' : 'Departments Count:' }}</h6>
                            <p class="mb-0">
                                <i class="fas fa-th-large me-2 text-success"></i>
                                {{ $userDepartments->count() }} {{ $lang == 'ar' ? 'قسم' : 'department(s)' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .department-card {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .department-card:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .sub-departments {
        border-left: 3px solid #dee2e6;
        padding-left: 15px;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Dark mode support */
    body.dark-theme .department-card {
        background-color: var(--dark-bg-secondary);
        border-color: var(--dark-border);
    }

    body.dark-theme .department-card:hover {
        background-color: var(--dark-bg-tertiary);
    }

    body.dark-theme .sub-departments {
        border-left-color: var(--dark-border);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentCheckboxes = document.querySelectorAll('.department-checkbox');
    const subDepartmentCheckboxes = document.querySelectorAll('.sub-department-checkbox');
    const form = document.getElementById('accountSettingsForm');

    // التحقق من عدد الأقسام المختارة
    function validateDepartments() {
        const checkedBoxes = document.querySelectorAll('input[name="departments[]"]:checked');
        const maxDepartments = 3;

        if (checkedBoxes.length > maxDepartments) {
            alert('{{ $lang == "ar" ? "يمكنك اختيار 3 أقسام فقط" : "You can select maximum 3 departments" }}');
            return false;
        }

        return true;
    }

    // إضافة مستمع الأحداث للنموذج
    form.addEventListener('submit', function(e) {
        if (!validateDepartments()) {
            e.preventDefault();
        }
    });

    // التحقق من الأقسام الفرعية عند اختيار القسم الرئيسي
    departmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const departmentId = this.id.replace('dept_', '');
            const subDepartments = document.querySelectorAll(`[data-parent="${departmentId}"]`);

            if (this.checked) {
                // إلغاء تحديد الأقسام الفرعية عند اختيار القسم الرئيسي
                subDepartments.forEach(sub => {
                    sub.checked = false;
                });
            }
        });
    });

    // التحقق من القسم الرئيسي عند اختيار الأقسام الفرعية
    subDepartmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const parentId = this.getAttribute('data-parent');
            const parentCheckbox = document.getElementById(`dept_${parentId}`);

            if (this.checked) {
                // إلغاء تحديد القسم الرئيسي عند اختيار الأقسام الفرعية
                parentCheckbox.checked = false;
            }
        });
    });
});
</script>
@endsection
