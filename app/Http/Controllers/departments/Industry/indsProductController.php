<?php

namespace App\Http\Controllers\departments\Industry;

use App\Http\Controllers\Controller;
use App\Models\indsCategory;
use App\Models\indsProduct;
use App\Models\indSubCategory;
use Illuminate\Http\Request;

class indsProductController extends Controller
{
    public function index(Request $request) {
        $query = IndsProduct::query();

        // فلترة حسب القسم الرئيسي
        if ($request->filled('inds_category_id')) {
            $query->where('inds_category_id', $request->inds_category_id);
        }

        // فلترة حسب القسم الفرعي
        if ($request->filled('ind_sub_category_id')) {
            $query->where('ind_sub_category_id', $request->ind_sub_category_id);
        }

        // فلترة حسب السعر
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // بحث بالكلمة المفتاحية في العنوان
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10); // أو get() لو مش عايز pagination

        // عشان تقدر تستخدم القوائم في الفلتر
        $categories = indsCategory::all();
        $subcategories = IndSubCategory::all();

        return view('admin.main_department.industry.product.viewProduct', compact('products', 'categories', 'subcategories'));
    }
    public function create(){
        $products = indsProduct::all();
        $categories = indsCategory::all();
        $subcategories = IndSubCategory::all();
        return view('admin.main_department.industry.product.create', compact( 'products','categories', 'subcategories'));
    }
    public function store(Request $request) {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'inds_category_id' => 'required|exists:inds_categories,id',
            'ind_sub_category_id' => 'required|exists:ind_sub_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // رفع الصورة إن وجدت
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('products', 'public');
            $fields['image'] = $path;
        }

        IndsProduct::create($fields);


        $products = IndsProduct::all();

        return redirect()->back()  ;
    }
    public function show($id) {
        $product = indsProduct::with(['category', 'subcategory', 'filters'])->findOrFail($id);
        return view('products.show', compact('product'));
    }
    public function viewProduct(){
        $products = indsProduct::latest()->get();
        $categories = indsCategory::all();
        $subcategories = IndSubCategory::all();
        return view('admin.main_department.industry.product.viewproduct', compact('products', 'categories', 'subcategories'));
    }
}
