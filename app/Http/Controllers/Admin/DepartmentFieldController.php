<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentField;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentFieldController extends Controller
{
    public function index(Department $department)
    {
        return view('admin.department_fields.index', compact('department'));
    }

    public function create($departmentId, Request $request)
    {
        $department = Department::findOrFail($departmentId);
        $subDepartments = SubDepartment::where('department_id', $departmentId)->get();
        $inputGroups = DepartmentField::where('department_id', $departmentId)->pluck('input_group')->unique()->filter()->values();
        $selectedSubDepartmentId = $request->get('sub_department_id');
        return view('admin.department_fields.create', compact('department', 'inputGroups', 'subDepartments', 'selectedSubDepartmentId'));
    }

    public function store(Request $request, Department $department)
    {
        $data = $this->validateRequest($request);
        // معالجة الخيارات إذا كان النوع select
        if ($data['type'] === 'select' && isset($data['options']) && is_array($data['options'])) {
            $data['options'] = array_filter(array_map('trim', $data['options']));
        } elseif (isset($data['options'])) {
            $data['options'] = null;
        }
        // معالجة القيمة الافتراضية
        if (isset($data['value']) && is_array($data['value'])) {
            $data['value'] = $data['value'][0] ?? null;
        }
        $department->fields()->create($data);

        return redirect()->route('admin.departments.fields.index', $department->id)
            ->with('success', 'Field created successfully.');
    }

    public function edit(DepartmentField $field)
    {
        $inputGroups = DepartmentField::whereNotNull('input_group')->distinct()->pluck('input_group')->toArray();
        return view('admin.department_fields.edit', compact('field', 'inputGroups'));
    }

    public function update(Request $request, DepartmentField $field)
    {
        $data = $this->validateRequest($request);
        $field->update($data);

        return redirect()->route('admin.departments.fields.index', $field->department_id)
            ->with('success', 'Field updated successfully.');
    }

    public function destroy(DepartmentField $field)
    {
        $departmentId = $field->department_id;
        $field->delete();

        return redirect()->route('admin.departments.fields.index', $departmentId)
            ->with('success', 'Field deleted successfully.');
    }

    protected function validateRequest(Request $request)
    {
        $rules = [
            'name' => 'nullable|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,checkbox,textarea,image,date,time',
            'options' => 'nullable', // مصفوفة أو نص
            'is_required' => 'nullable|boolean',
            'input_group' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_repeatable' => 'nullable|boolean',
            'sub_department_id' => 'nullable|exists:sub_departments,id',
        ];

        // إضافة قواعد خاصة لحقل القيمة حسب النوع
        $fieldType = $request->input('type');

        if ($fieldType === 'image') {
            $rules['value'] = 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['value'] = 'nullable';
        }

        try {
            $data = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // إضافة معلومات إضافية للأخطاء
            Log::error('Field validation error:', [
                'request_data' => $request->all(),
                'field_type' => $fieldType,
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        // إنشاء field key تلقائياً من الاسم الإنجليزي إذا لم يتم توفيره (للحقول الجديدة فقط)
        if (empty($data['name']) && !empty($data['name_en'])) {
            $data['name'] = $this->generateFieldKey($data['name_en']);
        }

        // التحقق من عدم تكرار field key في نفس القسم
        $existingField = DepartmentField::where('department_id', $request->input('department_id'))
            ->where('name', $data['name'])
            ->when($request->route('field'), function($query, $field) {
                return $query->where('id', '!=', $field->id);
            })
            ->first();

        if ($existingField) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'name' => 'Field key already exists in this department.'
            ]);
        }

        // التحقق من صحة field key (يجب أن يحتوي على أحرف وأرقام فقط مع underscore)
        if (!empty($data['name']) && !preg_match('/^[a-z0-9_]+$/', $data['name'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'name' => 'Field key must contain only lowercase letters, numbers, and underscores.'
            ]);
        }

        // معالجة خاصة لحقل الصورة
        if ($fieldType === 'image' && $request->hasFile('value')) {
            $data['value'] = $request->file('value')->store('field_images', 'public');
        }

        $data['is_required'] = $request->has('is_required');
        $data['is_repeatable'] = $request->has('is_repeatable');
        return $data;
    }

    /**
     * إنشاء field key من الاسم الإنجليزي
     */
    private function generateFieldKey($englishName)
    {
        return strtolower(
            preg_replace(
                '/[^a-zA-Z0-9\s]/',
                '',
                str_replace(' ', '_', trim($englishName))
            )
        );
    }

    /**
     * التحقق من صحة field key
     */
    private function validateFieldKey($fieldKey)
    {
        return preg_match('/^[a-z0-9_]+$/', $fieldKey);
    }
}
