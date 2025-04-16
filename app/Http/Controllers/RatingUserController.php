<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\AdsOrder;
use App\Models\WaterOrder;
use App\Models\BigCarOrder;
use App\Models\FamilyOrder;
use App\Models\GardenOrder;
use App\Models\WorkerOrder;
use App\Models\TeacherOrder;
use Illuminate\Http\Request;
use App\Models\CarWaterOrder;
use App\Models\CleaningOrder;
use App\Models\PublicGeOrder;
use App\Models\ContractingOrder;
use App\Models\MaintenanceOrder;
use App\Models\FollowCameraOrder;
use App\Models\CounterInsectsOrder;
use App\Models\PartyPreparationOrder;
use App\Models\FurnitureTransportationOrder;

class RatingUserController extends Controller
{
    public function add_rate($id){
        $current_url = url()->previous();
        $url = explode('/', $current_url);
        $value1 = ['furniture_transportations'];
        $value2 = ['surveillance_cameras'];
        $value3 = ['party_preparation'];
        $value4 = ['garden'];
        $value5 = ['counter_insects'];
        $value6 = ['cleaning'];
        $value7 = ['teacher'];
        $value8 = ['family'];
        $value9 = ['worker'];
        $value10 = ['public_ge'];
        $value11 = ['ads'];
        $value12 = ['water'];
        $value13 = ['car_water'];
        $value14 = ['big_car'];
        $value15 = ['contracting'];
        $value16 = ['maintenance'];
        if (array_intersect($url, $value1)) {
            $order = FurnitureTransportationOrder::find($id);
            $department_name = 'furniture_transportations';
        }elseif(array_intersect($url, $value2)){
            $order = FollowCameraOrder::find($id);
            $department_name = 'surveillance_cameras';
        }
        elseif(array_intersect($url, $value3)){
            $order = PartyPreparationOrder::find($id);
            $department_name = 'party_preparation';
        }
        elseif(array_intersect($url, $value4)){
            $order = GardenOrder::find($id);
            $department_name = 'garden';
        }
        elseif(array_intersect($url, $value5)){
            $order = CounterInsectsOrder::find($id);
            $department_name = 'counter_insects';
        }
        elseif(array_intersect($url, $value6)){
            $order = CounterInsectsOrder::find($id);
            $department_name = 'cleaning';
        }
        elseif(array_intersect($url, $value7)){
            $order = TeacherOrder::find($id);
            $department_name = 'teacher';
        }
        elseif(array_intersect($url, $value8)){
            $order = FamilyOrder::find($id);
            $department_name = 'family';
        }
        elseif(array_intersect($url, $value9)){
            $order = WorkerOrder::find($id);
            $department_name = 'worker';
        }
        elseif(array_intersect($url, $value10)){
            $order = PublicGeOrder::find($id);
            $department_name = 'public_ge';
        }
        elseif(array_intersect($url, $value11)){
            $order = AdsOrder::find($id);
            $department_name = 'ads';
        }
        elseif(array_intersect($url, $value12)){
            $order = WaterOrder::find($id);
            $department_name = 'water';
        }
        elseif(array_intersect($url, $value13)){
            $order = CarWaterOrder::find($id);
            $department_name = 'car_water';
        }
        elseif(array_intersect($url, $value14)){
            $order = BigCarOrder::find($id);
            $department_name = 'big_car';
        }
        elseif(array_intersect($url, $value15)){
            $order = ContractingOrder::find($id);
            $department_name = 'contracting';
        }
        elseif(array_intersect($url, $value16)){
            $order = MaintenanceOrder::find($id);
            $department_name = 'maintenance';
        }
        else{
            $order = Order::findOrFail($id);
            $department_name = 'general';
        }

        return view('front_office.rate.add_rate' , compact('order' , 'id' , 'department_name')) ;
    }

    public function store(Request $request){
        $request->validate([
            'order_id' => 'required',
            'professionalism_in_dealing' => "required",
            'communication_and_follow_up' => "required",
            'quality_of_work_delivered' => 'required',
            'experience_in_the_project_field' => 'required',
            'delivery_on_time' => 'required',
            'deal_with_him_again' => 'required',
        ]);

        if($request->department_name == 'furniture_transportations'){

            $order = FurnitureTransportationOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'surveillance_cameras'){
            $order = FollowCameraOrder::where('id' , $request->order_id)->first();

        }
        elseif($request->department_name == 'party_preparation'){
            $order = PartyPreparationOrder::where('id' , $request->order_id)->first();

        }
        elseif($request->department_name == 'garden'){
            $order = GardenOrder::where('id' , $request->order_id)->first();

        }
        elseif($request->department_name == 'counter_insects'){
            $order = CounterInsectsOrder::where('id' , $request->order_id)->first();

        }
        elseif($request->department_name == 'cleaning'){
            $order = CleaningOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'teacher'){
            $order = TeacherOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'family'){
            $order = FamilyOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'worker'){
            $order = WorkerOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'public_ge'){
            $order = PublicGeOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'ads'){
            $order = AdsOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'water'){
            $order = WaterOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'car_water'){
            $order = CarWaterOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'big_car'){
            $order = BigCarOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'contracting'){
            $order = ContractingOrder::where('id' , $request->order_id)->first();
        }
        elseif($request->department_name == 'maintenance'){
            $order = MaintenanceOrder::where('id' , $request->order_id)->first();
        }
        else{

            $order = Order::where('id' , $request->order_id)->first();
        }
        $data = $request->all();
        $data['creator_id'] = auth()->user()->id;
        $data['user_id'] = $order->service_provider_id;
        $data = $request->all();
        $rates = 0;
        $rates += (int)$data['professionalism_in_dealing'];
        $rates += (int)$data['communication_and_follow_up'];
        $rates += (int)$data['quality_of_work_delivered'];
        $rates += (int)$data['experience_in_the_project_field'];
        $rates += (int)$data['delivery_on_time'];
        $rates += (int)$data['deal_with_him_again'];

        $rate = Rating::create([
            'order_id' => $request->order_id,
            'department_name' => $request->department_name ?? 'general',
            'creator_id' => auth()->user()->id,
            'user_id' =>2,
            'professionalism_in_dealing' => (int)$data['professionalism_in_dealing'],
            'communication_and_follow_up' => (int)$data['communication_and_follow_up'],
            'quality_of_work_delivered' => (int)$data['quality_of_work_delivered'],
            'experience_in_the_project_field' => (int)$data['experience_in_the_project_field'],
            'deal_with_him_again' => (int)$data['deal_with_him_again'],
            'delivery_on_time' => (int)$data['delivery_on_time'],
            'rate' => $rates > 0 ? number_format($rates, 2) / 6 : 0,
            'comment' => $data['comment'],
            'created_at' => time(),
        ]);
        return redirect()->route('home')->with('success', "Add Rating successfully");
    }
}
