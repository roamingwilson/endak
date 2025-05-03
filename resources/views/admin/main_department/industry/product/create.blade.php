@extends('layouts.dashboard.dashboard')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ ($lang == 'ar') ? 'صناعة البلاستيك' : 'Plastic Industry' }}
@endsection

@section('page_name')
    {{ ($lang == 'ar') ? 'صناعة البلاستيك' : 'Plastic Industry' }}
@endsection

@section('content')
    <style>
        .filter-bar {
            margin-bottom: 20px;
        }

        .filter-bar h4 {
            margin-bottom: 10px;
        }

        .filter-bar a {
            display: inline-block;
            margin: 3px 8px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            border-radius: 4px;
            color: #333;
        }

        .filter-bar a.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
    </style>

    <div class="container mt-5">
        <h2 class="mb-4">{{ $lang == 'ar' ? 'إضافة منتج جديد' : 'Add New Product' }}</h2>

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

        {{-- نموذج المنتج --}}
        <form action="{{ route('indproducts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ $lang == 'ar' ? 'اسم المنتج' : 'Product Name' }}</label>
                <input type="text" name="title" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="{{ $lang == 'ar' ? 'مثال: عبوة مياه 5 لتر' : 'e.g. 5L Water Bottle' }}">

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">{{ $lang == 'ar' ? 'القسم الرئيسي' : 'Main Category' }}</label>
                <select name="inds_category_id" id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">{{ $lang == 'ar' ? '-- اختر قسم رئيسي --' : '-- Select Main Category --' }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="subcategory_id" class="form-label">{{ $lang == 'ar' ? 'القسم الفرعي' : 'Subcategory' }}</label>
                <select name="ind_sub_category_id" id="subcategory_id"
                        class="form-select @error('subcategory_id') is-invalid @enderror">
                    <option value="">{{ $lang == 'ar' ? '-- اختر قسم فرعي --' : '-- Select Subcategory --' }}</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>

                @error('subcategory_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">{{ $lang == 'ar' ? 'وصف المنتج' : 'Product Description' }}</label>
                <textarea name="description" id="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="4"
                          placeholder="{{ $lang == 'ar' ? 'أدخل وصف مختصر عن المنتج' : 'Enter a short product description' }}">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">{{ $lang == 'ar' ? 'صورة المنتج' : 'Product Image' }}</label>
                <input type="file" name="image" id="image"
                       class="form-control @error('image') is-invalid @enderror">

                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">{{ $lang == 'ar' ? 'سعر المنتج' : 'Product Price' }}</label>
                <input type="number" name="price" id="price"
                       class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}"
                       placeholder="{{ $lang == 'ar' ? 'أدخل السعر بالجنيه مثلا 150.00' : 'Enter price, e.g. 150.00' }}"
                       step="0.01" min="0">

                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                {{ $lang == 'ar' ? 'حفظ المنتج' : 'Save Product' }}
            </button>
        </form>
    </div>

    {{-- رسائل التنبيه --}}
    @if (Session::has('success'))
        <script>
            swal("{{ $lang == 'ar' ? 'نجاح' : 'Success' }}", "{{ Session::get('success') }}", 'success', {
                button: true,
                timer: 3000,
            });
        </script>
    @endif

    @if (Session::has('info'))
        <script>
            swal("{{ $lang == 'ar' ? 'معلومة' : 'Info' }}", "{{ Session::get('info') }}", 'info', {
                button: true,
                timer: 3000,
            });
        </script>
    @endif
@endsection
