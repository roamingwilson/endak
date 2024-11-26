<?php

namespace App\Http\Controllers\departments;

use Illuminate\Http\Request;
use App\Models\PublicGeOrder;
use App\Models\PublicGeService;
use App\Http\Controllers\Controller;

class OrderPublicGeController extends Controller
{
    public function store(Request $request){
        $is_create = PublicGeOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            PublicGeService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = PublicGeOrder::findOrFail($id);
        $service = PublicGeService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.public_ge' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = PublicGeOrder::get();
        return view('front_office.orders.public_ge_orders' ,compact('orders'));
    }
}
