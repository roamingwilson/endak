<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Models\GeneralComments;
use App\Models\GeneralOrder;
use App\Models\Services;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;

class ApiGeneralOrderController extends Controller
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
        return response()->apiSuccess('success');
    }


    public function index()
    {
        $user = auth()->user();
        $user_id = $user->id;
        $orders = GeneralOrder::with('service')->where('user_id', $user_id)->latest()->paginate(10);


        $comments = GeneralComments::where('customer_id', $user_id);


        return response()->apiSuccess($orders,$user,$comments,'success');
    }
    public function show($id)
    {
        // Fetch the order by its ID
        $order = GeneralOrder::findOrFail($id);
        // dd($order->orderable_type, $order->orderable_id);
        $service = $order->service()->get();
        // dd($service);

        return response()->apiSuccess($order,$service,'success');
    }
    public function destroy($id)
    {
        $order = GeneralOrder::findOrFail($id);
        $order->delete();

        return response()->apiSuccess('success');
    }
}
