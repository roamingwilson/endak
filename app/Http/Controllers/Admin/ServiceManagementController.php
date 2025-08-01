<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\GeneralOrder;
use App\Models\GeneralComments;
use App\Models\User;
use App\Models\Department;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceManagementController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية للخدمات
     */
    public function dashboard()
    {
        $stats = [
            'total_services' => Services::count(),
            'open_services' => Services::where('status', 'open')->count(),
            'pending_services' => Services::where('status', 'pending')->count(),
            'completed_services' => Services::where('status', 'confirm')->count(),
            'total_orders' => GeneralOrder::count(),
            'pending_orders' => GeneralOrder::where('status', 'pending')->count(),
            'completed_orders' => GeneralOrder::where('status', 'completed')->count(),
        ];

        $recent_services = Services::with(['user', 'department'])
            ->latest()
            ->take(5)
            ->get();

        $recent_orders = GeneralOrder::with(['service', 'user', 'service_provider'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.service_management.dashboard', compact('stats', 'recent_services', 'recent_orders'));
    }

    /**
     * عرض جميع الخدمات المطلوبة
     */
    public function services(Request $request)
    {
        $query = Services::with(['user', 'department', 'subDepartment', 'provider']);

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // فلترة حسب القسم
        if ($request->has('department_id') && $request->department_id !== '') {
            $query->where('department_id', $request->department_id);
        }

        // فلترة حسب المدينة
        if ($request->has('city') && $request->city !== '') {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $services = $query->latest()->paginate(15);
        $departments = Department::all();

        return view('admin.service_management.services', compact('services', 'departments'));
    }

    /**
     * عرض تفاصيل خدمة معينة
     */
    public function showService($id)
    {
        $service = Services::with(['user', 'department', 'subDepartment', 'provider', 'images', 'comments'])
            ->findOrFail($id);

        $orders = GeneralOrder::where('service_id', $id)
            ->with(['user', 'service_provider'])
            ->get();

        return view('admin.service_management.show_service', compact('service', 'orders'));
    }

    /**
     * تحديث حالة الخدمة
     */
    public function updateServiceStatus(Request $request, $id)
    {
        $service = Services::findOrFail($id);
        $service->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'تم تحديث حالة الخدمة بنجاح');
    }

    /**
     * حذف خدمة
     */
    public function deleteService($id)
    {
        $service = Services::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.service_management.services')->with('success', 'تم حذف الخدمة بنجاح');
    }

    /**
     * عرض جميع العروض المقدمة
     */
    public function orders(Request $request)
    {
        $query = GeneralComments::with(['user', 'commentable'])
            ->where('commentable_type', Services::class);

        // فلترة حسب مزود الخدمة
        if ($request->has('provider_id') && $request->provider_id !== '') {
            $query->where('service_provider', $request->provider_id);
        }

        // فلترة حسب السعر
        if ($request->has('price_min') && $request->price_min !== '') {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max !== '') {
            $query->where('price', '<=', $request->price_max);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // فلترة حسب الخدمة
        if ($request->has('service_id') && $request->service_id !== '') {
            $query->where('commentable_id', $request->service_id);
        }

        $offers = $query->latest()->paginate(15);
        $providers = User::where('role_id', 3)->get();
        $services = Services::with('department')->get();

        return view('admin.service_management.orders', compact('offers', 'providers', 'services'));
    }

    /**
     * عرض تفاصيل طلب معين
     */
    public function showOrder($id)
    {
        $order = GeneralOrder::with(['service', 'user', 'service_provider'])
            ->findOrFail($id);

        // جلب جميع العروض المقدمة للخدمة المرتبطة بهذا الطلب
        $offers = [];
        if ($order->service) {
            $offers = GeneralComments::where('commentable_id', $order->service->id)
                ->where('commentable_type', Services::class)
                ->with(['user', 'commentable'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.service_management.show_order', compact('order', 'offers'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = GeneralOrder::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * جلب العروض المقدمة للطلب
     */
    public function getOrderOffers($id)
    {
        $order = GeneralOrder::with(['service'])->findOrFail($id);

        if (!$order->service) {
            return response()->json([
                'success' => false,
                'message' => 'الخدمة المرتبطة بهذا الطلب تم حذفها'
            ]);
        }

        $offers = GeneralComments::where('commentable_id', $order->service->id)
            ->where('commentable_type', Services::class)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'offers' => $offers
        ]);
    }

    /**
     * عرض إحصائيات الخدمات
     */
    public function statistics()
    {
        // إحصائيات الخدمات حسب الحالة
        $serviceStats = Services::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // إحصائيات الطلبات حسب الحالة
        $orderStats = GeneralOrder::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // إحصائيات الخدمات حسب القسم
        $departmentStats = Services::with('department')
            ->select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->get();

        // إحصائيات الخدمات حسب المدينة
        $cityStats = Services::select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('admin.service_management.statistics', compact('serviceStats', 'orderStats', 'departmentStats', 'cityStats'));
    }

    /**
     * عرض مقدمي الخدمات
     */
    public function providers(Request $request)
    {
        $query = User::where('role_id', 3)->with(['userDepartments']);

        // فلترة حسب المدينة
        if ($request->has('city') && $request->city !== '') {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // فلترة حسب القسم
        if ($request->has('department_id') && $request->department_id !== '') {
            $query->whereHas('userDepartments', function($q) use ($request) {
                $q->where('commentable_type', Department::class)
                  ->where('commentable_id', $request->department_id);
            });
        }

        $providers = $query->paginate(15);
        $departments = Department::all();

        return view('admin.service_management.providers', compact('providers', 'departments'));
    }

    /**
     * عرض تفاصيل مقدم خدمة معين
     */
    public function showProvider($id)
    {
        $provider = User::with(['userDepartments', 'services', 'orders'])
            ->where('role_id', 3)
            ->findOrFail($id);

        return view('admin.service_management.show_provider', compact('provider'));
    }
}
