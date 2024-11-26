<?php

namespace App\Http\Controllers\Surveillance;

use Illuminate\Http\Request;
use App\Models\FollowCameraOrder;
use App\Models\FollowCameraService;
use App\Http\Controllers\Controller;

class OrderSurveillanceCamerasController extends Controller
{
    public function store(Request $request){
        $is_create = FollowCameraOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            FollowCameraService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = FollowCameraOrder::findOrFail($id);
        $service = FollowCameraService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.surveillance_cameras' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = FollowCameraOrder::get();
        return view('front_office.orders.camera' ,compact('orders'));
    }
}
