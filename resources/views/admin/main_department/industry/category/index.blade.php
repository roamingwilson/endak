
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">    {{ ($lang == 'ar')? 'إضافة قسم جديد ' : "Add new Categories" }}  </h2>

    {{-- رسائل النجاح أو الخطأ --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- نموذج إضافة قسم --}}
    <form action="{{ route('indscategories.store') }}" method="POST">
        @csrf
        @if(isset($industry))
    <input type="hidden" name="industry_id" value="1">
@endif

<div class="mb-3">
    <label for="name" class="form-label"> {{ ($lang == 'ar')? 'اسم القسم' : "Category" }}</label>
    <input type="text" name="name" id="name"
    class="form-control @error('name') is-invalid @enderror"
    value="{{ old('name') }}" placeholder="مثال: خامات بلاستيك">


</div>



        <button type="submit" class="btn btn-primary"> {{ ($lang == 'ar')? 'حفظ ' : "save" }}</button>

    </form>
</div>


@endsection
