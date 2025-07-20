<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentField;
use App\Models\SubDepartment;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,checkbox,textarea,image,date,time',
            'options' => 'nullable', // مصفوفة أو نص
            'is_required' => 'nullable|boolean',
            'input_group' => 'nullable|string|max:255',
            'value' => 'nullable',
            'description' => 'nullable|string|max:1000',
            'is_repeatable' => 'nullable|boolean',
            'sub_department_id' => 'nullable|exists:sub_departments,id',
        ]);
        $data['is_required'] = $request->has('is_required');
        $data['is_repeatable'] = $request->has('is_repeatable');
        return $data;
    }
}
