<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('sub_departments')->get();
        return view('admin.departments.index', compact('departments'));
    }
    public function create()
    {
        $departments = Department::all();
        return view('admin.departments.department_add', compact('departments'));
    }

    public function show($id)
    {
        $department = Department::with('products')->findOrFail($id);
        return view('admin.departments.department_show', compact('department'));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'nullable|in:0,1',
            'image' => 'nullable|image',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('departments', 'public');
        }
        $department->update($data);
        return redirect()->route('admin.departments')->with('success', __('general.updated_successfully'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'nullable|in:0,1',
            'image' => 'nullable|image',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('departments', 'public');
        }
        $department = Department::create($data);
        return redirect()->route('admin.departments.fields.create', $department->id)
            ->with('success', __('تم إنشاء القسم بنجاح. يمكنك الآن إضافة الحقول.'));
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('admin.departments')->with('success', __('general.deleted_successfully'));
    }
}
