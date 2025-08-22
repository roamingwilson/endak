<div class="head_menu_container">
    @php
        $lang = config('app.locale');
        $user = auth()->user();
    @endphp

    <header class="main-header bg-white shadow-sm sticky-top py-2">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Left Side - Logo and Menu Toggle -->
                <div class="d-flex align-items-center">
                    <!-- Mobile Menu Toggle -->
                    <button class="btn btn-link p-0 me-3 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <!-- Logo -->
                    <a href="{{ route('departments') }}" class="navbar-brand">
                        {{--  <img src="{{ asset('images/logo2.jpg') }}" alt="Logo" height="40px">  --}}
                    </a>
            </div>

                <!-- Center - Navigation (Desktop) -->
                <nav class="d-none d-lg-flex mx-auto">
                    <ul class="nav">
                        <li class="nav-item mx-2">
                            <a href="{{ route('departments') }}" class="nav-link fw-medium">{{ __('general.home') }}</a>
                        </li>
                        @if(!auth()->check() || (auth()->check() && auth()->user()->role_id == 3))
                        <li class="nav-item mx-2">
                            <a href="{{ route('all_services') }}" class="nav-link fw-medium">{{ $lang == 'ar' ? 'الخدمات المطلوبة' : 'All orders' }}</a>
                        </li>
                        @endif


                        <li class="nav-item mx-2">
                            <a href="{{ route('indsproducts.index') }}" class="nav-link fw-medium">
                                {{ $lang == 'ar' ? 'متجر البلاستيك' : 'Plastic Store' }}
                            </a>
                        </li>
                        {{--  <li class="nav-item mx-2">
                            <a href="{{ route('orders.all') }}" class="nav-link fw-medium">
                                {{ $lang == 'ar' ? 'كل الطلبات' : 'All Orders' }}
                            </a>
                        </li>  --}}
                        @if (auth()->check() && auth()->user()->role_id == 1)
                            <li class="nav-item mx-2">
                                <a href="{{ route('services.index') }}" class="nav-link fw-medium">
                                    {{ $lang == 'ar' ? 'طلباتي' : 'Services' }}
                                </a>
                            </li>
                        @endif

                        @if (auth()->check() && auth()->user()->role_id == 1)
                            <li class="nav-item mx-2">
                                <a href="{{ route('general_orders.customer.index') }}" class="nav-link fw-medium">
                                        {{ $lang == 'ar' ? 'العروض المقبولة' : 'Accepted Orders' }}
                                </a>
                            </li>
                        @endif
                            @if (auth()->check() && auth()->user()->role_id == 1)
                            <li class="nav-item mx-2">
                                <a href="{{ route('general_comments.show', $user->id) }}" class="nav-link fw-medium">
                                            {{ $lang == 'ar' ? 'العروض المقدمة' : 'My offers' }}
                            </a>
                            </li>
                        @endif
                        @if (auth()->check() && auth()->user()->role_id == 3)
                            <li class="nav-item mx-2">
                                <a href="{{ route('web.comments.my_comments', $user->id) }}" class="nav-link fw-medium">
                                    {{ $lang == 'ar' ? 'عروضي' : 'My Comments' }}
                                </a>
                            </li>
                        @endif
                        @if(auth()->check() && auth()->user()->role_id == 3)
                            <li class="nav-item mx-2">
                                <a href="{{ route('all_services') }}" class="nav-link fw-medium">
                                    {{ $lang == 'ar' ? 'كل الخدمات المطلوبة' : 'All Service Requests' }}
                                </a>
                            </li>
                        @endif
                        <!-- صفحات الموقع -->
                        <li class="nav-item mx-2 dropdown">
                            <a href="#" class="nav-link fw-medium dropdown-toggle" data-bs-toggle="dropdown">
                                {{ __('page.pages') }}
                            </a>
                            <ul class="dropdown-menu">
                                @forelse ($pages as $page)
                                    <li>
                                        <a href="{{ route('page', $page->slug) }}" class="dropdown-item">
                                            <i class="bi bi-house"></i>
                                            {{ $lang == 'ar' ? $page->title_ar : $page->title_en }}
                                        </a>
                                    </li>
                                @empty
                                    <li class="dropdown-item text-muted small">{{ __('general.no_pages') }}</li>
                                @endforelse
                            </ul>
                        </li>
                    </ul>
                </nav>

                <!-- Right Side - User Actions -->
                <div class="d-flex align-items-center gap-3">
                    <!-- WhatsApp Contact - Always Visible -->
                    <div class="d-flex align-items-center me-2">
                        <a href="https://wa.me/966568401348" target="_blank" class="whatsapp-contact-btn text-decoration-none">
                            <div class="d-flex align-items-center">
                                <span class="">
                                    <i class="bi bi-whatsapp"></i>
                                </span>
                                <div class="d-none d-md-block">
                                    <div class="small text-muted">{{ __('general.call_to_us') }}</div>
                                    <div class="fw-medium">+966568401348</div>
                                </div>
                            </div>
                        </a>
                    </div>



                    <!-- Notifications Dropdown -->
                    {{--  @auth
                    <div class="dropdown">
                        <a href="#" class="position-relative text-decoration-none" data-bs-toggle="dropdown">
                            <span class="avatar bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                <i class="bi bi-bell"></i>
                                @if($notifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $notifications->count() }}
                                </span>
                                @endif
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="width: 320px;">
                            <li class="dropdown-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">{{ __('general.notifications') }}</span>
                                <a href="#" class="small text-primary">Mark all as read</a>
                                </li>
                            <li class="px-2">
                                <div style="max-height: 300px; overflow-y: auto;">
                                    @forelse($notifications as $notification)
                                    <a href="{{ route('notification.read', $notification->id) }}"
                                       class="dropdown-item d-flex py-2 border-bottom">
                                        <div class="flex-shrink-0 me-2">
                                            <span class="avatar bg-info bg-opacity-10 text-info rounded-circle p-2">
                                                <i class="bi bi-info-circle"></i>
                                        </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $notification->data['title'] }}</h6>
                                            <p class="mb-0 small text-muted">
                                                {{ Str::limit($notification->data['message'] ?? ($notification->data['body'] ?? ''), 50) }}
                                            </p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                    @empty
                                    <div class="text-center py-3 text-muted">
                                        {{ __('general.no_notifications') }}
                                    </div>
                                    @endforelse
                                </div>
                                </li>
                        </ul>
                    </div>
                    @endauth  --}}

                    <!-- User Dropdown -->
                    @auth
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('storage/' . $user->image ?? 'users/user.png') }}"
                                 class="rounded-circle me-2" width="40" height="40" alt="Profile">
                            <span class="d-none d-md-inline">{{ $user->first_name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item" href="{{ route('web.profile', $user->id) }}">
                                <i class="bi bi-person me-2"></i> {{ __('general.profile') }}
                            </a></li>
                            @if($user->role_name == 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> {{ __('general.dashboard') }}
                            </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('auth.logout') }}
                            </a></li>
                            </ul>
                    </div>
                    @else
                    <a href="{{ route('login-page') }}" class="btn btn-outline-primary">
                        {{ __('auth.login') }}
                    </a>
                    @endauth

                    <!-- Language Selector -->
                    <div class="dropdown">
                        <a href="#" class="btn btn-link text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ URL::asset('images/flags/' . ($lang == 'ar' ? 'SA' : 'US') . '.png') }}"
                                 width="20" alt="{{ $lang == 'ar' ? 'Arabic' : 'English' }}">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li>
                                <a href="{{ route('lang', 'ar') }}" class="dropdown-item d-flex align-items-center">
                                    <img src="{{ URL::asset('images/flags/SA.png') }}" width="20" class="me-2" alt="Arabic">
                                    {{ __('general.arabic') }}
                                    @if($lang == 'ar')<i class="bi bi-check2 ms-auto text-primary"></i>@endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('lang', 'en') }}" class="dropdown-item d-flex align-items-center">
                                    <img src="{{ URL::asset('images/flags/US.png') }}" width="20" class="me-2" alt="English">
                                    {{ __('general.english') }}
                                    @if($lang == 'en')<i class="bi bi-check2 ms-auto text-primary"></i>@endif
                                </a>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <!-- Contact WhatsApp -->
                <li class="nav-item mb-3">
                    <a href="https://wa.me/966568401348" target="_blank" class="nav-link whatsapp-mobile-link">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fab fa-whatsapp"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-success">واتساب الدعم</div>
                                <div class="small text-muted">+966568401348</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>

                <li class="nav-item mb-1">
                    <a href="{{ route('departments') }}" class="nav-link"><i class="fas fa-home me-2"></i>{{ __('general.home') }}</a>
                </li>
                @if(!auth()->check() || (auth()->check() && auth()->user()->role_id == 3))
                <li class="nav-item mb-1">
                    <a href="{{ route('all_services') }}" class="nav-link"><i class="fas fa-tasks me-2"></i>{{ $lang == 'ar' ? 'الخدمات المطلوبة' : 'All orders' }}</a>
                </li>
                @endif

                <li class="nav-item mb-1">
                    <a href="{{ route('indsproducts.index') }}" class="nav-link"><i class="fas fa-store me-2"></i>{{ $lang == 'ar' ? 'متجر البلاستيك' : 'Plastic Store' }}</a>
                </li>
                {{--  <li class="nav-item mb-1">
                    <a href="{{ route('orders.all') }}" class="nav-link"><i class="fas fa-list me-2"></i>{{ $lang == 'ar' ? 'كل الطلبات' : 'All Orders' }}</a>
                </li>  --}}
                @if (auth()->check() && auth()->user()->role_id == 1)
                    <li class="nav-item mb-1">
                        <a href="{{ route('services.index') }}" class="nav-link"><i class="fas fa-clipboard-list me-2"></i>{{ $lang == 'ar' ? 'طلباتي' : 'Services' }}</a>
                    </li>
                @endif

                @if (auth()->check() && auth()->user()->role_id == 1)
                    <li class="nav-item mb-1">
                        <a href="{{ route('general_orders.customer.index') }}" class="nav-link"><i class="fas fa-check-circle me-2"></i>{{ $lang == 'ar' ? 'العروض المقبولة' : 'Accepted Orders' }}</a>
                    </li>
                @endif
                @if (auth()->check() && auth()->user()->role_id == 1)
                    <li class="nav-item mb-1">
                        <a href="{{ route('general_comments.show', $user->id) }}" class="nav-link"><i class="fas fa-gift me-2"></i>{{ $lang == 'ar' ? 'العروض المقدمة' : 'My offers' }}</a>
                    </li>
                @endif
                @if (auth()->check() && auth()->user()->role_id == 3)
                    <li class="nav-item mb-1">
                        <a href="{{ route('web.comments.my_comments', $user->id) }}" class="nav-link"><i class="fas fa-comments me-2"></i>{{ $lang == 'ar' ? 'عروضي' : 'My Comments' }}</a>
                    </li>
                @endif
                @if(auth()->check() && auth()->user()->role_id == 3)
                    <li class="nav-item mb-1">
                        <a href="{{ route('services.requests.all') }}" class="nav-link"><i class="fas fa-list-alt me-2"></i>{{ $lang == 'ar' ? 'كل الخدمات المطلوبة' : 'All Service Requests' }}</a>
                    </li>
                @endif
                @if(auth()->check())
                    <li class="nav-item mb-1">
                        <a href="{{ route('user.settings.account.show') }}" class="nav-link"><i class="fas fa-eye me-2"></i>{{ $lang == 'ar' ? 'عرض الإعدادات' : 'View Settings' }}</a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('user.settings.account') }}" class="nav-link"><i class="fas fa-cog me-2"></i>{{ $lang == 'ar' ? 'تعديل الإعدادات' : 'Edit Settings' }}</a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('user.settings.profile') }}" class="nav-link"><i class="fas fa-user-edit me-2"></i>{{ $lang == 'ar' ? 'الملف الشخصي' : 'Profile Settings' }}</a>
                    </li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <!-- صفحات الموقع -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-file-alt me-2"></i>{{ __('page.pages') }}</a>
                    <ul class="dropdown-menu">
                        @forelse ($pages as $page)
                            <li>
                                <a href="{{ route('page', $page->slug) }}" class="dropdown-item">
                                    <i class="bi bi-house"></i>
                                    {{ $lang == 'ar' ? $page->title_ar : $page->title_en }}
                                </a>
                            </li>
                        @empty
                            <li class="dropdown-item text-muted small">{{ __('general.no_pages') }}</li>
                        @endforelse
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .main-header {
        z-index: 1030;
        transition: all 0.3s ease;
        background-color: orange !important;
    }

    .nav-link {
        color: #495057;
        transition: all 0.2s;
    }

    .nav-link:hover, .nav-link:focus {
        color: var(--bs-primary);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* WhatsApp Contact Button Styles */
    .whatsapp-contact-btn {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white !important;
        padding: 8px 12px;
        border-radius: 25px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(37, 211, 102, 0.3);
        border: 2px solid transparent;
    }

    .whatsapp-contact-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        color: white !important;
        text-decoration: none;
    }

    .whatsapp-contact-btn .avatar {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .whatsapp-contact-btn .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .whatsapp-contact-btn .fw-medium {
        color: white !important;
        font-weight: 600;
    }

    /* Mobile WhatsApp Button */
    @media (max-width: 767.98px) {
        .whatsapp-contact-btn {
            padding: 8px;
            border-radius: 50%;
            min-width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .whatsapp-contact-btn .avatar {
            margin: 0 !important;
        }

        .whatsapp-contact-btn .avatar i {
            font-size: 1.2rem;
        }
    }

    /* Dark mode support for WhatsApp button */
    body.dark-theme .whatsapp-contact-btn {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white !important;
    }

    body.dark-theme .whatsapp-contact-btn:hover {
        background: linear-gradient(135deg, #128C7E 0%, #075E54 100%);
    }

    /* Mobile Menu WhatsApp Link */
    .whatsapp-mobile-link {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.1) 0%, rgba(18, 140, 126, 0.1) 100%);
        border: 2px solid #25D366;
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .whatsapp-mobile-link:hover {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.2) 0%, rgba(18, 140, 126, 0.2) 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    }

    .whatsapp-mobile-link .avatar {
        background-color: rgba(37, 211, 102, 0.2) !important;
        color: #25D366 !important;
    }

    /* Dark mode support for mobile WhatsApp link */
    body.dark-theme .whatsapp-mobile-link {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.15) 0%, rgba(18, 140, 126, 0.15) 100%);
        border-color: #25D366;
    }

    body.dark-theme .whatsapp-mobile-link:hover {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.25) 0%, rgba(18, 140, 126, 0.25) 100%);
    }

    @media (max-width: 991.98px) {
        .offcanvas {
            width: 280px;
        }
    }
</style>
