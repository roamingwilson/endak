@csrf

<!-- معلومات الحقل الأساسية -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-cog"></i> معلومات الحقل الأساسية</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">
                        <i class="fas fa-key text-primary"></i> مفتاح الحقل (Field Key)
                    </label>
                    <div class="input-group">
                        <select class="form-control" id="name" name="name" required>
                            <option value="">-- اختر مفتاح الحقل --</option>
                            <optgroup label="حقول الموقع">
                                <option value="neighborhood" @selected(old('name', $field->name ?? '') == 'neighborhood')>neighborhood - الحي</option>
                                <option value="from_city" @selected(old('name', $field->name ?? '') == 'from_city')>from_city - من المدينة</option>
                                <option value="from_neighborhood" @selected(old('name', $field->name ?? '') == 'from_neighborhood')>from_neighborhood - من الحي</option>
                                <option value="to_city" @selected(old('name', $field->name ?? '') == 'to_city')>to_city - إلى المدينة</option>
                                <option value="to_neighborhood" @selected(old('name', $field->name ?? '') == 'to_neighborhood')>to_neighborhood - إلى الحي</option>
                                <option value="location" @selected(old('name', $field->name ?? '') == 'location')>location - الموقع</option>
                            </optgroup>
                            <optgroup label="حقول المركبات">
                                <option value="model" @selected(old('name', $field->name ?? '') == 'model')>model - الموديل</option>
                                <option value="year" @selected(old('name', $field->name ?? '') == 'year')>year - السنة</option>
                                <option value="brand" @selected(old('name', $field->name ?? '') == 'brand')>brand - الماركة</option>
                                <option value="car_type" @selected(old('name', $field->name ?? '') == 'car_type')>car_type - نوع السيارة</option>
                                <option value="equip_type" @selected(old('name', $field->name ?? '') == 'equip_type')>equip_type - نوع المعدة</option>
                                <option value="part_number" @selected(old('name', $field->name ?? '') == 'part_number')>part_number - رقم القطعة</option>
                            </optgroup>
                            <optgroup label="حقول عامة">
                                <option value="quantity" @selected(old('name', $field->name ?? '') == 'quantity')>quantity - الكمية</option>
                                <option value="gender" @selected(old('name', $field->name ?? '') == 'gender')>gender - الجنس</option>
                                <option value="time" @selected(old('name', $field->name ?? '') == 'time')>time - الوقت</option>
                                <option value="date" @selected(old('name', $field->name ?? '') == 'date')>date - التاريخ</option>
                                <option value="day" @selected(old('name', $field->name ?? '') == 'day')>day - اليوم</option>
                                <option value="notes" @selected(old('name', $field->name ?? '') == 'notes')>notes - الملاحظات</option>
                                <option value="images[]" @selected(old('name', $field->name ?? '') == 'images[]')>images - الصور</option>
                                <option value="smart" @selected(old('name', $field->name ?? '') == 'smart')>smart - خيار ذكي</option>
                            </optgroup>
                            <option value="custom" @selected(old('name', $field->name ?? '') == 'custom')>إدخال مخصص</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary" id="generateKeyBtn">
                                <i class="fas fa-magic"></i> توليد تلقائي
                            </button>
                        </div>
                    </div>
                    <div class="mt-2" id="customKeyInput" style="display: none;">
                        <input type="text" class="form-control" id="customKey" name="custom_key" placeholder="أدخل مفتاح الحقل المخصص"
                               pattern="[a-z_][a-z0-9_]*"
                               title="يجب أن يبدأ بحرف صغير أو underscore ويحتوي على أحرف صغيرة وأرقام وunderscore فقط">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> يجب أن يبدأ بحرف صغير أو underscore ويحتوي على أحرف صغيرة وأرقام وunderscore فقط
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="type" class="form-label fw-bold">
                        <i class="fas fa-tag text-success"></i> نوع الحقل
                    </label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">-- اختر نوع الحقل --</option>
                        <option value="text" @selected(old('type', $field->type ?? '') == 'text')>Text - نص</option>
                        <option value="number" @selected(old('type', $field->type ?? '') == 'number')>Number - رقم</option>
                        <option value="select" @selected(old('type', $field->type ?? '') == 'select')>Select - قائمة منسدلة</option>
                        <option value="checkbox" @selected(old('type', $field->type ?? '') == 'checkbox')>Checkbox - مربع اختيار</option>
                        <option value="textarea" @selected(old('type', $field->type ?? '') == 'textarea')>Textarea - نص طويل</option>
                        <option value="image" @selected(old('type', $field->type ?? '') == 'image')>Image - صورة</option>
                        <option value="date" @selected(old('type', $field->type ?? '') == 'date')>Date - تاريخ</option>
                        <option value="time" @selected(old('type', $field->type ?? '') == 'time')>Time - وقت</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="name_ar" class="form-label">اسم الحقل بالعربي</label>
    <input type="text" class="form-control" id="name_ar" name="name_ar"
           value="{{ old('name_ar', $field->name_ar ?? '') }}"
           required
           maxlength="100"
           pattern="[ء-ي\s]+"
           title="يجب أن يحتوي على أحرف عربية فقط">
    <small class="text-muted">أقصى 100 حرف، أحرف عربية فقط</small>
