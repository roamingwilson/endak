@csrf
<div class="mb-3">
    <label for="name" class="form-label">Field Key (name)</label>
    <select class="form-control" id="name" name="name" required>
        <option value="">-- اختر --</option>
        <option value="neighborhood" @selected(old('name', $field->name ?? '') == 'neighborhood')>neighborhood</option>
        <option value="from_city" @selected(old('name', $field->name ?? '') == 'from_city')>from_city</option>
        <option value="from_neighborhood" @selected(old('name', $field->name ?? '') == 'from_neighborhood')>from_neighborhood</option>
        <option value="to_city" @selected(old('name', $field->name ?? '') == 'to_city')>to_city</option>
        <option value="to_neighborhood" @selected(old('name', $field->name ?? '') == 'to_neighborhood')>to_neighborhood</option>
        <option value="model" @selected(old('name', $field->name ?? '') == 'model')>model</option>
        <option value="images[]" @selected(old('name', $field->name ?? '') == 'images[]')>images</option>
        <option value="year" @selected(old('name', $field->name ?? '') == 'year')>year</option>
        <option value="brand" @selected(old('name', $field->name ?? '') == 'brand')>brand</option>
        <option value="part_number" @selected(old('name', $field->name ?? '') == 'part_number')>part_number</option>
        <option value="equip_type" @selected(old('name', $field->name ?? '') == 'equip_type')>equip_type</option>
        <option value="car_type" @selected(old('name', $field->name ?? '') == 'car_type')>car_type</option>
        <option value="location" @selected(old('name', $field->name ?? '') == 'location')>location</option>
        <option value="gender" @selected(old('name', $field->name ?? '') == 'gender')>gender</option>
        <option value="time" @selected(old('name', $field->name ?? '') == 'time')>time</option>
        <option value="date" @selected(old('name', $field->name ?? '') == 'date')>date</option>
        <option value="day" @selected(old('name', $field->name ?? '') == 'day')>day</option>
        <option value="smart" @selected(old('name', $field->name ?? '') == 'smart')>smartcheckBox</option>
        <option value="notes" @selected(old('name', $field->name ?? '') == 'notes')>notes</option>
        <option value="quantity" @selected(old('name', $field->name ?? '') == 'quantity')>quantity</option>
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
<div class="mb-3" id="options-container" style="display: none;">
    <label for="options" class="form-label">Options (comma-separated)</label>
    <input type="text" class="form-control" id="options" name="options"
        value="{{ old('options', isset($field) && is_array($field->options) ? implode(',', $field->options) : '') }}">
</div>
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1"
        @checked(old('is_required', $field->is_required ?? false))>
    <label class="form-check-label" for="is_required">Is Required?</label>
</div>
<button type="submit" class="btn btn-primary">Submit</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const optionsContainer = document.getElementById('options-container');

        function toggleOptions() {
            optionsContainer.style.display = typeSelect.value === 'select' ? 'block' : 'none';
        }

        toggleOptions();
        typeSelect.addEventListener('change', toggleOptions);
    });
</script>
