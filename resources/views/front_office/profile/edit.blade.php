@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'تعديل الصفحة الشخصية' : 'Edit Profile' }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2-4.0.3/css/select2.css') }}">
    <style>
        .profile-cover-container {
            position: relative;
            width: 100%;
            min-height: 400px; /* يمكنك استخدام min-height لضمان وجود ارتفاع أساسي */
            background-color: #f5f5f5;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-content {
            padding-top: 20px;
        }
        .profile-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* مثال على استجابة بعض العناصر باستخدام Media Queries */
        @media (max-width: 767.98px) {
            .profile-card {
                padding: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="profile-cover-container py-4">
        <div class="profile-content">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- استخدم أعمدة متجاوبة بدلًا من تعيين عرض ثابت -->
                    <div class="col-12 col-md-10 col-lg-8">
                        <?php $user = auth()->user(); ?>
                        <form action="{{ route('web.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-card rounded-lg shadow-xs bg-white p-3 p-md-4">
                            @csrf
                            <div class="form-group mt-2">
                                <label for="first_name">{{ __('user.first_name') }} :</label>
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="last_name">{{ __('user.last_name') }} :</label>
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="phone">{{ __('user.phone') }} :</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="email">{{ __('user.email') }} :</label>
                                <input type="text" class="form-control" name="email" value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="departments"  >{{ __('department.departments') }} : </label>
                                <select name="departments[]" id="tags" class="form-control main_departments select2" multiple="multiple">
                                    @foreach ($merged_departments as $merged_department_item)
                                        <option value="{{ $merged_department_item->name_en . '-' . $merged_department_item->id }}">
                                            {{ $lang == 'ar' ? $merged_department_item->name_ar : $merged_department_item->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departments') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mt-2">
                                <label for="about_me">{{ __('user.about_me') }} :</label>
                                <textarea class="form-control" name="about_me" cols="30" rows="10">{{ old('about_me', $user->about_me) }}</textarea>
                            </div>
                            <div class="form-group mt-2">
                                <label for="image">{{ __('user.image') }} :</label>
                                <input type="file" class="form-control" name="image" value="{{ old('image', $user->image) }}">
                            </div>
                            <div class="form-group mt-2 text-right">
                                <button type="submit" class="btn btn-primary mt-2">{{ __('general.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
@endsection

@section('script')
    <script src="{{ asset('js/feather-icons/dist/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('select2-4.0.3/js/select2.min.js') }}"></script>
    <script>
        $(".main_departments").select2({
            topics: true,
            tokenSeparators: [',', ' ']
        });
    </script>
@endsection
