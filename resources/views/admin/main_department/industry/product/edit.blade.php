@extends('layouts.dashboard.dashboard')
<?php $lang = config('app.locale'); ?>
@section('title')
    {{ $lang == 'ar' ? 'تعديل المنتج' : 'Edit Product' }}
@endsection

@section('page_name')
    {{ $lang == 'ar' ? 'تعديل المنتج' : 'Edit Product' }}
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{ $lang == 'ar' ? 'تعديل المنتج' : 'Edit Product' }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('indproducts.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- اسم المنتج --}}
        <div class="mb-3">
            <label for="title" class="form-label">{{ $lang == 'ar' ? 'اسم المنتج' : 'Product Name' }}</label>
            <input type="text" name="title" id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $product->title) }}" placeholder="{{ $lang == 'ar' ? 'مثال: عبوة مياه 5 لتر' : 'Example: 5L Water Bottle' }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- القسم الرئيسي --}}
        <div class="mb-3">
            <label for="inds_category_id" class="form-label">{{ $lang == 'ar' ? 'القسم الرئيسي' : 'Main Category' }}</label>
            <select name="inds_category_id" id="inds_category_id"
                    class="form-select @error('inds_category_id') is-invalid @enderror">
                <option value="">{{ $lang == 'ar' ? '-- اختر قسم رئيسي --' : '-- Select Main Category --' }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('inds_category_id', $product->inds_category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('inds_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- القسم الفرعي --}}
        <div class="mb-3">
            <label for="ind_sub_category_id" class="form-label">{{ $lang == 'ar' ? 'القسم الفرعي' : 'Sub Category' }}</label>
            <select name="ind_sub_category_id" id="ind_sub_category_id"
                    class="form-select @error('ind_sub_category_id') is-invalid @enderror">
                <option value="">{{ $lang == 'ar' ? '-- اختر قسم فرعي --' : '-- Select Sub Category --' }}</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ old('ind_sub_category_id', $product->ind_sub_category_id) == $subcategory->id ? 'selected' : '' }}>
                        {{ $subcategory->name }}
                    </option>
                @endforeach
            </select>
            @error('ind_sub_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- وصف المنتج --}}
        <div class="mb-3">
            <label for="description" class="form-label">{{ $lang == 'ar' ? 'وصف المنتج' : 'Product Description' }}</label>
            <textarea name="description" id="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- صورة المنتج --}}
        <div class="mb-3">
            <label for="image" class="form-label">{{ $lang == 'ar' ? 'صورة المنتج' : 'Product Image' }}</label>
            <input type="file" name="image" id="image"
                   class="form-control @error('image') is-invalid @enderror">
            @if($product->image)
                <small class="d-block mt-1">{{ $lang == 'ar' ? 'الصورة الحالية:' : 'Current Image:' }} <img src="{{ asset('storage/' . $product->image) }}" width="100"></small>
            @endif
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- السعر --}}
        <div class="mb-3">
            <label for="price" class="form-label">{{ $lang == 'ar' ? 'سعر المنتج' : 'Product Price' }}</label>
            <input type="number" name="price" id="price"
                   class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $product->price) }}" step="0.01" min="0">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">{{ $lang == 'ar' ? 'تحديث المنتج' : 'Update Product' }}</button>
    </form>
</div>
@endsection
