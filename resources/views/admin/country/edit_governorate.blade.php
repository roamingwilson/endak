@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'تعديل المحافظة': "Edit Governorate" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'تعديل المحافظة': "Edit Governorate" }}
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>{{ ($lang == 'ar')? 'تعديل المحافظة': "Edit Governorate" }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('governorates.update', $governorate->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="country_id">{{ ($lang == 'ar')? 'اختيار الدولة': "Choose Country" }}</label>
                    <select name="country_id" class="form-control @error('country_id') is-invalid @enderror" required>
                        <option value="">{{ ($lang == 'ar')? 'اختر الدولة': "Select Country" }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $governorate->country_id == $country->id ? 'selected' : '' }}>
                                {{ ($lang == 'ar')? $country->name_ar : $country->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name_ar">{{ ($lang == 'ar')? 'اسم المحافظة باللغة العربية': "Governorate Name in Arabic" }}</label>
                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ $governorate->name_ar }}" required>
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name_en">{{ ($lang == 'ar')? 'اسم المحافظة باللغة الإنجليزية': "Governorate Name in English" }}</label>
                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ $governorate->name_en }}" required>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">{{ ($lang == 'ar')? 'حفظ التغييرات': "Save Changes" }}</button>
                    <a href="{{ route('governorates.index') }}" class="btn btn-secondary">{{ ($lang == 'ar')? 'إلغاء': "Cancel" }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
