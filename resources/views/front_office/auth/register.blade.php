@extends('layouts.home')
@section('title', 'تسجيل جديد - خطوات')
@section('style')
    <link rel="stylesheet" href="{{ asset('select2-4.0.3/css/select2.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .steps-progress {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .step-indicator {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            position: relative;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background-color: white;
            color: #4e73df;
            transform: scale(1.1);
        }

        .step-indicator:not(:last-child):after {
            content: '';
            position: absolute;
            width: 25px;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.3);
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }

        .step-indicator.active:not(:last-child):after {
            background-color: white;
        }

        .role-card {
            border: 3px solid #e9ecef;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.4s ease;
            height: 100%;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .role-card:hover::before {
            left: 100%;
        }

        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .role-card.client-card:hover {
            border-color: #17a2b8;
            box-shadow: 0 15px 35px rgba(23, 162, 184, 0.2);
        }

        .role-card.provider-card:hover {
            border-color: #ffc107;
            box-shadow: 0 15px 35px rgba(255, 193, 7, 0.2);
        }

        .role-card.active {
            border-color: #4e73df;
            background: linear-gradient(145deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
            box-shadow: 0 10px 25px rgba(78, 115, 223, 0.2);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            color: #4e73df;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .client-card .icon-circle {
            background: linear-gradient(145deg, rgba(23, 162, 184, 0.1) 0%, rgba(23, 162, 184, 0.05) 100%);
            color: #17a2b8;
        }

        .provider-card .icon-circle {
            background: linear-gradient(145deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
            color: #ffc107;
        }

        .role-card:hover .icon-circle {
            transform: scale(1.1);
        }

        .verification-code {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
            padding-right: 12px;
        }

        .form-control.is-valid {
            border-color: #28a745;
            background-image: none;
            padding-right: 12px;
        }

        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .card {
            border-radius: 20px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #e9ecef;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-label i {
            margin-left: 8px;
            color: #4e73df;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            color: #dc3545;
        }

        .countdown-timer {
            background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 10px 20px;
            display: inline-block;
            margin-top: 10px;
        }

        .otp-input-wrapper {
            position: relative;
            display: inline-block;
        }

        .otp-input-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #4e73df, #224abe);
            transition: width 0.3s ease;
        }

        .otp-input-wrapper:focus-within::after {
            width: 100%;
        }

        .department-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .department-card:hover {
            border-color: #4e73df;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.1);
        }

        .step-1-5 .form-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
        }

        .step-1-5 .form-control {
            font-size: 1rem;
            padding: 12px 15px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple {
            min-height: 50px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 5px 10px;
            margin: 2px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ffc107;
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

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        إنشاء حساب جديد
                    </h3>
                    <div class="steps-progress mt-3" id="steps-indicator">
                        <div class="step-indicator active" data-step="1">1</div>
                        <div class="step-indicator d-none" data-step="1.5">1.5</div>
                        <div class="step-indicator" data-step="2">2</div>
                        <div class="step-indicator" data-step="3">3</div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form id="register-steps-form" method="POST" action="{{ route('register.post') }}" novalidate>
                        @csrf

                        <!-- Step 1: Role Selection -->
                        <div class="step step-1">
                            <div id="step1-errors" class="alert alert-danger d-none"></div>
                            <div class="text-center mb-5">
                                <i class="fas fa-user-tie fa-4x text-primary mb-3"></i>
                                <h4 class="fw-bold">اختر نوع حسابك</h4>
                                <p class="text-muted">سيحدد هذا الاختيار الميزات المتاحة لك في المنصة</p>
                            </div>
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="role-card client-card" data-role="1">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <h5 class="fw-bold"> عميل</h5>
                                            <p class="text-muted">نوع الحساب الأول</p>
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="radio" name="role" id="role-1" value="1" required>
                                                <label class="form-check-label ms-2" for="role-1">
                                                    اختر هذا الدور
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="role-card provider-card" data-role="3">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <h5 class="fw-bold">مزود خدمة</h5>
                                            <p class="text-muted">نوع الحساب الثاني</p>
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="radio" name="role" id="role-3" value="3" required>
                                                <label class="form-check-label ms-2" for="role-3">
                                                    اختر هذا الدور
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg w-100 next-step py-3">
                                التالي <i class="fas fa-arrow-left ms-2"></i>
                            </button>
                        </div>

                        <!-- Step 1.5: City and Department Selection (for provider only) -->
                        @php
                            $departments = \App\Models\Department::with(['sub_departments' => function($q){$q->where('status',1);}])->where('department_id',0)->where('status',1)->get(['id','name_ar','name_en']);
                        @endphp
                        <div class="step step-1-5 d-none">
                            <div id="step15-errors" class="alert alert-danger d-none"></div>
                            <div class="text-center mb-4">
                                <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                                <h4 class="fw-bold">اختر المدن والأقسام التي تقدم فيها الخدمة</h4>
                                <p class="text-muted">هذه الخطوة خاصة بمزود الخدمة فقط<br>اختر المدن وحدد الأقسام التي تعمل فيها</p>
                            </div>

                            <!-- Cities Selection -->
                            <div class="mb-4">
                                <div class="card p-3 border-primary">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt text-primary"></i> المدن التي تعمل فيها
                                    </label>
                                    <p class="text-muted small mb-3">يمكنك اختيار عدة مدن تعمل فيها</p>
                                    <select name="provider_cities[]" id="provider_cities" class="form-control form-control-lg" multiple required>
                                        @if(isset($govers) && $govers->count() > 0)
                                            @foreach($govers as $gover)
                                                <option value="{{ $gover->id }}">{{ app()->getLocale() == 'ar' ? $gover->name_ar : $gover->name_en }}</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>لا توجد مدن متاحة</option>
                                        @endif
                                    </select>
                                    <div class="invalid-feedback">يرجى اختيار مدينة واحدة على الأقل</div>
                                </div>
                            </div>

                            <!-- Department Selection -->
                            <div class="mb-4">
                                <div class="card p-3 border-success">
                                    <label class="form-label">
                                        <i class="fas fa-list text-success"></i> الأقسام التي تقدم فيها الخدمة
                                    </label>
                                    <p class="text-muted small mb-3">يمكنك اختيار حتى 3 أقسام رئيسية فقط، والأقسام الفرعية غير محدودة</p>

                                    <!-- عداد الأقسام المختارة -->
                                    <div class="alert alert-info mb-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <strong id="main-count">0</strong>
                                                <div class="small">أقسام رئيسية</div>
                                            </div>
                                            <div class="col-6">
                                                <strong id="sub-count">0</strong>
                                                <div class="small">أقسام فرعية</div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="row g-3 justify-content-center">
                                    @foreach($departments as $department)
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <div class="card department-card h-100 p-3">
                                                <div class="fw-bold mb-2">{{ app()->getLocale() == 'ar' ? $department->name_ar : $department->name_en }}</div>
                                                @if($department->sub_departments->count())
                                                    <div class="row">
                                                        <!-- القسم الرئيسي -->
                                                        <div class="col-12 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input main-department-checkbox" type="checkbox" name="main_departments[]" value="{{ $department->id }}" id="main-{{ $department->id }}">
                                                                <label class="form-check-label fw-bold" for="main-{{ $department->id }}">
                                                                    {{ app()->getLocale() == 'ar' ? $department->name_ar : $department->name_en }} (قسم رئيسي)
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <!-- الأقسام الفرعية -->
                                                        <div class="col-12">
                                                            <small class="text-muted d-block mb-2">الأقسام الفرعية:</small>
                                                            @foreach($department->sub_departments as $sub)
                                                                <div class="col-12">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input sub-department-checkbox" type="checkbox" name="departments[]" value="sub-{{ $sub->id }}-parent-{{ $department->id }}" id="sub-{{ $sub->id }}" data-parent="{{ $department->id }}">
                                                                        <label class="form-check-label" for="sub-{{ $sub->id }}">
                                                                            {{ app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-check">
                                                        <input class="form-check-input main-department-checkbox" type="checkbox" name="main_departments[]" value="{{ $department->id }}" id="main-{{ $department->id }}">
                                                        <label class="form-check-label" for="main-{{ $department->id }}">
                                                            {{ app()->getLocale() == 'ar' ? $department->name_ar : $department->name_en }}
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback d-block text-center" id="departments-error" style="display:none;"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-step">
                                    <i class="fas fa-arrow-right me-2"></i> السابق
                                </button>
                                <button type="button" class="btn btn-primary btn-lg next-step py-3">
                                    التالي <i class="fas fa-arrow-left ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: User Information -->
                        <div class="step step-2 d-none">
                            <div id="step2-errors" class="alert alert-danger d-none"></div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user"></i> الاسم الأول
                                    </label>
                                    <input type="text" name="first_name" class="form-control form-control-lg" required
                                           placeholder="أدخل اسمك الأول" pattern="[\p{Arabic}\s]+">
                                    <div class="invalid-feedback">يرجى إدخال اسم أول صحيح (أحرف عربية فقط)</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user"></i> الاسم الأخير
                                    </label>
                                    <input type="text" name="last_name" class="form-control form-control-lg" required
                                           placeholder="أدخل اسمك الأخير" pattern="[\p{Arabic}\s]+">
                                    <div class="invalid-feedback">يرجى إدخال اسم أخير صحيح (أحرف عربية فقط)</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i> رقم الجوال السعودي
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">+966</span>
                                    <input type="tel" name="phone" class="form-control form-control-lg" required
                                           placeholder="5XXXXXXXX" pattern="^5[0-9]{8}$">
                                </div>
                                <div class="invalid-feedback">يرجى إدخال رقم جوال سعودي صحيح يبدأ بـ 5 ويتكون من 9 أرقام بعد +966</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> البريد الإلكتروني (اختياري)
                                </label>
                                <input type="email" name="email" class="form-control form-control-lg"
                                       placeholder="example@example.com">
                                <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-globe"></i> البلد
                                </label>
                                <select name="country" id="country" class="form-control form-control-lg" required>
                                    <option value="">اختر البلد</option>
                                    @if(isset($countries) && $countries->count() > 0)
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en }}</option>
                                        @endforeach
                                    @else
                                        <option value="1">المملكة العربية السعودية</option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">يرجى اختيار البلد</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> المدينة
                                </label>
                                <select name="governement" id="governement" class="form-control form-control-lg" required>
                                    <option value="">اختر المدينة</option>
                                    @if(isset($govers) && $govers->count() > 0)
                                        @foreach($govers as $gover)
                                            <option value="{{ $gover->id }}">{{ app()->getLocale() == 'ar' ? $gover->name_ar : $gover->name_en }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>لا توجد مدن متاحة</option>
                                    @endif
                                </select>
                                <div class="invalid-feedback">يرجى اختيار المدينة</div>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> كلمة المرور
                                </label>
                                <input type="password" name="password" class="form-control form-control-lg" required
                                       placeholder="أدخل كلمة المرور">
                                <div class="invalid-feedback">يرجى إدخال كلمة المرور</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> تأكيد كلمة المرور
                                </label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg" required
                                       placeholder="أعد إدخال كلمة المرور">
                                <div class="invalid-feedback">كلمة المرور غير متطابقة</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step">
                                    <i class="fas fa-arrow-right me-2"></i> السابق
                                </button>
                                <button type="button" class="btn btn-primary btn-lg next-step py-3">
                                    التالي <i class="fas fa-arrow-left ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: OTP Verification -->
                        <div class="step step-3 d-none">
                            <div id="step3-errors" class="alert alert-danger d-none"></div>
                            <div class="text-center mb-4">
                                <i class="fas fa-mobile-alt fa-4x text-primary mb-3"></i>
                                <h4 class="fw-bold">تحقق من هاتفك</h4>
                                <p class="text-muted">لقد أرسلنا رمز التحقق إلى رقم الهاتف
                                    <span class="fw-bold text-primary" id="phone-display"></span>
                                </p>
                                <div class="alert alert-info d-flex align-items-center" id="otp-demo" style="display:none;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="otp-code-display"></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-center d-block">
                                    <i class="fas fa-key"></i> رمز التحقق
                                </label>
                                <div class="d-flex justify-content-center">
                                    <div class="otp-input-wrapper">
                                        <input type="text" name="otp" class="form-control form-control-lg text-center verification-code"
                                               maxlength="4" pattern="\d{4}" required style="width: 180px;">
                                    </div>
                                </div>
                                <div class="invalid-feedback text-center">يرجى إدخال رمز التحقق المكون من 4 أرقام</div>
                                <div class="text-center mt-3">
                                    <a href="#" id="resend-otp" class="text-primary fw-bold">إعادة إرسال الرمز</a>
                                    <div class="countdown-timer" id="countdown">
                                        (يمكنك إعادة الإرسال خلال 60 ثانية)
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step">
                                    <i class="fas fa-arrow-right me-2"></i> السابق
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    إنهاء التسجيل <i class="fas fa-check-circle ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@if(isset($govers) && $govers->count() > 0)
    <script>
        console.log('Cities loaded:', @json($govers));
    </script>
@endif
<script>
    // تحميل المحافظات حسب البلد المختار
    function loadGovernements(countryId) {
        if (countryId) {
            $.ajax({
                url: '{{ route("get.governorates") }}',
                method: 'GET',
                data: { country_id: countryId },
                success: function(response) {
                    const governementSelect = $('#governement');
                    governementSelect.empty();
                    governementSelect.append('<option value="">اختر المدينة</option>');

                    if (response.length > 0) {
                        response.forEach(function(gover) {
                            governementSelect.append(`<option value="${gover.id}">${gover.name_ar}</option>`);
                        });
                    } else {
                        governementSelect.append('<option value="" disabled>لا توجد مدن متاحة</option>');
                    }
                },
                error: function() {
                    console.error('خطأ في تحميل المحافظات');
                }
            });
        }
    }

    // عند تغيير البلد
    $(document).on('change', '#country', function() {
        const countryId = $(this).val();
        loadGovernements(countryId);
    });
</script>
<script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2-4.0.3/js/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
    let currentStep = 1;
    let resendTimer = null;
    let secondsLeft = 60;
    let otpCode = null; // Variable to store OTP code

    // Show current step with animation
    function showStep(step) {
        $('.step').fadeOut(300).promise().done(function() {
            $('.step').addClass('d-none');
            $('.step-' + step).removeClass('d-none').fadeIn(300);

            // Initialize select2 for provider cities when step 1.5 is shown
            if(step === '1-5') {
                setTimeout(function() {
                    $('#provider_cities').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'اختر المدن التي تعمل فيها',
                        allowClear: true,
                        closeOnSelect: false
                    });
                }, 350);
            }
        });

        $('.step-indicator').removeClass('active');
        $(`.step-indicator[data-step="${step}"]`).addClass('active');
        currentStep = step;

        $('html, body').animate({
            scrollTop: $('.card').offset().top - 20
        }, 500);
    }

    // Validate step 1 (role selection)
    function validateStep1() {
        const selectedRole = $('input[name="role"]:checked').val();
        if (!selectedRole) {
            $('#step1-errors').removeClass('d-none').text('الرجاء اختيار نوع الحساب قبل المتابعة');
            $('.role-card').addClass('is-invalid');
            return false;
        }
        $('#step1-errors').addClass('d-none').empty();
        $('.role-card').removeClass('is-invalid');
        return true;
    }

    // Validate step 2 (user info)
    function validateStep2() {
        let isValid = true;
        const step2Inputs = $('.step-2 input[required]');
        const step2Selects = $('.step-2 select[required]');

        step2Inputs.each(function() {
            const input = $(this);
            if (!input.val()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
                if (input.attr('name') === 'phone') {
                    // تنظيف رقم الهاتف
                    let phone = input.val();
                    // إزالة 00 أو + من البداية إذا وجدت
                    phone = phone.replace(/^(\+|00)/, '');
                    // إزالة أي أحرف غير رقمية
                    phone = phone.replace(/[^0-9]/g, '');

                    if (phone.length < 7 || phone.length > 20) {
                        input.addClass('is-invalid');
                        isValid = false;
                    }
                }
                if (input.attr('name') === 'password' && input.val().trim() === '') {
                    input.addClass('is-invalid');
                    isValid = false;
                }
                if (input.attr('name') === 'password_confirmation' && input.val() !== $('input[name="password"]').val()) {
                    input.addClass('is-invalid');
                    isValid = false;
                }
            }
        });

        step2Selects.each(function() {
            const select = $(this);
            if (!select.val()) {
                select.addClass('is-invalid');
                isValid = false;
            } else {
                select.removeClass('is-invalid');
            }
        });

        return isValid;
    }

    // تحديث شريط الخطوات حسب الدور
    function updateStepIndicators(role) {
        if(role == '3') {
            // مزود خدمة: أظهر خطوة 1.5
            $('[data-step="1.5"]').removeClass('d-none');
            $('[data-step="2"]').text('2');
            $('[data-step="3"]').text('3');
        } else {
            // عميل: أخفِ خطوة 1.5
            $('[data-step="1.5"]').addClass('d-none');
            $('[data-step="2"]').text('2');
            $('[data-step="3"]').text('3');
        }
    }
    // عند اختيار الدور
    $('.role-card').on('click', function() {
        $('.role-card').removeClass('active');
        $(this).addClass('active');
        selectedRole = $(this).data('role');
        $(`#role-${selectedRole}`).prop('checked', true);
        updateStepIndicators(selectedRole);
    });
    // Next step from 1 to 1.5 or 2
    $('.step-1 .next-step').on('click', function() {
        selectedRole = $('input[name="role"]:checked').val();
        if (!validateStep1()) return;
        if(selectedRole == '3') {
            showStep('1-5');
            $('[data-step]').removeClass('active');
            $('[data-step="1.5"]').addClass('active');
        } else {
            showStep(2);
            $('[data-step]').removeClass('active');
            $('[data-step="2"]').addClass('active');
        }
    });
    // Next step from 1.5 to 2
    $('.step-1-5 .next-step').on('click', function() {
        if (!validateDepartmentsStep()) return;
        showStep(2);
        $('[data-step]').removeClass('active');
        $('[data-step="2"]').addClass('active');
    });
    // Next step from 2 to 3 (user info -> OTP)
    $('.step-2 .next-step').on('click', function() {
        if (!validateStep2()) {
            $('#step2-errors').removeClass('d-none').text('الرجاء تصحيح الأخطاء في النموذج قبل المتابعة');
            return;
        }
        $('#step2-errors').addClass('d-none').empty();
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الإرسال...');
        // أرسل الأقسام المختارة (حلل القيم sub-X-parent-Y و main-X)
        let departments = [];
        let mainDepartments = [];
        $('.department-checkbox:checked').each(function(){
            const val = $(this).val();
            if(val.startsWith('sub-')) {
                // مثال: sub-5-parent-2
                const matches = val.match(/^sub-(\d+)-parent-(\d+)$/);
                if(matches) {
                    departments.push(matches[1]); // id الفرعي
                    mainDepartments.push(matches[2]); // id الرئيسي
                }
            } else if(val.startsWith('main-')) {
                const matches = val.match(/^main-(\d+)$/);
                if(matches) {
                    mainDepartments.push(matches[1]);
                }
            }
        });
        // إزالة التكرار
        mainDepartments = [...new Set(mainDepartments)];
        departments = [...new Set(departments)];

        // تحديد المدن حسب نوع المستخدم
        let selectedCities = [];
        if($('input[name="role"]:checked').val() == '3') {
            selectedCities = $('#provider_cities').val();
        } else {
            selectedCities = [$('select[name="governement"]').val()];
        }

        // أرسلهم مع البيانات
        $.ajax({
            url: '{{ route('register') }}',
            method: 'POST',
            data: {
                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                phone: $('input[name="phone"]').val(),
                email: $('input[name="email"]').val(),
                country: $('select[name="country"]').val(),
                governement: selectedCities,
                password: $('input[name="password"]').val(),
                password_confirmation: $('input[name="password_confirmation"]').val(),
                role: $('input[name="role"]:checked').val(),
                departments: departments,
                main_departments: mainDepartments,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.success) {
                    otpCode = response.otp;
                $('#phone-display').text($('input[name="phone"]').val());
                    $('#otp-code-display').text('كود التحقق (للعرض فقط): ' + otpCode);
                    $('#otp-demo').show();
                startResendCountdown();
                showStep(3);
                    $('[data-step]').removeClass('active');
                    $('[data-step="3"]').addClass('active');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                for (let field in errors) {
                    $(`[name="${field}"]`).addClass('is-invalid');
                    $(`[name="${field}"]`).next('.invalid-feedback').text(errors[field][0]);
                }
            }
        });
    });
    // تحقق خطوة المدن والأقسام
    function validateDepartmentsStep() {
        // التحقق من المدن
        const selectedCities = $('#provider_cities').val();
        if (!selectedCities || selectedCities.length === 0) {
            $('#provider_cities').addClass('is-invalid');
            $('#step15-errors').removeClass('d-none').text('يرجى اختيار مدينة واحدة على الأقل');
            return false;
        }
        $('#provider_cities').removeClass('is-invalid');

        // التحقق من الأقسام الرئيسية
        const mainChecked = $('.main-department-checkbox:checked').length;
        const subChecked = $('.sub-department-checkbox:checked').length;

        if(mainChecked == 0 && subChecked == 0) {
            $('#departments-error').text('يرجى اختيار قسم واحد على الأقل').show();
            return false;
        }
        if(mainChecked > 3) {
            $('#departments-error').text('يمكنك اختيار 3 أقسام رئيسية فقط').show();
            return false;
        }
        $('#departments-error').hide();
        $('#step15-errors').addClass('d-none').empty();
        return true;
    }

    // منع اختيار أكثر من 3 أقسام رئيسية
    $(document).on('change', '.main-department-checkbox', function() {
        const checkedCount = $('.main-department-checkbox:checked').length;
        if(checkedCount > 3) {
            this.checked = false;
            $('#departments-error').text('يمكنك اختيار 3 أقسام رئيسية فقط').show();
        } else {
            $('#departments-error').hide();
        }

        // تحديث العداد
        updateDepartmentCounters();
    });

        // عند اختيار قسم رئيسي، إلغاء تحديد الأقسام الفرعية التابعة له
    $(document).on('change', '.main-department-checkbox', function() {
        const departmentId = $(this).val();
        const isChecked = $(this).is(':checked');

        if(isChecked) {
            // إلغاء تحديد الأقسام الفرعية التابعة لهذا القسم الرئيسي
            $(`.sub-department-checkbox[data-parent="${departmentId}"]`).prop('checked', false);
        }

        // تحديث العداد
        updateDepartmentCounters();
    });

        // عند اختيار قسم فرعي، إلغاء تحديد القسم الرئيسي التابع له
    $(document).on('change', '.sub-department-checkbox', function() {
        const parentId = $(this).data('parent');
        const isChecked = $(this).is(':checked');

        if(isChecked) {
            // إلغاء تحديد القسم الرئيسي
            $(`#main-${parentId}`).prop('checked', false);
        }

        // تحديث العداد
        updateDepartmentCounters();
    });

    // دالة تحديث عداد الأقسام
    function updateDepartmentCounters() {
        const mainCount = $('.main-department-checkbox:checked').length;
        const subCount = $('.sub-department-checkbox:checked').length;

        $('#main-count').text(mainCount);
        $('#sub-count').text(subCount);

        // تغيير لون العداد حسب الحد الأقصى
        if(mainCount >= 3) {
            $('#main-count').parent().addClass('text-danger');
        } else {
            $('#main-count').parent().removeClass('text-danger');
        }
    }

    // تحديث العداد عند التحميل
    $(document).ready(function() {
        updateDepartmentCounters();
    });

    // Previous step
    $('.prev-step').on('click', function() {
        showStep(currentStep - 1);
    });

    // Form submission (final step)
    $('#register-steps-form').on('submit', function(e) {
        e.preventDefault();

        if (!$('input[name="otp"]').val() || $('input[name="otp"]').val().length !== 4) {
            $('#step3-errors').removeClass('d-none').text('يرجى إدخال رمز التحقق المكون من 4 أرقام');
            $('input[name="otp"]').addClass('is-invalid');
            return;
        }

        $('#step3-errors').addClass('d-none').empty();
        $('input[name="otp"]').removeClass('is-invalid');

        // إرسال طلب التحقق من OTP
    // إرسال طلب التحقق من OTP
const submitBtn = $(this).find('button[type="submit"]');
submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التحقق...');

$.ajax({
    url: '{{ route("otp.verify") }}',
    method: 'POST',
    data: {
        otp: $('input[name="otp"]').val(),
        _token: $('input[name="_token"]').val(),
        phone: $('input[name="phone"]').val() // إضافة رقم الهاتف للتحقق
    },
    success: function(response) {
        console.log(response);
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم التحقق بنجاح!',
                text: 'تم تفعيل حسابك وسيتم تحويلك للصفحة الرئيسية.',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(function() {
                window.location.href = response.redirect;
            }, 2000);
        }
    },
    error: function(xhr) {
        let msg = 'حدث خطأ أثناء التحقق من الرمز، يرجى المحاولة مرة أخرى.';
        if (xhr.responseJSON && xhr.responseJSON.error) {
            msg = xhr.responseJSON.error;
        }
        $('#step3-errors').removeClass('d-none').text(msg);
        $('input[name="otp"]').addClass('is-invalid');
        submitBtn.prop('disabled', false).html('إنهاء التسجيل <i class="fas fa-check-circle ms-2"></i>');
    }
});
    });

    // Resend OTP
    $('#resend-otp').on('click', function(e) {
        e.preventDefault();
        if (secondsLeft > 0) return;

        $.ajax({
            url: '{{ route('register.resend_otp') }}',
            method: 'POST',
            data: {
                phone: $('input[name="phone"]').val(),
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                secondsLeft = 60;
                startResendCountdown();

                const originalText = $('#resend-otp').text();
                $('#resend-otp').text('تم الإرسال!').addClass('text-success');
                setTimeout(() => {
                    $('#resend-otp').text(originalText).removeClass('text-success');
                }, 2000);
            },
            error: function() {
                alert('حدث خطأ أثناء إعادة إرسال الرمز، يرجى المحاولة مرة أخرى');
            }
        });
    });

    // Start resend countdown
    function startResendCountdown() {
        clearInterval(resendTimer);
        $('#resend-otp').css('pointer-events', 'none').addClass('text-muted');
        $('#countdown').text(`(يمكنك إعادة الإرسال خلال ${secondsLeft} ثانية)`);

        resendTimer = setInterval(function() {
            secondsLeft--;
            if (secondsLeft <= 0) {
                clearInterval(resendTimer);
                $('#resend-otp').css('pointer-events', 'auto').removeClass('text-muted');
                $('#countdown').text('(يمكنك الآن إعادة إرسال الرمز)');
            } else {
                $('#countdown').text(`(يمكنك إعادة الإرسال خلال ${secondsLeft} ثانية)`);
            }
        }, 1000);
    }

    // Auto move between OTP digits
    $('.verification-code').on('input', function() {
        if ($(this).val().length === 4) {
            $(this).removeClass('is-invalid');
            $('#step3-errors').addClass('d-none').empty();
        }
    });

    // Real-time validation for step 2
    $('.step-2 input, .step-2 select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $('#step2-errors').addClass('d-none').empty();
    });

    // Real-time validation for step 1.5
    $('#provider_cities').on('change', function() {
        $(this).removeClass('is-invalid');
        $('#step15-errors').addClass('d-none').empty();
    });

    // Load governorates when country is selected
    $('#country').on('change', function() {
        const countryId = $(this).val();
        const governementSelect = $('#governement');

        if (countryId) {
            $.ajax({
                url: '{{ route("get.governorates") }}',
                method: 'GET',
                data: { country_id: countryId },
                success: function(response) {
                    governementSelect.empty();
                    governementSelect.append('<option value="">اختر المدينة</option>');

                    response.forEach(function(governorate) {
                        const name = '{{ app()->getLocale() }}' === 'ar' ? governorate.name_ar : governorate.name_en;
                        governementSelect.append(`<option value="${governorate.id}">${name}</option>`);
                    });
                },
                error: function() {
                    console.error('Error loading governorates');
                }
            });
        } else {
            governementSelect.empty();
            governementSelect.append('<option value="">اختر المدينة</option>');
        }
    });

    // Initialize select2 for better UX
    $('select').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Initialize select2 for provider cities when step 1.5 is shown
    $(document).on('shown.bs.step', function() {
        if($('.step-1-5').is(':visible')) {
            $('#provider_cities').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'اختر المدن التي تعمل فيها',
                allowClear: true,
                closeOnSelect: false
            });
        }
    });

    // Load all cities on page load
    loadAllCities();

    function loadAllCities() {
        $.ajax({
            url: '{{ route("get.governorates") }}',
            type: 'GET',
            data: { country_id: 0 }, // 0 means all cities
                        success: function(response) {
                var governementSelect = $('#governement');
                var providerCitiesSelect = $('#provider_cities');

                governementSelect.empty();
                governementSelect.append('<option value="">اختر المدينة</option>');

                providerCitiesSelect.empty();

                response.forEach(function(city) {
                    var cityName = '{{ app()->getLocale() }}' === 'ar' ? city.name_ar : city.name_en;
                    governementSelect.append('<option value="' + city.id + '">' + cityName + '</option>');
                    providerCitiesSelect.append('<option value="' + city.id + '">' + cityName + '</option>');
                });
            },
            error: function() {
                console.log('Error loading cities');
            }
        });
    }
});
</script>
@endsection
