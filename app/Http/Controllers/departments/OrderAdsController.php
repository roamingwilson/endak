<?php

namespace App\Http\Controllers\departments;

use App\Models\AdsOrder;
use App\Models\AdsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderAdsController extends Controller
{
    public function store(Request $request){
        $is_create = AdsOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            AdsService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = AdsOrder::findOrFail($id);
        $service = AdsService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.ads' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = AdsOrder::get();
        return view('front_office.orders.ads_orders' ,compact('orders'));
    }
}
