<?php

namespace App\Http\Controllers\departments;

use App\Models\WaterOrder;
use App\Models\WaterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderWaterController extends Controller
{
    public function store(Request $request){
        $is_create = WaterOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            WaterService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = WaterOrder::findOrFail($id);
        $service = WaterService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.water' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = WaterOrder::get();
        return view('front_office.orders.water_orders' ,compact('orders'));
    }
}
