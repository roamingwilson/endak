@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'تعديل الصفحة الشخصية' : 'Edit Profile' }}
@endsection

@section('style')
    <style>
    .profile-edit-bg {
        background: linear-gradient(to bottom, #f4be2c 0%, #fffbe6 100%);
        min-height: 100vh;
        padding: 40px 0;
    }
    .profile-edit-card {
        max-width: 480px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 32px 24px;
            position: relative;
        }
    .profile-edit-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
            object-fit: cover;
        border: 3px solid #f4be2c;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: block;
        margin: 0 auto;
    }
    .profile-edit-avatar-label {
        position: absolute;
        bottom: 0;
        right: 50%;
        transform: translateX(50%);
        background: #f4be2c;
            border-radius: 50%;
        padding: 6px;
        border: 2px solid #fff;
        cursor: pointer;
    }
    .profile-edit-form {
        margin-top: 24px;
    }
    .profile-edit-form .form-group {
        margin-bottom: 18px;
    }
    .profile-edit-form label {
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
            display: block;
    }
    .profile-edit-form input,
    .profile-edit-form textarea,
    .profile-edit-form select {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 1rem;
        background: #fafafa;
        transition: border 0.2s;
        }
    .profile-edit-form input:focus,
    .profile-edit-form textarea:focus,
    .profile-edit-form select:focus {
        border-color: #f4be2c;
        outline: none;
        background: #fffbe6;
    }
    .profile-edit-form .error {
        color: #e53e3e;
        font-size: 0.95em;
        margin-top: 4px;
        }
    .profile-edit-form .btn-save {
        background: #f4be2c;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        padding: 12px 0;
        width: 100%;
        font-size: 1.1em;
        transition: background 0.2s;
        margin-top: 10px;
        }
    .profile-edit-form .btn-save:hover {
        background: #e0a800;
    }
    @media (max-width: 600px) {
        .profile-edit-card {
            padding: 18px 6px;
        }
        .profile-edit-form input,
        .profile-edit-form textarea,
        .profile-edit-form select {
            font-size: 0.98rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="profile-edit-bg">
    <div class="profile-edit-card">
        <h2 style="text-align:center; font-weight:bold; color:#444; margin-bottom:18px;">{{ $lang == 'ar' ? 'تعديل الصفحة الشخصية' : 'Edit Profile' }}</h2>
                        <?php $user = auth()->user(); ?>
        <form action="{{ route('web.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form">
                            @csrf
            <div style="position:relative; width:fit-content; margin:0 auto 18px auto;">
                <img src="{{ asset('storage/' . ($user->image ?? 'user.png')) }}" alt="Profile Image" class="profile-edit-avatar">
                <label for="image" class="profile-edit-avatar-label">
                    <i class="fas fa-camera text-white text-xs"></i>
                    <input type="file" id="image" name="image" class="hidden">
                </label>
            </div>
            <div class="form-group">
                <label for="first_name">{{ __('user.first_name') }}</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                            </div>
            <div class="form-group">
                <label for="last_name">{{ __('user.last_name') }}</label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                            </div>
            <div class="form-group">
                <label for="phone">{{ __('user.phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>
            <div class="form-group">
                <label for="email">{{ __('user.email') }}</label>
                <input type="text" name="email" value="{{ old('email', $user->email) }}">
                            </div>
            @if($user->role_id == 3)
            <div class="form-group">
                <label for="departments">{{ __('department.departments') }}</label>
                <small style="display:block; color:#888; margin-bottom:6px;">{{ $lang == 'ar' ? 'يمكنك اختيار 3 أقسام رئيسية أو فرعية فقط' : 'You can select up to 3 main or sub departments only' }}</small>
                <select name="departments[]" id="tags" class="main_departments select2" multiple="multiple">
                    @foreach ($main_departments as $main)
                        <option value="main-{{ $main->id }}">{{ $lang == 'ar' ? $main->name_ar : $main->name_en }}</option>
                        @if($main->sub_departments && $main->sub_departments->count())
                            <optgroup label="{{ $lang == 'ar' ? $main->name_ar : $main->name_en }} - {{ $lang == 'ar' ? 'الأقسام الفرعية' : 'Sub Departments' }}">
                                @foreach($main->sub_departments as $sub)
                                    <option value="sub-{{ $sub->id }}">&nbsp;&nbsp;{{ $lang == 'ar' ? $sub->name_ar : $sub->name_en }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                                    @endforeach
                                </select>
                @error('departments') <span class="error">{{ $message }}</span> @enderror
                            </div>
            @endif
            <div class="form-group">
                <label for="about_me">{{ __('user.about_me') }}</label>
                <textarea name="about_me" cols="30" rows="4">{{ old('about_me', $user->about_me) }}</textarea>
                            </div>
            <button type="submit" class="btn-save">{{ __('general.save') }}</button>
                        </form>
                    </div>
                </div>
    @if (Session::has('success'))
    <script>
        swal("نجاح", "{{ Session::get('success') }}", 'success', {
            button: true,
            button: "{{ app()->getLocale() == 'ar' ? 'حسناً' : 'Ok' }}",
            timer: 3000,
        })
    </script>
@endif
    @if (Session::has('error'))
        <script>
            swal("Message", "{{ Session::get('error') }}", 'error', {
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
@endsection

@section('script')
    <script src="{{ asset('js/feather-icons/dist/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    @if($user->role_id != 3)
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('select2-4.0.3/js/select2.min.js') }}"></script>
    <script>
        $(".main_departments").select2({
            topics: true,
            tokenSeparators: [',', ' '],
            maximumSelectionLength: 3,
            language: {
                maximumSelected: function (e) {
                    return '{{ $lang == "ar" ? "يمكنك اختيار 3 أقسام فقط" : "You can select up to 3 departments only" }}';
                }
            }
        });

        // تحسين التقييد لمنع اختيار أكثر من 3 أقسام
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.querySelector('.main_departments');

            // التحقق من عدد الأقسام المختارة عند التحميل
            function checkSelectionLimit() {
                const selectedOptions = select.selectedOptions;
                if (selectedOptions.length > 3) {
                    // إزالة الخيارات الزائدة
                    for (let i = 3; i < selectedOptions.length; i++) {
                        selectedOptions[i].selected = false;
                    }
                    alert('{{ $lang == "ar" ? "يمكنك اختيار 3 أقسام فقط" : "You can select up to 3 departments only" }}');
                }
            }

            // التحقق عند تغيير الاختيار
            select.addEventListener('change', function() {
                checkSelectionLimit();
            });

            // التحقق الأولي
            checkSelectionLimit();
        });
    </script>
    @endif
@endsection
