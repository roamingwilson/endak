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

    public function edit($id)
    {
        $order = ProductOrder::with('items.product', 'user')->findOrFail($id);
        return view('admin.main_department.industry.edit_pro_order', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = ProductOrder::with('items')->findOrFail($id);
        if ($request->has('items')) {
            foreach ($order->items as $item) {
                if (isset($request->items[$item->id])) {
                    $item->quantity = $request->items[$item->id]['quantity'];
                    $item->price = $request->items[$item->id]['price'];
                    $item->save();
                }
            }
        }
        // تحديث الإجمالي الكلي إذا لزم
        $order->total = $order->items->sum(function($item) { return $item->quantity * $item->price; });
        $order->save();
        return redirect()->route('product_orders.show', $order->id)->with('success', 'تم تحديث الطلب بنجاح');
    }
}
