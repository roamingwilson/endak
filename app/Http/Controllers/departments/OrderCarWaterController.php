<?php

namespace App\Http\Controllers\departments;

use Illuminate\Http\Request;
use App\Models\CarWaterOrder;
use App\Models\CarWaterService;
use App\Http\Controllers\Controller;

class OrderCarWaterController extends Controller
{
    public function store(Request $request){
        $is_create = CarWaterOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            CarWaterService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = CarWaterOrder::findOrFail($id);
        $service = CarWaterService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.car_water' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = CarWaterOrder::get();
        return view('front_office.orders.car_water_orders' ,compact('orders'));
    }
}
