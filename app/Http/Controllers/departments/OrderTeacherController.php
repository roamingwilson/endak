<?php

namespace App\Http\Controllers\departments;

use App\Models\TeacherOrder;
use Illuminate\Http\Request;
use App\Models\TeacherService;
use App\Http\Controllers\Controller;

class OrderTeacherController extends Controller
{
    public function store(Request $request){
        $is_create = TeacherOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            TeacherService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = TeacherOrder::findOrFail($id);
        $service = TeacherService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.teacher' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = TeacherOrder::get();
        return view('front_office.orders.teacher_orders' ,compact('orders'));
    }
}
