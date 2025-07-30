@extends('layouts.home')
@section('title', 'تعديل الخدمة')
@section('content')
<div class="container mt-4">
    <h2>تعديل بيانات الخدمة</h2>
    <div class="card p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $service->user_id }}">
            <input type="hidden" name="department_id" value="{{ $service->department_id }}">
            <input type="hidden" name="type" value="{{ $service->type }}">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="from_city" class="form-label"><i class="fas fa-map-marker-alt me-1"></i> {{ app()->getLocale() == 'ar' ? 'من المدينة' : 'From City' }}</label>
                    <select name="from_city" id="from_city" class="form-control js-select2-custom" required>
                        <option value="">{{ app()->getLocale() == 'ar' ? 'اختر المدينة' : 'Select City' }}</option>
                        @foreach($cities as $city)
                            <option value="{{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}" {{ old('from_city', $service->from_city) == (app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en) ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- الحقول المجمعة (repeatable groups) --}}
            @if($groupedFields->count())
                <div class="mb-4">
                    <h4 class="text-center mb-3" style="color:#1976d2; font-weight:bold;"><i class="fas fa-object-group"></i> {{ app()->getLocale() == 'ar' ? 'الخدمات المطلوبة' : 'Ordered Services' }}</h4>
                </div>
                @foreach($groupedFields as $group => $fields)
                    @php
                        $repeatable = $fields->first()->is_repeatable ?? false;
                        $groupValues = old('custom_fields.' . $group, $service->custom_fields[$group] ?? []);
                    @endphp
                    @if($group)
                        <div class="card mb-4 shadow-sm border-0 group-block" data-group="{{ $group }}">
                            <div class="card-header bg-info text-white text-center" style="font-size:1.1rem; font-weight:bold; border-radius: 8px 8px 0 0;">
                                <i class="fas fa-layer-group"></i>
                                {{ $group }}
                            </div>
                            <div class="card-body group-fields-list" data-group="{{ $group }}">
                                @if($repeatable && is_array($groupValues) && count($groupValues))
                                    @foreach($groupValues as $idx => $groupInstance)
                                        <div class="row g-3 align-items-end group-fields-instance" data-index="{{ $idx }}">
                                            @foreach($fields as $field)
                                                @php $value = $groupInstance[$field->name] ?? null; @endphp
                                                <div class="col-md-3 col-sm-6 mb-2 d-flex align-items-center gap-1" style="background: #f9f9fb; border-radius: 8px; padding: 10px 12px; box-shadow: 0 1px 4px #e3e8ef;">
                                                    <label for="custom_fields_{{ $group }}_{{ $idx }}_{{ $field->name }}" style="font-weight:bold; margin-bottom:0; min-width: 60px; color:#1976d2; margin-left:4px; margin-right:4px;">
                                                        {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                    </label>
                                                    <div style="flex:1; margin:0;">
                                                        @if($field->type === 'select' && is_array($field->options))
                                                            <select name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control">
                                                                @foreach($field->options as $option)
                                                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($field->type === 'checkbox')
                                                            <div class="form-check m-0">
                                                                <input type="checkbox" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" value="1" class="form-check-input" {{ $value ? 'checked' : '' }}>
                                                            </div>
                                                        @elseif($field->type === 'image')
                                                            @if($value)
                                                                <div class="mb-2">
                                                                    <img src="{{ asset('storage/' . $value) }}" alt="صورة" style="max-width:80px; margin:3px; border-radius:6px;">
                                                                </div>
                                                            @endif
                                                            <input type="file" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" accept="image/*" class="form-control">
                                                        @elseif($field->type === 'date')
                                                            <input type="date" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control" value="{{ $value }}">
                                                        @elseif($field->type === 'time')
                                                            <input type="time" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control" value="{{ $value }}">
                                                        @elseif($field->type === 'textarea')
                                                            <textarea name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control" rows="2">{{ $value }}</textarea>
                                                        @else
                                                            <input type="{{ $field->type }}" name="custom_fields[{{ $group }}][{{ $idx }}][{{ $field->name }}]" class="form-control" value="{{ $value }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-group-btn" style="{{ count($groupValues) == 1 ? 'display:none;' : '' }}"><i class="fas fa-trash"></i> حذف المجموعة</button>
                                                @if($repeatable && $loop->last)
                                                    <button type="button" class="btn btn-success btn-sm add-group-btn" data-group="{{ $group }}">
                                                        <i class="fas fa-plus"></i> إضافة مجموعة
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row g-3 align-items-end group-fields-instance" data-index="0">
                                        @foreach($fields as $field)
                                            <div class="col-md-3 col-sm-6 mb-2 d-flex align-items-center gap-1" style="background: #f9f9fb; border-radius: 8px; padding: 10px 12px; box-shadow: 0 1px 4px #e3e8ef;">
                                                <label for="custom_fields_{{ $group }}_0_{{ $field->name }}" style="font-weight:bold; margin-bottom:0; min-width: 60px; color:#1976d2; margin-left:4px; margin-right:4px;">
                                                    {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                </label>
                                                <div style="flex:1; margin:0;">
                                                    @if($field->type === 'select' && is_array($field->options))
                                                        <select name="custom_fields[{{ $group }}][0][{{ $field->name }}]" class="form-control">
                                                            @foreach($field->options as $option)
                                                                <option value="{{ $option }}">{{ $option }}</option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($field->type === 'checkbox')
                                                        <div class="form-check m-0">
                                                            <input type="checkbox" name="custom_fields[{{ $group }}][0][{{ $field->name }}]" value="1" class="form-check-input">
                                                        </div>
                                                    @elseif($field->type === 'image')
                                                        <input type="file" name="custom_fields[{{ $group }}][0][{{ $field->name }}]" accept="image/*" class="form-control">
                                                    @elseif($field->type === 'date')
                                                        <input type="date" name="custom_fields[{{ $group }}][0][{{ $field->name }}]" class="form-control">
                                                    @elseif($field->type === 'time')
                                                        <input type="time" name="custom_fields[{{ $group }}][0][{{ $field->name }}]" class="form-control">
                                                    @elseif($field->type === 'textarea')
                                                        <textarea name="custom_fields[{{ $group }}][0][{{ $field->name }}]" class="form-control" rows="2"></textarea>
                                                    @else
                                                        <input type="{{ $field->type }}" name="custom_fields[{{ $group }}][0][{{ $field->name }}]" class="form-control">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-group-btn" style="display:none;"><i class="fas fa-trash"></i> حذف المجموعة</button>
                                            @if($repeatable)
                                                <button type="button" class="btn btn-success btn-sm add-group-btn" data-group="{{ $group }}">
                                                    <i class="fas fa-plus"></i> إضافة مجموعة
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            {{-- الحقول غير المجمعة (individual fields) --}}
            @if($service->department && $service->department->fields)
                @foreach($service->department->fields->where('input_group', null) as $field)
                    <div class="mb-3">
                        <label for="custom_fields[{{ $field->name }}]">
                            {{ $field->name_ar }} ({{ $field->name_en }})
                        </label>
                        @php
                            $value = old('custom_fields.' . $field->name, $service->custom_fields[$field->name] ?? null);
                            // التحقق من أن القيمة ليست مصفوفة
                            if (is_array($value)) {
                                $value = is_array($value) ? implode(', ', $value) : '';
                            }
                        @endphp
                        @if($field->type === 'image' || $field->type === 'images[]' || $field->type === 'imagess')
                            <div class="dynamic-images-uploader mb-2" data-field="custom_fields_{{ $field->name }}">
                                <div class="text-center mb-2" style="color: #6c757d; font-size: 0.9em;">
                                    <i class="fas fa-cloud-upload-alt"></i> اسحب الصور هنا أو اضغط لاختيار الملفات
                                </div>
                                <input type="file" name="custom_fields[{{ $field->name }}][]" id="custom_fields_{{ $field->name }}" accept="image/*" class="form-control image-input" multiple style="margin-bottom:8px;">
                                <div class="preview-images d-flex flex-wrap gap-2">
                                    @if(is_array($service->custom_fields[$field->name] ?? null))
                                        @foreach($service->custom_fields[$field->name] as $imagePath)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $imagePath) }}" style="width:80px; height:80px; object-fit:cover; border-radius:6px; border:1px solid #ddd; margin:2px;">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @elseif($field->type === 'checkbox')
                            <input type="hidden" name="custom_fields[{{ $field->name }}]" value="0">
                            <input type="checkbox" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" value="1" class="form-check-input" {{ $value ? 'checked' : '' }}>
                        @elseif($field->type === 'textarea')
                            <textarea name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control">{{ $value }}</textarea>
                        @elseif($field->type === 'select' && is_array($field->options))
                            <select name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control">
                                <option value="">{{ app()->getLocale() == 'ar' ? 'اختر' : 'Select' }}</option>
                                @foreach($field->options as $option)
                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $field->type }}" name="custom_fields[{{ $field->name }}]" id="custom_fields_{{ $field->name }}" class="form-control" value="{{ $value }}">
                        @endif
                    </div>
                @endforeach
            @endif
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4">حفظ التعديلات</button>
                <a href="{{ route('services.show', $service->id) }}" class="btn btn-secondary px-4">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // معالجة الصور المتعددة
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
            for (let file of Array.from(input.files)) {
                if (file.type.startsWith('image/')) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('حجم الصورة يجب أن لا يتجاوز 5 ميجابايت');
                        continue;
                    }
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
            input.value = '';
        });

        function renderPreviews() {
            preview.innerHTML = '';
            if (filesArr.length === 0) {
                preview.innerHTML = '<div class="text-center text-muted" style="padding: 20px;"><i class="fas fa-images"></i><br>لم يتم اختيار صور بعد</div>';
                return;
            }

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

                if (filesArr.length < 10) {
                    input.disabled = false;
                }
            }
        });

        wrapper.closest('form').addEventListener('submit', function(e) {
            if(filesArr.length === 0) {
                input.files = null;
                return;
            }

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

    // استخدم التفويض بدلاً من event لكل زر
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
            // إزالة زر الإضافة من آخر مجموعة حالياً
            var lastAddBtn = groupList.querySelector('.add-group-btn');
            if(lastAddBtn) lastAddBtn.remove();
            // إضافة المجموعة الجديدة بعد آخر مجموعة
            groupList.appendChild(template);
            // إضافة زر الإضافة الجديد أسفل المجموعة الجديدة فقط
            var newAddBtn = btn.cloneNode(true);
            template.querySelector('.col-12').appendChild(newAddBtn);
            // إخفاء زر الإضافة إذا وصل الحد الأقصى
            if(groupList.querySelectorAll('.group-fields-instance').length >= 10) {
                newAddBtn.style.display = 'none';
            } else {
                newAddBtn.style.display = '';
            }
        }
        // حذف مجموعة
        if(e.target.closest('.remove-group-btn')) {
            var instance = e.target.closest('.group-fields-instance');
            var groupList = instance.parentElement;
            var group = groupList.getAttribute('data-group');
            var addBtn = groupList.querySelector('.add-group-btn');
            instance.remove();
            // إذا لم يوجد زر إضافة، أضفه لآخر مجموعة
            if(!groupList.querySelector('.add-group-btn')) {
                var lastInstance = groupList.querySelector('.group-fields-instance:last-child');
                if(lastInstance) {
                    var newAddBtn = addBtn ? addBtn.cloneNode(true) : document.createElement('button');
                    if(!addBtn) {
                        newAddBtn.type = 'button';
                        newAddBtn.className = 'btn btn-success btn-sm add-group-btn';
                        newAddBtn.setAttribute('data-group', group);
                        newAddBtn.innerHTML = '<i class="fas fa-plus"></i> إضافة مجموعة';
                    }
                    lastInstance.querySelector('.col-12').appendChild(newAddBtn);
                }
            }
            // إظهار زر الإضافة إذا كان العدد أقل من 10
            var instances = groupList.querySelectorAll('.group-fields-instance');
            var addBtnNow = groupList.querySelector('.add-group-btn');
            if(instances.length < 10 && addBtnNow) {
                addBtnNow.style.display = '';
            }
            // إعادة ترتيب الفهارس
            groupList.querySelectorAll('.group-fields-instance').forEach(function(inst, idx) {
                inst.setAttribute('data-index', idx);
                inst.querySelectorAll('input, select, textarea').forEach(function(input) {
                    var name = input.getAttribute('name');
                    if(name) {
                        name = name.replace(/custom_fields\[[^\]]+\]\[\d+\]/, 'custom_fields['+group+']['+idx+']');
                        input.setAttribute('name', name);
                    }
                });
            });
        }
    });
</script>
@endsection

<style>
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
</style>
