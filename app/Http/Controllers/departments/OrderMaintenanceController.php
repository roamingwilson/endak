<?php

namespace App\Http\Controllers\departments;

use Illuminate\Http\Request;
use App\Models\MaintenanceOrder;
use App\Models\MaintenanceService;
use App\Http\Controllers\Controller;

class OrderMaintenanceController extends Controller
{
    public function store(Request $request){
        $is_create = MaintenanceOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            MaintenanceService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = MaintenanceOrder::findOrFail($id);
        $service = MaintenanceService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.maintenance' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = MaintenanceOrder::get();
        return view('front_office.orders.maintenance_orders' ,compact('orders'));
    }
}
