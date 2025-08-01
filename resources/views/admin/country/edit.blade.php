@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'تعديل الدولة': "Edit Country" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'تعديل الدولة': "Edit Country" }}
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>{{ ($lang == 'ar')? 'تعديل الدولة': "Edit Country" }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('countries.update', $country->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name_ar">{{ ($lang == 'ar')? 'اسم الدولة باللغة العربية': "Country Name in Arabic" }}</label>
                    <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ $country->name_ar }}" required>
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name_en">{{ ($lang == 'ar')? 'اسم الدولة باللغة الإنجليزية': "Country Name in English" }}</label>
                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ $country->name_en }}" required>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">{{ ($lang == 'ar')? 'حفظ التغييرات': "Save Changes" }}</button>
                    <a href="{{ route('countries.index') }}" class="btn btn-secondary">{{ ($lang == 'ar')? 'إلغاء': "Cancel" }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
