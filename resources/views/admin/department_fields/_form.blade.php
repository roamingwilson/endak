@csrf
<div class="mb-3">
    <label for="name" class="form-label">Field Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $field->name ?? '') }}"
        required>
</div>
<div class="mb-3">
    <label for="type" class="form-label">Field Type</label>
    <select class="form-control" id="type" name="type" required>
        <option value="text" @selected(old('type', $field->type ?? '') == 'text')>Text</option>
        <option value="number" @selected(old('type', $field->type ?? '') == 'number')>Number</option>
        <option value="select" @selected(old('type', $field->type ?? '') == 'select')>Select</option>
        <option value="checkbox" @selected(old('type', $field->type ?? '') == 'checkbox')>Checkbox</option>
        <option value="textarea" @selected(old('type', $field->type ?? '') == 'textarea')>Textarea</option>
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
