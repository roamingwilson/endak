<?php

namespace App\Http\Controllers\departments;

use Illuminate\Http\Request;
use App\Models\CounterInsectsOrder;
use App\Http\Controllers\Controller;
use App\Models\CounterInsectsService;

class OrderCounterInsectsController extends Controller
{
    public function store(Request $request){
        $is_create = CounterInsectsOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            CounterInsectsService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = CounterInsectsOrder::findOrFail($id);
        $service = CounterInsectsService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.counter_insects' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = CounterInsectsOrder::get();
        return view('front_office.orders.insects_orders' ,compact('orders'));
    }
}
