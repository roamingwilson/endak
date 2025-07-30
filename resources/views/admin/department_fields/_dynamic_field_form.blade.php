@php
    $value = old('value', $field->value ?? '');
    $options = old('options', isset($field) && is_array($field->options) ? $field->options : []);
@endphp
<div class="mb-3">
    <label for="name" class="form-label">Field Key (name) <span style="color:red">*</span></label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $field->name ?? '') }}" required>
    <small class="text-muted">مثال: from_city أو quantity (بدون مسافات أو رموز خاصة)</small>
</div>
<div class="mb-3">
    <label for="type" class="form-label">نوع الحقل</label>
    <select class="form-control" id="type" name="type" required>
        <option value="text" @selected(old('type', $field->type ?? '') == 'text')>نص</option>
        <option value="number" @selected(old('type', $field->type ?? '') == 'number')>رقم</option>
        <option value="select" @selected(old('type', $field->type ?? '') == 'select')>قائمة منسدلة</option>
        <option value="checkbox" @selected(old('type', $field->type ?? '') == 'checkbox')>تشيك بوكس</option>
        <option value="textarea" @selected(old('type', $field->type ?? '') == 'textarea')>مربع نص</option>
        <option value="image" @selected(old('type', $field->type ?? '') == 'image')>صورة</option>
        <option value="date" @selected(old('type', $field->type ?? '') == 'date')>تاريخ</option>
        <option value="time" @selected(old('type', $field->type ?? '') == 'time')>وقت</option>
    </select>
</div>
<div class="mb-3">
    <label for="name_ar" class="form-label">اسم الحقل بالعربي</label>
    <input type="text" class="form-control" id="name_ar" name="name_ar" value="{{ old('name_ar', $field->name_ar ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="name_en" class="form-label">Field Name (English)</label>
    <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en', $field->name_en ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="input_group" class="form-label">مجموعة الإدخال (لدمج الحقول في صف واحد)</label>
    <input type="text" class="form-control" id="input_group" name="input_group" value="{{ old('input_group', $field->input_group ?? '') }}">
    <small class="text-muted">ضع نفس القيمة للحقول التي تريد دمجها في صف واحد (مثلاً: group1)</small>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_repeatable" name="is_repeatable" value="1" @checked((old('is_repeatable') !== null ? old('is_repeatable') : ($field->is_repeatable ?? false)))>
    <label class="form-check-label" for="is_repeatable">قابل للتكرار (يمكن للمستخدم إضافة أكثر من مجموعة)</label>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" @checked(old('is_required', $field->is_required ?? false))>
    <label class="form-check-label" for="is_required">مطلوب؟</label>
</div>
<div class="mb-3" id="dynamic-field-input"></div>
<div class="mb-3">
    <label for="description" class="form-label">ملاحظات/وصف الحقل (اختياري)</label>
    <input type="text" class="form-control" name="description" id="description" value="{{ old('description', $field->description ?? '') }}">
</div>
<button type="submit" class="btn btn-success btn-block mt-3">
    <i class="fas fa-save"></i> حفظ الحقل
</button>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const dynamicFieldInput = document.getElementById('dynamic-field-input');
        let options = @json($options);
        let value = @json($value);

        function renderFieldInput() {
            let type = typeSelect.value;
            let html = '';

            // إضافة حقل مخفي لتخزين النوع الحالي
            html += `<input type="hidden" name="current_type" value="${type}">`;

            if(type === 'select') {
                html += `<label>خيارات القائمة:</label><div id="select-options-list">`;
                if (!Array.isArray(options) || options.length === 0) options = [''];
                options.forEach((opt, idx) => {
                    html += `<div class="input-group mb-2 select-option-item">
                        <input type="text" name="options[]" class="form-control" value="${opt}" placeholder="خيار">
                        <button type="button" class="btn btn-danger remove-option">&times;</button>
                    </div>`;
                });
                html += `</div><button type="button" class="btn btn-sm btn-primary" id="add-select-option">إضافة خيار</button>`;
                html += `<label class="mt-2">القيمة الافتراضية:</label><select name="value" id="select-default-value" class="form-control">`;
                options.forEach(opt => {
                    html += `<option value="${opt}" ${(value == opt) ? 'selected' : ''}>${opt}</option>`;
                });
                html += `</select>`;
            } else if(type === 'checkbox') {
                html = `<label>قيمة افتراضية:</label><input type="checkbox" name="value" value="1" ${(value==1)?'checked':''}>`;
            } else if(type === 'image') {
                html = `<label>قيمة افتراضية:</label><input type="file" name="value" class="form-control" accept="image/*" ${value ? '' : 'required'}>`;
                if(value) html += `<div class='mt-2'><img src='${value}' style='max-width:120px;'></div>`;
            } else if(type === 'date') {
                html = `<label>قيمة افتراضية:</label><input type="date" name="value" class="form-control" value="${value}">`;
            } else if(type === 'time') {
                html = `<label>قيمة افتراضية:</label><input type="time" name="value" class="form-control" value="${value}">`;
            } else if(type === 'number') {
                html = `<label>قيمة افتراضية:</label><input type="number" name="value" class="form-control" value="${value}">`;
            } else if(type === 'textarea') {
                html = `<label>قيمة افتراضية:</label><textarea name="value" class="form-control">${value}</textarea>`;
            } else {
                html = `<label>قيمة افتراضية:</label><input type="text" name="value" class="form-control" value="${value}">`;
            }
            dynamicFieldInput.innerHTML = html;

            // Events for select options
            if(type === 'select') {
                document.getElementById('add-select-option').onclick = function() {
                    options.push('');
                    renderFieldInput();
                };
                dynamicFieldInput.querySelectorAll('.remove-option').forEach((btn, idx) => {
                    btn.onclick = function() {
                        options.splice(idx, 1);
                        renderFieldInput();
                    };
                });
                dynamicFieldInput.querySelectorAll('input[name="options[]"]').forEach((input, idx) => {
                    input.oninput = function() {
                        options[idx] = input.value;
                        // تحديث قائمة القيم الافتراضية
                        renderFieldInput();
                    };
                });
            }
        }
        renderFieldInput();
        typeSelect.addEventListener('change', function() {
            if(typeSelect.value !== 'select') options = [];
            renderFieldInput();
        });
    });
</script>
