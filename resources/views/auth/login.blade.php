@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
    <div class="auth-container login-mode" id="authContainer">
        <div class="auth-card login-card">
            <!-- الجانب الأيسر -->
            <div class="side-panel left-panel">
                <div class="content text-center">
                    <i class="fas fa-door-open fa-3x text-warning mb-3"></i>
                    <h2>أهلًا بعودتك!</h2>
                    <p>ليس لديك حساب بعد؟</p>
                    <button class="btn btn-outline-light mt-3 switch-btn" id="switchToRegister">إنشاء حساب جديد</button>
                </div>
            </div>

            <!-- قسم النموذج -->
            <div class="form-section fadeInRight">
                <div class="logo mb-4 text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark fs-3 fw-bold">
                        <img src="{{ asset('home.png') }}" alt="Endak Logo" class="me-2"
                            style="height: 50px; width: auto;"> Endak
                    </a>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3 position-relative">
                        <i class="fas fa-mobile-alt input-icon text-secondary"></i>
                        <input type="tel" class="form-control" name="phone" placeholder="رقم الهاتف" required>
                    </div>

                    <div class="mb-3 position-relative">
                        <i class="fas fa-lock input-icon text-secondary"></i>
                        <input type="password" class="form-control" name="password" placeholder="كلمة المرور" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">تذكرني</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100">تسجيل الدخول</button>

                    <!-- زر تسجيل الدخول عبر OTP -->
                    <button type="button" class="btn btn-outline-secondary w-100 mt-3" data-bs-toggle="modal"
                        data-bs-target="#otpModal">
                        هل تريد تسجيل الدخول عبر رمز التحقق (OTP)؟
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- مودال OTP -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">تسجيل الدخول عبر OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 notice-section mb-3" dir="rtl">
                        </div>
                        <div class="col-12 info-section" dir="rtl">
                            <p>أدخل رقم الهاتف لاستلام رمز التحقق عبر رسالة قصيرة.</p>
                            <div class="mb-3 position-relative">
                                <i class="fas fa-mobile-alt input-icon text-secondary"></i>
                                <input type="tel" name="phone" id="otp" class="form-control" dir="rtl"
                                    placeholder="رقم الهاتف" required>
                            </div>
                        </div>
                        <div class="col-12 otp-section d-none" dir="rtl">
                            <p>أدخل رمز التحقق المرسل إلى رقم هاتفك.</p>
                            <div class="mb-3 position-relative">
                                <i class="fas fa-key input-icon text-secondary"></i>
                                <input type="text" name="otp_code" id="otp_code" class="form-control" dir="rtl"
                                    placeholder="رمز التحقق" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" dir="rtl">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="send-otp">إرسال رمز التحقق</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("switchToRegister").addEventListener("click", function() {
            window.location.href = "{{ route('register') }}";
        });
    </script>

    <style>
        /* ========== عام ========== */
        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f6fa;
            overflow-x: hidden;
        }

        /* ========== الحاوية الرئيسية ========== */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ========== البطاقة ========== */
        .auth-card {
            display: flex;
            width: 900px;
            max-width: 95%;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideInLeft 0.8s ease;
        }

        /* ========== الجانب الأيسر ========== */
        .side-panel {
            width: 45%;
            background: linear-gradient(135deg, #2f5c69, #3c7d8b);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }

        .side-panel .btn {
            border-radius: 30px;
            padding: 0.6rem 1.5rem;
            border: 2px solid #fff;
            color: #fff;
            transition: all 0.3s ease;
        }

        .side-panel .btn:hover {
            background: #f3a446;
            border-color: #f3a446;
        }

        /* ========== قسم النموذج ========== */
        .form-section {
            width: 55%;
            padding: 3rem;
        }

        /* ========== الـ Inputs ========== */
        .form-control {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 0.75rem 2.8rem 0.75rem 1rem;
            /* مساحة كافية للأيقونة */
            color: #333;
            font-size: 15px;
        }

        .form-control::placeholder {
            color: #999;
            font-style: italic;
            opacity: 0.9;
        }

        /* الأيقونة داخل الـ input */
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            pointer-events: none;
        }

        /* عند التركيز */
        .form-control:focus {
            border-color: #3c7d8b;
            box-shadow: 0 0 6px rgba(47, 92, 105, 0.3);
            outline: none;
        }

        /* ========== زر تسجيل الدخول ========== */
        .btn-login {
            background: linear-gradient(90deg, #2f5c69, #3c7d8b);
            border: none;
            border-radius: 30px;
            padding: 0.75rem;
            color: #fff;
            font-weight: 600;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            background: #f3a446;
        }

        /* ========== الأنيميشن ========== */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ========== الموبايل ========== */
        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                width: 95%;
                animation: slideDown 0.8s ease;
            }

            .side-panel {
                width: 100%;
                border-radius: 20px 20px 0 0;
                padding: 1.5rem;
            }

            .form-section {
                width: 100%;
                padding: 2rem;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-80px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
@endsection
@push('scripts')
    <script>
        // OTP code
        $('#otp').on('input', function() {
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        // Focus on the input when modal opens
        $('#otpModal').on('shown.bs.modal', function() {
            $('#otp').focus();
        });

        $('#send-otp').on('click', function() {
            var phone = $('#otp').val().trim();
            if (phone === '') {
                alert('يرجى إدخال رقم الهاتف.');
                return;
            }

            $.ajax({
                url: "{{ route('send-otp') }}",
                method: 'POST',
                data: {
                    phone: phone,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('.notice-section').empty();
                    $('.notice-section').html(
                        '<div class="alert alert-success" role="alert">تم إرسال رمز التحقق إلى واتس اب.</div>'
                    );
                    $('.otp-section').removeClass('d-none');
                    $('.info-section').addClass('d-none');
                },
                error: function(xhr) {
                    $('.notice-section').html(
                        '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إرسال رمز التحقق. يرجى المحاولة مرة أخرى.</div>'
                    );
                }
            });
        });
    </script>
@endpush
