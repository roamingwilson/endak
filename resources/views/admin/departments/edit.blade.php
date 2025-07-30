@extends('layouts.dashboard.dashboard')

@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ ($lang == 'ar')? 'تعديل القسم: ' . $department->name_ar : 'Edit Department: ' . $department->name_en }}
@endsection

@section('page_name')
    {{ ($lang == 'ar')? 'تعديل القسم' : 'Edit Department' }}
@endsection

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <h3><i class="fas fa-exclamation-triangle"></i> {{ ($lang == 'ar')? 'حدث خطأ!' : 'Error Occurred!' }}</h3>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.departments.update', $department->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-edit"></i>
                                {{ ($lang == 'ar')? 'معلومات القسم' : 'Department Information' }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_ar" class="form-label">
                                            <i class="fas fa-font"></i>
                                            {{ __('department.name_ar') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
                                               value="{{ old('name_ar', $department->name_ar) }}" required>
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_en" class="form-label">
                                            <i class="fas fa-font"></i>
                                            {{ __('department.name_en') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror"
                                               value="{{ old('name_en', $department->name_en) }}" required>
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description_ar" class="form-label">
                                            <i class="fas fa-align-right"></i>
                                            {{ ($lang == 'ar')? 'الوصف بالعربية' : 'Description (Arabic)' }}
                                        </label>
                                        <textarea name="description_ar" id="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                                  rows="3">{{ old('description_ar', $department->description_ar) }}</textarea>
                                        @error('description_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description_en" class="form-label">
                                            <i class="fas fa-align-left"></i>
                                            {{ ($lang == 'ar')? 'الوصف بالإنجليزية' : 'Description (English)' }}
                                        </label>
                                        <textarea name="description_en" id="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                                  rows="3">{{ old('description_en', $department->description_en) }}</textarea>
                                        @error('description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department_id" class="form-label">
                                            <i class="fas fa-sitemap"></i>
                                            {{ ($lang == 'ar')? 'القسم الأب' : 'Parent Department' }}
                                        </label>
                                        <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                            <option value="0">{{ ($lang == 'ar')? 'قسم رئيسي' : 'Main Department' }}</option>
                                            @foreach(\App\Models\Department::where('id', '!=', $department->id)->get() as $dept)
                                                <option value="{{ $dept->id }}" {{ old('department_id', $department->department_id) == $dept->id ? 'selected' : '' }}>
                                                    {{ $lang == 'ar' ? $dept->name_ar : $dept->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sort_order" class="form-label">
                                            <i class="fas fa-sort-numeric-up"></i>
                                            {{ ($lang == 'ar')? 'ترتيب العرض' : 'Display Order' }}
                                        </label>
                                        <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                                               value="{{ old('sort_order', $department->sort_order ?? 0) }}" min="0">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-cogs"></i>
                                {{ ($lang == 'ar')? 'الإعدادات' : 'Settings' }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image" class="form-label">
                                    <i class="fas fa-image"></i>
                                    {{ __('settings.logo') }}
                                </label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror dropify"
                                       accept="image/*" data-default-file="{{ $department->image_url }}">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="icon" class="form-label">
                                    <i class="fas fa-icons"></i>
                                    {{ ($lang == 'ar')? 'الأيقونة' : 'Icon' }}
                                </label>
                                <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror"
                                       value="{{ old('icon', $department->icon) }}" placeholder="fas fa-home">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="color" class="form-label">
                                            <i class="fas fa-palette"></i>
                                            {{ ($lang == 'ar')? 'اللون' : 'Color' }}
                                        </label>
                                        <input type="color" name="color" id="color" class="form-control @error('color') is-invalid @enderror"
                                               value="{{ old('color', $department->color ?? '#1976d2') }}">
                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="background_color" class="form-label">
                                            <i class="fas fa-fill-drip"></i>
                                            {{ ($lang == 'ar')? 'لون الخلفية' : 'Background Color' }}
                                        </label>
                                        <input type="color" name="background_color" id="background_color" class="form-control @error('background_color') is-invalid @enderror"
                                               value="{{ old('background_color', $department->background_color ?? '#e3f2fd') }}">
                                        @error('background_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="status" id="status" class="form-check-input" value="1"
                                           {{ old('status', $department->status) ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label">
                                        <i class="fas fa-toggle-on"></i>
                                        {{ ($lang == 'ar')? 'نشط' : 'Active' }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1"
                                           {{ old('is_featured', $department->is_featured) ? 'checked' : '' }}>
                                    <label for="is_featured" class="form-check-label">
                                        <i class="fas fa-star"></i>
                                        {{ ($lang == 'ar')? 'مميز' : 'Featured' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-search"></i>
                                {{ ($lang == 'ar')? 'معلومات SEO' : 'SEO Information' }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_title_ar" class="form-label">
                                            <i class="fas fa-heading"></i>
                                            {{ ($lang == 'ar')? 'عنوان Meta بالعربية' : 'Meta Title (Arabic)' }}
                                        </label>
                                        <input type="text" name="meta_title_ar" id="meta_title_ar" class="form-control @error('meta_title_ar') is-invalid @enderror"
                                               value="{{ old('meta_title_ar', $department->meta_title_ar) }}">
                                        @error('meta_title_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_title_en" class="form-label">
                                            <i class="fas fa-heading"></i>
                                            {{ ($lang == 'ar')? 'عنوان Meta بالإنجليزية' : 'Meta Title (English)' }}
                                        </label>
                                        <input type="text" name="meta_title_en" id="meta_title_en" class="form-control @error('meta_title_en') is-invalid @enderror"
                                               value="{{ old('meta_title_en', $department->meta_title_en) }}">
                                        @error('meta_title_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_description_ar" class="form-label">
                                            <i class="fas fa-align-left"></i>
                                            {{ ($lang == 'ar')? 'وصف Meta بالعربية' : 'Meta Description (Arabic)' }}
                                        </label>
                                        <textarea name="meta_description_ar" id="meta_description_ar" class="form-control @error('meta_description_ar') is-invalid @enderror"
                                                  rows="2">{{ old('meta_description_ar', $department->meta_description_ar) }}</textarea>
                                        @error('meta_description_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_description_en" class="form-label">
                                            <i class="fas fa-align-left"></i>
                                            {{ ($lang == 'ar')? 'وصف Meta بالإنجليزية' : 'Meta Description (English)' }}
                                        </label>
                                        <textarea name="meta_description_en" id="meta_description_en" class="form-control @error('meta_description_en') is-invalid @enderror"
                                                  rows="2">{{ old('meta_description_en', $department->meta_description_en) }}</textarea>
                                        @error('meta_description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i>
                        {{ __('general.save') }}
                    </button>
                    <a href="{{ route('admin.departments') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i>
                        {{ ($lang == 'ar')? 'العودة' : 'Back' }}
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Initialize dropify
    $('.dropify').dropify({
        messages: {
            default: '{{ ($lang == "ar")? "اسحب وأفلت ملف هنا أو انقر" : "Drag and drop a file here or click" }}',
            replace: '{{ ($lang == "ar")? "اسحب وأفلت أو انقر لاستبدال" : "Drag and drop or click to replace" }}',
            remove: '{{ ($lang == "ar")? "إزالة" : "Remove" }}',
            error: '{{ ($lang == "ar")? "عذراً، حدث خطأ." : "Sorry, an error occurred." }}'
        }
    });

    // Auto-generate meta title from name
    $('#name_ar, #name_en').on('input', function() {
        var nameAr = $('#name_ar').val();
        var nameEn = $('#name_en').val();

        if (!$('#meta_title_ar').val()) {
            $('#meta_title_ar').val(nameAr);
        }
        if (!$('#meta_title_en').val()) {
            $('#meta_title_en').val(nameEn);
        }
    });

    // Auto-generate meta description from description
    $('#description_ar, #description_en').on('input', function() {
        var descAr = $('#description_ar').val();
        var descEn = $('#description_en').val();

        if (!$('#meta_description_ar').val()) {
            $('#meta_description_ar').val(descAr);
        }
        if (!$('#meta_description_en').val()) {
            $('#meta_description_en').val(descEn);
        }
    });
});
</script>
@endsection
