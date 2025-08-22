<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-nav-style="menu-hover" data-theme-mode="light">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Eslam Badawy">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- TITLE -->
    <title>@yield('title')</title>

    <!-- Meta -->
    <meta property="og:title" content="عندك">
    <meta property="og:description" content="وصف مختصر عن الموقع.">
    <meta property="og:image" content="{{ asset('images/logo.jpg') }}">
    <meta property="og:url" content="https://endak.net/">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo2.jpg') }}">

    <!-- Critical CSS - Load First -->
    <style>
        /* Critical CSS for above-the-fold content */
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 8px 16px; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; color: white; }
        .text-center { text-align: center; }
        .mb-3 { margin-bottom: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .p-4 { padding: 1.5rem; }
        .d-flex { display: flex; }
        .justify-content-end { justify-content: flex-end; }
        .gap-2 { gap: 0.5rem; }
        .mx-1 { margin-left: 0.25rem; margin-right: 0.25rem; }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #ffcc00;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            z-index: 1000;
        }
        .bottom-nav a {
            text-align: center;
            color: #000;
            font-size: 12px;
            text-decoration: none;
        }
        .bottom-nav i {
            display: block;
            font-size: 18px;
        }

        /* Loading indicator */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .loading.hidden { display: none; }

        /* Image optimization */
        img {
            max-width: 100%;
            height: auto;
        }
        img[width][height] {
            aspect-ratio: attr(width) / attr(height);
        }
        img.lazy {
            background: #f0f0f0;
            min-height: 200px;
        }

        /* Prevent layout shift for images */
        .img-container {
            position: relative;
            overflow: hidden;
        }
        .img-container::before {
            content: '';
            display: block;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
        }
        .img-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('home/assets/css/styles.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('home/assets/libs/bootstrap/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" as="style">

    <!-- Non-critical CSS - Load asynchronously -->
    <link rel="preload" href="{{ asset('home/assets/css/styles.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('home/assets/css/styles.min.css') }}"></noscript>

    <link rel="preload" href="{{ asset('home/assets/libs/bootstrap/css/bootstrap.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('home/assets/libs/bootstrap/css/bootstrap.min.css') }}"></noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"></noscript>

    <!-- Other CSS - Load after critical content -->
    <link rel="stylesheet" href="{{ asset('home/assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('home/assets/css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('select2-4.0.3/css/select2.css') }}">

    @yield('style')
</head>

<body class="main-body light-theme" data-theme="light">
    @php
        $lang = config('app.locale');
        $user = auth()->user();
        $isPlasticStore = request()->routeIs('indsproducts.index') || request()->routeIs('indsproducts.show') || request()->routeIs('indsproducts.products');
    @endphp

    <!-- Back-to-top -->


    <!-- <a href="javascript:void(0);" class="buy-now" data-target="html">
        <span class="fe fe-message-square"></span>
    </a> -->
    <a href="#top" id="back-to-top" class="back-to-top rounded-circle shadow all-ease-03 fade-in">
        <i class="fe fe-arrow-up"></i>
    </a>

    <div class="page">

        <?php $lang = config('app.locale'); ?>


        @include('layouts.front_office.header')



        {{-- <section>
                <div class="section banner-4 banner-section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <div class="">
                                    <p class="mb-3 content-1 h5 text-white">Blog <span class="tx-info-dark">Page</span></p>
                                    <p class="mb-0 tx-28">We Fight Passionately to Get Our Clients Every Time They Deserve</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section> --}}
        @yield('content')
        @php  $lang = config('app.locale'); @endphp
        <nav class="bottom-nav">

            <a href="{{ route('home') }}"><i class="fas fa-home"></i> {{ $lang == 'ar' ? 'الرئيسية' : 'Home' }}</a>
            @auth
                <a href="{{ route('notifications.index') }}" id="notificationsLink1" style="position: relative;">
                    <i class="fas fa-bell"></i> {{ $lang == 'ar' ? 'الإشعارات' : 'Notifications' }}
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span id="notificationsBadge1" style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('web.send_message', auth()->id()) }}" id="messagesLink1" style="position: relative;">
                    <i class="fas fa-envelope"></i> {{ $lang == 'ar' ? 'الرسائل' : 'Messages' }}
                    @php
                        $unreadMessagesCount = \App\Models\Message::where('recipient_id', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    @if($unreadMessagesCount > 0)
                        <span id="messagesBadge1" style="position: absolute; top: -8px; right: -8px; background: #007bff; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                        </span>
                    @endif
                </a>
                @if($user->role_id == 1)
                    <a href="{{ route('services.index') }}"><i class="fas fa-clipboard-list"></i> {{ $lang == 'ar' ? 'طلباتي' : 'My Orders' }}</a>
                @elseif($user->role_id == 3)
                    <a href="{{ route('web.comments.my_comments', $user->id) }}"><i class="fas fa-comments"></i> {{ $lang == 'ar' ? 'عروضي' : 'My Offers' }}</a>
                @endif
                @if($isPlasticStore)
                    <a href="{{ route('pro_cart.index') }}"><i class="fas fa-shopping-cart"></i> {{ $lang == 'ar' ? 'السلة' : 'Cart' }}</a>
                @endif
            @endauth

            {{-- <a href="#"><i class="fas fa-plus-circle"></i> نشر منتج</a> --}}
        </nav>

    </div>




    @include('layouts.front_office.footer')

    <!-- Loading indicator -->
    <div class="loading" id="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Fixed WhatsApp Button for Mobile -->
    <a href="https://wa.me/966568401348" target="_blank" class="fixed-whatsapp-btn d-md-none">
        <i class="fab fa-whatsapp"></i>
    </a>

    @php  $lang = config('app.locale'); @endphp
    <nav class="bottom-nav">

               @if(auth()->check() && auth()->user()->role_id == 3)
            <a href="{{ route('all_services') }}"><i class="fas fa-home"></i> {{ $lang == 'ar' ? 'الخدمات المطلوبة' : 'All Services' }}</a>
        @else
            <a href="{{ route('departments') }}"><i class="fas fa-th-large"></i> {{ $lang == 'ar' ? 'الخدمات' : 'Services' }}</a>
        @endif
        @auth
        <a href="{{ route('notifications.index') }}" id="notificationsLink2" style="position: relative;">
            <i class="fas fa-bell"></i> {{ $lang == 'ar' ? 'الإشعارات' : 'Notifications' }}
            @php
                $unreadCount = auth()->user()->unreadNotifications()->count();
            @endphp
            @if($unreadCount > 0)
                <span id="notificationsBadge2" style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('web.send_message', auth()->id()) }}" id="messagesLink2" style="position: relative;">
            <i class="fas fa-envelope"></i> {{ $lang == 'ar' ? 'الرسائل' : 'Messages' }}
            @php
                $unreadMessagesCount = \App\Models\Message::where('recipient_id', auth()->id())->whereNull('read_at')->count();
            @endphp
            @if($unreadMessagesCount > 0)
                <span id="messagesBadge2" style="position: absolute; top: -8px; right: -8px; background: #007bff; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                </span>
            @endif
        </a>
        @if($user->role_id == 1)
            <a href="{{ route('services.index') }}"><i class="fas fa-clipboard-list"></i> {{ $lang == 'ar' ? 'طلباتي' : 'My Orders' }}</a>
        @elseif($user->role_id == 3)
            <a href="{{ route('web.comments.my_comments', $user->id) }}"><i class="fas fa-comments"></i> {{ $lang == 'ar' ? 'عروضي' : 'My Offers' }}</a>
        @endif
        @if($isPlasticStore)
        <a href="{{ route('orders.index') }}"><i class="fas fa-clipboard-list"></i> {{ $lang == 'ar' ? 'طلباتي' : 'My Orders' }}</a>
            <a href="{{ route('pro_cart.index') }}"><i class="fas fa-shopping-cart"></i> {{ $lang == 'ar' ? 'السلة' : 'Cart' }}</a>
        @endif
        <a href="{{ route('user.settings.account.show') }}"><i class="fas fa-eye"></i> {{ $lang == 'ar' ? 'عرض الإعدادات' : 'View' }}</a>
        <a href="{{ route('user.settings.profile') }}"><i class="fas fa-user-edit"></i> {{ $lang == 'ar' ? 'الملف الشخصي' : 'Profile' }}</a>
        @endauth

        {{-- <a href="#"><i class="fas fa-plus-circle"></i> نشر منتج</a> --}}
    </nav>

    @if (Session::has('error'))
        <script>
            swal("Message", "{{ Session::get('error') }}", 'error', {
                button: true,
                button: "Ok",
                timer: 3000,
            })
        </script>
    @endif

    <!-- Bootstrap js -->
    <script src="{{ asset('home/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Popper JS -->
    <script src="{{ asset('home/assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('home/assets/js/defaultmenu.js') }}"></script>

    <!-- Categorymenu JS -->
    <script src="{{ asset('home/assets/js/category-menu.js') }}"></script>

    <!-- Accept-cookie JS -->
    <script src="{{ asset('home/assets/js/cookies.js') }}"></script>

    <!-- Custom-switcher JS -->
    <script src="{{ asset('home/assets/js/custom-switcher.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('home/assets/js/sticky.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('home/assets/js/custom.js') }}"></script>
    <script>
        // الحصول على لغة التطبيق من Laravel
        // $lang = config('app.locale');
        const appLanguage = "{{ config('app.locale') }}";
        // const appLanguage = "{{ app()->getLocale() }}";
        const rtlBtn = document.getElementById('rtlBtn'); // استبدل 'rtl-button-id' بالمعرف الفعلي للزر RTL
        const ltrBtn = document.getElementById('ltrBtn'); // استبدل 'ltr-button-id' بالمعرف الفعلي للزر LTR

        // تحديد الاتجاه بناءً على اللغة
        if (appLanguage === "ar") {
            localStorage.setItem("hostmartl", true);
            localStorage.removeItem("hostmaltr");
            rtlFn(); // نفذ وظيفة RTL إذا كانت اللغة العربية
        } else {
            localStorage.setItem("hostmaltr", true);
            localStorage.removeItem("hostmartl");
            ltrFn(); // نفذ وظيفة LTR إذا كانت اللغة ليست عربية
        }

        // إضافة مستمع للأزرار إذا كانت موجودة
        if (rtlBtn) {
            rtlBtn.addEventListener('click', () => {
                localStorage.setItem("hostmartl", true);
                localStorage.removeItem("hostmaltr");
                rtlFn();
            });
        }

        if (ltrBtn) {
            ltrBtn.addEventListener('click', () => {
                localStorage.setItem("hostmaltr", true);
                localStorage.removeItem("hostmartl");
                ltrFn();
            });
        }
    </script>
