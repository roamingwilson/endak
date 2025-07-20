@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'الصفحة الشخصية' : 'Profile' }}
@endsection
@section('style')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    {{-- <style>
      .profile-page {
    background-color: #f3f4f6;
    min-height: 100vh;
    direction: rtl;
}

.profile-page .top-bar {
    background-color: #f4be2c;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.profile-page .user-avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    object-fit: cover;
    margin: auto;
}

.profile-page .lock-icon {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: #f4be2c;
    padding: 4px;
    border-radius: 50%;
}

.profile-page ul {
    margin-top: 2rem;
    padding: 0;
    list-style: none;
}

.profile-page ul li {
    padding: 1rem 0;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    font-size: 1rem;
    border-bottom: 1px solid #eee;
}

.profile-page ul li i {
    margin-left: 10px;
    color: #666;
}

.logout-button {
    background-color: #f44336;
    color: white;
    width: 100%;
    padding: 0.75rem;
    border-radius: 0.5rem;
    margin-top: 2rem;
    font-weight: bold;
}

.modal-background {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    text-align: center;
}

    </style> --}}
@endsection
{{-- @section('content')
    <section class="profile-cover-container">
        @if (auth()->check())
            @if (auth()->user()->id == $user->id)
                <a href="{{ route('web.profile.edit', $user->id) }}" class="btn btn-primary m-3">تعديل الملف الشخصي</a>
            @endif
        @endif
        @if ($user->image )
            <img src="{{ $user->image_url }}" class="img-cover profile-img" alt="{{ $user->first_name }}" />
        @else
            <img src="{{ asset('images/user.png') }}" class="img-cover profile-img" alt="{{ $user->first_name }}" />
        @endif
        <div class="profile-content pt-40">
            <div class="container position-relative">
                <div
                    class="profile-card d-flex flex-column flex-md-row align-items-center justify-content-between rounded-lg shadow-xs bg-white p-15 p-md-30">

                    <div class="user-info d-flex flex-column align-items-center text-center">
                        <strong class="user-name font-20 text-dark-blue font-weight-bold">{{ $user->first_name }}
                            {{ $user->last_name }}</strong>
                        <span class="user-role font-14 text-gray">{{ $user->role }}</span>
                    </div>

                    @php
                        $i = 5;

                        $rate = $user->rates();
                    @endphp
                    @if ($user->role_id == 3)
                        <div class="stars-card d-flex align-items-center ">


                            @while (--$i >= 5 - $rate)
                                <i data-feather="star" width="20" height="20" class="active"></i>
                            @endwhile
                            @while ($i-- >= 0)
                                <i data-feather="star" width="20" height="20" class=""></i>
                            @endwhile
                            <span class="badge badge-primary ml-10 bg-primary">{{ $rate }}</span>

                        </div>
                    @endif
                    <div class="user-stats d-flex justify-content-between mt-20 mt-md-0 mb-30 mb-md-0">
                        @if ($user->role_id == 3)
                            <div class="stat-item">
                                <span class="stat-label font-14 text-gray">{{ trans('order.count') }}</span>
                                <strong class="stat-value font-20">{{ $order->count() }}</strong>
                            </div>
                        @endif
                        @if ($user->role_id == 1)
                            <div class="stat-item">
                                <span class="stat-label font-14 text-gray">{{ trans('order.order_complete_count') }}</span>
                                <strong class="stat-value font-20">{{ $order->count()  }}</strong>
                            </div>
                        @endif
                    </div>

                </div>
            </div>


        </div>


    </section>
    <div style="margin-top: -90px " class=" mb-5">
        <div class="profile-content pt-40">
            <div class="container position-relative">
                <div
                    class="profile-card d-flex flex-column flex-md-row align-items-center justify-content-between rounded-lg shadow-xs bg-white mb-5 mt-5  p-15 p-md-30">
                    <p class="mb-2">
                        <strong>{{ __('user.about_me') }} : </strong>
                         {{ $user->about_me }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @if (Session::has('success'))
    <script>
        swal("Message", "{{ Session::get('success') }}", 'success', {
            button: true,
            button: "Ok",
            timer: 3000,
        })
    </script>
@endif
@if (Session::has('info'))
    <script>
        swal("Message", "{{ Session::get('info') }}", 'info', {
            button: true,
            button: "Ok",
            timer: 3000,
        })
    </script>
@endif
@endsection --}}
{{-- تأكد أن عندك لياوت app --}}

@section('content')
<div class="profile-page bg-gray-100 min-h-screen">
    <div class="bg-yellow-400 p-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="text-white">
                <i class="fas fa-home"></i> {{-- أيقونة الرجوع --}}
            </a>
        </div>
        <h1 class="text-white text-lg font-bold">  {{ $lang == 'ar' ? 'الصفحة الشخصية' : 'Profile' }}</h1>
    </div>

    <div class="p-4">
        {{-- صورة وبروفايل --}}
        <div class="flex flex-col items-center">
            <div class="relative">
                <img src="{{ asset('storage/' . ($user->image ?? 'user.png')) }}" alt="Profile Image" class="w-24 h-24 rounded-full object-cover">

                {{-- <div class="absolute bottom-0 right-0 bg-yellow-400 p-1 rounded-full">
                    <i class="fas fa-lock text-white text-xs"></i>
                </div> --}}
            </div>
            <h2 class="mt-2 text-lg font-semibold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>

        {{-- القائمة --}}
        <ul class="mt-6 space-y-4">
            <li class="flex items-center justify-between">
                <a href="#" class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-wallet"></i>  {{ $lang == 'ar' ? ' رصيدي' : 'my credit' }}
                </a>
            </li>
            <li class="flex items-center justify-between">
                <a href="{{route('web.profile.edit',auth()->id())}}" class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-edit"></i> {{ $lang == 'ar' ? ' تعديل الملف الشخصي ' : 'Edit Profile' }}
                </a>
            </li>

            <li class="flex items-center justify-between">
                <a href="{{route('feedback')}}" class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-comments"></i> {{ $lang == 'ar' ? ' ملاحظات المستخدمين ' : 'User`s Feedback' }}
                </a>
            </li>
            <li class="flex items-center justify-between">
                <a href="{{route('terms')}}" class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-file-alt"></i> {{ $lang == 'ar' ? ' الشروط و الأحكام ' : 'Terms and Conditions' }}
                </a>
            </li>

            <li class="flex items-center justify-between">
                <a href="{{route('faq')}}" class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-question-circle"></i> {{ $lang == 'ar' ? ' الأسئلة الشائعة ' : 'FAQ' }}
                </a>
            </li>
            <li class="flex items-center justify-between">
                <a href="{{route('privcy')}}" class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-shield-alt"></i> {{ $lang == 'ar' ? ' سياسة الخصوصية ' : 'Privcy Policy' }}
                </a>
            </li>
        </ul>

        {{-- زر تسجيل الخروج --}}
        <div class="mt-10">
            <a href="{{ route('logout') }}"  onclick="confirmLogout()" class="w-full bg-red-500 text-white py-2 rounded-lg" style="width: 100%"> {{ $lang == 'ar' ? ' تسجيل الخروج' : 'Log Out' }}</a>
        </div>
    </div>
</div>

{{-- عرض الأقسام المشترك بها المستخدم --}}
@if(isset($user))
    <div class="card mb-4">
        <div class="card-header fw-bold">الأقسام المشترك بها</div>
        <div class="card-body">
            @php
                $departments = $user->getAllDepartments();
            @endphp
            <div>
                <span class="fw-bold">الأقسام الرئيسية:</span>
                @forelse($departments['main'] as $dep)
                    <span class="badge bg-info text-dark">{{ app()->getLocale() == 'ar' ? $dep->name_ar : $dep->name_en }}</span>
                @empty
                    <span class="text-muted">لا يوجد</span>
                @endforelse
            </div>
            <div class="mt-2">
                <span class="fw-bold">الأقسام الفرعية:</span>
                @forelse($departments['sub'] as $dep)
                    <span class="badge bg-secondary">{{ app()->getLocale() == 'ar' ? $dep->name_ar : $dep->name_en }}</span>
                @empty
                    <span class="text-muted">لا يوجد</span>
                @endforelse
            </div>
        </div>
    </div>
@endif

{{-- مودال تأكيد تسجيل الخروج --}}
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-lg mb-4">هل تريد تسجيل الخروج؟</h2>
        <div class="flex justify-center gap-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded"> {{ $lang == 'ar' ? ' تسجيل الخروج' : 'my credit' }}</button>
            </form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function confirmLogout() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
        document.getElementById('logoutModal').classList.remove('flex');
    }
</script>
@endsection

@section('script')
    {{-- <script src="{{ asset('js/app.js') }}" ></script> --}}
    <script src="{{ asset('js/feather-icons/dist/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    {{-- import feather from 'feather-icons';
    feather.replace(); --}}
@endsection
