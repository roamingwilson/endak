<?php

namespace App\Http\Controllers\departments\Industry;

use App\Http\Controllers\Controller;
use App\Models\indsCategory;
use App\Models\industries;
use Illuminate\Http\Request;

class indsCategoryController extends Controller
{
    public function index() {
        $categories = indsCategory::latest()->get();
        $industry = industries::first();

        // dd($industry);
        return view('admin.main_department.industry.category.index', compact('categories','industry'));
    }



    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'industry_id' => 'required|exists:industries,id'

        ]);

        indsCategory::create($request->all());
        return view('admin.main_department.industry.category.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit($id) {
        $category = indsCategory::findOrFail($id);
        return view('admin.main_department.industry.category.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $category = indsCategory::findOrFail($id);
        $request->validate([
            'name' => 'required',


        ]);

        $category->update($request->all());
        return view('admin.main_department.industry.category.show')->with('success', 'تم تعديل القسم بنجاح');
    }

    public function destroy($id) {
        $category = indsCategory::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'تم حذف القسم');
    }
}
