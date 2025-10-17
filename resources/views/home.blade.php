@extends('layouts.app')

@section('title', 'الرئيسية')


@section('content')
    <!-- Hero Section -->
    <section class="hero-area position-relative text-white overflow-hidden">
        <div class="hero-bg-small bg-info"></div>

        <div class="container py-5 d-flex align-items-center">
            <div class="row align-items-center w-100 flex-column-reverse flex-lg-row">
                <!-- النص -->
                <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                    <div class="d-flex justify-content-center justify-content-lg-start align-items-center mb-3">
                        <i class="fas fa-star text-warning fs-4 mx-1"></i>
                        <i class="fas fa-star text-warning fs-4 mx-1"></i>
                        <i class="fas fa-star text-warning fs-4 mx-1"></i>
                        <i class="fas fa-star text-warning fs-4 mx-1"></i>
                        <i class="fas fa-star text-warning fs-4 mx-1"></i>
                        <span class="ms-2 small">تقييم 5.0 من أكثر من 10K مستخدم</span>
                    </div>

                    <h1 class="display-5 fw-bold mb-3 text-white">
                        منصة <span style="color: #f3a446;">Endak</span> — حيث يلتقي
                        <span class="text-info">الطلب</span> بالعَرض.
                    </h1>

                    <p class="lead mb-4 border-start border-3 ps-3 text-white">
                        اكتشف الخدمات التي تحتاجها، أو ابدأ رحلتك كمستقل وقدم عروضك على المشاريع المناسبة لك.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3 mb-5">
                        <a href="{{ route('categories.index') }}" class="btn btn-lg px-4 rounded-pill shadow"
                            style="background-color:#f3a446;color:#fff;border:none;">تصفح الأقسام</a>
                        <a href="{{ route('services.index') }}"
                            class="btn btn-outline-light btn-lg px-4 rounded-pill">استكشف الخدمات</a>
                    </div>
                </div>

                <div class="col-lg-6 text-center position-relative mb-4 mb-lg-0">
                    <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                        alt="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}" class="img-fluid hero-img">
                </div>
            </div>
        </div>

        <svg viewBox="0 0 1440 200" preserveAspectRatio="none" class="position-absolute bottom-0 start-0 w-100 hero-wave">
            <defs>
                <linearGradient id="waveGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#2f5c69" />
                    <stop offset="100%" stop-color="#f1f7ff" />
                </linearGradient>

                <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
                    <feDropShadow dx="0" dy="-2" stdDeviation="4" flood-color="#1c3944"
                        flood-opacity="0.5" />
                </filter>
            </defs>

            <path id="wavePath" d="M0,120 Q360,70 720,120 T1440,120 L1440,200 L0,200 Z" fill="url(#waveGradient)"
                filter="url(#shadow)" opacity="0.95">
                <animate attributeName="d" dur="6s" repeatCount="indefinite"
                    values="
                    M0,120 Q360,70 720,120 T1440,120 L1440,200 L0,200 Z;
                    M0,125 Q360,80 720,125 T1440,125 L1440,200 L0,200 Z;
                    M0,120 Q360,70 720,120 T1440,120 L1440,200 L0,200 Z" />
            </path>
        </svg>

        <style>
            .hero-area {
                background: linear-gradient(135deg, #2f5c69, #3c6f7d, #2f5c69);
                min-height: auto;
                position: relative;
                display: flex;
                align-items: center;
                overflow: hidden;
                padding: 0;
                margin-top: 40px
            }


            .hero-img {
                max-width: 80%;
                z-index: 2;
                position: relative;
                animation: floatMove 4s ease-in-out infinite;
            }

            @keyframes floatMove {
                0% {
                    transform: rotate(0deg) translateX(0);
                }

                25% {
                    transform: rotate(1.5deg) translateX(8px);
                }

                50% {
                    transform: rotate(0deg) translateX(0);
                }

                75% {
                    transform: rotate(-1.5deg) translateX(-8px);
                }

                100% {
                    transform: rotate(0deg) translateX(0);
                }
            }

            .hero-wave {
                z-index: 3;
                bottom: 0;
            }

            @media (max-width: 768px) {
                .hero-area {
                    padding: 0 !important;
                    text-align: center;
                    margin-top: 0px
                }

                .hero-img {
                    width: 80%;
                    margin: 0 auto;
                }

                .col-lg-6 {
                    padding: 0 !important;
                }

                .hero-area .container {
                    padding: 0 !important;
                }

                .hero-area h1 {
                    font-size: 1.6rem;
                }

                .hero-area p {
                    font-size: 1rem;
                    margin-bottom: 1rem;
                }
            }
        </style>
    </section>





    <section class="categories-section py-5 text-center">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-black">الأقسام الرئيسية</h2>
                <p class="text-light-50">اختر من بين مجموعة واسعة من الأقسام</p>
            </div>

            <div class="row justify-content-center">
                @forelse($categories as $category)
                    <div class="col-6 col-md-6 col-lg-3 mb-4 fade-card">
                        <a href="{{ $category->hasChildren() ? route('categories.subcategories', $category->slug) : route('services.request', $category->id) }}"
                            class="card-link">
                            <div class="card category-card h-100">
                                <div class="category-image-container">
                                    <img src="{{ $category->image_url }}" class="category-image"
                                        alt="{{ $category->name }}">
                                    <div class="category-overlay">
                                        <h1 class="category-title">
                                            {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">لا توجد أقسام متاحة حالياً</p>
                    </div>
                @endforelse
            </div>
        </div>

        <style>
            .categories-section {
                background: linear-gradient(135deg, #f1f7ff, #e6eefc, #f1f7ff);
                color: #1b3c72;
                position: relative;
                overflow: hidden;
            }

            .fade-card {
                opacity: 0;
                transform: translateY(30px);
                animation: fadeUp 0.8s ease forwards;
            }

            .fade-card:nth-child(odd) {
                transform: translateX(-50px);
            }

            .fade-card:nth-child(even) {
                transform: translateX(50px);
            }

            @keyframes fadeUp {
                0% {
                    opacity: 0;
                    transform: translateY(30px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card-link {
                text-decoration: none;
                color: inherit;
                display: block;
            }

            .category-card {
                position: relative;
                background: rgba(255, 255, 255, 0.7);
                border-radius: 20px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                transition: all 0.4s ease;
                border: none;
                text-align: center;
                overflow: hidden;
                padding-bottom: 15px;
            }

            .category-card::before {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                width: 100px;
                height: 100px;
                border-top: 3px solid #004d40;
                border-right: 3px solid #004d40;
                border-top-right-radius: 20px;
                transition: all 0.4s ease;
            }

            .category-card::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100px;
                height: 100px;
                border-bottom: 3px solid #004d40;
                border-left: 3px solid #004d40;
                border-bottom-left-radius: 20px;
                transition: all 0.4s ease;
            }

            .category-card:hover {
                transform: scale(1.05);
                box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
            }

            .category-card:hover::before,
            .category-card:hover::after {
                width: 100%;
                height: 100%;
                border-radius: 20px;
            }

            .category-image-container {
                position: relative;
                height: 200px;
                overflow: hidden;
                border-radius: 20px 20px 0 0;
            }

            .category-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }

            .category-card:hover .category-image {
                transform: scale(1.1);
            }

            .category-overlay {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.3);
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 20px;
                transition: background 0.3s ease;
            }

            .category-card:hover .category-overlay {
                background: rgba(0, 0, 0, 0.45);
            }

            .category-title {
                font-size: 1.5rem;
                font-weight: bold;
                color: #fff;
                text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
                margin: 0;
            }

            @media (min-width: 1200px) {
                .col-lg-3 {
                    flex: 0 0 25%;
                    max-width: 25%;
                }
            }

            @media (max-width: 768px) {
                .category-image-container {
                    height: 150px;
                }

                .category-title {
                    font-size: 1rem;
                }
            }

            @media (max-width: 576px) {
                .col-6 {
                    flex: 0 0 50%;
                    max-width: 50%;
                }

                .category-image-container {
                    height: 120px;
                }

                .category-title {
                    font-size: 0.9rem;
                }
            }
        </style>
    </section>

    <!-- Latest Services Section -->
    @if ($latestServices->count() > 0)
        @auth
            @if (auth()->user()->isProvider())
                <section class="py-5">
                    <div class="container">
                        <div class="text-center mb-5">
                            <h6 class="fw-bold">أحدث الخدمات</h6>
                            <p class="text-muted">اكتشف أحدث الخدمات المضافة</p>
                        </div>

                        <div class="row">
                            @foreach ($latestServices as $service)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <img src="{{ $service->image_url }}" class="card-img-top" alt="{{ $service->title }}"
                                            style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $service->title }}</h5>
                                            <p class="card-text text-muted">{{ Str::limit($service->description, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-primary fw-bold">{{ $service->formatted_price }}</span>
                                                <small class="text-muted">{{ $service->category->name }}</small>
                                            </div>

                                            @php
                                                $userOffer = $service->offers
                                                    ->where('provider_id', auth()->id())
                                                    ->first();
                                            @endphp
                                            @if ($userOffer)
                                                <div class="mt-3 p-2 bg-success bg-opacity-10 border border-success rounded">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <i class="fas fa-check-circle text-success me-2"></i>
                                                            <span class="text-success fw-bold">تم تقديم العرض</span>
                                                        </div>
                                                        <span class="badge bg-success">{{ $userOffer->formatted_price }}</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            تم التقديم:
                                                            {{ $userOffer->created_at ? $userOffer->created_at->diffForHumans() : 'غير محدد' }}
                                                        </small>
                                                    </div>
                                                    @if ($userOffer->status !== 'pending')
                                                        <div class="mt-1">
                                                            <span
                                                                class="badge bg-{{ $userOffer->status === 'accepted' ? 'success' : ($userOffer->status === 'rejected' ? 'danger' : 'warning') }}">
                                                                {{ $userOffer->status_label }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('services.show', $service->slug) }}"
                                                class="btn btn-primary w-100">
                                                عرض التفاصيل
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">عرض جميع الخدمات</a>
                        </div>
                    </div>
                </section>
            @endif
        @else
            <!-- <section class="latest-services py-5 position-relative">
                    <div class="container">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-dark">أحدث الخدمات</h2>
                            <p class="text-muted">اكتشف أحدث الخدمات المضافة على منصة <span style="color:#f3a446;">Endak</span></p>
                        </div>

                        <div class="row g-4">
                            @foreach ($latestServices as $index => $service)
        <div class="col-md-6 col-lg-4">
                                    <div class="service-card" style="animation-delay: {{ $index * 0.2 }}s;">
                                        <div class="service-img">
                                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}">
                                            <div class="overlay"></div>
                                        </div>

                                        <div class="service-content">
                                            <h5>{{ $service->title }}</h5>
                                            <p>{{ Str::limit($service->description, 80) }}</p>

                                            <div class="service-meta">
                                                <span class="price">{{ $service->formatted_price }}</span>
                                                <small class="category">{{ $service->category->name }}</small>
                                            </div>

                                            @php
                                                $userOffer = $service->offers
                                                    ->where('provider_id', auth()->id())
                                                    ->first();
                                            @endphp
                                            @if ($userOffer)
        <div class="offer-status mt-3">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    <span>تم تقديم العرض</span>
                                                </div>
        @endif
                                        </div>

                                        <div class="card-footer bg-transparent text-center">
                                            <a href="{{ route('services.show', $service->slug) }}" class="btn-view">
                                                عرض التفاصيل
                                            </a>
                                        </div>
                                    </div>
                                </div>
        @endforeach
                        </div>

                        <div class="text-center mt-5">
                            <a href="{{ route('services.index') }}" class="btn btn-lg px-5 py-2 rounded-pill" style="background:#f3a446; color:#fff; border:none;">
                                عرض جميع الخدمات
                            </a>
                        </div>
                    </div>

                    <style>
                        .latest-services {
                            background: linear-gradient(135deg, #f1f7ff, #e6eefc, #f1f7ff);
                        }

                        .service-card {
                            background: #fff;
                            border-radius: 20px;
                            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                            overflow: hidden;
                            transform: translateY(40px);
                            opacity: 0;
                            animation: slideFadeIn 0.8s ease forwards;
                        }

                        @keyframes slideFadeIn {
                            0% { transform: translateY(40px); opacity: 0; }
                            100% { transform: translateY(0); opacity: 1; }
                        }

                        /* حركة كل كارد: يمين-شمال بالتبادل */
                        .col-md-6:nth-child(odd) .service-card {
                            animation-name: slideFromRight;
                        }
                        .col-md-6:nth-child(even) .service-card {
                            animation-name: slideFromLeft;
                        }

                        @keyframes slideFromRight {
                            0% { transform: translateX(80px); opacity: 0; }
                            100% { transform: translateX(0); opacity: 1; }
                        }

                        @keyframes slideFromLeft {
                            0% { transform: translateX(-80px); opacity: 0; }
                            100% { transform: translateX(0); opacity: 1; }
                        }

                        .service-img {
                            position: relative;
                            height: 200px;
                            overflow: hidden;
                        }

                        .service-img img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            transition: transform 0.6s ease;
                        }

                        .service-card:hover .service-img img {
                            transform: scale(1.1);
                        }

                        .overlay {
                            position: absolute;
                            top: 0; left: 0; right: 0; bottom: 0;
                            background: rgba(47, 92, 105, 0.25);
                            opacity: 0;
                            transition: opacity 0.3s ease;
                        }

                        .service-card:hover .overlay {
                            opacity: 1;
                        }

                        .service-content {
                            padding: 20px;
                            text-align: start;
                        }

                        .service-content h5 {
                            font-weight: 700;
                            color: #2f5c69;
                            margin-bottom: 10px;
                        }

                        .service-content p {
                            color: #666;
                            font-size: 0.95rem;
                        }

                        .service-meta {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-top: 15px;
                        }

                        .price {
                            color: #f3a446;
                            font-weight: bold;
                        }

                        .category {
                            color: #3c6f7d;
                            font-size: 0.85rem;
                        }

                        .offer-status {
                            background: rgba(76, 175, 80, 0.1);
                            border-radius: 10px;
                            padding: 6px 10px;
                            font-size: 0.9rem;
                            color: #28a745;
                            display: inline-flex;
                            align-items: center;
                        }

                        .btn-view {
                            display: inline-block;
                            background: linear-gradient(135deg, #2f5c69, #3c6f7d);
                            color: #fff;
                            border: none;
                            padding: 10px 25px;
                            border-radius: 12px;
                            font-weight: 500;
                            transition: all 0.3s ease;
                        }

                        .btn-view:hover {
                            background: #f3a446;
                            transform: scale(1.05);
                        }
                    </style>
                </section> -->

        @endauth
    @endif

    <!-- Features Section -->
    <section class="features-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="icon-box mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>خدمات آمنة</h4>
                    <p>جميع الخدمات مضمونة وآمنة 100%</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="icon-box mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>خدمة سريعة</h4>
                    <p>احصل على الخدمة في أسرع وقت ممكن</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="icon-box mb-3">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>جودة عالية</h4>
                    <p>نختار لك أفضل مزودي الخدمات</p>
                </div>
            </div>
        </div>
    </section>

    <style>
        .features-section {
            background: linear-gradient(135deg, #e6eefe, #f1f7ee);
            color: #2f5c69;
            overflow: hidden;
        }

        .features-section h4 {
            color: #2f5c69;
            font-weight: 600;
            margin-top: 15px;
        }

        .features-section p {
            color: #4b6e79;
            font-size: 0.95rem;
        }

        .icon-box {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            background: rgba(47, 92, 105, 0.1);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.4s ease;
            animation: floatIcon 3s ease-in-out infinite;
        }

        .icon-box i {
            font-size: 2.5rem;
            color: #2f5c69;
            transition: all 0.4s ease;
        }

        .icon-box:hover {
            background: rgba(47, 92, 105, 0.2);
            transform: translateY(-8px);
        }

        .icon-box:hover i {
            color: #f3a446;
            transform: scale(1.1);
        }

        @keyframes floatIcon {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>

@endsection
