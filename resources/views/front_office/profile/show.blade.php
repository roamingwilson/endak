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
<div class="bg-gradient-to-b from-yellow-400 to-yellow-100 min-h-screen py-8 px-2">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-6 relative">
        <div class="flex flex-col items-center">
            <div class="relative">
                <img src="{{ asset('storage/' . ($user->image ?? 'user.png')) }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover border-4 border-yellow-400 shadow">
            </div>
            <h2 class="mt-4 text-2xl font-bold text-gray-800 flex items-center gap-2">
                {{ $user->first_name }} {{ $user->last_name }}
                {{-- حالة الحساب --}}
                @if($user->status == 'active')
                    <span class="ml-2 px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">{{ $lang == 'ar' ? 'مفعل' : 'Active' }}</span>
                @elseif($user->status == 'disactive')
                    <span class="ml-2 px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">{{ $lang == 'ar' ? 'غير مفعل' : 'Disactive' }}</span>
                    @if(auth()->id() == $user->id)
                        <a href="{{ route('activate_phone') }}" class="ml-2 px-2 py-1 rounded bg-yellow-400 text-white text-xs font-semibold hover:bg-yellow-500 transition">{{ $lang == 'ar' ? 'تفعيل الحساب' : 'Activate Now' }}</a>
                    @endif
                @elseif($user->status == 'banned')
                    <span class="ml-2 px-2 py-1 rounded-full bg-gray-300 text-gray-700 text-xs font-semibold">{{ $lang == 'ar' ? 'محظور' : 'Banned' }}</span>
                @endif
            </h2>
            <span class="mt-1 inline-block px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300">
                {{ $user->role_id == 3 ? ($lang == 'ar' ? 'مزود خدمة' : 'Provider') : ($lang == 'ar' ? 'عميل' : 'User') }}
            </span>
            {{-- تقييم وعدد الخدمات --}}
            @if($user->role_id == 3)
                @php
                    $rate = $user->rates();
                    $servicesCount = $user->serv()->count();
                @endphp
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-yellow-500 flex items-center">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $rate)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="text-gray-700 font-semibold">{{ $rate }}/5</span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-gray-700 font-semibold">
                        <i class="fas fa-briefcase text-yellow-500 mr-1"></i>
                        {{ $lang == 'ar' ? 'عدد الخدمات:' : 'Services:' }} {{ $servicesCount }}
                    </span>
                </div>
            @endif
            @if($user->about_me)
                <p class="mt-2 text-gray-600 text-center">{{ $user->about_me }}</p>
            @endif
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4">
            <a href="{{route('web.profile.edit',auth()->id())}}" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 rounded-lg text-center transition"> <i class="fas fa-edit mr-2"></i> {{ $lang == 'ar' ? 'تعديل الملف الشخصي' : 'Edit Profile' }}</a>
            <a href="{{ route('logout') }}" onclick="confirmLogout()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2 rounded-lg text-center transition"> <i class="fas fa-sign-out-alt mr-2"></i> {{ $lang == 'ar' ? 'تسجيل الخروج' : 'Log Out' }}</a>
        </div>
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-3 flex items-center"><i class="fas fa-layer-group mr-2 text-yellow-500"></i> {{ $lang == 'ar' ? 'الأقسام المشترك بها' : 'Subscribed Departments' }}</h3>
            @php
                $departments = $user->getAllDepartments();

                $mainDepartments = $departments['main'];
                $subDepartments = $departments['sub'];
                $shownSubIds = [];
            @endphp
            <div class="space-y-3">
                @foreach($mainDepartments as $main)
                    <div class="bg-yellow-50 rounded-lg p-3 shadow flex flex-col md:flex-row md:items-center md:gap-4">
                        <span class="font-bold text-yellow-800 text-base flex-shrink-0">{{ app()->getLocale() == 'ar' ? $main->name_ar : $main->name_en }}</span>
                        @php
                            $subs = $main->sub_departments->filter(function($sub) use ($subDepartments) {
                                return $subDepartments->contains('id', $sub->id);
                            });
                        @endphp
                        @if($subs->count())
                            <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                                @foreach($subs as $sub)
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold shadow">{{ app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en }}</span>
                                    @php $shownSubIds[] = $sub->id; @endphp
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                @php
                    $unlinkedSubs = $subDepartments->filter(function($sub) use ($shownSubIds) {
                        return !in_array($sub->id, $shownSubIds);
                    });
                @endphp
                @if($unlinkedSubs->count())
                    <div class="bg-gray-50 rounded-lg p-3 shadow">
                        <span class="font-bold text-gray-700">{{ $lang == 'ar' ? 'الأقسام الفرعية فقط:' : 'Sub Departments Only:' }}</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($unlinkedSubs as $sub)
                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold shadow">{{ app()->getLocale() == 'ar' ? $sub->name_ar : $sub->name_en }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($mainDepartments->isEmpty() && $subDepartments->isEmpty())
                    <span class="text-gray-400">{{ $lang == 'ar' ? 'لا يوجد' : 'None' }}</span>
                @endif
            </div>
        </div>
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="flex items-center gap-2 bg-gray-50 hover:bg-yellow-50 p-4 rounded-lg shadow transition">
                <i class="fas fa-wallet text-yellow-500"></i> <span class="font-semibold text-gray-700">{{ $lang == 'ar' ? 'رصيدي' : 'My Credit' }}</span>
            </a>
            <a href="{{route('feedback')}}" class="flex items-center gap-2 bg-gray-50 hover:bg-yellow-50 p-4 rounded-lg shadow transition">
                <i class="fas fa-comments text-yellow-500"></i> <span class="font-semibold text-gray-700">{{ $lang == 'ar' ? 'ملاحظات المستخدمين' : 'User`s Feedback' }}</span>
            </a>
            <a href="{{route('terms')}}" class="flex items-center gap-2 bg-gray-50 hover:bg-yellow-50 p-4 rounded-lg shadow transition">
                <i class="fas fa-file-alt text-yellow-500"></i> <span class="font-semibold text-gray-700">{{ $lang == 'ar' ? 'الشروط و الأحكام' : 'Terms and Conditions' }}</span>
            </a>
            <a href="{{route('faq')}}" class="flex items-center gap-2 bg-gray-50 hover:bg-yellow-50 p-4 rounded-lg shadow transition">
                <i class="fas fa-question-circle text-yellow-500"></i> <span class="font-semibold text-gray-700">{{ $lang == 'ar' ? 'الأسئلة الشائعة' : 'FAQ' }}</span>
            </a>
            <a href="{{route('privcy')}}" class="flex items-center gap-2 bg-gray-50 hover:bg-yellow-50 p-4 rounded-lg shadow transition">
                <i class="fas fa-shield-alt text-yellow-500"></i> <span class="font-semibold text-gray-700">{{ $lang == 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</span>
            </a>
        </div>
    </div>
</div>
{{-- مودال تأكيد تسجيل الخروج --}}
<div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-lg mb-4">{{ $lang == 'ar' ? 'هل تريد تسجيل الخروج؟' : 'Do you want to log out?' }}</h2>
        <div class="flex justify-center gap-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">{{ $lang == 'ar' ? 'تسجيل الخروج' : 'Log Out' }}</button>
            </form>
            <button onclick="closeLogoutModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">{{ $lang == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
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
