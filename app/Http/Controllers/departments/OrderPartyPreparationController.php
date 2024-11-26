<?php

namespace App\Http\Controllers\departments;

use App\Models\PartyPreparationOrder;
use Illuminate\Http\Request;
use App\Models\PartyPreparation;
use App\Http\Controllers\Controller;
use App\Models\PartyPreparationService;

class OrderPartyPreparationController extends Controller
{
    public function store(Request $request){
        $is_create = PartyPreparationOrder::create([
            'service_id'    => $request->service_id,
            'customer_id'    => $request->customer_id,
            'service_provider_id'    => $request->service_provider_id,
        ]);
        if($is_create){
            PartyPreparationService::find($request->service_id)->update(['status' => "pending"]);
        }
        return redirect()->route('home')->with('success' , 'تم قبول العرض بنجاح');
    }
    public function show($id){
        $order = PartyPreparationOrder::findOrFail($id);
        $service = PartyPreparationService::withoutGlobalScope('status')->where('id',$order->service_id)->first();
         
        return view('front_office.orders.party_preparation' ,compact('order' , 'service'));
    }
}
