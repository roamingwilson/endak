<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CleaningOrder;
use App\Models\CleaningService;

class OrderCleaningController extends Controller
{
    public function store(Request $request){
        $is_create = CleaningOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            CleaningService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = CleaningOrder::findOrFail($id);
        $service = CleaningService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.cleaning' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = CleaningOrder::get();
        return view('front_office.orders.cleaning_orders' ,compact('orders'));
    }
}
