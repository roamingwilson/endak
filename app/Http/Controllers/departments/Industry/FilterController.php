<?php

namespace App\Http\Controllers\departments\Industry;

use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\indsProduct;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index($productId) {
        $product = indsProduct::findOrFail($productId);
        $filters = Filter::where('product_id', $productId)->get();
        return view('filters.index', compact('filters', 'product'));
    }

    public function create($productId) {
        $product = indsProduct::findOrFail($productId);
        return view('filters.create', compact('product'));
    }

    public function store(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'key' => 'required',
            'value' => 'required'
        ]);

        Filter::create($request->all());
        return redirect()->route('filters.index', $request->product_id)->with('success', 'تم إضافة الخاصية');
    }

    public function destroy($id) {
        $filter = Filter::findOrFail($id);
        $productId = $filter->product_id;
        $filter->delete();
        return redirect()->route('filters.index', $productId)->with('success', 'تم حذف الخاصية');
    }
}
