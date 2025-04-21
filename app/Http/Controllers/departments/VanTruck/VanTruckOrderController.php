<?php

namespace App\Http\Controllers\departments\VanTruck;

use App\Http\Controllers\Controller;
use App\Models\VanTruckOrder;
use App\Models\VanTruckService;
use Illuminate\Http\Request;

class VanTruckOrderController extends Controller
{
        public function store(Request $request){
            $is_create = VanTruckOrder::create([
                'service_id'    => $request->service_id,
                'customer_id'    => $request->customer_id,
                'service_provider_id'    => $request->service_provider_id,
            ]);
        if ($is_create) {
            $service = VanTruckService::find($request->service_id);

            if ($service) {
                $service->update(['status' => "pending"]);
            } else {
                return redirect()->back()->with('error', 'الخدمة غير موجودة');
            }
        }
            return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
        }
    public function show($id){
        $order = VanTruckOrder::findOrFail($id);
        $service = VanTruckService::withoutGlobalScope('status')->where('id',$order->service_id)->first();

        return view('front_office.orders.contracting' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = VanTruckOrder::get();
        return view('front_office.orders.contracting_orders' ,compact('orders'));
    }
}
