<?php

namespace App\Http\Controllers\departments;

use App\Models\GardenOrder;
use Illuminate\Http\Request;
use App\Models\GardenService;
use App\Http\Controllers\Controller;

class OrderGardenController extends Controller
{
    public function store(Request $request){
        // dd($request->all());
        $is_create = GardenOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            GardenService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = GardenOrder::findOrFail($id);
        $service = GardenService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.garden' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = GardenOrder::get();
        return view('front_office.orders.garden_orders' ,compact('orders'));
    }
}
