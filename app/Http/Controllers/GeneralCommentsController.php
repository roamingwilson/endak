<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdsService;
use App\Models\AirCondition;
use App\Models\AirConditionService;
use App\Models\WaterService;
use Illuminate\Http\Request;
use App\Models\BigCarService;
use App\Models\FamilyService;
use App\Models\GardenService;
use App\Models\WorkerService;
use App\Models\TeacherService;
use App\Models\CarWaterService;
use App\Models\CleaningService;
use App\Models\GeneralComments;
use App\Models\PublicGeService;
use App\Models\ContractingService;
use App\Models\FollowCameraService;
use App\Models\CounterInsectsService;
use App\Models\PartyPreparationService;
use App\Notifications\CommentNotification;
use App\Models\FurnitureTransportationService;
use App\Models\GeneralOrder;
use App\Models\HeavyEquipment;
use App\Models\HeavyEquipmentService;
use App\Models\Maintenance;
use App\Models\MaintenanceService;
use App\Models\Services;
use App\Models\SpareParts;
use App\Models\SparePartServices;
use App\Models\VanTruckService;

class GeneralCommentsController extends Controller
{
    public function store(Request $request){

        $service = Services::findOrFail($request->service_id);

    // العميل اللي طالب الخدمة
        $customer = User::findOrFail($service->user_id);

        $user = auth()->user();

        $comment = new GeneralComments([
            'service_provider'                      => $user->id,
            'customer_id'                           => $customer->id,
            'body'                                  => $request->body  ?? null,
            'price'                                 => $request->price  ?? null,
            'date'                                  => $request->date  ?? null,
            'time'                                  => $request->time  ?? null,
            'notes'                                 => $request->notes  ?? null,
            'city'                                  => $request->city  ?? null,
            'location'                              => $request->location  ?? null,
            'day'                                   => $request->day  ?? null,
            'number_of_days_of_warranty'            => $request->number_of_days_of_warranty  ?? null,

        ]);
        $service->comments()->save($comment);

        // $type = ;
        if($comment){
            $customer->notify(new CommentNotification([
                'id' => $comment->id,
                'title' => "قدم $user->first_name  لك عرضا",
                'body' => "$comment->notes",
                'url' => route('notifications.index'),
            ]));
        }
        return redirect()->route('home')->with('success','تم تقديم العرض بنجاح');
    }
    public function index($id){
        $comments = GeneralComments::where('customer_id', $id)
            ->with('commentable')->latest()->paginate(10);
        // ->paginate(5);


        $service = $comments->map(function ($comment) {
            return $comment->commentable;

        });

return view('front_office.comments.my_offers', compact('comments' ,'service'));
    }
    public function edit($id)
{
    $comment = GeneralComments::findOrFail($id);

    // تحقق من أن المستخدم الحالي هو صاحب التعليق
    if (auth()->id() !== $comment->service_provider) {
        abort(403, 'Unauthorized action.');
    }

    return view('front_office.general_comments.edit', compact('comment'));
}
public function update(Request $request, $id)
{
    $comment = GeneralComments::findOrFail($id);
    $comment->update([
        'price' => $request->price,
        'notes' => $request->notes,
    ]);
    return redirect()->route('home')->with('success', 'تم التحديث بنجاح');
}
public function destroy($id)
{
    $comment = GeneralComments::findOrFail($id);

    // تحقق من أن المستخدم هو نفس الشخص الذي قام بإنشاء التعليق
    if (auth()->id() !== $comment->service_provider) {
        abort(403, 'Unauthorized action.');
    }

    // حذف التعليق
    $comment->delete();

    // إرجاع المستخدم إلى الصفحة السابقة مع رسالة نجاح
    return redirect()->route('home')->with('success', 'Comment deleted successfully.');
}



}
