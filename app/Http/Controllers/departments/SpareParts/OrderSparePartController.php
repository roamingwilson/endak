<?php

namespace App\Http\Controllers\departments\SpareParts;

use App\Http\Controllers\Controller;
use App\Models\SparePartOrder;
use App\Models\SparePartServices;
use Illuminate\Http\Request;

class OrderSparePartController extends Controller
{
    public function store(Request $request){
        $is_create = SparePartOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            SparePartServices::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = SparePartOrder::findOrFail($id);
        $service = SparePartServices::withoutGlobalScope('status')->where('id',$order->service_id)->first();

        return view('front-office.orders.spare_parts' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = SparePartOrder::get();
        return view('front_office.orders.spare_parts_orders' ,compact('orders'));
    }
}