</div>
<div class="mb-3">
    <label for="name_en" class="form-label">Field Name (English)</label>
    <input type="text" class="form-control" id="name_en" name="name_en"
           value="{{ old('name_en', $field->name_en ?? '') }}"
           required
           maxlength="100"
           pattern="[a-zA-Z\s]+"
           title="يجب أن يحتوي على أحرف إنجليزية فقط">
    <small class="text-muted">أقصى 100 حرف، أحرف إنجليزية فقط</small>
</div>
<div class="mb-3">
    <label for="type" class="form-label">Field Type</label>
    <select class="form-control" id="type" name="type" required>
        <option value="text" @selected(old('type', $field->type ?? '') == 'text')>Text</option>
        <option value="number" @selected(old('type', $field->type ?? '') == 'number')>Number</option>
        <option value="select" @selected(old('type', $field->type ?? '') == 'select')>Select</option>
        <option value="checkbox" @selected(old('type', $field->type ?? '') == 'checkbox')>Checkbox</option>
        <option value="textarea" @selected(old('type', $field->type ?? '') == 'textarea')>Textarea</option>
        <option value="image" @selected(old('type', $field->type ?? '') == 'image')>Image</option>
        <option value="date" @selected(old('type', $field->type ?? '') == 'date')>Date</option>
        <option value="time" @selected(old('type', $field->type ?? '') == 'time')>Time</option>
    </select>
</div>
<div class="mb-3" id="field-value-input"></div>
<div class="mb-3" id="options-container" style="display: none;">
    <label for="options" class="form-label">خيارات القائمة (كل خيار في سطر منفصل)</label>
    <textarea class="form-control" id="options" name="options"
              rows="3"
              maxlength="1000"
              placeholder="أدخل كل خيار في سطر منفصل&#10;مثال:&#10;خيار 1&#10;خيار 2&#10;خيار 3">{{ old('options', isset($field) && is_array($field->options) ? implode("\n", $field->options) : '') }}</textarea>
    <small class="text-muted">أقصى 1000 حرف، كل خيار في سطر منفصل</small>
</div>
<div class="mb-3">
    <label for="input_group" class="form-label">مجموعة الإدخال (لدمج الحقول في صف واحد)</label>
    <input type="text" class="form-control" id="input_group" name="input_group"
           value="{{ old('input_group', $field->input_group ?? '') }}"
           list="input_group_list"
           pattern="[a-zA-Z0-9_]+"
           title="يجب أن يحتوي على أحرف وأرقام وunderscore فقط"
           maxlength="50">
    <datalist id="input_group_list">
        @if(isset($inputGroups))
            @foreach($inputGroups as $group)
                <option value="{{ $group }}">
            @endforeach
        @endif
    </datalist>
    <small class="text-muted">أقصى 50 حرف، أحرف وأرقام وunderscore فقط (مثلاً: group1)</small>
