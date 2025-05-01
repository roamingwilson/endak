
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'إضافة دولة ومحافظاتها ': "add country" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'إضافة دولة ومحافظاتها ': "add country" }}
@endsection


@section('content')
<div class="container">


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{route('store_gover')}}" method="POST">
        @csrf
        <label>{{ ($lang == 'ar')? ' اختيار الدولة ': "Choose Country  " }}</label>
        <div class="mb-3">
            @foreach ($countries as $country)

            <select name="country_id" id="">
                <option value="{{$country->id}}">{{ ($lang == 'ar')? $country->name_ar : $country->name_en }}</option>
            </select>
        </div>
        @endforeach

        <div id="governorates-wrapper">


        <div class="mb-3">
            <label for="name"> {{ ($lang == 'ar')? 'اسم المحافظة باللغة العربية': "country's name in Arabic" }}</label>
            <input type="text" name="name_ar" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name"> {{ ($lang == 'ar')? 'اسم المحافظة باللغة الانجليزية': "country's name in english" }}</label>
            <input type="text" name="name_en" class="form-control" required>
        </div>
        </div>



        <br>
        <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
    </form>
</div>
@endsection
