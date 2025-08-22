<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Models\GeneralComments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptimizedServiceController extends Controller
{
    /**
     * عرض الخدمة مع تحسين الاستعلامات
     */
    public function show($id)
    {
        // تحسين الاستعلام باستخدام eager loading
        $service = Services::with([
            'department.fields',
            'department.sub_departments',
            'comments.user',
            'images'
        ])->findOrFail($id);

        // تحسين استعلام التعليقات
        $comments = GeneralComments::with(['user'])
            ->where('commentable_id', $id)
            ->where('commentable_type', Services::class)
            ->orderBy('created_at', 'desc')
            ->get();

        // تحسين استعلام الأقسام المسموحة للمستخدم
        $user = auth()->user();
        $allowedMain = [];
        $allowedSub = [];

        if ($user && $user->role_id == 3) {
            // استخدام الطريقة المباشرة لتجنب مشاكل الـ linter
            $mainDeps = $user->userDepartments->where('commentable_type', \App\Models\Department::class)->pluck('commentable_id')->unique();
            $subDeps = $user->userDepartments->where('commentable_type', \App\Models\SubDepartment::class)->pluck('commentable_id')->unique();
            $allowedMain = $mainDeps->toArray();
            $allowedSub = $subDeps->toArray();
        }

        return view('front_office.services.show', compact(
            'service',
            'comments',
            'allowedDepartments'
        ));
    }

    /**
     * عرض جميع الخدمات مع تحسين الاستعلامات
     */
    public function index(Request $request)
    {
        // تحسين الاستعلام باستخدام pagination و eager loading
        $services = Services::with([
            'department',
            'user',
            'comments'
        ])
        ->when($request->department_id, function($query, $departmentId) {
            return $query->where('department_id', $departmentId);
        })
        ->when($request->city, function($query, $city) {
            return $query->where('from_city', 'like', "%{$city}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('front_office.services.index', compact('services'));
    }

    /**
     * البحث في الخدمات مع تحسين الأداء
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('services.index');
        }

        // تحسين استعلام البحث
        $services = Services::with(['department', 'user'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('from_city', 'like', "%{$query}%");
            })
            ->orWhereHas('department', function($q) use ($query) {
                $q->where('name_ar', 'like', "%{$query}%")
                  ->orWhere('name_en', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('front_office.services.search', compact('services', 'query'));
    }
}
