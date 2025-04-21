<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class notificationController extends Controller
{
    public function index()
{
    $notifications = auth()->user()->notifications; // استرجاع جميع الإشعارات للمستخدم
    return view('front_office.notify.show', compact('notifications'));
}
}
