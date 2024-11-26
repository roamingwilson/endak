<?php

namespace App\Http\Controllers\Furniture;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\CommentNotification;
use App\Models\FurnitureTransportationOrder;
use App\Models\FurnitureTransportationService;

class OrderFurnitureTransportationsController extends Controller
{
    public function store(Request $request){
        
        $is_create = FurnitureTransportationOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            FurnitureTransportationService::find($request->service_id)->update(['status' => "pending"]);
        }
        if($is_create){
            $provider = User::where('id' , $request->service_provider_id)->first();
            $user = User::where('id' , $request->customer_id)->first();
            $provider->notify(new CommentNotification([
                'id' => $is_create->id,
                'title' => "وافق $user->full_name  علي عرضك",
                'body' => "",
                'url' => route('show_order_furniture' , $is_create->id)
            ]));
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = FurnitureTransportationOrder::findOrFail($id);
        return view('front_office.orders.furniture_transportatio_order' ,compact('order'));
    }
    public function show_orders(){
        $orders = FurnitureTransportationOrder::get();
        return view('front_office.orders.furniture_transportatio_orders' ,compact('orders'));
    }
}
