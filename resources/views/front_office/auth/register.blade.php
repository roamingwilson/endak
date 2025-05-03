@extends('layouts.home')
<?php $lang = config('app.locale'); ?>

@section('title')
    {{ __('login') }}
    <?php $lang = Session::get('locale'); ?>
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('select2-4.0.3/css/select2.css') }}">

@endsection
@section('content')
@php
    use App\Models\Country;
    use App\Models\Governements;

    $countries = Country::with('Governements')->get();
@endphp
    <section>
        <div class="container" style="display: flex; justify-content:center;align-items:center">
            <div style="width: 400px;margin-bottom:80px;margin-top:60px">
                <h1 style="text-align: center;margin:30px">{{ __('auth.register') }}</h1>
                {{-- <livewire:register /> --}}
                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="" class="m-2">{{ __('auth.first_name') }}</label>
                        <input class="form-control" name="first_name" type="text" placeholder="{{ __('auth.first_name') }}" value="{{ old('first_name') }}">
                        @error('first_name') <span class="error text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="form-group">
                        <label for="" class="m-2">{{ __('auth.last_name') }}</label>
                        <input class="form-control" name="last_name" type="text" placeholder="{{ __('auth.last_name') }}" value="{{ old('last_name') }}">
                        @error('last_name') <span class="error text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="form-group">
                        <label for="" class="m-2">{{ __('auth.phone') }}</label>
                        <input class="form-control" name="phone" type="text" placeholder="{{ __('auth.phone') }}" value="{{ old('phone') }}">
                        @error('phone') <span class="error text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="form-group">
                        <label for="departments" class="m-2">{{ __('department.departments') }}</label>
                        <select name="departments[]" id="tags" class="form-control main_departments select2" multiple="multiple">
                            {{-- <option value="">{{ __('department.select_product') }}</option> --}}
                            @foreach ($merged_departments as $merged_department_item)
                            {{-- <input class="form-control" name="department_type[]" type="hidden"  value="{{ old('email') }}"> --}}
                                <option value="{{ $merged_department_item->name_en. '-'  . $merged_department_item ->id }}" >
                                    {{ ($lang == 'ar') ? $merged_department_item->name_ar : $merged_department_item->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('departments') <span class="error text-danger">{{ $message }}</span> @enderror

                    </div>


                    <form>
                        <div class="mb-3">
                            <label for="country">{{ $lang == 'ar' ? 'الدولة' : 'Country' }}</label>
                            <select id="country" class="form-control">
                                <option value="">{{ $lang == 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $lang == 'ar' ? $country->name_ar : $country->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="governement">{{ $lang == 'ar' ? 'المحافظة' : 'Governorate' }}</label>
                            <select id="governement" class="form-control">
                                <option value="">{{ $lang == 'ar' ? 'اختر المحافظة' : 'Select Governorate' }}</option>
                            </select>
                        </div>
                    </form>






                    <div class="form-group">
                        <label for="" class="m-2">{{ __('auth.password') }}</label>
                        <input class="form-control" name="password" type="password" placeholder="{{ __('auth.password') }}" value="{{ old('password') }}">
                        @error('password') <span class="error text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="form-group">
                            <label for="">
                                <input type="radio" name="role_id" id="work_type-" value="1"
                                    class="m-2" checked>{{ $lang == 'en' ? 'Customer'  : 'عميل' }}
                            </label>
                            <label for="">
                                <input type="radio" name="role_id" id="work_type-" value="3"
                                    class="m-2">{{ $lang == 'en' ? 'Service Provider'  : 'مزود خدمة' }}
                            </label>

                    </div>
                    {{-- <div class="form-group">
                        <label for="" class="m-2">{{ __('general.image') }}</label>
                        <input class="form-control" name="image" type="file" placeholder="{{ __('general.image') }}" value="{{ old('image') }}">
                        @error('image') <span class="error text-danger">{{ $message }}</span> @enderror

                    </div> --}}
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg w-100 btn-primary mt-2 mb-2">{{ __('auth.register') }}</button>

                        <p class="m-2 d-inline">
                            <a href="{{ route('login-page') }}">{{ __('auth.Do_You_Have_Account') }}</a>
                        </p>

                    </div>
                </form>
            </div>

        </div>

    </section>

    @if (isset($message))
        <script>
            swal("Message", "{{ $message }}", 'success', {
                button: true,
                button: "Ok",
                timer: 5000,
            })
        </script>
    @endif
@endsection
@section('script')
<script src="{{ asset('js/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('select2-4.0.3/js/select2.min.js') }}"></script>

<script>
    $(".main_departments").select2({
        topics: true,
        tokenSeparators: [',', ' ']
    })
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch('/get-countries')
            .then(response => response.json())
            .then(data => {
                const countrySelect = document.getElementById('country');
                const governementSelect = document.getElementById('governement');

                countrySelect.innerHTML = '<option value="">اختر الدولة</option>';

                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);
                });

                countrySelect.addEventListener('change', function () {
                    const selected = data.find(c => c.id == this.value);
                    governementSelect.innerHTML = '<option value="">اختر المحافظة</option>';

                    if (selected && selected.governements.length > 0) {
                        selected.governements.forEach(gov => {
                            const option = document.createElement('option');
                            option.value = gov.id;
                            option.textContent = gov.name;
                            governementSelect.appendChild(option);
                        });
                    }
                });
            });
    });
</script>

@endsection
