<div class="head_menu_container">
    <?php $settings = App\Models\Settings::first();
    $lang = config('app.locale');
    if (auth()->check()) {
        $user = auth()->user();
    }
    ?>
    <header class="main-header" id="stickyHeader">

        <!-- Start::main-brand-header -->
        <div class="main-brand-header">
            <div class="container brand-header-container">
                <div class="d-flex align-items-center">
                    <!-- Start::header-element -->
                    <div class="header-element me-1">
                        <!-- Start::header-link -->
                        <a href="javascript:void(0);" class="sidemenu-toggle1 header-link" data-bs-toggle="sidebar">
                            <span class="open-toggle">
                                <i class="bi bi-text-indent-left header-link-icon"></i>
                            </span>
                        </a>
                        <!-- End::header-link -->
                    </div>
                    <!-- End::header-element -->
                    @auth

                        <a href="#" class="brand-main">
                            <img src="{{ asset('storage/' . $user->image ?? 'users/user.png') }}" width="50"
                                height="50" alt="img" class="desktop-logo logo-dark">
                            {{-- <img src="../assets/images/brand/toggle-dark.png" alt="img"
                            class="mobile-logo mobile-dark mx-3">
                        <img src="../assets/images/brand/logo-color.png" alt="img" class="desktop-logo logo-color">
                        <img src="../assets/images/brand/toggle-color.png" alt="img"
                            class="mobile-logo mobile-color"> --}}
                        </a>

                    @endauth
                    {{-- <ul class="categories-dropdowns">
                        <li class="category-dropdown px-2 py-3">
                            <a href="javascript:void(0);" class="avatar bg-white-1 border rounded-circle tx-15 border-white-2 categorydropdown" onclick="toggleDropdown(this)">
                                <i class="bi bi-grid"></i>
                            </a>
                            <ul class="main-dropdown" id="dropdown-toggle">
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-cast tx-17"></i></span>
                                        Web Hosting
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="linux-shared-hosting.html" class="category-item">Linux Shared Hosting</a></li>
                                        <li><a href="windows-shared-hosting.html" class="category-item">Windows Shared Hosting</a></li>
                                        <li><a href="wordpress-shared-hosting.html" class="category-item">Wordpress Shared Hosting</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-globe tx-17"></i></span>
                                        Domains
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="register-domain.html" class="category-item">Register Domain</a></li>
                                        <li><a href="domain-transfer.html" class="category-item">Transfer Domain</a></li>
                                        <li><a href="premium-domains.html" class="category-item">Premium Domains</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-layers tx-17"></i></span>
                                        Reseller Hosting
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="linux-reseller-hosting.html" class="category-item">Linux Reseller Hosting</a></li>
                                        <li><a href="windows-reseller-hosting.html" class="category-item">Windows Reseller Hosting</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-clouds tx-17"></i></span>
                                        Cloud Hosting
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="cloud.html" class="category-item">Cloud Hosting</a></li>
                                        <li><a href="professional-cloud.html" class="category-item">Professional Cloud Hosting</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-hdd-network tx-17"></i></span>
                                        Servers
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="virtualserver-linux-hosting.html" class="category-item">Linux KVM VPS</a></li>
                                        <li><a href="dedicated-server.html" class="category-item">Dedicated Servers</a></li>
                                        <li><a href="windows-dedicated-server.html" class="category-item">Windows Dedicated Servers</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-envelope-plus tx-17"></i></span>
                                        Email & Productivity
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="business-email.html" class="category-item">Business Email</a></li>
                                        <li><a href="enterprise-email.html" class="category-item">Enterprise Email</a></li>
                                        <li><a href="google-workspace.html" class="category-item">Google Workspace</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="categories-dropdowns__item">
                                        <span class="me-1"><i class="bi bi-shield-check tx-17"></i></span>
                                        Security
                                        <span class="float-end"><i class="bi bi-chevron-right tx-11"></i></span>
                                    </div>
                                    <ul class="category-menu">
                                        <li><a href="ssl-certificates.html" class="category-item">SSL Certificate</a></li>
                                        <li><a href="codeguard-backup.html" class="category-item">CodeGuard Website Backup</a></li>
                                        <li><a href="sitelock.html" class="category-item">SiteLock</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul> --}}
                </div>

                <ul class="nav list-unstyled align-items-center">
                    <li class="d-flex align-items-center position-relative me-md-4 me-2">
                        <a href="tel:+1236789657" class="stretched-link"></a>
                        <span class="avatar bg-white-1 border rounded-circle tx-15 border-white-2 me-2"><i
                                class="bi bi-telephone"></i></span>
                        <div class="d-none d-md-block">
                            <a href="javascript:void(0);" class="nav-link tx-15 p-0"
                                style="color: black;">{{ __('general.call_to_us') }}</a>
                            <a href="tel:{{ $settings->phone ?? '01150099801' }}"
                                class="mb-0 nav-link p-0 tx-13 op-8 lh-sm"
                                style="color: black;">{{ $settings->phone ?? '01150099801' }}</a>
                        </div>
                    </li>


                    <li class="d-flex align-items-center position-relative">

                        @if (auth()->check())
                            <span class="avatar bg-white-1 border rounded-circle tx-15 border-white-2 me-2">
                                <a href="{{ route('logout') }}" style="color: black;"><i
                                        class="bi bi-box-arrow-in-right"></i>
                                </a>

                            </span>
                            <div class="d-none d-md-block">
                                <a href="{{ route('web.profile', $user->id) }}" class="nav-link tx-15 p-0"
                                    style="color: black;">{{ $user->first_name }}</a>
                            </div>
                            @if ($user->role_name == 'admin')
                                <a style="{{ $lang == 'ar' ? 'margin-right: 10px;' : 'margin-left: 10px;' }} color:white"
                                    href="{{ route('admin.dashboard') }}">{{ __('general.dashboard') }}</a>
                            @endif
                        @else
                            <a id="live-chat" href="{{ route('login-page') }}" class="stretched-link"></a>
                            <span class="avatar bg-white-1 border rounded-circle tx-15 border-white-2 me-2">
                                <i class="bi bi-box-arrow-in-right"></i>

                            </span>
                            <div class="d-none d-md-block">
                                <a href="{{ route('login-page') }}" class="nav-link tx-15 p-0"
                                    style="color: black;">{{ __('auth.login') }}</a>
                            </div>
                        @endif

                    </li>
                    <style>
                        .dropdown-menu {
                            max-height: 300px;
                            overflow-y: auto;
                        }
                    </style>
                    @if (auth()->check())
                        <li class="d-flex align-items-center position-relative me-md-4 me-2 dropdown">
                            <?php
                            // جلب آخر رسالة للمستقبل للمستخدم
                            $message = App\Models\Message::where('recipient_id', auth()->user()->id)
                                ->latest()
                                ->first();
                            ?>
                            <span data-bs-toggle="dropdown" aria-expanded="false" style="color: #1a4388"
                                class="dropdown-toggle avatar bg-white-1 border rounded-circle tx-15 border-white-2 me-2"
                                style="border: none; margin: 0;">
                                <i class="bi bi-chat">
                                    @if (isset($message) && $message->created_at >= Carbon\Carbon::now()->subMinutes(10))
                                        <span style="color:red">1</span> <!-- إشعار بوجود رسالة جديدة -->
                                    @endif
                                </i>
                            </span>

                            <?php
                            // جلب جميع المحادثات
                            $sender = auth()->user();
                            $conversations = App\Models\Conversation::where(function ($query) use ($sender) {
                                $query->where('sender_id', $sender->id);
                            })
                                ->orWhere(function ($query) use ($sender) {
                                    $query->where('recipient_id', $sender->id);
                                })
                                ->orderBy('created_at', 'desc')
                                ->get(); // جلب جميع المحادثات بترتيب تنازلي
                            ?>

                            <ul class="dropdown-menu dropdown-menu-end">
                                @forelse ($conversations as $conversation)
                                    <?php
                                    // تحديد المرسل أو المستقبل
                                    $id = auth()->user()->id == $conversation->sender_id ? $conversation->recipient->id : $conversation->sender->id;
                                    $sender_me = App\Models\User::find($id);
                                    $message = App\Models\Message::where('conversation_id', $conversation->id)->latest()->first();
                                    ?>

                                    <li>
                                        <a href="{{ route('web.send_message', $id) }}" class="dropdown-item">
                                            <!-- Message Start -->
                                            <div class="media d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    @if ($sender_me->image)
                                                        <img src="{{ $sender_me->image_url }}" alt="User Avatar"
                                                            width="auto" height="40px"
                                                            class="img-size-50 mr-3 img-circle">
                                                    @else
                                                        <img src="{{ asset('storage/users/default_avatar.png') }}"
                                                            alt="User Avatar" width="auto" height="40px"
                                                            class="img-size-50 mr-3 img-circle">
                                                    @endif
                                                    <h6 class="dropdown-item-title mb-0">
                                                        {{ $sender_me->first_name }}
                                                    </h6>
                                                </div>

                                                <div class="media-body m-2">
                                                    <div class="media-body m-2">
                                                        @if ($message)
                                                            <p class="text-sm mt-3">
                                                                {{ implode(' ', array_slice(explode(' ', $message->message), 0, 5)) }}
                                                                @if (strlen($message->message) > 5)
                                                                    ...
                                                                @endif
                                                            </p>
                                                        @else
                                                            <p class="text-sm mt-3 text-muted">
                                                                لا توجد رسائل بعد.
                                                            </p>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- Message End -->
                                        </a>
                                    </li>
                                @empty
                                    <li>لا توجد رسائل</li>
                                @endforelse
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (auth()->check())
                        <li class="d-flex align-items-center position-relative me-md-4 me-2 dropdown">
                            <?php $notifications = auth()->user()->unreadNotifications; ?>

                            <span data-bs-toggle="dropdown" aria-expanded="false"
                                class="dropdown-toggle avatar bg-white-1 border rounded-circle tx-15 border-white-2 me-2"
                                style="border: none; margin: 0;">
                                <i class="bi bi-alarm"><span
                                        style="color:red">{{ $notifications->count() == 0 ? '' : $notifications->count() }}</span></i>
                            </span>

                            <ul class="dropdown-menu dropdown-menu-end">
                                @forelse ($notifications as $notification)
                                    <li>
                                        <a href="{{ route('notification.read', $notification->id) }}"
                                            class="dropdown-item">
                                            <!-- Display notification title -->
                                            <div class="media">
                                                <div class="d-flex align-items-center">
                                                    <p>{{ $notification->data['title'] }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="dropdown-item">لا توجد اشعارات</li>
                                @endforelse
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            </ul>
                        </li>
                    @endif


                </ul>
            </div>
        </div>
        <!-- End::main-brand-header -->

    </header>
    <div class="sticky">
        <!-- Start::app-sidebar -->
        <aside class="app-sidebar" id="sidebar">

            <div class="app-toggle-header">
                <div class="header-element">
                    <!-- Start::header-link -->
                    <a href="javascript:void(0);" class="sidemenu-toggle header-link" data-bs-toggle="sidebar">
                        <span class="close-toggle">
                            <i class="bi bi-x header-link-icon"></i>
                        </span>
                    </a>
                    <!-- End::header-link -->
                </div>
                <!-- End::header-element -->
                {{-- <a href="index.html" class="brand-main">
                    <img src="../assets/images/brand/logo-white.png" alt="img" class="desktop-logo logo-dark">
                    <img src="../assets/images/brand/logo-color.png" alt="img" class="desktop-logo logo-color">
                </a> --}}
            </div>

            <!-- Start::main-sidebar -->
            <div class="main-sidebar container" id="sidebar-scroll">

                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills sub-open">
                    <ul class="main-menu">

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('home') }}" class="side-menu__item">
                                <span class="side-menu__label" style="color: black;">{{ __('general.home') }}</span>
                                {{-- <i class="fe fe-chevron-down side-menu__angle"></i> --}}
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('departments') }}" class="side-menu__item">
                                <span class="side-menu__label"
                                    style="color: black;">{{ __('department.departments') }}</span>
                                {{-- <i class="fe fe-chevron-down side-menu__angle"></i> --}}
                            </a>
                        </li>
                        <!--<li class="slide has-sub">-->
                        <!--    <a href="{{ route('departments') }}" class="side-menu__item">-->
                        <!--        <span class="side-menu__label">{{ __('department.departments') }}</span>-->
                        <!--        <i class="fe fe-chevron-down side-menu__angle"></i>-->
                        <!--    </a>-->
                        <!--    <?php $departments = App\Models\Department::paginate(5); ?>-->
                        <!--    <ul class="slide-menu child1 mega-slide-menu-onefr without-icon">-->
                        <!--        <li class="mega-menu">-->
                        <!--            @forelse ($departments as $department)
