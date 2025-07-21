<?php

namespace App\Http\Controllers;

use App\Models\indsProduct;
use App\Models\ProductItems;
use App\Models\ProductOrder;
use App\Models\ProductOrderitems;
use Illuminate\Http\Request;

class ProductOrderitemsController extends Controller
{
    public function index($orderId)
    {
        $order = ProductOrder::with('items.product')->findOrFail($orderId);
        return view('order_items.index', compact('order'));
    }

    // إضافة عنصر جديد لطلب
    public function create($orderId)
    {
        $products = indsProduct::all();
        return view('order_items.create', compact('orderId', 'products'));
    }

    // تخزين عنصر جديد داخل الطلب
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'inds_product_id' => 'required|exists:inds_products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        ProductOrderitems::create([
            'product_order_id' => $orderId,
            'inds_product_id' => $request->inds_product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return redirect()->route('product_orders.show', $orderId)
                         ->with('success', 'تم إضافة المنتج إلى الطلب');
    }

    // تعديل عنصر طلب موجود
    public function edit($id)
    {
        $orderItem = ProductOrderitems::findOrFail($id);
        $products = indsProduct::all();
        return view('order_items.edit', compact('orderItem', 'products'));
    }

    // تحديث بيانات العنصر
    public function update(Request $request, $id)
    {
        $orderItem = ProductOrderitems::findOrFail($id);

        $request->validate([
            'inds_product_id' => 'required|exists:inds_products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem->update([
            'inds_product_id' => $request->inds_product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return redirect()->route('order_items.index', $orderItem->order_id)
                         ->with('success', 'تم تحديث تفاصيل المنتج');
    }

    // حذف عنصر من الطلب
    public function destroy($id)
    {
        $orderItem = ProductOrderitems::findOrFail($id);
        $orderId = $orderItem->product_order_id;
        $orderItem->delete();

        return redirect()->route('product_orders.show', $orderId)
                         ->with('success', 'تم حذف المنتج من الطلب');
    }
}