<script>
    let mediaRecorder;
    let audioChunks = [];
    let audioBlob;
    let stream;
    const startRecordBtn = document.getElementById('startRecord');
    const stopRecordBtn = document.getElementById('stopRecord');
    const resetRecordBtn = document.getElementById('resetRecord');
    const audioPlayback = document.getElementById('audioPlayback');
    const downloadLink = document.getElementById('downloadLink');
    const recordingStatus = document.getElementById('recordingStatus');
    const recordingTimer = document.getElementById('recordingTimer');
    let timerInterval;
    let seconds = 0;

    function updateTimerDisplay() {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        recordingTimer.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    function startTimer() {
        seconds = 0;
        updateTimerDisplay();
        recordingTimer.style.display = 'inline';
        timerInterval = setInterval(() => {
            seconds++;
            updateTimerDisplay();
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        recordingTimer.style.display = 'none';
    }

    startRecordBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        try {
            stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = function(e) {
                if (e.data.size > 0) {
                    audioChunks.push(e.data);
                }
            };
            mediaRecorder.onstart = function() {
                recordingStatus.style.display = 'block';
                recordingStatus.textContent = "{{ $lang == 'ar' ? 'جاري التسجيل...' : 'Recording...' }}";
                startRecordBtn.disabled = true;
                stopRecordBtn.disabled = false;
                resetRecordBtn.style.display = 'none';
                audioPlayback.style.display = 'none';
                downloadLink.style.display = 'none';
                startTimer();
            };
            mediaRecorder.onstop = function() {
                stopTimer();
                audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayback.src = audioUrl;
                audioPlayback.style.display = 'block';
                downloadLink.href = audioUrl;
                downloadLink.download = 'voice-note.wav';
                downloadLink.style.display = 'inline-block';
                recordingStatus.style.display = 'none';
                startRecordBtn.disabled = false;
                stopRecordBtn.disabled = true;
                resetRecordBtn.style.display = 'inline-block';
                // Stop all tracks
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            };
            mediaRecorder.start();
        } catch (error) {
            recordingStatus.style.display = 'block';
            recordingStatus.textContent = "{{ $lang == 'ar' ? 'خطأ في الوصول إلى الميكروفون: ' : 'Microphone access error: ' }}" + error.message;
            startRecordBtn.disabled = false;
            stopRecordBtn.disabled = true;
            resetRecordBtn.style.display = 'none';
        }
    });

    stopRecordBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
    });

    resetRecordBtn.addEventListener('click', function(e) {
        e.preventDefault();
        audioBlob = null;
        audioPlayback.src = '';
        audioPlayback.style.display = 'none';
        downloadLink.style.display = 'none';
        resetRecordBtn.style.display = 'none';
        startRecordBtn.disabled = false;
        stopRecordBtn.disabled = true;
        recordingStatus.style.display = 'none';
        seconds = 0;
        updateTimerDisplay();
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        if (audioBlob) {
            if (!document.querySelector('input[name=\"voice_note_data\"]')) {
                e.preventDefault();
                const reader = new FileReader();
                reader.readAsDataURL(audioBlob);
                reader.onloadend = function() {
                    const base64data = reader.result;
                    let existingInput = document.createElement('input');
                    existingInput.type = 'hidden';
                    existingInput.name = 'voice_note_data';
                    existingInput.value = base64data;
                    document.querySelector('form').appendChild(existingInput);
                    e.target.submit();
                };
            }
        }
    });

        // تفعيل قراءة جميع الإشعارات عند الضغط على أيقونة الإشعارات
    document.addEventListener('DOMContentLoaded', function() {
        const notificationsLinks = document.querySelectorAll('a[href*="notifications"]');

        notificationsLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // إرسال طلب AJAX لقراءة جميع الإشعارات
                fetch("{{ route('notifications.markAllAsRead') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        // إخفاء جميع badges
                        const badges = document.querySelectorAll('[id^="notificationsBadge"]');
                        badges.forEach(badge => {
                            badge.style.display = 'none';
                        });

                        // الانتقال إلى صفحة الإشعارات
                        window.location.href = this.href;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // في حالة الخطأ، إخفاء الـ badges أيضاً
                    const badges = document.querySelectorAll('[id^="messagesBadge"]');
                    badges.forEach(badge => {
                        badge.style.display = 'none';
                        console.log('Hidden badge on error:', badge.id);
                    });

                    // طريقة بديلة لإخفاء جميع badges في حالة الخطأ
                    const allBadges = document.querySelectorAll('span[style*="background: #007bff"]');
                    allBadges.forEach(badge => {
                        badge.style.display = 'none';
                        console.log('Hidden blue badge on error');
                    });

                    // طريقة ثالثة لإخفاء جميع badges في حالة الخطأ
                    const allSpans = document.querySelectorAll('span');
                    allSpans.forEach(span => {
                        if (span.style.backgroundColor === 'rgb(0, 123, 255)' || span.style.background === '#007bff') {
                            span.style.display = 'none';
                            console.log('Hidden span with blue background on error');
                        }
                    });

                    // في حالة الخطأ، انتقل إلى الصفحة مباشرة
                    console.log('Redirecting due to error');
                    window.location.href = this.href;
                });
            });
        });

                // تفعيل قراءة جميع الرسائل عند الضغط على أيقونة الرسائل
        const messagesLinks = document.querySelectorAll('a[href*="send_message"]');
        messagesLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // أخفي جميع الـ badges فورًا
                const badges = document.querySelectorAll('[id^="messagesBadge"]');
                badges.forEach(badge => {
                    badge.style.display = 'none';
                });
                // أخفي أي badge أزرق إضافي
                const allBadges = document.querySelectorAll('span[style*="background: #007bff"]');
                allBadges.forEach(badge => {
                    badge.style.display = 'none';
                });

                // أرسل الطلب للسيرفر (اختياري)
                fetch("{{ route('messages.markAllAsRead') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });

                // انتقل للصفحة بعد 200ms
                setTimeout(() => {
                    window.location.href = this.href;
                }, 200);
            });
        });
    });
