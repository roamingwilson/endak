<?php

namespace App\Http\Controllers\departments\Aircondition;

use App\Http\Controllers\Controller;
use App\Models\AirCondition;
use App\Models\AirConditionOrder;
use App\Models\AirConditionService;
use Illuminate\Http\Request;

class OrderAirconController extends Controller
{
    public function store(Request $request){
        $is_create = AirConditionOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            AirConditionService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = AirConditionOrder::findOrFail($id);
        $service = AirConditionService::withoutGlobalScope('status')->where('id',$order->service_id)->first();

        return view('front_office.orders.air_con' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = AirConditionOrder::get();
        return view('front_office.orders.air_con_orders' ,compact('orders'));
    }
}