-->
                        <!--                <div class="">-->
                        <!--                    <ul>-->
                        <!--                        <li>-->
                        <!--                            <p class="mb-3 d-flex align-items-center menu-label">-->
                        <!--                                <span class="category-loop-icon">-->

                        <!--                                    <i class="la {{ $department->icon_class }}"-->
                        <!--                                        data-toggle="tooltip" title="Category"></i>-->

                        <!--                                </span> <span-->
                        <!--                                    class="tx-14 tx-primary">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}</span>-->
                        <!--                            </p>-->
                        <!--                        </li>-->
                        <!--                        @forelse ($department->sub_Departments as $sub_Department)
-->
                        <!--                            <li class="slide">-->
                        <!--                                <a href="{{ route('web.posts', $sub_Department->id) }}"-->
                        <!--                                    class="side-menu__item">-->
                        <!--                                    <div class="d-lg-flex align-items-start">-->
                        <!--                                        <div class="me-3">-->
                        <!--                                            <span-->
                        <!--                                                class="avatar header__dropavatar mb-2 bg-secondary-transparent rounded-circle">-->
                        <!--                                                <i-->
                        <!--                                                    class="bi bi-hdd-stack me-0 tx-secondary"></i>-->
                        <!--                                            </span>-->
                        <!--                                        </div>-->
                        <!--                                        <div class="flex-grow-1">-->
                        <!--                                            <h6 class="d-block mb-1">-->
                        <!--                                                {{ $lang == 'ar' ? $sub_Department->name_ar : $sub_Department->name_en }}-->
                        <!--                                            </h6>-->
                        <!--                                            <span-->
                        <!--                                                class="tx-default tx-14">{{ $lang == 'ar' ? $sub_Department->description_ar : $sub_Department->description_en }}</span>-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->
                        <!--                                </a>-->
                        <!--                            </li>-->
                    <!--                        @empty-->
                        <!--
