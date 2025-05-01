<?php

namespace App\Http\Controllers\departments;

use App\Http\Controllers\Controller;
use App\Models\HeavyEquipmentOrder;
use App\Models\HeavyEquipmentservice;
use Illuminate\Http\Request;

class OrderHeavyEquipController extends Controller
{
    public function store(Request $request){
        $is_create = HeavyEquipmentOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            HeavyEquipmentservice::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = HeavyEquipmentOrder::findOrFail($id);
        $service = HeavyEquipmentservice::withoutGlobalScope('status')->where('id',$order->service_id)->first();

        return view('front_office.orders.heavy_equipment' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = HeavyEquipmentOrder::get();
        return view('front_office.orders.heavy_equip_orders' ,compact('orders'));
    }
}
