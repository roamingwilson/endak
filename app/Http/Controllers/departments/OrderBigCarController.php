<?php

namespace App\Http\Controllers\departments;

use App\Models\BigCarOrder;
use Illuminate\Http\Request;
use App\Models\BigCarService;
use App\Http\Controllers\Controller;

class OrderBigCarController extends Controller
{
    public function store(Request $request){
        $is_create = BigCarOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            BigCarService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = BigCarOrder::findOrFail($id);
        $service = BigCarService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.big_car' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = BigCarOrder::get();
        return view('front_office.orders.big_car_orders' ,compact('orders'));
    }
}
