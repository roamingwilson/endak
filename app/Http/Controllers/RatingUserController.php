<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\AdsOrder;
use App\Models\AirCondition;
use App\Models\AirConditionOrder;
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
use App\Models\GeneralOrder;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentOrder;
use App\Models\SparePartOrder;
use App\Models\SpareParts;
use App\Models\VanTruck;

class RatingUserController extends Controller
{
    // public function add_rate($id){
    //     $current_url = url()->previous();
    //     $url = explode('/', $current_url);
    //     $value1 = ['furniture_transportations'];
    //     $value2 = ['surveillance_cameras'];
    //     $value3 = ['party_preparation'];
    //     $value4 = ['garden'];
    //     $value5 = ['counter_insects'];
    //     $value6 = ['cleaning'];
    //     $value7 = ['teacher'];
    //     $value8 = ['family'];
    //     $value9 = ['worker'];
    //     $value10 = ['public_ge'];
    //     $value11 = ['ads'];
    //     $value12 = ['water'];
    //     $value13 = ['car_water'];
    //     $value14 = ['big_car'];
    //     $value15 = ['contracting'];
    //     $value16 = ['maintenance'];
    //     $value17 = ['heavy_equip'];
    //     $value18 = ['spare_part'];
    //     $value19 = ['air_con'];
    //     $value20 = ['van_truck'];
    //     if (array_intersect($url, $value1)) {
    //         $order = FurnitureTransportationOrder::find($id);
    //         $department_name = 'furniture_transportations';
    //     }elseif(array_intersect($url, $value2)){
    //         $order = FollowCameraOrder::find($id);
    //         $department_name = 'surveillance_cameras';
    //     }
    //     elseif(array_intersect($url, $value3)){
    //         $order = PartyPreparationOrder::find($id);
    //         $department_name = 'party_preparation';
    //     }
    //     elseif(array_intersect($url, $value4)){
    //         $order = GardenOrder::find($id);
    //         $department_name = 'garden';
    //     }
    //     elseif(array_intersect($url, $value5)){
    //         $order = CounterInsectsOrder::find($id);
    //         $department_name = 'counter_insects';
    //     }
    //     elseif(array_intersect($url, $value6)){
    //         $order = CounterInsectsOrder::find($id);
    //         $department_name = 'cleaning';
    //     }
    //     elseif(array_intersect($url, $value7)){
    //         $order = TeacherOrder::find($id);
    //         $department_name = 'teacher';
    //     }
    //     elseif(array_intersect($url, $value8)){
    //         $order = FamilyOrder::find($id);
    //         $department_name = 'family';
    //     }
    //     elseif(array_intersect($url, $value9)){
    //         $order = WorkerOrder::find($id);
    //         $department_name = 'worker';
    //     }
    //     elseif(array_intersect($url, $value10)){
    //         $order = PublicGeOrder::find($id);
    //         $department_name = 'public_ge';
    //     }
    //     elseif(array_intersect($url, $value11)){
    //         $order = AdsOrder::find($id);
    //         $department_name = 'ads';
    //     }
    //     elseif(array_intersect($url, $value12)){
    //         $order = WaterOrder::find($id);
    //         $department_name = 'water';
    //     }
    //     elseif(array_intersect($url, $value13)){
    //         $order = CarWaterOrder::find($id);
    //         $department_name = 'car_water';
    //     }
    //     elseif(array_intersect($url, $value14)){
    //         $order = BigCarOrder::find($id);
    //         $department_name = 'big_car';
    //     }
    //     elseif(array_intersect($url, $value15)){
    //         $order = ContractingOrder::find($id);
    //         $department_name = 'contracting';
    //     } elseif (array_intersect($url, $value16)) {
    //         $order = MaintenanceOrder::find($id);
    //         $department_name = 'maintenance';
    //     }
    //     elseif(array_intersect($url, $value17)){
    //         $order = HeavyEquipmentOrder::find($id);
    //         $department_name = 'heavy_equip';
    //     }
    //     elseif(array_intersect($url, $value18)){
    //         $order = SparePartOrder::find($id);
    //         $department_name = 'spare_part';
    //     }
    //     elseif(array_intersect($url, $value19)){
    //         $order = AirConditionOrder::find($id);
    //         $department_name = 'air_con';
    //     }
    //     elseif(array_intersect($url, $value19)){
    //         $order = VanTruck::find($id);
    //         $department_name = 'van_truck';
    //     }
    //     else{
    //         $order = Order::findOrFail($id);
    //         $department_name = 'general';
    //     }

    //     return view('front_office.rate.add_rate' , compact('order' , 'id' , 'department_name')) ;
    // }

    public function add_rate($id)
    {
        $order = GeneralOrder::findOrFail($id);

        if (auth()->id() != $order->user_id) {
            abort(403);
        }

        return view('front_office.rate.add_rate', compact('order'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required',
            'professionalism_in_dealing' => 'required',
            'communication_and_follow_up' => 'required',
            'quality_of_work_delivered' => 'required',
            'experience_in_the_project_field' => 'required',
            'delivery_on_time' => 'required',
            'deal_with_him_again' => 'required',
        ]);

        if ($request->order_id != $id) {
            abort(400, 'Invalid Order ID.');
        }

        $order = GeneralOrder::findOrFail($id);
        if (Rating::where('order_id', $order->id)->exists()) {
            return back()->with('error', __('لقد قمت بتقييم هذا الطلب من قبل'));
        }
        // حماية من التقييم المكرر
        // if (Rating::where('order_id', $id)->where('creator_id', auth()->id())->exists()) {
        //     return back()->withErrors(['error' => 'لقد قمت بتقييم هذا الطلب مسبقًا.']);
        // }

        $data = $request->all();
        $rates = (int) $data['professionalism_in_dealing']
            + (int) $data['communication_and_follow_up']
            + (int) $data['quality_of_work_delivered']
            + (int) $data['experience_in_the_project_field']
            + (int) $data['delivery_on_time']
            + (int) $data['deal_with_him_again'];

        Rating::create([
            'order_id' => $request->order_id,
            'department_name' => $request->department_name ?? 'general',
            'creator_id' => auth()->id(),
            'user_id' => $order->service_provider_id,
            'professionalism_in_dealing' => (int) $data['professionalism_in_dealing'],
            'communication_and_follow_up' => (int) $data['communication_and_follow_up'],
            'quality_of_work_delivered' => (int) $data['quality_of_work_delivered'],
            'experience_in_the_project_field' => (int) $data['experience_in_the_project_field'],
            'deal_with_him_again' => (int) $data['deal_with_him_again'],
            'delivery_on_time' => (int) $data['delivery_on_time'],
            'rate' => round($rates / 6, 2),
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()->route('home')->with('success', 'تمت إضافة التقييم بنجاح');
    }
}