@endforelse-->


                        <!--                    </ul>-->
                        <!--                </div>-->
                    <!--            @empty-->
                        <!--
@endforelse-->


                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        <!-- End::slide -->


                        <!-- Start::slide -->



                        @if (auth()->check() && auth()->user()->role_id == 1)
                            <li class="slide">
                                <a href="{{ route('services.index') }}" class="side-menu__item">
                                    <span class="side-menu__label"
                                        style="color: black;">{{ $lang == 'ar' ? 'طلباتي' : 'Services' }}</span>
                                    <i class="fe fe-chevron-down side-menu__angle"></i>
                                </a>

                            </li>
                        @endif
                        @if (auth()->check())
                            <li class="slide">
                                <a href="{{ route('web.user.service_provider') }}" class="side-menu__item">
                                    <span class="side-menu__label"
                                        style="color: black;">{{ __('user.service_provider') }}</span>
                                    <i class="fe fe-chevron-down side-menu__angle"></i>
                                </a>

                            </li>
                        @endif

                        @if (auth()->check() && auth()->user()->role_id == 1)
                            <li class="slide">
                                <a href="{{ route('general_orders.customer.index') }}" class="side-menu__item">
                                    <span class="side-menu__label" style="color: black;">
                                        {{ $lang == 'ar' ? 'العروض المقبولة' : 'Accepted Orders' }}

                                    </span>

                                </a>
                        @endif
                        @if (auth()->check())
                            @if (auth()->check() && auth()->user()->role_id == 1)
                                <li class="slide">
                                    <a href="{{ route('general_comments.show', $user->id) }}"
                                        class="side-menu__item">
                                        <span class="side-menu__label" style="color: black;">
                                            {{ $lang == 'ar' ? 'العروض المقدمة' : 'My offers' }}
                            @endif
                            </span>

                            </a>


                        @endif
                        @if (auth()->check() && auth()->user()->role_id == 3)
                            <li class="slide">
                                <a href="{{ route('web.comments.my_comments', $user->id) }}" class="side-menu__item">
                                    <span class="side-menu__label"
                                        style="color: black;">{{ $lang == 'ar' ? 'عروضي' : 'My Comments' }}</span>
                                    <i class="fe fe-chevron-down side-menu__angle"></i>
                                </a>

                            </li>
                        @endif
                        <!-- End::slide -->

                        <?php $pages = App\Models\Page::paginate(5); ?>
                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <span class="side-menu__label" style="color: black;">{{ __('page.pages') }}</span>
                                <i class="fe fe-chevron-down side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1 mega-slide-menu-onefr without-icon">
                                @forelse ($pages as $page)
                                    <li class="slide">
                                        <a href="{{ route('page', $page->slug) }}" class="side-menu__item">
                                            <i class="bi bi-house"></i>
                                            <span
                                                class="fw-500 tx-15">{{ $lang == 'ar' ? $page->title_ar : $page->title_en }}</span>
                                        </a>
                                    </li>
                                @empty
                                @endforelse



                            </ul>
                        </li>


                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <span class="side-menu__label" style="color: black;">
                                    {{ session()->has('locale') && session()->get('locale') == 'en' ? __('general.english') : __('general.arabic') }}
                                </span>
                                <span class="float-right text-muted text-sm">
                                    {{-- <img src="{{ (session()->has('locale') && session()->get('locale') == 'ar') ? URL::asset('images/flags/SA.png') : URL::asset('images/flags/US.png') }}" alt=""> --}}
                                </span>
                            </a>
                            <ul class="slide-menu child1 mega-slide-menu-onefr without-icon">
                                <li class="slide">
                                    <a href="{{ route('lang', 'ar') }}" class="dropdown-item" id="rtlBtn">
                                        {{ __('general.arabic') }}
                                        <span class="float-right text-muted text-sm">
                                            <img src="{{ URL::asset('images/flags/SA.png') }}" alt="">

                                        </span>
                                    </a>

                                </li>
                                <li class="slide">

                                    <a href="{{ route('lang', 'en') }}" class="dropdown-item" id="ltrBtn">
                                        {{ __('general.english') }}
                                        <span class="float-right text-muted text-sm">
                                            <img src="{{ URL::asset('images/flags/US.png') }}" alt="">

                                        </span>
                                    </a>
                                </li>




                            </ul>
                        </li>
                    </ul>
                    {{-- <div class="d-xl-flex d-lg-none d-grid gap-2 text-center">
                        <a href="register.html" class="btn btn-secondary min-w-fit-content">Register</a>
                        <a href="login.html" class="btn btn-outline-light login-btn min-w-fit-content">Login</a>
                    </div> --}}

                </nav>
                <!-- End::nav -->

            </div>
            <!-- End::main-sidebar -->

        </aside>
        <!-- End::app-sidebar -->
    </div>
</div>
