<?php

namespace App\Http\Controllers\departments\Industry;

use App\Http\Controllers\Controller;
use App\Models\indsCategory;
use App\Models\indSubCategory;
use Illuminate\Http\Request;

class indSubCategoryController extends Controller
{
    public function index() {
        $subcategories = indSubCategory::with('category')->get();
        $categories = indsCategory::latest()->get();
        return view('admin.main_department.industry.subcategory.index', compact('subcategories','categories'));
    }



    public function store(Request $request) {
        $request->validate([
            'name' => 'required',

            'inds_category_id' => 'required|exists:inds_categories,id'
        ]);

        indSubCategory::create($request->all());
        return redirect()->back()->with('success', 'تمت إضافة التصنيف الفرعي');
    }

    public function edit($id) {
        $subcategory = indSubCategory::findOrFail($id);
        $categories = indsCategory::all();
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id) {
        $subcategory = indSubCategory::findOrFail($id);
        $request->validate([
            'name' => 'required',

            'inds_category_id' => 'required|exists:inds_categories,id'
        ]);

        $subcategory->update($request->all());
        return redirect()->route('subcategories.index')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($id) {
        $subcategory = indSubCategory::findOrFail($id);
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'تم الحذف بنجاح');
    }
}
