<?php

namespace App\Http\Controllers\departments;

use App\Models\WorkerOrder;
use Illuminate\Http\Request;
use App\Models\WorkerService;
use App\Http\Controllers\Controller;

class OrderWorkerController extends Controller
{
    public function store(Request $request){
        $is_create = WorkerOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            WorkerService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = WorkerOrder::findOrFail($id);
        $service = WorkerService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.worker' ,compact('order' , 'service'));
    }
    public function show_orders(){
        $orders = WorkerOrder::get();
        return view('front_office.orders.worker_orders' ,compact('orders'));
    }
}
