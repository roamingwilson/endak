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
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <img src="{{ asset('images/logo2.jpg') }}" alt="Logo" height="40">
                    </a>
            </div>

                <!-- Center - Navigation (Desktop) -->
                <nav class="d-none d-lg-flex mx-auto">
                    <ul class="nav">
                        <li class="nav-item mx-2">
                            <a href="{{ route('home') }}" class="nav-link fw-medium">{{ __('general.home') }}</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a href="{{ route('all_services') }}" class="nav-link fw-medium">{{
                                $lang == 'ar' ? 'الخدمات المطلوبة' : 'All orders' }}</a>
                        </li>

                        <li class="nav-item mx-2">
                            <a href="{{ route('departments') }}" class="nav-link fw-medium">{{ __('department.departments') }}</a>
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
                        @if (auth()->check())
                            <li class="nav-item mx-2">
                                <a href="{{ route('web.user.service_provider') }}" class="nav-link fw-medium">
                                    {{ __('user.service_provider') }}
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
                                <a href="{{ route('services.requests.all') }}" class="nav-link fw-medium">
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
                    <!-- Contact -->
                    <div class="d-none d-md-flex align-items-center me-2">
                        <a href="tel:{{ $settings->phone ?? '01150099801' }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <span class="avatar bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <div>
                                    <div class="small text-muted">{{ __('general.call_to_us') }}</div>
                                    <div class="fw-medium">{{ $settings->phone ?? '01150099801' }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Messages Dropdown -->
                    @auth
                    <div class="dropdown">
                        <a href="#" class="position-relative text-decoration-none" data-bs-toggle="dropdown">
                            <span class="avatar bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                <i class="bi bi-chat"></i>
                                @if($conversations->isNotEmpty() && $conversations->first()->latestMessage && $conversations->first()->latestMessage->created_at->gt(now()->subMinutes(10)))
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    1
                                </span>
                                @endif
                                </span>
                            </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="width: 320px;">
                            <li class="dropdown-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">{{ __('general.messages') }}</span>
                                <a href="#" class="small text-primary">View All</a>
                            </li>
                            <li class="px-2">
                                <div style="max-height: 300px; overflow-y: auto;">
                                    @forelse($conversations as $conversation)
                                    @php
                                        $otherUser = $user->id == $conversation->sender_id
                                            ? $conversation->recipient
                                            : $conversation->sender;
                                        $message = $conversation->latestMessage;
                                    @endphp
                                    <a href="{{ route('web.send_message', $otherUser->id) }}"
                                       class="dropdown-item d-flex align-items-center py-2 border-bottom">
                                        <img src="{{ $otherUser->image_url ?? asset('storage/users/default_avatar.png') }}"
                                             class="rounded-circle me-2" width="40" height="40"
                                             alt="{{ $otherUser->first_name }}">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">{{ $otherUser->first_name }}</h6>
                                                <small class="text-muted">{{ $message ? $message->created_at->diffForHumans() : '' }}</small>
                                            </div>
                                            <p class="mb-0 text-truncate small" style="max-width: 200px;">
                                                {{ $message ? Str::limit($message->message, 30) : __('general.no_messages') }}
                                            </p>
                                        </div>
                                    </a>
                                    @empty
                                    <div class="text-center py-3 text-muted">
                                        {{ __('general.no_conversations') }}
                                    </div>
                                    @endforelse
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endauth

                    <!-- Notifications Dropdown -->
                    @auth
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
                    @endauth

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
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">{{ __('general.home') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('departments') }}" class="nav-link">{{ __('department.departments') }}</a>
                </li>
                {{--  <li class="nav-item">
                    <a href="{{ route('orders.all') }}" class="nav-link">{{ $lang == 'ar' ? 'كل الطلبات' : 'All Orders' }}</a>
                </li>  --}}
                @if (auth()->check() && auth()->user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('services.index') }}" class="nav-link">{{ $lang == 'ar' ? 'طلباتي' : 'Services' }}</a>
                    </li>
                @endif
                @if (auth()->check())
                    <li class="nav-item">
                        <a href="{{ route('web.user.service_provider') }}" class="nav-link">{{ __('user.service_provider') }}</a>
                    </li>
                @endif
                @if (auth()->check() && auth()->user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('general_orders.customer.index') }}" class="nav-link">{{ $lang == 'ar' ? 'العروض المقبولة' : 'Accepted Orders' }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('general_comments.show', $user->id) }}" class="nav-link">{{ $lang == 'ar' ? 'العروض المقدمة' : 'My offers' }}</a>
                    </li>
                @endif
                @if (auth()->check() && auth()->user()->role_id == 3)
                    <li class="nav-item">
                        <a href="{{ route('web.comments.my_comments', $user->id) }}" class="nav-link">{{ $lang == 'ar' ? 'عروضي' : 'My Comments' }}</a>
                    </li>
                @endif
                @if(auth()->check() && auth()->user()->role_id == 3)
                    <li class="nav-item">
                        <a href="{{ route('services.requests.all') }}" class="nav-link">{{ $lang == 'ar' ? 'كل الخدمات المطلوبة' : 'All Service Requests' }}</a>
                    </li>
                @endif
                <!-- صفحات الموقع -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ __('page.pages') }}</a>
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

    @media (max-width: 991.98px) {
        .offcanvas {
            width: 280px;
        }
    }
</style>
