
@extends('layouts.dashboard.dashboard')
@section('title')
<?php $lang = config('app.locale'); ?>

{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('content')
    <div>
        <div class="container mt-5">
            <h2 class="mb-4">  {{ ($lang == 'ar')? "إضافة قسم فرعي جديد" : "Add Subcategory" }}</h2>

            {{-- عرض الأخطاء --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- نموذج الإضافة --}}
            <form action="{{ route('indsubcategories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">  {{ ($lang == 'ar')? 'اسم القسم الفرع ': " Subcategory" }}</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="مثال: عبوات بلاستيك">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">
                        {{ ($lang == 'ar') ? 'القسم الرئيسي' : 'Main Category' }}
                    </label>
                    <select name="inds_category_id" id="category_id"
                            class="form-select @error('inds_category_id') is-invalid @enderror">
                        <option value="">{{ ($lang == 'ar') ? '-- اختر قسم رئيسي --' : '-- Select Main Category --' }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('inds_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">  {{ ($lang == 'ar')? 'حفظ ' : "save" }}</button>

            </form>
        </div>
        <div class="container mt-5">


            {{-- رسائل الفيدباك --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif


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
@endsection
