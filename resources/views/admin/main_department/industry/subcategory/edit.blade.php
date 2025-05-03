@extends('layouts.dashboard.dashboard')
@section('title')
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection

@section('page_name')
{{ ($lang == 'ar')? 'تعديل القسم الفرعي' : "SubCategory" }}
@endsection

@section('content')
<div class="container mt-5">
    <h2>{{ ($lang == 'ar')? 'تعديل القسم الفرعي' : "SubCategory" }} </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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

    <form action="{{ route('indsubcategories.update', $subcategory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">  {{ ($lang == 'ar')? 'اسم القسم الفرعي' : "SubCategory name" }} </label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $subcategory->name) }}">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inds_category_id" class="form-label">    {{ ($lang == 'ar')? 'القسم الرئيسي' : "Category name" }} </label>
            <select name="inds_category_id" id="inds_category_id"
                    class="form-select @error('inds_category_id') is-invalid @enderror">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $subcategory->inds_category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('inds_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary"> {{ ($lang == 'ar')? 'حفظ التعديلات' : "Save" }}</button>
    </form>
</div>
@endsection
