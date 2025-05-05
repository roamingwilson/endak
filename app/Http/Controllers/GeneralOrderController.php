<?php

namespace App\Http\Controllers;

use App\Models\AdsService;
use App\Models\AirConditionService;
use App\Models\BigCarService;
use App\Models\CarWaterService;
use App\Models\CleaningService;
use App\Models\ContractingService;
use App\Models\CounterInsectsService;
use App\Models\FamilyService;
use App\Models\FollowCameraService;
use App\Models\FurnitureTransportationService;
use App\Models\GardenService;
use App\Models\GeneralComments;
use App\Models\GeneralOrder;
use App\Models\HeavyEquipmentService;
use App\Models\PartyPreparationService;
use App\Models\PublicGeService;
use App\Models\Services;
use App\Models\SparePartServices;
use App\Models\TeacherService;
use App\Models\User;
use App\Models\VanTruckService;
use App\Models\WaterService;
use App\Models\WorkerService;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;

class GeneralOrderController extends Controller
{



    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'service_provider_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,completed,cancel',
        ]);

        $provider = User::findOrFail($request->service_provider_id);
        // DD($validated);
       $order = GeneralOrder::create($validated);
         // تحديث حالة الخدمة إلى pending
        $service = Services::findOrFail($validated['service_id']);
        $service->update([
            'status' => 'pending',
        ]);
        if ($order) {
            $provider->notify(new CommentNotification([
                'id' => $order->id,
                'title' => "وافق $user->first_name  علي عرضك",
                'body' => "$order->notes",
                'url' => route('notifications.index'),
            ]));
        }
        return redirect()->route('home')->with('success', '  تم قبول العرض');
    }


    public function index()
    {
        $user = auth()->user();
        $user_id = $user->id;
        $orders = GeneralOrder::with('service')->where('user_id', $user_id)->latest()->paginate(10);


        $comments = GeneralComments::where('customer_id', $user_id);


        return view('front_office.orders.general_orders', compact('orders','user','comments'));
    }
    public function previous(){
        // dd('ok');
        $user = auth()->user();
        $user_id = $user->id;
        $orders = GeneralOrder::with('service')->where('user_id', $user_id)->latest()->paginate(10);


        $comments = GeneralComments::where('customer_id', $user_id);
        return view('front_office.orders.complete_general_orders', compact('orders', 'user', 'comments'));
    }
    public function show($id)
    {
        // Fetch the order by its ID
        $order = GeneralOrder::findOrFail($id);
        // dd($order->orderable_type, $order->orderable_id);
        $service = $order->service()->get();
        // dd($service);

        return view('front_office.orders.show_general_orders', compact('order','service'));
    }
    public function destroy($id)
    {
        $order = GeneralOrder::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success', 'تم حذف الطلب بنجاح.');
    }
 }
