<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Post;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {

        $departments = Department::where('department_id', 0)->orderBy('id', 'desc')->paginate(6);
        return view('front_office.departments.index', compact('departments'));
    }
    public function show($id)
    {
        $user = auth()->user();
        $department =  Department::findOrFail($id);
        if ($department->department_id == 0) {
            $main = $department;
            $services = Post::where('department_id', $main->id)->paginate();
            $sub_departments = $main->sub_Departments;
            $products = $main->products;
            $inputs = $main->inputs;
            if(isset($products) && isset($inputs) && $products->count() > 0 && $inputs->count() > 0) {  
                return view('front_office.main_departments.show_products_inputs', compact('main', 'services' , 'products' , 'inputs'));
            }
            // if (count($services) > 0) {
            //     return view('admin.main_department.general.show', compact('main', 'services'));
            // } elseif (count($sub_departments) > 0) {
            //     return view('admin.main_department.general.show_sub_departments', compact('main', 'sub_departments'));
            // } elseif (count($products) > 0) {
            // }
        }
        if ($department->department_id != 0) {
            $sub = $department;
            $main = Department::findOrFail($department->department_id);
            $services = Post::where('department_id', $sub->id)->paginate();
            $sub_departments = $sub->sub_Departments;
            $products = $sub->products;
            $inputs = $sub->inputs;
            if (count($sub_departments) > 0) {
                return view('admin.main_department.general.show_sub_departments', compact('main', 'sub_departments'));
            } 
            // elseif (count($services) > 0) {
            //     return view('admin.main_department.general.show', compact('main', 'services'));
            // } 

            elseif ((count($products) > 0 && count($inputs) > 0)  ) {
                return view('admin.main_department.general.show_products_inputs', compact('main', 'products' ,'inputs', 'sub' ,'services' ));
            } elseif (count($products) > 0 && count($inputs) == 0) {
                return view('admin.main_department.general.show_products', compact('main', 'products' , 'sub'));
            } elseif (count($products) == 0 && count($inputs) > 0) {

            }
        }
        // if (isset($user) && $user->role_id == 1) {
        //     return view('admin.main_department.ads.show', compact('main', 'services'));
        // } elseif (isset($user) && $user->role_id == 3) {
        //     $main = Ads::first();
        //     $services = AdsService::paginate();
        //     return view('admin.main_department.ads.show', compact('main', 'services'));
        // } else {
        //     return view('admin.main_department.ads.show', compact('main', 'services'));
        // }
    }
    // public function show($id, Request $request)
    // {
    //     $department = Department::findOrFail($id);
    //     $main_department = null;

    //     if ($department->department_id == 0) {
    //         $main_department = $department;
    //     }
    //     if (isset($main_department->sub_Departments) && $main_department->sub_Departments->isNotEmpty()) {
    //         $sub_departments = Department::where('department_id', $main_department->id)
    //             ->with('sub_Departments', 'sub_Departments.sub_Departments')
    //             ->paginate(6);

    //         return view('front_office.departments.show', compact('sub_departments'));
    //     }

    //     // if ($main_department->sub_Departments->isEmpty() && $main_department->posts->isNotEmpty()) {
    //     //     $products = $main_department->products;
    //     //     $posts = Post::where('department_id', $main_department->id)->paginate(6);
    //     //     return view('front_office.departments.show_posts', compact('posts', 'products'));
    //     // }

    //     // if ($main_department->sub_Departments->isEmpty() && $main_department->products->isNotEmpty()) {
    //     //     $categories = Category::get();
    //     //     $products_query = $main_department->products()->newQuery(); // Initialize query
    //     //     if ($request->bulk_action_btn == 'filter') {
    //     //         if ($request->category) {
    //     //             if ($request->category) {
    //     //                 $products_query = $products_query->whereHas('topics', function ($query) use ($request) {
    //     //                     $query->whereIn('products_categories.category_id', $request->category); // Specify table name to avoid ambiguity
    //     //                 });
    //     //             }

    //     //         }
    //     //         $products = $products_query->get();
    //     //     } else {
    //     //         $products = $main_department->products;
    //     //     }

    //     //     return view('front_office.departments.show_product', compact('products', 'categories'));
    //     // }
    //     else {
    //         $categories = Category::get();
    //         $products_query = $department->products()->newQuery(); // Initialize query
    //         if ($request->bulk_action_btn == 'filter') {
    //             if ($request->category) {
    //                 if ($request->category) {
    //                     $products_query = $products_query->whereHas('topics', function ($query) use ($request) {
    //                         $query->whereIn('products_categories.category_id', $request->category); // Specify table name to avoid ambiguity
    //                     });
    //                 }

    //             }
    //             $products = $products_query->paginate(5);
    //         } else {
    //             $products = $department->products;
    //         }
    //         $posts = Post::where('department_id', $department->id)->paginate(6);
    //         return view('front_office.departments.show_posts', compact('posts', 'products', 'categories'));
    //     }
    // }

    // public function show($id){
    //     $department = Department::findOrFail($id);

    //     $departments = Department::where('department_id' , $department->id)->with('sub_Departments', 'sub_Departments.sub_Departments')->paginate(6);
    //     if($departments->isNotEmpty()){

    //         return view('front_office.departments.show' , compact('departments'));
    //     }
    //     elseif($department->products->isNotEmpty()){

    //         return view('front_office.departments.show_product' , compact('departments'));
    //     }elseif($department->posts->isNotEmpty()){
    //         return view('front_office.departments.show_posts' , compact('departments'));

    //     }
    // }
}
