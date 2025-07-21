@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'تعديل الطلب' : 'Edit Order' }}
@endsection
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body py-4">
                    <h3 class="fw-bold mb-4" style="color:#ff9800;"><i class="fas fa-edit me-2"></i>{{ $lang == 'ar' ? 'تعديل الطلب رقم' : 'Edit Order #' }}{{ $order->id }}</h3>
                    <form action="{{ route('product_orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @foreach($order->items as $item)
                            <div class="card mb-3 shadow-sm border-0 rounded-4 d-flex flex-column flex-md-row align-items-center gap-4 position-relative">
                                <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : asset('images/default-product.png') }}" style="width:60px;height:60px;border-radius:10px;object-fit:cover;box-shadow:0 2px 8px #eee;">
                                <div class="flex-grow-1 w-100">
                                    <h5 class="fw-bold mb-2 text-primary">{{ $item->product->title }}</h5>
                                    <div class="row g-2 align-items-end">
                                        <div class="col-6 col-md-4">
                                            <label class="form-label">{{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}</label>
                                            <input type="number" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}" min="1" class="form-control rounded-pill">
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <label class="form-label">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</label>
                                            <input type="number" value="{{ $item->price }}" class="form-control rounded-pill" readonly>
                                            <input type="hidden" name="items[{{ $item->id }}][price]" value="{{ $item->price }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute" style="top:10px;{{ $lang == 'ar' ? 'left:10px;' : 'right:10px;' }}">
                                    <form action="{{ route('pro_order_items.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('هل أنت متأكد من حذف المنتج من الطلب؟')">
                                            <i class="fas fa-trash"></i> {{ $lang == 'ar' ? 'حذف المنتج' : 'Delete Item' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        <div class="alert alert-info text-center mt-2 mb-4 small">{{ $lang == 'ar' ? 'لحذف منتج من الطلب استخدم زر الحذف بجانب كل منتج بعد الحفظ.' : 'To delete an item, use the delete button next to each product after saving.' }}</div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5">
                                <i class="fas fa-save"></i> {{ $lang == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
