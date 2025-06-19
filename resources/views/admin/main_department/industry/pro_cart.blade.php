@extends('layouts.home')
@php  $lang = config('app.locale'); @endphp
@section('content')
    <div class="container-fluid" style="margin-top: 10px !important; padding-top: 0 !important;">
        <div class="cart-header">
            <i class="fas fa-shopping-cart"></i>
            {{ ($lang =='ar') ? 'سلة المشتريات ' : 'Cart' }}
        </div>

        @if($cartItems->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        {{-- إذا كان لديك صورة للمنتج أضف هذا العمود --}}
                        {{-- <th>{{ ($lang =='ar') ? 'صورة' : 'Image' }}</th> --}}
                        <th>{{ ($lang =='ar') ? 'المنتج' : 'product' }}</th>
                        <th>{{ ($lang =='ar') ? 'السعر' : 'Price' }}</th>
                        <th>{{ ($lang =='ar') ? 'الكمية' : 'Quantity' }}</th>
                        <th>{{ ($lang =='ar') ? 'الإجمالي' : 'Total' }}</th>
                        <th>{{ ($lang =='ar') ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $cartItem)
                        <tr>
                            {{-- إذا كان لديك صورة للمنتج أضف هذا العمود --}}
                            {{-- <td><img src="{{ $cartItem->product->image_url }}" style="width:40px;height:40px;border-radius:8px"></td> --}}
                            <td class="product-title">{{ $cartItem->product->title }}</td>
                            <td>{{ $cartItem->product->price }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }} </td>
                            <td>
                                <form action="{{ route('pro_cart.update', $cartItem->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" class="form-control" style="width: auto;">
                                    <button type="submit" class="btn btn-success mt-2">
                                        <i class="fas fa-sync-alt"></i> {{ ($lang =='ar') ? 'تحديث' : 'update' }}
                                    </button>
                                </form>
                            </td>
                            <td>{{ $cartItem->product->price * $cartItem->quantity }}   {{ $lang == 'ar' ? 'ر.س' : 'SAR' }} </td>
                            <td>
                                <form action="{{ route('pro_cart.remove', $cartItem->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> {{ ($lang =='ar') ? 'حذف' : 'Delete' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            <form action="{{ route('pro_cart.checkout') }}" method="POST" class="mt-4" id="payment-form">
                @csrf

                <div class="form-group mb-3 text-center">
                    <label for="payment_method">{{ ($lang =='ar') ? '  اختر وسيلة الدفع' : 'Payment Method' }}   :  </label>
                    <select name="payment_method" id="payment_method" class="form-control" style="max-width: auto; margin: 0 auto;">
                        <option value="cash"> {{ ($lang =='ar') ? '  الدفع نقدًا ' : 'Cash On Delivery' }} </option>
                        <option value="visa"> {{ ($lang =='ar') ? '  الدفع ببطاقة فيزا' : 'Payment With Credit' }} </option>
                    </select>
                </div>

                <div class="stripe-payment-container">
                    <!-- يظهر فقط إذا كانت طريقة الدفع Visa -->
                    <div id="card-element-container" class="card-element-container" style="display: none;">
                        <label for="card-element" class="form-label"> {{ ($lang =='ar') ? '  بطاقة الائتمان /الخصم ' : 'Payment With Credit' }}</label>
                        <div id="card-element" class="form-control mb-3"></div>
                        <div id="card-errors" class="text-danger mb-2"></div>
                    </div>

                    <!-- عنصر مخفي لحفظ Token -->
                    <input type="hidden" name="stripeToken" id="stripeToken">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i>  {{ ($lang =='ar') ? '  إتمام الطلب  ' : 'Submit' }}
                    </button>
                </div>
            </form>
        @else
            <p class="text-center mt-4" style="font-size: 20px; font-weight: bold; color: #007bff;">   {{ ($lang =='ar') ? '  لا توجد منتجات في السلة.   ' : 'No products' }}</p>
        @endif
    </div>
@endsection

@section('style')
<style>
.container-fluid {
    margin-top: 10px !important;
    padding-top: 0 !important;
}

.cart-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 28px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 10px !important;
}
.cart-header i {
    color: #28a745;
    font-size: 32px;
}

.table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.07);
}

.table th, .table td {
    vertical-align: middle !important;
}

.table tbody tr:hover {
    background: #f1f7ff;
    transition: background 0.2s;
}

.btn-success, .btn-danger, .btn-primary {
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    font-size: 15px;
}

.btn-danger i, .btn-success i, .btn-primary i {
    font-size: 16px;
}

.product-title {
    font-weight: 600;
    color: #333;
}

@media (max-width: 600px) {
    .table-responsive {
        font-size: 13px;
    }
    .cart-header {
        font-size: 20px;
    }
}

body {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();
    const card = elements.create('card');
    const paymentForm = document.getElementById('payment-form');
    const clientSecretInput = document.getElementById('stripeToken');
    const paymentMethodSelect = document.getElementById('payment_method');
    const cardElementContainer = document.getElementById('card-element-container');

    // إخفاء/إظهار بطاقة الدفع حسب الاختيار
    paymentMethodSelect.addEventListener('change', function() {
        if (paymentMethodSelect.value === 'visa') {
            cardElementContainer.style.display = 'block';
        } else {
            cardElementContainer.style.display = 'none';
        }
    });

    // إرسال بيانات البطاقة
    paymentForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (paymentMethodSelect.value === 'visa') {
            const {token, error} = await stripe.createToken(card);

            if (error) {
                console.error(error.message);
                return;
            }

            clientSecretInput.value = token.id;
        }

        paymentForm.submit(); // إرسال النموذج
    });

    // تفعيل الـ Card Element
    card.mount('#card-element');
</script>
@endsection
