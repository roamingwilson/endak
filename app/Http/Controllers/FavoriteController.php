<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle($departmentId)
    {
        $user = Auth::user();
        $department = Department::findOrFail($departmentId);

        if ($user->favoriteDepartments()->where('favorites.department_id', $departmentId)->exists()) {
            $user->favoriteDepartments()->detach($departmentId);
            return back()->with('message', 'تم إزالة القسم من المفضلة');
        } else {
            $user->favoriteDepartments()->attach($departmentId);
            return back()->with('message', 'تم إضافة القسم إلى المفضلة');
        }
    }
    public function index()
    {
        $favorites = Auth::user()->favoriteDepartments;
        return view('favorites.index', compact('favorites'));
    }
}
