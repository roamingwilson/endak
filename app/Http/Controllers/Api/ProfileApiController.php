<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileApiController extends Controller
{
    public function index($id){
        $user = User::with('rate')->find($id);
        return response()->apiSuccess($user);
    }
}
