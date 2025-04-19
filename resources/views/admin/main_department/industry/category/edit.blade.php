@extends('layouts.dashboard.dashboard')

@section('title')
    <?php $lang = config('app.locale'); ?>

    {{ ($lang == 'ar')? 'معدات ثقيلة' : "Heavy equipment" }}

    @endsection

@section('page_name')
{{ ($lang == 'ar')? 'معدات ثقيلة' : "Heavy equipment" }}

@endsection

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">تعديل القسم: {{ $category->name }}</h2>

    {{-- رسائل الأخطاء --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- نموذج تعديل القسم --}}
    <form action="{{ route('indscategories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if (isset($industry) && $industry->id)
        <input type="hidden" name="industry_id" value="{{ $industry->id }}">
             @endif
        <div class="mb-3">
            <label for="name" class="form-label">اسم القسم</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $category->name) }}" placeholder="مثال: خامات بلاستيك">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>



        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        <a href="{{ route('indscategories.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