</div>
@if(old('input_group', $field->input_group ?? ''))
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_repeatable" name="is_repeatable" value="1" @checked(old('is_repeatable', $field->is_repeatable ?? false))>
    <label class="form-check-label" for="is_repeatable">قابل للتكرار (يمكن للمستخدم إضافة أكثر من مجموعة)</label>
</div>
@endif


<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" @checked(old('is_required', $field->is_required ?? false))>
        <label class="form-check-label" for="is_required">حقل مطلوب</label>
    </div>
</div>

<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_searchable" name="is_searchable" value="1" @checked(old('is_searchable', $field->is_searchable ?? false))>
        <label class="form-check-label" for="is_searchable">قابل للبحث</label>
    </div>
</div>

<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="is_filterable" name="is_filterable" value="1" @checked(old('is_filterable', $field->is_filterable ?? false))>
        <label class="form-check-label" for="is_filterable">قابل للفلترة</label>
    </div>
</div>

<div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> حفظ الحقل
    </button>
    <button type="button" class="btn btn-secondary" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> رجوع
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const nameSelect = document.getElementById('name');
        const customKeyInput = document.getElementById('customKeyInput');
        const customKey = document.getElementById('customKey');
        const generateKeyBtn = document.getElementById('generateKeyBtn');
        const nameArInput = document.getElementById('name_ar');
        const nameEnInput = document.getElementById('name_en');
        const optionsContainer = document.getElementById('options-container');
        let lastOptionsValue = '';
        let lastType = typeSelect.value;

        // التحقق من صحة الحقول
        function validateField(field, pattern, message) {
            const isValid = pattern.test(field.value);
            field.classList.toggle('is-invalid', !isValid);
            field.classList.toggle('is-valid', isValid);

            // إزالة رسائل الخطأ السابقة
            const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }

            if (!isValid) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = message;
                field.parentNode.appendChild(feedback);
            }

            return isValid;
        }

                // توليد مفتاح تلقائي من الاسم العربي
        function generateKeyFromArabic() {
            const arabicText = nameArInput.value.trim();
            if (arabicText) {
                // تحويل النص العربي إلى مفتاح مناسب
                let key = arabicText
                    .replace(/[ء-ي]/g, '') // إزالة الأحرف العربية
                    .replace(/[^a-zA-Z0-9\s]/g, '') // إزالة الأحرف الخاصة
                    .trim()
                    .toLowerCase()
                    .replace(/\s+/g, '_'); // استبدال المسافات بـ underscore

                if (key.length === 0) {
                    key = 'field_' + Date.now(); // إذا لم يتبق شيء، استخدم timestamp
                }

                return key;
            }
            return '';
        }

        // توليد مفتاح تلقائي من الاسم الإنجليزي
        function generateKeyFromEnglish() {
            const englishText = nameEnInput.value.trim();
            if (englishText) {
                return englishText
                    .toLowerCase()
                    .replace(/[^a-zA-Z0-9\s]/g, '')
                    .trim()
                    .replace(/\s+/g, '_');
            }
            return '';
        }

        // توليد مفتاح تلقائي ذكي بناءً على نوع الحقل
        function generateSmartKey() {
            const fieldType = typeSelect.value;
            const arabicName = nameArInput.value.trim();
            const englishName = nameEnInput.value.trim();

            // إذا كان هناك اسم عربي أو إنجليزي، استخدمه
            if (arabicName || englishName) {
                return arabicName ? generateKeyFromArabic() : generateKeyFromEnglish();
            }

            // توليد مفتاح بناءً على نوع الحقل
            const typeKeys = {
                'text': 'text_field',
                'number': 'number_field',
                'select': 'select_field',
                'checkbox': 'checkbox_field',
                'textarea': 'textarea_field',
                'image': 'image_field',
                'date': 'date_field',
                'time': 'time_field'
            };

            const baseKey = typeKeys[fieldType] || 'custom_field';
            return baseKey + '_' + Date.now();
        }

        // معالجة تغيير نوع الحقل
        function toggleOptions() {
            optionsContainer.style.display = typeSelect.value === 'select' ? 'block' : 'none';
        }

        function saveOptionsValue() {
            const optionsTextarea = document.getElementById('options');
            if (optionsTextarea) {
                lastOptionsValue = optionsTextarea.value;
            }
        }

        function restoreOptionsValue() {
            const optionsTextarea = document.getElementById('options');
            if (optionsTextarea && lastOptionsValue !== undefined) {
                optionsTextarea.value = lastOptionsValue;
                optionsTextarea.focus();
                optionsTextarea.selectionStart = optionsTextarea.selectionEnd = optionsTextarea.value.length;
            }
        }

        // معالجة تغيير اختيار مفتاح الحقل
        nameSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customKeyInput.style.display = 'block';
                customKey.required = true;
                customKey.focus();
            } else {
                customKeyInput.style.display = 'none';
                customKey.required = false;
                customKey.value = '';

                // ملء الأسماء تلقائياً بناءً على الاختيار
                const fieldMappings = {
                    'neighborhood': { ar: 'الحي', en: 'Neighborhood' },
                    'from_city': { ar: 'من المدينة', en: 'From City' },
                    'from_neighborhood': { ar: 'من الحي', en: 'From Neighborhood' },
                    'to_city': { ar: 'إلى المدينة', en: 'To City' },
                    'to_neighborhood': { ar: 'إلى الحي', en: 'To Neighborhood' },
                    'model': { ar: 'الموديل', en: 'Model' },
                    'year': { ar: 'السنة', en: 'Year' },
                    'brand': { ar: 'الماركة', en: 'Brand' },
                    'part_number': { ar: 'رقم القطعة', en: 'Part Number' },
                    'equip_type': { ar: 'نوع المعدة', en: 'Equipment Type' },
                    'car_type': { ar: 'نوع السيارة', en: 'Car Type' },
                    'location': { ar: 'الموقع', en: 'Location' },
                    'gender': { ar: 'الجنس', en: 'Gender' },
                    'time': { ar: 'الوقت', en: 'Time' },
                    'date': { ar: 'التاريخ', en: 'Date' },
                    'day': { ar: 'اليوم', en: 'Day' },
                    'quantity': { ar: 'الكمية', en: 'Quantity' },
                    'notes': { ar: 'الملاحظات', en: 'Notes' },
                    'images[]': { ar: 'الصور', en: 'Images' },
                    'smart': { ar: 'خيار ذكي', en: 'Smart Option' }
                };

                const mapping = fieldMappings[this.value];
                if (mapping) {
                    nameArInput.value = mapping.ar;
                    nameEnInput.value = mapping.en;

                    // إزالة classes التحقق
                    nameArInput.classList.remove('is-invalid', 'is-valid');
                    nameEnInput.classList.remove('is-invalid', 'is-valid');
                }
            }
        });

                // زر التوليد التلقائي
        generateKeyBtn.addEventListener('click', function() {
            let generatedKey = '';

            // محاولة التوليد من الأسماء أولاً
            if (nameArInput.value.trim()) {
                generatedKey = generateKeyFromArabic();
            } else if (nameEnInput.value.trim()) {
                generatedKey = generateKeyFromEnglish();
            } else {
                // إذا لم يكن هناك أسماء، استخدم التوليد الذكي
                generatedKey = generateSmartKey();
            }

            if (generatedKey) {
                // تغيير الاختيار إلى مخصص وإدخال المفتاح
                nameSelect.value = 'custom';
                customKeyInput.style.display = 'block';
                customKey.value = generatedKey;
                customKey.required = true;

                // إظهار رسالة نجاح
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show mt-2';
                successAlert.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    تم توليد المفتاح تلقائياً: <strong>${generatedKey}</strong>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                nameSelect.parentNode.parentNode.appendChild(successAlert);

                // إزالة الرسالة بعد 3 ثواني
                setTimeout(() => {
                    successAlert.remove();
                }, 3000);
            } else {
                alert('حدث خطأ في توليد المفتاح');
            }
        });

        // التحقق من صحة الحقول عند الإدخال
        nameArInput.addEventListener('input', function() {
            validateField(this, /^[ء-ي\s]+$/, 'يجب أن يحتوي على أحرف عربية فقط');
        });

        nameEnInput.addEventListener('input', function() {
            validateField(this, /^[a-zA-Z\s]+$/, 'يجب أن يحتوي على أحرف إنجليزية فقط');
        });

        customKey.addEventListener('input', function() {
            validateField(this, /^[a-z_][a-z0-9_]*$/, 'يجب أن يبدأ بحرف صغير أو underscore ويحتوي على أحرف صغيرة وأرقام وunderscore فقط');
        });

        // التحقق من صحة مجموعة الإدخال
        const inputGroupField = document.getElementById('input_group');
        if (inputGroupField) {
            inputGroupField.addEventListener('input', function() {
                validateField(this, /^[a-zA-Z0-9_]*$/, 'يجب أن يحتوي على أحرف وأرقام وunderscore فقط');
            });
        }

        // التحقق من صحة الخيارات
        const optionsField = document.getElementById('options');
        if (optionsField) {
            optionsField.addEventListener('input', function() {
                const lines = this.value.split('\n').filter(line => line.trim().length > 0);
                if (lines.length > 0) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        }

                // التحقق من صحة النموذج قبل الإرسال
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessages = [];

            // التحقق من الحقول المطلوبة
            const requiredFields = [nameArInput, nameEnInput];
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    errorMessages.push(`حقل ${field.name} مطلوب`);
                }
            });

            // التحقق من اختيار نوع الحقل
            if (!nameSelect.value) {
                nameSelect.classList.add('is-invalid');
                isValid = false;
                errorMessages.push('يجب اختيار نوع الحقل');
            }

            // التحقق من مفتاح الحقل المخصص إذا كان محدداً
            if (nameSelect.value === 'custom') {
                if (!customKey.value.trim()) {
                    customKey.classList.add('is-invalid');
                    isValid = false;
                    errorMessages.push('يجب إدخال مفتاح الحقل المخصص');
                } else if (!/^[a-z_][a-z0-9_]*$/.test(customKey.value)) {
                    customKey.classList.add('is-invalid');
                    isValid = false;
                    errorMessages.push('مفتاح الحقل المخصص غير صحيح');
                }
            }

            // التحقق من نوع الحقل
            if (!typeSelect.value) {
                typeSelect.classList.add('is-invalid');
                isValid = false;
                errorMessages.push('يجب اختيار نوع الحقل');
            }

            // التحقق من الخيارات إذا كان النوع select
            if (typeSelect.value === 'select') {
                const optionsValue = document.getElementById('options').value.trim();
                if (!optionsValue) {
                    document.getElementById('options').classList.add('is-invalid');
                    isValid = false;
                    errorMessages.push('يجب إدخال خيارات للحقل من نوع select');
                }
            }

            if (!isValid) {
                e.preventDefault();
                const errorMessage = errorMessages.join('\n');
                alert(`يرجى تصحيح الأخطاء التالية:\n${errorMessage}`);
            }
        });

        // تهيئة الصفحة
        toggleOptions();
        saveOptionsValue();

        // معالجة تغيير نوع الحقل
        typeSelect.addEventListener('change', function() {
            saveOptionsValue();
            toggleOptions();
            setTimeout(restoreOptionsValue, 0);
            lastType = typeSelect.value;
        });
    });
</script>
