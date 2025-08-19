@extends('layouts.home')

@section('title', $lang == 'ar' ? 'إعدادات الملف الشخصي' : 'Profile Settings')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary mb-3">
                    <i class="fas fa-user-edit me-2"></i>
                    {{ $lang == 'ar' ? 'إعدادات الملف الشخصي' : 'Profile Settings' }}
                </h2>
                <p class="text-muted">
                    {{ $lang == 'ar' ? 'تحديث معلوماتك الشخصية وصورة الملف الشخصي' : 'Update your personal information and profile picture' }}
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

            <!-- Profile Settings Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        {{ $lang == 'ar' ? 'المعلومات الشخصية' : 'Personal Information' }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.settings.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileSettingsForm">
                        @csrf

                        <!-- Profile Image -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('storage/' . ($user->image ?? 'users/user.png')) }}"
                                     alt="Profile Image"
                                     class="rounded-circle border border-3 border-primary"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                                <label for="image" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer"
                                       style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="image" name="image" class="d-none" accept="image/*">
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    {{ $lang == 'ar' ? 'انقر على الكاميرا لتغيير الصورة' : 'Click camera to change image' }}
                                </small>
                            </div>
                            @error('image')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Personal Information -->
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-bold">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    {{ $lang == 'ar' ? 'الاسم الأول' : 'First Name' }}
                                </label>
                                <input type="text"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name"
                                       name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-bold">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    {{ $lang == 'ar' ? 'الاسم الأخير' : 'Last Name' }}
                                </label>
                                <input type="text"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name"
                                       name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    {{ $lang == 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                                </label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    {{ $lang == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}
                                </label>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- About Me -->
                        <div class="mb-4">
                            <label for="about_me" class="form-label fw-bold">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                {{ $lang == 'ar' ? 'نبذة عنك' : 'About Me' }}
                            </label>
                            <textarea class="form-control @error('about_me') is-invalid @enderror"
                                      id="about_me"
                                      name="about_me"
                                      rows="4"
                                      placeholder="{{ $lang == 'ar' ? 'اكتب نبذة مختصرة عنك...' : 'Write a brief description about yourself...' }}">{{ old('about_me', $user->about_me) }}</textarea>
                            <div class="form-text">
                                {{ $lang == 'ar' ? 'حد أقصى 1000 حرف' : 'Maximum 1000 characters' }}
                            </div>
                            @error('about_me')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.settings.account.show') }}" class="btn btn-outline-info me-md-2">
                                <i class="fas fa-eye me-2"></i>
                                {{ $lang == 'ar' ? 'عرض الإعدادات' : 'View Settings' }}
                            </a>
                            <a href="{{ route('user.settings.account') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-cog me-2"></i>
                                {{ $lang == 'ar' ? 'إعدادات الحساب' : 'Account Settings' }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                {{ $lang == 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Profile Summary -->
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        {{ $lang == 'ar' ? 'ملخص الملف الشخصي' : 'Profile Summary' }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">{{ $lang == 'ar' ? 'الاسم الكامل:' : 'Full Name:' }}</h6>
                            <p class="mb-0">
                                <i class="fas fa-user me-2 text-success"></i>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">{{ $lang == 'ar' ? 'رقم الهاتف:' : 'Phone:' }}</h6>
                            <p class="mb-0">
                                <i class="fas fa-phone me-2 text-info"></i>
                                {{ $user->phone }}
                            </p>
                        </div>
                    </div>
                    @if($user->about_me)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-primary">{{ $lang == 'ar' ? 'نبذة عنك:' : 'About Me:' }}</h6>
                            <p class="mb-0">
                                <i class="fas fa-quote-left me-2 text-warning"></i>
                                {{ Str::limit($user->about_me, 100) }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Dark mode support */
    body.dark-theme .form-control {
        background-color: var(--dark-input-bg);
        border-color: var(--dark-border);
        color: var(--dark-text-primary);
    }

    body.dark-theme .form-control:focus {
        background-color: var(--dark-input-bg);
        border-color: var(--dark-border-focus);
        color: var(--dark-text-primary);
    }

    body.dark-theme .form-text {
        color: var(--dark-text-secondary);
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const profileImage = document.querySelector('img[alt="Profile Image"]');

    // معاينة الصورة عند اختيارها
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // التحقق من نوع الملف
            if (!file.type.startsWith('image/')) {
                alert('{{ $lang == "ar" ? "يرجى اختيار ملف صورة صحيح" : "Please select a valid image file" }}');
                return;
            }

            // التحقق من حجم الملف (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('{{ $lang == "ar" ? "حجم الصورة يجب أن يكون أقل من 2 ميجابايت" : "Image size must be less than 2MB" }}');
                return;
            }

            // معاينة الصورة
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // التحقق من طول النص في "نبذة عنك"
    const aboutMeTextarea = document.getElementById('about_me');
    aboutMeTextarea.addEventListener('input', function() {
        const maxLength = 1000;
        const currentLength = this.value.length;

        if (currentLength > maxLength) {
            this.value = this.value.substring(0, maxLength);
        }
    });
});
</script>
@endsection
