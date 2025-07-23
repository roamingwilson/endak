<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-nav-style="menu-hover" data-theme-mode="light">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Eslam Badawy">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/themes/fontawesome-stars.css"
        rel="stylesheet">

    <!-- TITLE -->
    <title>@yield('title')</title>


    <!-- Meta -->
    <meta property="og:title" content="عندك">
    <meta property="og:description" content="وصف مختصر عن الموقع.">
    <meta property="og:image" content="{{ asset('images/logo.jpg') }}">
    <meta property="og:url" content="https://endak.net/">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo2.jpg') }}">


    <!-- Favicon -->
    {{-- <link rel="icon" href="{{ asset('assets/images/brand/favicon.ico') }}" type="image/x-icon"> --}}
    <link rel="stylesheet" href="{{ asset('home/assets/css/line-awesome.min.css') }}">

    <!-- BOOTSTRAP CSS -->
    {{-- <link id="style" href="{{ asset('home/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- STYLE CSS -->
    <link href="{{ asset('home/assets/css/styles.css') }}" rel="stylesheet">
    {{-- @if (config('app.locale') == 'ar') --}}
    <link href="{{ asset('home/assets/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    {{-- @else --}}
    <link id="style" href="{{ asset('home/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- @endif --}}
    <!-- Simonwep-picker CSS -->
    <link href="{{ asset('home/assets/libs/@simonwep/pickr/themes/classic.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/libs/@simonwep/pickr/themes/monolith.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/libs/@simonwep/pickr/themes/nano.min.css') }}" rel="stylesheet">
    <!-- إضافة Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('select2-4.0.3/css/select2.css') }}">
    <!-- ICONS CSS -->
    <link href="{{ asset('home/assets/css/icons.css') }}" rel="stylesheet">
    @yield('style')
    <style>
        a {
            text-decoration: none;
            color: black
        }

        * {
            color: black
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #ffcc00;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-top: 1px solid #ddd;
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

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
    </style>

</head>

<body class="main-body light-theme">
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
                <a href="{{ route('notifications.index') }}"><i class="fas fa-bell"></i> {{ $lang == 'ar' ? 'الإشعارات' : 'Notifications' }}</a>
                <a href="{{ route('web.send_message', auth()->id()) }}"><i class="fas fa-envelope"></i> {{ $lang == 'ar' ? 'الرسائل' : 'Messages' }}</a>
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
    @php  $lang = config('app.locale'); @endphp
    <nav class="bottom-nav">

        <a href="{{ route('home') }}"><i class="fas fa-home"></i> {{ $lang == 'ar' ? 'الرئيسية' : 'Home' }}</a>
        @auth
        <a href="{{ route('notifications.index') }}"><i class="fas fa-bell"></i> {{ $lang == 'ar' ? 'الإشعارات' : 'Notifications' }}</a>
        <a href="{{ route('web.send_message', auth()->id()) }}"><i class="fas fa-envelope"></i> {{ $lang == 'ar' ? 'الرسائل' : 'Messages' }}</a>
        @if($user->role_id == 1)
            <a href="{{ route('services.index') }}"><i class="fas fa-clipboard-list"></i> {{ $lang == 'ar' ? 'طلباتي' : 'My Orders' }}</a>
        @elseif($user->role_id == 3)
            <a href="{{ route('web.comments.my_comments', $user->id) }}"><i class="fas fa-comments"></i> {{ $lang == 'ar' ? 'عروضي' : 'My Offers' }}</a>
        @endif
        @if($isPlasticStore)
        <a href="{{ route('orders.index') }}"><i class="fas fa-clipboard-list"></i> {{ $lang == 'ar' ? 'طلباتي' : 'My Orders' }}</a>
            <a href="{{ route('pro_cart.index') }}"><i class="fas fa-shopping-cart"></i> {{ $lang == 'ar' ? 'السلة' : 'Cart' }}</a>
        @endif
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
</script>
    @yield('script')
</body>

</html>
