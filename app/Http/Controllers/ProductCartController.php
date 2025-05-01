<?php

namespace App\Http\Controllers;

use App\Models\ProductCart;
use App\Models\ProductOrder;
use App\Models\ProductOrderitems;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class ProductCartController extends Controller
{
    public function index()
    {
        $cartItems = ProductCart::with('product')->where('user_id', auth()->id())->get();
        return view('admin.main_department.industry.pro_cart', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        $cart = ProductCart::firstOrCreate(
            ['user_id' => auth()->id(), 'inds_product_id' => $productId],
            ['quantity' => 0]
        );

        $cart->increment('quantity');
        return back()->with('success', 'تم إضافة المنتج للسلة');
    }

    public function update(Request $request, $id)
    {
        $cart = ProductCart::findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'تم تحديث السلة');
    }

    public function remove($id)
    {
        $cart = ProductCart::findOrFail($id);
        $cart->delete();
        return back()->with('success', 'تم حذف المنتج من السلة');
    }

    public function checkout(Request $request)
    {
        $cartItems = ProductCart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        // الدفع الإلكتروني
        if ($request->payment_method === 'visa') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $charge = Charge::create([
                    'amount' => $total * 100, // Stripe يستخدم السنت، لذا نضرب × 100
                    'currency' => 'egp',
                    'source' => $request->stripeToken, // يتم إرساله من واجهة الدفع
                    'description' => 'دفع طلب منتجات صناعية',
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'فشل الدفع: ' . $e->getMessage());
            }
        }

        // إنشاء الطلب بعد الدفع أو الكاش
        $order = ProductOrder::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cartItems as $item) {
            ProductOrderitems::create([
                'product_order_id' => $order->id,
                'inds_product_id' => $item->inds_product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        ProductCart::where('user_id', auth()->id())->delete();

        return redirect()->route('pro_order.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }
    }
