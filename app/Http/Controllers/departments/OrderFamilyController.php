<?php

namespace App\Http\Controllers\departments;

use App\Models\FamilyOrder;
use Illuminate\Http\Request;
use App\Models\FamilyService;
use App\Http\Controllers\Controller;

class OrderFamilyController extends Controller
{
        public function store(Request $request){
        $is_create = FamilyOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            FamilyService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = FamilyOrder::findOrFail($id);
        $service = FamilyService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.family' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = FamilyOrder::get();
        return view('front_office.orders.family_orders' ,compact('orders'));
    }
}
