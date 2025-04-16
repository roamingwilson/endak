<?php

namespace App\Http\Controllers\departments;

use Illuminate\Http\Request;
use App\Models\ContractingOrder;
use App\Models\ContractingService;
use App\Http\Controllers\Controller;

class OrderContractingController extends Controller
{
    public function store(Request $request){
        $is_create = ContractingOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            ContractingService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = ContractingOrder::findOrFail($id);
        $service = ContractingService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.contracting' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = ContractingOrder::get();
        return view('front_office.orders.contracting_orders' ,compact('orders'));
    }
}
