@extends('layouts.home')
@section('title', 'تسجيل جديد - خطوات')
@section('content')
<style>
    .steps-progress {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 15px;
    }

    .step-indicator {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        position: relative;
    }

    .step-indicator.active {
        background-color: white;
        color: #4e73df;
    }

    .step-indicator:not(:last-child):after {
        content: '';
        position: absolute;
        width: 20px;
        height: 2px;
        background-color: rgba(255, 255, 255, 0.3);
        left: 100%;
    }

    .step-indicator.active:not(:last-child):after {
        background-color: white;
    }

    .role-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
    }

    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .role-card.client-card:hover {
        border-color: #17a2b8;
    }

    .role-card.provider-card:hover {
        border-color: #ffc107;
    }

    .role-card.active {
        border-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.05);
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: #4e73df;
    }

    .client-card .icon-circle {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .provider-card .icon-circle {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .verification-code {
        font-size: 24px;
        font-weight: bold;
    }

    .was-validated .form-control:invalid,
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
        padding-right: 12px;
    }

    .was-validated .form-control:valid,
    .form-control.is-valid {
        border-color: #28a745;
        background-image: none;
        padding-right: 12px;
    }
</style>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-user-plus"></i> إنشاء حساب جديد</h3>
                    <div class="steps-progress mt-3">
                        <div class="step-indicator active" data-step="1"></div>
                        <div class="step-indicator" data-step="2"></div>
                        <div class="step-indicator" data-step="3"></div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form id="register-steps-form" method="POST" action="{{ route('register') }}" novalidate>
                        @csrf

                        <!-- Step 1: Role Selection -->
                        <div class="step step-1">
                            <div id="step1-errors" class="alert alert-danger d-none"></div>
                            <div class="text-center mb-5">
                                <i class="fas fa-user-tie fa-4x text-primary mb-3"></i>
                                <h4>اختر نوع حسابك</h4>
                                <p class="text-muted">سيحدد هذا الاختيار الميزات المتاحة لك في المنصة</p>
                            </div>
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="role-card client-card" data-role="1">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-circle mb-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <h5>الرول 1</h5>
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
                                            <h5>الرول 3</h5>
                                            <p class="text-muted">نوع الحساب الثالث</p>
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

                        <!-- Step 2: User Information -->
                        <div class="step step-2 d-none">
                            <div id="step2-errors" class="alert alert-danger d-none"></div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-user"></i> الاسم الأول</label>
                                    <input type="text" name="first_name" class="form-control form-control-lg" required
                                           placeholder="أدخل اسمك الأول" pattern="[\p{Arabic}\s]+">
                                    <div class="invalid-feedback">يرجى إدخال اسم أول صحيح (أحرف عربية فقط)</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-user"></i> الاسم الأخير</label>
                                    <input type="text" name="last_name" class="form-control form-control-lg" required
                                           placeholder="أدخل اسمك الأخير" pattern="[\p{Arabic}\s]+">
                                    <div class="invalid-feedback">يرجى إدخال اسم أخير صحيح (أحرف عربية فقط)</div>
                                </div>
                            </div>
                            <!-- اختيار الأقسام لمزود الخدمة -->
                            @php
                                $departments = \App\Models\Department::where('department_id',0)->where('status',1)->get(['id','name_ar','name_en']);
                            @endphp
                            <div class="mb-3" id="departments-section" style="display:none;">
                                <label class="form-label"><i class="fas fa-list"></i> اختر حتى 3 أقسام تقدم فيها الخدمة</label>
                                <div class="row">
                                    @foreach($departments as $department)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input department-checkbox" type="checkbox" name="departments[]" value="{{ $department->id }}" id="dep-{{ $department->id }}">
                                                <label class="form-check-label" for="dep-{{ $department->id }}">
                                                    {{ app()->getLocale() == 'ar' ? $department->name_ar : $department->name_en }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback d-block" id="departments-error" style="display:none;"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-phone"></i> رقم الهاتف</label>
                                <div class="input-group">
                                    <span class="input-group-text">+966</span>
                                    <input type="tel" name="phone" class="form-control form-control-lg" required
                                           placeholder="5XXXXXXXX" pattern="5[0-9]{8}">
                                </div>
                                <div class="invalid-feedback">يرجى إدخال رقم هاتف صحيح (مثال: 512345678)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-envelope"></i> البريد الإلكتروني (اختياري)</label>
                                <input type="email" name="email" class="form-control form-control-lg"
                                       placeholder="example@example.com">
                                <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-globe"></i> البلد</label>
                                <select name="country" id="country" class="form-control form-control-lg" required>
                                    <option value="">اختر البلد</option>
                                    @foreach (\App\Models\Country::all() as $country)
                                        <option value="{{ $country->id }}" data-code="{{ $country->code }}">
                                            {{ app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">يرجى اختيار البلد</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-map-marker-alt"></i> المحافظة</label>
                                <select name="governement" id="governement" class="form-control form-control-lg" required>
                                    <option value="">اختر المحافظة</option>
                                </select>
                                <div class="invalid-feedback">يرجى اختيار المحافظة</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-lock"></i> كلمة المرور</label>
                                <input type="password" name="password" class="form-control form-control-lg" required
                                       placeholder="أدخل كلمة المرور">
                                <div class="invalid-feedback">يرجى إدخال كلمة المرور</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-lock"></i> تأكيد كلمة المرور</label>
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
                                <h4>تحقق من هاتفك</h4>
                                <p class="text-muted">لقد أرسلنا رمز التحقق إلى رقم الهاتف <span class="fw-bold" id="phone-display"></span></p>
                                <div class="alert alert-info d-flex align-items-center" id="otp-demo" style="display:none;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="otp-code-display"></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-key"></i> رمز التحقق</label>
                                <div class="d-flex justify-content-center">
                                    <input type="text" name="otp" class="form-control form-control-lg text-center verification-code"
                                           maxlength="4" pattern="\d{4}" required style="width: 150px; letter-spacing: 5px;">
                                </div>
                                <div class="invalid-feedback text-center">يرجى إدخال رمز التحقق المكون من 4 أرقام</div>
                                <div class="text-center mt-3">
                                    <a href="#" id="resend-otp" class="text-primary">إعادة إرسال الرمز</a>
                                    <div class="text-muted small mt-1" id="countdown">(يمكنك إعادة الإرسال خلال 60 ثانية)</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary prev-step">
                                    <i class="fas fa-arrow-right me-2"></i> السابق
                                </button>
                                <button type="submit" class="btn btn-primary">
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
<script>
$(document).ready(function() {
    let currentStep = 1;
    let otpCode = null;
    let resendTimer = null;
    let secondsLeft = 60;

    // Show current step
    function showStep(step) {
        $('.step').addClass('d-none');
        $('.step-' + step).removeClass('d-none');
        $('.step-indicator').removeClass('active');
        $(`.step-indicator[data-step="${step}"]`).addClass('active');
        currentStep = step;
        $('html, body').animate({ scrollTop: $('.card').offset().top - 20 }, 300);
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
                if (input.attr('name') === 'phone' && !/^5\d{8}$/.test(input.val())) {
                    input.addClass('is-invalid');
                    isValid = false;
                }
                if (input.attr('name') === 'password' && input.val().length < 6) {
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

        // تحقق من الأقسام إذا كان مزود خدمة
        if($('input[name="role"]:checked').val() == '3') {
            if($('.department-checkbox:checked').length == 0) {
                $('#departments-error').text('يرجى اختيار قسم واحد على الأقل').show();
                isValid = false;
            } else {
                $('#departments-error').hide();
            }
        }
        return isValid;
    }

    // Next step from 1 to 2 (role -> user info)
    $('.step-1 .next-step').on('click', function() {
        if (!validateStep1()) return;
        showStep(2);
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
        // جمع الأقسام المختارة
        let departments = [];
        $('.department-checkbox:checked').each(function(){
            departments.push($(this).val());
        });
        $.ajax({
            url: '{{ route('register') }}',
            method: 'POST',
            data: {
                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                phone: $('input[name="phone"]').val(),
                email: $('input[name="email"]').val(),
                country: $('select[name="country"]').val(),
                governement: $('select[name="governement"]').val(),
                password: $('input[name="password"]').val(),
                password_confirmation: $('input[name="password_confirmation"]').val(),
                role: $('input[name="role"]:checked').val(),
                departments: departments,
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

    // Next step from 3 (OTP -> submit)
    $('.step-3 .next-step').on('click', function() {
        // Not needed, form submits directly
    });

    // Role selection
    $('.role-card').on('click', function() {
        $('.role-card').removeClass('active');
        $(this).addClass('active');
        const role = $(this).data('role');
        $(`#role-${role}`).prop('checked', true);
        // إظهار/إخفاء اختيار الأقسام حسب الدور
        if(role == 3) {
            $('#departments-section').show();
        } else {
            $('#departments-section').hide();
            $('.department-checkbox').prop('checked', false);
            $('#departments-error').hide();
        }
    });
    // منع اختيار أكثر من 3 أقسام
    $(document).on('change', '.department-checkbox', function() {
        const checkedCount = $('.department-checkbox:checked').length;
        if(checkedCount > 3) {
            this.checked = false;
            $('#departments-error').text('يمكنك اختيار 3 أقسام فقط').show();
        } else {
            $('#departments-error').hide();
        }
    });

    // Previous step
    $('.prev-step').on('click', function() {
        showStep(currentStep - 1);
    });

    // Form submission (final step)
    $('#register-steps-form').on('submit', function(e) {
        if (!$('input[name="otp"]').val() || $('input[name="otp"]').val().length !== 4) {
            e.preventDefault();
            $('#step3-errors').removeClass('d-none').text('يرجى إدخال رمز التحقق المكون من 4 أرقام');
            $('input[name="otp"]').addClass('is-invalid');
            return;
        }
        if ($('input[name="otp"]').val() != otpCode) {
            e.preventDefault();
            $('#step3-errors').removeClass('d-none').text('رمز التحقق غير صحيح');
            $('input[name="otp"]').addClass('is-invalid');
            return;
        }
        $('#step3-errors').addClass('d-none').empty();
        $('input[name="otp"]').removeClass('is-invalid');
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
                otpCode = response.otp;
                $('#otp-code-display').text('كود التحقق الجديد (للعرض فقط): ' + otpCode);
                $('#otp-demo').show();
                secondsLeft = 60;
                startResendCountdown();
                const originalText = $('#resend-otp').text();
                $('#resend-otp').text('تم الإرسال!');
                setTimeout(() => {
                    $('#resend-otp').text(originalText);
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
    $('.step-2 input').on('input', function() {
        $(this).removeClass('is-invalid');
        $('#step2-errors').addClass('d-none').empty();
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
                    governementSelect.append('<option value="">اختر المحافظة</option>');

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
            governementSelect.append('<option value="">اختر المحافظة</option>');
        }
    });

    // Validate select fields in step 2
    $('.step-2 select').on('change', function() {
        $(this).removeClass('is-invalid');
        $('#step2-errors').addClass('d-none').empty();
    });
});
</script>
@endsection