</script>

        <!-- Optimized JavaScript Loading -->
    <script>
        // Performance optimization for Time to Interactive
        const perfOptimizer = {
            init() {
                this.hideLoadingIndicator();
                this.optimizeInteractivity();
                this.preloadCriticalResources();
            },

            hideLoadingIndicator() {
                const hideLoading = () => {
                    const loadingEl = document.getElementById('loading');
                    if (loadingEl) loadingEl.classList.add('hidden');
                };

                // Hide loading when DOM is interactive
                if (document.readyState === 'interactive' || document.readyState === 'complete') {
                    hideLoading();
                } else {
                    document.addEventListener('DOMContentLoaded', hideLoading);
                }

                // Fallback timeout
                setTimeout(hideLoading, 2000);
            },

            optimizeInteractivity() {
                // Defer non-critical operations until after page is interactive
                const deferredTasks = [];

                // Add tasks that can be deferred
                deferredTasks.push(() => {
                    // Initialize analytics or other non-critical features
                    console.log('Deferred tasks initialized');
                });

                // Execute deferred tasks after interaction is possible
                const executeDeferredTasks = () => {
                    requestIdleCallback(() => {
                        deferredTasks.forEach(task => {
                            try { task(); } catch(e) { console.warn('Deferred task failed:', e); }
                        });
                    });
                };

                if (document.readyState === 'complete') {
                    executeDeferredTasks();
                } else {
                    window.addEventListener('load', executeDeferredTasks);
                }
            },

            preloadCriticalResources() {
                // Preload resources that will be needed soon
                const criticalResources = [
                    { href: '/css/critical.css', as: 'style' },
                    { href: '/js/essential.js', as: 'script' }
                ];

                criticalResources.forEach(resource => {
                    if (!document.querySelector(`link[href="${resource.href}"]`)) {
                        const link = document.createElement('link');
                        link.rel = 'preload';
                        link.href = resource.href;
                        link.as = resource.as;
                        document.head.appendChild(link);
                    }
                });
            }
        };

        // Initialize performance optimizer
        perfOptimizer.init();
    </script>

    <!-- Load critical JavaScript first -->
    <script src="{{ asset('home/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

    <!-- Load non-critical JavaScript asynchronously -->
    <script src="{{ asset('home/assets/libs/@popperjs/core/umd/popper.min.js') }}" async></script>
    <script src="{{ asset('home/assets/js/defaultmenu.js') }}" async></script>
    <script src="{{ asset('home/assets/js/category-menu.js') }}" async></script>
    <script src="{{ asset('home/assets/js/cookies.js') }}" async></script>
    <script src="{{ asset('home/assets/js/custom-switcher.js') }}" async></script>

    <!-- Load essential JavaScript first -->
    <script src="{{ asset('js/optimized-core.js') }}" defer></script>

    <!-- Load modern JavaScript features -->
    <script src="{{ asset('js/modern-features.js') }}" defer></script>

    <!-- Load external libraries only when needed -->
    <script>
        // Lazy load jQuery and Select2 only if needed
        function loadLibraryIfNeeded(selector, libUrl, callback) {
            if (document.querySelector(selector)) {
                const script = document.createElement('script');
                script.src = libUrl;
                script.onload = callback;
                document.head.appendChild(script);
            }
        }

        // Load libraries after page load
        window.addEventListener('load', () => {
            // Load jQuery only if Select2 elements exist
            loadLibraryIfNeeded('.select2', 'https://code.jquery.com/jquery-3.6.0.min.js', () => {
                loadLibraryIfNeeded('.select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', () => {
                    $('.select2').select2();
                });
            });

            // Load SweetAlert only if swal function is called
            if (typeof swal !== 'undefined' || document.querySelector('[data-swal]')) {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js';
                document.head.appendChild(script);
            }
        });
    </script>

    <!-- Image optimization script -->
    <script src="{{ asset('js/image-optimizer.js') }}" async></script>

    <!-- Theme Switcher JavaScript -->
    <script src="{{ asset('js/theme-switcher.js') }}" async></script>

    @yield('script')
</body>

</html>
