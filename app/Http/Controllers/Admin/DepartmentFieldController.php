<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentField;
use Illuminate\Http\Request;

class DepartmentFieldController extends Controller
{
    public function index(Department $department)
    {
        return view('admin.department_fields.index', compact('department'));
    }

    public function create(Department $department)
    {
        return view('admin.department_fields.create', compact('department'));
    }

    public function store(Request $request, Department $department)
    {
        $data = $this->validateRequest($request);
        $department->fields()->create($data);

        return redirect()->route('admin.departments.fields.index', $department->id)
            ->with('success', 'Field created successfully.');
    }

    public function edit(DepartmentField $field)
    {
        return view('admin.department_fields.edit', compact('field'));
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,checkbox,textarea',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
        ]);

        if ($request->type === 'select' && !empty($data['options'])) {
            $data['options'] = array_map('trim', explode(',', $data['options']));
        } else {
            $data['options'] = null;
        }

        $data['is_required'] = $request->has('is_required');

        return $data;
    }
}
