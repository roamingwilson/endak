<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\indsCategory;
use App\Models\indsProduct;
use App\Models\indSubCategory;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index (){
        $department = Department::where('name_en' ,'plastic')->first();
        return view('admin.main_department.industry.index',compact('department'));
    }
    public function show_cat(){
        $category = indsCategory::latest()->paginate(10);
        return view('admin.main_department.industry.category.show',compact('category'));
    }
    public function show_sub_cat()
    {
        $category = indSubCategory::latest()->paginate(10);
        return view('admin.main_department.industry.subcategory.show',compact('category'));
    }
    public function show_product(){
        $category = indsProduct::latest()->paginate(10);
        // dd($category);
        return view('admin.main_department.industry.product.show',compact('category'));
    }
}
