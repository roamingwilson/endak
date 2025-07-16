<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $departments = Department::with('sub_Departments', 'inputs')->where('status', 1)->get();
        return view('front_office.departments.show', compact('departments'));
    }
}
