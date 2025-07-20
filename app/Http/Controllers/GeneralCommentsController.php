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
    public function store(Request $request)
    {
        // تحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login-page')->with('error', 'يجب تسجيل الدخول أولاً لتقديم عرض.');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'body' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'day' => 'nullable|string|max:100',
            'number_of_days_of_warranty' => 'nullable|integer|min:0',
        ]);

        // جلب الخدمة
        $service = Services::findOrFail($validated['service_id']);

        // تحقق من الاشتراك في القسم أو القسم الفرعي
        $user = auth()->user();
        $allowedMain = $user->getAllDepartments()['main']->pluck('id')->toArray();
        $allowedSub = $user->getAllDepartments()['sub']->pluck('id')->toArray();
        $serviceDepartmentId = $service->department_id ?? null;
        $serviceSubDepartmentId = $service->sub_department_id ?? null;
        if ($serviceSubDepartmentId) {
            // الخدمة مرتبطة بقسم فرعي: يجب أن يكون مشترك في هذا الفرع تحديدًا
            if (!in_array($serviceSubDepartmentId, $allowedSub)) {
                return redirect()->back()->with('error', 'غير مسموح لك بتقديم عرض في هذا القسم الفرعي.');
            }
        } else {
            // الخدمة مرتبطة بقسم رئيسي فقط: يجب أن يكون مشترك في الرئيسي
            if (!in_array($serviceDepartmentId, $allowedMain)) {
                return redirect()->back()->with('error', 'غير مسموح لك بتقديم عرض في هذا القسم.');
            }
        }

        // العميل صاحب الخدمة
        $customer = User::findOrFail($service->user_id);

        // مزود الخدمة (المستخدم الحالي)
        $existingComment = GeneralComments::where('commentable_id', $validated['service_id'])
            ->where('service_provider', $user->id)
            ->first();
        if ($existingComment) {
            return redirect()->back()->with('error', 'لقد قمت بالفعل بتقديم عرض/تعليق على هذه الخدمة.');
        }
        // إنشاء العرض
        $comment = new GeneralComments([
            'service_provider' => $user->id,
            'customer_id' => $customer->id,
            'body' => $validated['body'] ?? null,
            'price' => $validated['price'] ?? null,
            'date' => $validated['date'] ?? null,
            'time' => $validated['time'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'city' => $validated['city'] ?? null,
            'location' => $validated['location'] ?? null,
            'day' => $validated['day'] ?? null,
            'number_of_days_of_warranty' => $validated['number_of_days_of_warranty'] ?? null,
        ]);

        // ربط العرض بالخدمة
        $service->comments()->save($comment);

        // إرسال إشعار للعميل إذا تم حفظ العرض
        if ($comment) {
            $customer->notify(new CommentNotification([
                'id' => $comment->id,
                'title' => "قدم $user->first_name لك عرضًا",
                'body' => $comment->notes,
                'url' => route('notifications.index'),
            ]));
        }

        return redirect()->route('home')->with('success', 'تم تقديم العرض بنجاح');
    }
    public function index($id)
    {
        $comments = GeneralComments::where('customer_id', $id)
            ->with('commentable')->latest()->paginate(10);
        // ->paginate(5);


        $service = $comments->map(function ($comment) {
            return $comment->commentable;
        });

        return view('front_office.comments.my_offers', compact('comments', 'service'));
    }
    public function edit($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login-page')->with('error', 'يجب تسجيل الدخول أولاً.');
        }
        $comment = GeneralComments::findOrFail($id);

        // تحقق من أن المستخدم الحالي هو صاحب التعليق
        if (auth()->id() !== $comment->service_provider) {
            abort(403, 'Unauthorized action.');
        }

        return view('front_office.general_comments.edit', compact('comment'));
    }
    public function update(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login-page')->with('error', 'يجب تسجيل الدخول أولاً.');
        }
        $comment = GeneralComments::findOrFail($id);
        $comment->update([
            'price' => $request->price,
            'notes' => $request->notes,
        ]);

        // إرسال إشعار لصاحب الطلب (العميل)
        if ($comment->customer) {
            $comment->customer->notify(new \App\Notifications\CommentNotification($comment));
        }

        return redirect()->route('home')->with('success', 'تم التحديث بنجاح');
    }
    public function destroy($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login-page')->with('error', 'يجب تسجيل الدخول أولاً.');
        }
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
