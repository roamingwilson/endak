<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubDepartment;
use App\Models\Department;
use Illuminate\Http\Request;

class SubDepartmentController extends Controller
{
    public function index()
    {
        $subDepartments = SubDepartment::with('department')->latest()->paginate(20);
        return view('admin.sub_departments.index', compact('subDepartments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.sub_departments.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sub_departments', 'public');
        }
        SubDepartment::create($data);
        return redirect()->route('admin.sub_departments.index')->with('success', 'تم إضافة القسم الفرعي بنجاح');
    }

    public function edit($id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $departments = Department::all();
        return view('admin.sub_departments.edit', compact('subDepartment', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sub_departments', 'public');
        }
        $subDepartment->update($data);
        return redirect()->route('admin.sub_departments.index')->with('success', 'تم تحديث القسم الفرعي بنجاح');
    }

    public function destroy($id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $subDepartment->delete();
        return redirect()->route('admin.sub_departments.index')->with('success', 'تم حذف القسم الفرعي بنجاح');
    }

    public function duplicate($id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $departments = Department::all();
        return view('admin.sub_departments.duplicate', compact('subDepartment', 'departments'));
    }

    public function duplicateStore(Request $request, $id)
    {
        $originalSubDepartment = SubDepartment::findOrFail($id);

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // نسخ الصورة إذا كانت موجودة
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sub_departments', 'public');
        } else {
            // نسخ الصورة الأصلية إذا لم يتم رفع صورة جديدة
            $data['image'] = $originalSubDepartment->image;
        }

        // إنشاء القسم الفرعي الجديد
        $newSubDepartment = SubDepartment::create($data);

        // نسخ الحقول المرتبطة إذا كانت موجودة
        if($originalSubDepartment->fields->count() > 0) {
            foreach($originalSubDepartment->fields as $field) {
                $fieldData = $field->toArray();
                unset($fieldData['id']);
                unset($fieldData['created_at']);
                unset($fieldData['updated_at']);
                $fieldData['sub_department_id'] = $newSubDepartment->id;

                // إنشاء الحقل الجديد
                \App\Models\DepartmentField::create($fieldData);
            }
        }

        return redirect()->route('admin.sub_departments.index')->with('success', 'تم تكرار القسم الفرعي بنجاح');
    }
}
