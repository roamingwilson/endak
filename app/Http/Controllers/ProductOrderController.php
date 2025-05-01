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
        $order = ProductOrder::where('user_id', auth()->id())->with('items.product')->findOrFail($id);
        return view('admin.main_department.industry.show_pro_order', compact('order'));
    }

    // إدارة الطلبات (مثلاً للمشرفين Admin)
    public function manage()
    {
        $orders = ProductOrder::with('items.product', 'user')->latest()->get();
        return view('admin.main_department.industry.admin_order', compact('orders'));
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
