<?php

namespace App\Http\Controllers;

use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    public function index()
    {
        $orders = ProductOrder::where('user_id', auth()->id())->with('items.product')->latest()->get();
        return view('admin.main_department.industry.pro_order', compact('orders'));
    }


    // عرض تفاصيل طلب معين
    public function show($id)
    {
        $order = ProductOrder::with('items.product', 'user')->findOrFail($id);

        return view('admin.main_department.industry.show_pro_order', compact('order'));
    }

    public function manage()
    {
        $orders = ProductOrder::with('items.product', 'user')
            ->latest()
            ->paginate(10);
        // dd($orders);
        return view('admin.main_department.industry.admin_order', compact('orders'));
    }
    public function bulkAction(Request $request)
{
    $ids = $request->bulk_ids;
    if ($request->bulk_action_btn == 'delete') {
        ProductOrder::whereIn('id', $ids)->delete();
        return back()->with('success', 'تم حذف الطلبات المحددة');
    } elseif ($request->bulk_action_btn == 'update_status') {
        ProductOrder::whereIn('id', $ids)->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث حالة الطلبات المحددة');
    }

    return back()->with('info', 'لم يتم تنفيذ أي إجراء.');
}

    // عرض تفاصيل الطلب للإدارة
    public function adminShow($id)
    {
        $order = ProductOrder::with('items.product', 'user')->findOrFail($id);
        return view('admin.main_department.industry.show_admin_order', compact('order'));
    }

    // تغيير حالة الطلب (مثلا إلى مكتمل أو ملغي)
    public function updateStatus(Request $request, $id)
    {
        $order = ProductOrder::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    // حذف طلب بالكامل
    public function destroy($id)
    {
        $order = ProductOrder::findOrFail($id);
        $order->delete();

        return back()->with('success', 'تم حذف الطلب');
    }
}
