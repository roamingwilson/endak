@extends('layouts.home')
@section('title')
    {{ __('department.departments') }}
@endsection


<?php $lang = config('app.locale'); ?>

@section('content')
    <style>
        .department-card {
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            background: linear-gradient(135deg, #f8fafc 80%, #e3e8ef 100%);
            padding: 32px 24px 24px 24px;
            margin-bottom: 32px;
            transition: box-shadow 0.2s;
        }
        .department-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
        }
        .department-card img {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.09);
            background: #fff;
            border: 2px solid #e3e3e3;
        }
        .field-card {
            border: 1px solid #e3e3e3;
            border-radius: 12px;
            background: #f5f7fa;
            margin-bottom: 18px;
            padding: 18px 16px 10px 16px;
            transition: box-shadow 0.2s;
            display: flex;
            align-items: center;
        }
        .field-card:hover {
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .field-label {
            font-weight: bold;
            color: #1a237e;
            margin-bottom: 8px;
            flex: 1 0 120px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .field-label i {
            color: #1976d2;
            font-size: 1.2em;
        }
        .form-control, select {
            border-radius: 8px;
            min-width: 120px;
        }
        .btn-success {
            border-radius: 8px;
            font-size: 1.1rem;
            padding: 12px 0;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(76,175,80,0.08);
        }
        .section-title {
            font-size: 2rem;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 24px;
            text-align: center;
            letter-spacing: 1px;
        }
        /* تحسين البطاقات */
        .department-card, .card {
            transition: box-shadow 0.3s, transform 0.2s;
        }
        .department-card:hover, .card:hover {
            box-shadow: 0 12px 32px rgba(25, 118, 210, 0.13);
            transform: translateY(-4px) scale(1.01);
        }

        /* تحسين الحقول */
        .field-card {
            margin-bottom: 24px;
            border-left: 4px solid #1976d2;
            background: #f8fafc;
        }
        .field-card:focus-within {
            border-color: #43e97b;
            box-shadow: 0 0 0 2px #43e97b33;
        }

        /* تحسين الأزرار */
        .btn-success, .btn-primary, .btn-danger, .btn-secondary {
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            box-shadow: 0 4px 16px #43e97b33;
        }
        .btn-primary:hover {
            background: #1565c0;
        }
        .btn-danger:hover {
            background: #c62828;
        }
        .btn-secondary:hover {
            background: #616161;
        }

        /* رسائل الأخطاء */
        .alert-danger {
            border-radius: 8px;
            font-size: 1.1rem;
            background: #fff3f3;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .alert-danger ul {
            margin-bottom: 0;
            padding-left: 1.5em;
        }
        .alert-danger li:before {
            content: "⚠️ ";
            margin-right: 4px;
        }

        /* منطقة التسجيل الصوتي */
        .voice-note-container {
            background: #e3e8ef;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 8px;
            margin-bottom: 8px;
            position: relative;
        }
        #recordingStatus:before {
            content: '';
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #d9534f;
            border-radius: 50%;
            margin-right: 6px;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        /* دعم RTL */
        body[dir="rtl"] .department-card,
        body[dir="rtl"] .card,
        body[dir="rtl"] .field-card {
            direction: rtl;
            text-align: right;
        }
    </style>
    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="section-title">
                                <i class="fas fa-layer-group"></i>
                                {{ $lang == 'ar' ? 'بيانات القسم' : 'Department Details' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="department-card text-center mb-4">
                            <div class="text-start mb-3">
                                <a href="{{ route('departments') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left"></i> العودة للأقسام
                                </a>
                                @if(isset($selectedSubDepartmentId) && $selectedSubDepartmentId)
                                    <a href="{{ route('departments.show', $department->id) }}" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-arrow-left"></i> العودة للأقسام الفرعية
                                    </a>
                                @endif
                            </div>
                            @php
                                $img = (!empty($department->image) && file_exists(public_path('storage/' . $department->image)))
                                    ? asset('storage/' . $department->image)
                                    : asset('images/logo.jpg');
                            @endphp
                            <img src="{{ $img }}" alt="img" width="160" height="160" class="mb-3">
                            <h2 class="mb-2" style="color:#1976d2">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}</h2>
                            <p class="text-muted">{{ $lang == 'ar' ? $department->description_ar : $department->description_en }}</p>
                                                                @if(isset($selectedSubDepartmentId) && $selectedSubDepartmentId)
                                        @php
                                            $selectedSubDepartment = $department->sub_departments->where('id', $selectedSubDepartmentId)->first();
                                        @endphp
                                        @if($selectedSubDepartment)
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-check-circle"></i>
                                                <strong>القسم الفرعي المحدد:</strong> {{ $selectedSubDepartment->name_ar ?? $selectedSubDepartment->name_en }}
                                            </div>
                                        @endif
                                    @endif

                                    <!-- عنوان الحقول الأساسية -->
                                    {{--  <div class="mb-4">
                                        <h5 class="text-center mb-3" style="color:#28a745; font-weight:bold; border-bottom: 2px solid #28a745; padding-bottom: 10px;">
                                            <i class="fas fa-info-circle"></i> {{ $lang == 'ar' ? 'المعلومات الأساسية' : 'Basic Information' }}
                                        </h5>
                                    </div>  --}}
                        </div>
                        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        @php
                            // اجمع الحقول حسب input_group
                            $groupedFields = $department->fields->groupBy('input_group');
                            // استبعد الحقول التي ليس لها مجموعة (null أو فارغ)
                            $groupedFields = $groupedFields->filter(function($fields, $group) { return $group; });
                        @endphp
                        @if($groupedFields->filter(function($fields, $group){ return $group; })->count())
                            <div class="mb-4">
                                {{--  <h4 class="text-center mb-3" style="color:#1976d2; font-weight:bold;"><i class="fas fa-object-group"></i> عناصر مجمعة (Group Controls)</h4>  --}}
                                </div>
                        @endif
                                    @foreach($groupedFields as $group => $fields)
                                        @php
                                            $repeatable = $fields->first()->is_repeatable ?? false;
                                        @endphp
                                        @if($group)
                                            <div class="card mb-3 border-0 group-block" data-group="{{ $group }}" data-repeatable="{{ $repeatable ? '1' : '0' }}" style="background: #fcfcfd; border-radius: 14px; box-shadow: 0 1px 6px #e3e8ef;">
                                                <div class="card-body group-fields-list position-relative" data-group="{{ $group }}">
                                                    @php $groupValues = old('custom_fields.' . $group, [[]]); @endphp
                                                    @foreach($groupValues as $idx => $groupInstance)
                                                        <div class="group-fields-instance mb-3 p-3 position-relative" data-index="{{ $idx }}" style="background: #fff; border-radius: 10px; border: 1px solid #e3e8ef;">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span style="font-size:1rem; font-weight:500; color:#1976d2;">  </span>
                                                                <button type="button" class="btn btn-outline-danger btn-sm remove-group-btn" style="{{ count($groupValues) == 1 ? 'display:none;' : '' }}; border-radius:50%; min-width:32px; min-height:32px; display:flex; align-items:center; justify-content:center;" title="حذف المجموعة">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <div class="row g-2 align-items-end">
                                                @foreach($fields as $field)
                                                                    <div class="col-md-3 col-sm-6 mb-2">
                                                                        @if($field->type === 'title')
                                                                            <div class="col-12 mb-3">
                                                                                <h4 class="text-dark mb-0" style="font-weight: bold; font-size: 1.5rem; border-bottom: 2px solid #333; padding-bottom: 8px;">
                                                                                    <i class="fas fa-heading"></i> {{ $field->value ?? (app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en) }}
                                                                                </h4>
                                                                            </div>
                                                                        @else
                                                                            <label for="custom_fields_{{ $group }}_{{ $idx }}_{{ $field->name }}" style="font-weight:500; color:#333; font-size:0.98rem; margin-bottom:4px; display:block;">
                                                                                {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                                            </label>
                                                                        @endif
                                                                        @if($field->type === 'select' && is_array($field->options))
                                                    <select name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control form-control-sm">
                                                        <option value="" disabled selected>{{ $lang == 'ar' ? 'اختر' : 'Select' }}</option>
                                                        @foreach($field->options as $option)
                                                            <option value="{{ $option }}" {{ (isset($groupInstance[$field->name]) && $groupInstance[$field->name] == $option) ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                                                                        </select>
                                                @endif
                                                @if($field->type === 'checkbox')
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="checkbox" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" value="1" class="form-check-input" {{ (isset($groupInstance[$field->name]) && $groupInstance[$field->name] == '1') ? 'checked' : '' }}>
                                                        <label for="custom_fields_{{ $group }}_{{ $idx }}_{{ $field->name }}" style="font-weight:500; color:#333; font-size:0.98rem; margin-bottom:0;">
                                                            {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                        </label>
                                                    </div>
                                                @endif
                                                @if($field->type === 'image[]' || $field->type === 'image' || $field->name === 'image')
                                                                            <div class="dynamic-image-uploader mb-2" data-field="custom_fields_{{ $field->name }}">
                                                                                <div class="text-center mb-2" style="color: #6c757d; font-size: 0.9em;">
                                                                                    <i class="fas fa-cloud-upload-alt"></i> اسحب الصور هنا أو اضغط لاختيار الملفات
                                                                                </div>
                                                                                <input type="file" name="custom_fields[{{ $field->name }}][]" id="custom_fields_{{ $field->name }}" accept="image/*" class="form-control image-input" multiple style="margin-bottom:8px;">
                                                                                <div class="preview-image d-flex flex-wrap gap-2"></div>
                                                                            </div>
                                                                        @endif
                                                                        @if($field->type === 'date')
                                                                            <input type="date" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control form-control-sm" value="{{ $groupInstance[$field->name] ?? '' }}">
                                                                        @endif
                                                                        @if($field->type === 'time')
                                                                            <input type="time" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control form-control-sm" value="{{ $groupInstance[$field->name] ?? '' }}">
                                                                        @endif
                                                                        @if($field->type === 'textarea')
                                                                            <textarea name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control form-control-sm" rows="2">{{ $groupInstance[$field->name] ?? '' }}</textarea>
                                                                        @endif
                                                                        @if($field->type !== 'title' && $field->type !== 'select' && $field->type !== 'checkbox' && $field->type !== 'image[]' && $field->type !== 'image' && $field->name !== 'image' && $field->type !== 'date' && $field->type !== 'time' && $field->type !== 'textarea')
                                                                            <input type="{{ $field->type }}" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control form-control-sm" value="{{ $groupInstance[$field->name] ?? '' }}">
                                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                                    {{-- زر الإضافة الديناميكي بالجافاسكريبت فقط --}}
                                    @if($repeatable)
                                        <button type="button" class="btn btn-circle add-group-btn mb-3" data-group="{{ $group }}" title="إضافة مجموعة جديدة">
                                            <i class="fas fa-plus"></i> إضافة مجموعة
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                                    @endforeach
                                        <div class="card">
                            <div class="card-header text-center bg-primary text-white" style="font-size:1.2rem; font-weight:bold;">
                                <i class="fas fa-concierge-bell"></i> {{ __('طلب خدمة من هذا القسم') }}
                            </div>
                            <div class="card-body">

                                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <input type="hidden" name="type" value="{{ $department->name_en }}">
                                    @if(isset($selectedSubDepartmentId) && $selectedSubDepartmentId)
                                        <input type="hidden" name="sub_department_id" value="{{ $selectedSubDepartmentId }}">
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <!-- حقل اختيار المدينة -->
                                    <div class="field-card" style="border-left: 4px solid #28a745;">
                                        <div class="field-label">
                                            <i class="fas fa-map-marker-alt" style="color: #28a745;"></i>
                                            <label for="from_city" style="margin-bottom:0; font-weight:bold; color: #28a745;">
                                                {{ $lang == 'ar' ? 'اختر المدينة *' : 'Select City *' }}
                                            </label>
                                        </div>
                                        <div style="flex:2;">
                                            <select name="from_city" id="from_city" class="form-control js-select2-custom" required>
                                                <option value="">{{ $lang == 'ar' ? 'اختر المدينة' : 'Select City' }}</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}">
                                                        {{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- حقل اختيار الحي (اختياري) -->
                                    {{--  <div class="field-card">
                                        <div class="field-label">
                                            <i class="fas fa-home"></i>
                                            <label for="neighborhood" style="margin-bottom:0; font-weight:bold;">
                                                {{ $lang == 'ar' ? 'الحي (اختياري)' : 'Neighborhood (Optional)' }}
                                            </label>
                                        </div>
                                        <div style="flex:2;">
                                            <input type="text" name="neighborhood" id="neighborhood" class="form-control" value="{{ old('neighborhood') }}" placeholder="{{ $lang == 'ar' ? 'أدخل اسم الحي' : 'Enter neighborhood name' }}">
                                        </div>
                                    </div>

                                    <!-- حقل اختيار المدينة الهدف (اختياري) -->
                                    <div class="field-card" style="border-left: 4px solid #ffc107;">
                                        <div class="field-label">
                                            <i class="fas fa-map-marker-alt" style="color: #ffc107;"></i>
                                            <label for="to_city" style="margin-bottom:0; font-weight:bold; color: #ffc107;">
                                                {{ $lang == 'ar' ? 'إلى المدينة (اختياري)' : 'To City (Optional)' }}
                                            </label>
                                        </div>
                                        <div style="flex:2;">
                                            <select name="to_city" id="to_city" class="form-control js-select2-custom">
                                                <option value="">{{ $lang == 'ar' ? 'اختر المدينة الهدف' : 'Select Destination City' }}</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" {{ old('to_city') == $city->id ? 'selected' : '' }}>
                                                        {{ $lang == 'ar' ? $city->name_ar : $city->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <!-- حقل اختيار الحي الهدف (اختياري) -->
                                    <div class="field-card">
                                        <div class="field-label">
                                            <i class="fas fa-home"></i>
                                            <label for="to_neighborhood" style="margin-bottom:0; font-weight:bold;">
                                                {{ $lang == 'ar' ? 'إلى الحي (اختياري)' : 'To Neighborhood (Optional)' }}
                                            </label>
                                        </div>
                                        <div style="flex:2;">
                                            <input type="text" name="to_neighborhood" id="to_neighborhood" class="form-control" value="{{ old('to_neighborhood') }}" placeholder="{{ $lang == 'ar' ? 'أدخل اسم الحي الهدف' : 'Enter destination neighborhood' }}">
                                        </div>
                                    </div>  --}}

                                    <!-- عنوان الحقول المخصصة -->
                                    {{--  <div class="mb-4 mt-5">
                                        <h5 class="text-center mb-3" style="color:#1976d2; font-weight:bold; border-bottom: 2px solid #1976d2; padding-bottom: 10px;">
                                            <i class="fas fa-cogs"></i> {{ $lang == 'ar' ? 'الحقول المخصصة للقسم' : 'Department Custom Fields' }}
                                        </h5>
                                    </div>  --}}

                                    @foreach($department->fields->where('input_group', null) as $field)
                                        @if($field->type === 'title')
                                            <div class="col-12 mb-3">
                                                <h4 class="text-dark mb-0" style="font-weight: bold; font-size: 1.5rem; border-bottom: 2px solid #333; padding-bottom: 8px;">
                                                     {{ $field->value ?? (app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en) }}
                                                </h4>
                                            </div>
                                        @else
                                            <div class="field-card">
                                                <div class="field-label">
                                                    <i class="fas fa-{{
                                                        $field->type === 'text' ? 'font' :
                                                        ($field->type === 'number' ? 'hashtag' :
                                                        ($field->type === 'select' ? 'list' :
                                                        ($field->type === 'checkbox' ? 'check-square' :
                                                        ($field->type === 'image' ? 'image' :
                                                        ($field->type === 'date' ? 'calendar-alt' :
                                                        ($field->type === 'time' ? 'clock' :
                                                        ($field->type === 'textarea' ? 'align-left' : 'edit'))))))) }}"></i>
                                                    <label for="custom_fields_{{ $field->name }}" style="margin-bottom:0; font-weight:bold;">
                                                        {{ $lang == 'ar' ? $field->name_ar : $field->name_en }}
                                                    </label>
                                                </div>
                                                <div style="flex:2;">
                                                @if($field->type === 'select' && is_array($field->options))
                                                    <select name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control">
                                                        <option value="" disabled selected>{{ $lang == 'ar' ? 'اختر' : 'Select' }}</option>
                                                        @foreach($field->options as $option)
                                                            <option value="{{ $option }}" {{ old('custom_fields.' . $field->name) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                @if($field->type === 'checkbox')
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="checkbox" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" value="1" class="form-check-input" {{ old('custom_fields.' . $field->name) == '1' ? 'checked' : '' }}>
                                                        <label for="custom_fields_{{ $field->name }}" style="font-weight:500; color:#333; font-size:0.98rem; margin-bottom:0;">
                                                            {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                        </label>
                                                    </div>
                                                @endif
                                                @if($field->type === 'images[]' || $field->type === 'images' || $field->name === 'images')
                                                    <div class="dynamic-images-uploader mb-2" data-field="custom_fields_{{ $field->name }}">
                                                        <div class="text-center mb-2" style="color: #6c757d; font-size: 0.9em;">
                                                            <i class="fas fa-cloud-upload-alt"></i> اسحب الصور هنا أو اضغط لاختيار الملفات
                                                        </div>
                                                        <input type="file" name="custom_fields[{{ $field->name }}][]" id="custom_fields_{{ $field->name }}" accept="image/*" class="form-control image-input" multiple style="margin-bottom:8px;">
                                                        <div class="preview-images d-flex flex-wrap gap-2"></div>
                                                    </div>
                                                @endif
                                                @if($field->type === 'date')
                                                    <input type="date" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ old('custom_fields.' . $field->name) }}">
                                                @endif
                                                @if($field->type === 'time')
                                                    <input type="time" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ old('custom_fields.' . $field->name) }}">
                                                @endif
                                                @if($field->type === 'textarea')
                                                    <textarea name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control">{{ old('custom_fields.' . $field->name) }}</textarea>
                                                @endif
                                                @if($field->type !== 'title' && $field->type !== 'select' && $field->type !== 'checkbox' && $field->type !== 'images[]' && $field->type !== 'images' && $field->name !== 'images' && $field->type !== 'date' && $field->type !== 'time' && $field->type !== 'textarea')
                                                    <input type="{{ $field->type }}" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ old('custom_fields.' . $field->name) }}">
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    <!-- Note Section -->
                                    <div class="field-card">
                                        <div class="field-label">
                                            <i class="fas fa-sticky-note"></i> {{ $lang == 'ar' ? 'ملاحظات إضافية' : 'Additional Notes' }}
                                        </div>
                                        <div style="flex:2;">
                                            <textarea name="notes" id="note" class="form-control" rows="4" placeholder="{{ $lang == 'ar' ? 'أضف أي ملاحظات إضافية هنا...' : 'Add any additional notes here...' }}">{{ old('note') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Voice Note Section -->
                                    <div class="field-card">
                                        <div class="field-label">
                                            <i class="fas fa-microphone"></i> {{ $lang == 'ar' ? 'ملاحظة صوتية' : 'Voice Note' }}
                                        </div>
                                        <div style="flex:2;">
                                            <div class="voice-note-container">
                                                <div class="recordingStatus" style="margin-bottom: 8px; color: #d9534f; display: none;"></div>
                                                <button type="button" class="startRecord btn btn-primary">{{ $lang == 'ar' ? 'بدء التسجيل' : 'Start Recording' }}</button>
                                                <button type="button" class="stopRecord btn btn-danger" disabled>{{ $lang == 'ar' ? 'ايقاف التسجيل' : 'Stop Recording' }}</button>
                                                <button type="button" class="resetRecord btn btn-secondary" style="display:none;">{{ $lang == 'ar' ? 'إعادة التسجيل' : 'Reset Recording' }}</button>
                                                <span class="recordingTimer" style="margin-left: 10px; font-weight: bold; display:none;">00:00</span>
                                                <audio class="audioPlayback" controls style="display: none; margin-top: 10px;"></audio>
                                                <a class="downloadLink btn btn-success" style="display: none; margin-top: 10px;">{{ $lang == 'ar' ? 'تنزيل التسجيل' : 'Download Recording' }}</a>
                                                <input type="hidden" name="voice_note_data" class="voiceNoteData">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block mt-3">
                                        <i class="fas fa-paper-plane"></i> {{ __('إرسال الطلب') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if (auth()->check() && auth()->user()->role_id == 3)
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="row">
                                @forelse ($services as $service)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title" style="color:#1976d2; font-weight:bold;">
                                                    {{ $service->title ?? ($lang == 'ar' ? $service->name_ar : $service->name_en) }}
                                                </h5>
                                                <div class="mb-2 text-muted" style="font-size:0.95em;">
                                                    <i class="fas fa-user"></i> {{ $service->user->full_name ?? '-' }}
                                                </div>
                                                <div class="mb-2 text-muted" style="font-size:0.95em;">
                                                    <i class="fas fa-clock"></i> {{ $service->created_at ? $service->created_at->diffForHumans() : '-' }}
                                                </div>
                                                <a href="{{ route('show_myservice', $service->id) }}" class="btn btn-outline-primary mt-auto">
                                                    {{ $lang == 'ar' ? 'عرض التفاصيل' : 'View Details' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">
                                        {{ $lang == 'ar' ? 'لا توجد خدمات متاحة' : 'No services available.' }}
                                    </div>
                                @endforelse
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {!! $services->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        @endif
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.voice-note-container');
    if (!container) return;

    const startBtn = container.querySelector('.startRecord');
    const stopBtn = container.querySelector('.stopRecord');
    const resetBtn = container.querySelector('.resetRecord');
    const audioPlayback = container.querySelector('.audioPlayback');
    const downloadLink = container.querySelector('.downloadLink');
    const recordingStatus = container.querySelector('.recordingStatus');
    const recordingTimer = container.querySelector('.recordingTimer');
    const voiceNoteData = container.querySelector('.voiceNoteData');

    let mediaRecorder, audioChunks = [], audioBlob, stream, timerInterval, seconds = 0;

    function updateTimerDisplay() {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        recordingTimer.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }
    function startTimer() {
        seconds = 0;
        updateTimerDisplay();
        recordingTimer.style.display = 'inline';
        timerInterval = setInterval(() => {
            seconds++;
            updateTimerDisplay();
        }, 1000);
    }
    function stopTimer() {
        clearInterval(timerInterval);
        recordingTimer.style.display = 'none';
    }

    startBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        try {
            stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = function(e) {
                if (e.data.size > 0) {
                    audioChunks.push(e.data);
                }
            };
            mediaRecorder.onstart = function() {
                recordingStatus.style.display = 'block';
                recordingStatus.textContent = "جاري التسجيل...";
                startBtn.disabled = true;
                stopBtn.disabled = false;
                resetBtn.style.display = 'none';
                audioPlayback.style.display = 'none';
                downloadLink.style.display = 'none';
                startTimer();
            };
            mediaRecorder.onstop = function() {
                stopTimer();
                audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayback.src = audioUrl;
                audioPlayback.style.display = 'block';
                downloadLink.href = audioUrl;
                downloadLink.download = 'voice-note.wav';
                downloadLink.style.display = 'inline-block';
                recordingStatus.style.display = 'none';
                startBtn.disabled = false;
                stopBtn.disabled = true;
                resetBtn.style.display = 'inline-block';
                // Stop all tracks
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                // حفظ البيانات في input مخفي
                const reader = new FileReader();
                reader.readAsDataURL(audioBlob);
                reader.onloadend = function() {
                    voiceNoteData.value = reader.result;
                };
            };
            mediaRecorder.start();
        } catch (error) {
            recordingStatus.style.display = 'block';
            recordingStatus.textContent = 'خطأ في الوصول إلى الميكروفون: ' + error.message;
            startBtn.disabled = false;
            stopBtn.disabled = true;
            resetBtn.style.display = 'none';
        }
    });

    stopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
    });

    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        audioBlob = null;
        audioPlayback.src = '';
        audioPlayback.style.display = 'none';
        downloadLink.style.display = 'none';
        resetBtn.style.display = 'none';
        startBtn.disabled = false;
        stopBtn.disabled = true;
        recordingStatus.style.display = 'none';
        seconds = 0;
        updateTimerDisplay();
        voiceNoteData.value = '';
    });

    // تكرار وحذف المجموعات الديناميكية
    document.addEventListener('click', function(e) {
        // إضافة مجموعة جديدة
        if(e.target.classList.contains('add-group-btn')) {
            var btn = e.target;
            var group = btn.getAttribute('data-group');
            var groupList = document.querySelector('.group-fields-list[data-group="' + group + '"]');
            var instances = groupList.querySelectorAll('.group-fields-instance');
            if(instances.length >= 10) return;
            var newIndex = instances.length;
            var template = instances[0].cloneNode(true);
            template.setAttribute('data-index', newIndex);
            template.querySelectorAll('input, select, textarea').forEach(function(input) {
                if(input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
                var name = input.getAttribute('name');
                if(name) {
                    name = name.replace(/custom_fields\[[^\]]+\]\[\d+\]/, 'custom_fields['+group+']['+newIndex+']');
                    input.setAttribute('name', name);
                }
            });
            groupList.appendChild(template);

            // إزالة كل أزرار الإضافة
            groupList.querySelectorAll('.add-group-btn').forEach(function(b){ b.remove(); });
            // إضافة زر الإضافة فقط في آخر مجموعة إذا العدد أقل من 10
            var allInstances = groupList.querySelectorAll('.group-fields-instance');
            if(allInstances.length < 10) {
                var newAddBtn = btn.cloneNode(true);
                // جرب أولاً col-12، إذا لم توجد أضف الزر في نهاية المجموعة
                var col12 = allInstances[allInstances.length-1].querySelector('.col-12');
                if(col12) {
                    col12.appendChild(newAddBtn);
                } else {
                    allInstances[allInstances.length-1].appendChild(newAddBtn);
                }
            }
        }
        // حذف مجموعة
        if(e.target.closest('.remove-group-btn')) {
            var instance = e.target.closest('.group-fields-instance');
            var groupList = instance.parentElement;
            var group = groupList.getAttribute('data-group');
            instance.remove();
            // إعادة ترتيب الفهارس
            var allInstances = groupList.querySelectorAll('.group-fields-instance');
            allInstances.forEach(function(inst, idx) {
                inst.setAttribute('data-index', idx);
                inst.querySelectorAll('input, select, textarea').forEach(function(input) {
                    var name = input.getAttribute('name');
                    if(name) {
                        name = name.replace(/custom_fields\[[^\]]+\]\[\d+\]/, 'custom_fields['+group+']['+idx+']');
                        input.setAttribute('name', name);
                    }
                });
            });
            // إزالة كل أزرار الإضافة
            groupList.querySelectorAll('.add-group-btn').forEach(function(b){ b.remove(); });
            // إضافة زر الإضافة فقط في آخر مجموعة إذا العدد أقل من 10
            if(allInstances.length && allInstances.length < 10) {
                var newAddBtn = document.createElement('button');
                newAddBtn.type = 'button';
                newAddBtn.className = 'btn btn-success btn-sm add-group-btn';
                newAddBtn.setAttribute('data-group', group);
                newAddBtn.innerHTML = '<i class="fas fa-plus"></i> إضافة مجموعة';
                var col12 = allInstances[allInstances.length-1].querySelector('.col-12');
                if(col12) {
                    col12.appendChild(newAddBtn);
                } else {
                    allInstances[allInstances.length-1].appendChild(newAddBtn);
                }
            }
        }
    });

    // واجهة رفع صور ديناميكية للحقول المفردة والمجمعة
    document.querySelectorAll('.dynamic-images-uploader').forEach(function(wrapper) {
        const input = wrapper.querySelector('.image-input');
        const preview = wrapper.querySelector('.preview-images');
        let filesArr = [];

        // دعم drag and drop
        wrapper.addEventListener('dragover', function(e) {
            e.preventDefault();
            wrapper.style.borderColor = '#1976d2';
            wrapper.style.backgroundColor = '#e3f2fd';
        });

        wrapper.addEventListener('dragleave', function(e) {
            e.preventDefault();
            wrapper.style.borderColor = '#dee2e6';
            wrapper.style.backgroundColor = '#f8f9fa';
        });

        wrapper.addEventListener('drop', function(e) {
            e.preventDefault();
            wrapper.style.borderColor = '#dee2e6';
            wrapper.style.backgroundColor = '#f8f9fa';

            const files = Array.from(e.dataTransfer.files);
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('حجم الصورة يجب أن لا يتجاوز 5 ميجابايت');
                        return;
                    }
                    // التحقق من عدد الصور (حد أقصى 10)
                    if (filesArr.length >= 10) {
                        alert('يمكن رفع 10 صور كحد أقصى');
                        return;
                    }
                    filesArr.push(file);
                }
            });
            renderPreviews();
        });

        input.addEventListener('change', function(e) {
            // أضف الصور الجديدة للمصفوفة
            for (let file of Array.from(input.files)) {
                // التحقق من نوع الملف
                if (file.type.startsWith('image/')) {
                    // التحقق من حجم الملف (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('حجم الصورة يجب أن لا يتجاوز 5 ميجابايت');
                        continue;
                    }
                    // التحقق من عدد الصور (حد أقصى 10)
                    if (filesArr.length >= 10) {
                        alert('يمكن رفع 10 صور كحد أقصى');
                        input.disabled = true;
                        break;
                    }
                    filesArr.push(file);
                } else {
                    alert('يجب اختيار ملفات صور فقط');
                    continue;
                }
            }
            renderPreviews();
            // إعادة تعيين input حتى يمكن اختيار نفس الصورة مرة أخرى
            input.value = '';
        });

        // إضافة معالج للأخطاء
        input.addEventListener('error', function(e) {
            console.error('Error loading file:', e);
            alert('حدث خطأ في تحميل الملف');
        });

        function renderPreviews() {
            preview.innerHTML = '';
            if (filesArr.length === 0) {
                preview.innerHTML = '<div class="text-center text-muted" style="padding: 20px;"><i class="fas fa-images"></i><br>لم يتم اختيار صور بعد</div>';
                return;
            }

            // إضافة عداد للصور
            const counter = document.createElement('div');
            counter.className = 'text-center text-muted mb-2';
            counter.style.fontSize = '0.9em';
            counter.innerHTML = `تم اختيار ${filesArr.length} صورة من أصل 10`;
            preview.appendChild(counter);

            filesArr.forEach(function(file, idx) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'position-relative';
                    div.style.display = 'inline-block';
                    div.innerHTML = `<img src="${e.target.result}" style="width:80px; height:80px; object-fit:cover; border-radius:6px; border:1px solid #ddd; margin:2px;" />` +
                        `<button type="button" class="btn btn-sm btn-danger remove-img-btn position-absolute top-0 end-0" style="transform:translate(30%,-30%); border-radius:50%; padding:2px 6px; font-size:0.9em;" data-idx="${idx}"><i class="fas fa-times"></i></button>`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        preview.addEventListener('click', function(e) {
            if(e.target.closest('.remove-img-btn')) {
                const idx = parseInt(e.target.closest('.remove-img-btn').getAttribute('data-idx'));
                filesArr.splice(idx, 1);
                renderPreviews();

                // إعادة تفعيل input إذا كان عدد الصور أقل من 10
                if (filesArr.length < 10) {
                    input.disabled = false;
                }
            }
        });

        // عند إرسال النموذج: أنشئ كائن DataTransfer جديد وأضف الملفات المختارة إليه
        wrapper.closest('form').addEventListener('submit', function(e) {
            // إذا لم يتم اختيار صور، لا تفعل شيء
            if(filesArr.length === 0) {
                // إزالة الملفات من input إذا لم يتم اختيار أي شيء
                input.files = null;
                return;
            }

            // التحقق من عدد الصور (حد أقصى 10)
            if(filesArr.length > 10) {
                alert('يمكن رفع 10 صور كحد أقصى');
                e.preventDefault();
                return;
            }

            const dt = new DataTransfer();
            filesArr.forEach(f => dt.items.add(f));
            input.files = dt.files;
        });
    });
});
$(document).ready(function() {
    $('.js-select2-custom').select2({
        placeholder: "{{ $lang == 'ar' ? 'اختر المدينة' : 'Select City' }}",
        allowClear: true,
        language: {
            noResults: function() {
                return "{{ $lang == 'ar' ? 'لا توجد نتائج' : 'No Results Found' }}";
            }
        }
    });
});
</script>
<style>
.btn-circle.add-group-btn {
    min-width: 38px !important;
    height: 38px !important;
    border-radius: 19px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 1.1rem !important;
    box-shadow: 0 2px 8px #1976d233 !important;
    background: linear-gradient(135deg, #1976d2 60%, #43e97b 100%) !important;
    border: none !important;
    color: #fff !important;
    transition: background 0.2s, box-shadow 0.2s;
    padding: 0 16px !important;
    font-weight: bold;
    gap: 6px;
}
.btn-circle.add-group-btn:hover {
    background: linear-gradient(135deg, #43e97b 60%, #1976d2 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 16px #43e97b33 !important;
}
.preview-images img {
    box-shadow:0 2px 8px #1976d233;
    transition: transform 0.2s;
}
.preview-images img:hover {
    transform: scale(1.05);
}
.remove-img-btn {
    z-index: 2;
    background: #dc3545;
    border: none;
    color: white;
    font-size: 0.8em;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.remove-img-btn:hover {
    background: #c82333;
}
.dynamic-images-uploader {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
    transition: border-color 0.2s, background-color 0.2s;
    cursor: pointer;
    position: relative;
}
.dynamic-images-uploader:hover {
    border-color: #1976d2;
    background-color: #e3f2fd;
}
.dynamic-images-uploader .image-input {
    border: none;
    background: transparent;
    cursor: pointer;
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}
.dynamic-images-uploader .text-center {
    pointer-events: none;
    z-index: 0;
}
.preview-images {
    min-height: 40px;
    margin-top: 10px;
}
.preview-images:empty::after {
    content: 'لم يتم اختيار صور بعد';
    color: #6c757d;
    font-style: italic;
    display: block;
    text-align: center;
    padding: 10px;
}
.preview-images .text-center {
    color: #6c757d;
    font-size: 0.9em;
}
</style>
@endsection
