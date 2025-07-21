@extends('layouts.home')
@php  $lang = config('app.locale'); @endphp
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="cart-header mb-4">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ ($lang =='ar') ? 'سلة المشتريات ' : 'Cart' }}</span>
                </div>
                @if($cartItems->count() > 0)
                <div class="card shadow-lg border-0 rounded-4 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-borderless">
                                <thead class="bg-light">
                                    <tr>
                                        <th></th>
                                        <th>{{ ($lang =='ar') ? 'المنتج' : 'Product' }}</th>
                                        <th>{{ ($lang =='ar') ? 'السعر' : 'Price' }}</th>
                                        <th>{{ ($lang =='ar') ? 'الكمية' : 'Quantity' }}</th>
                                        <th>{{ ($lang =='ar') ? 'الإجمالي' : 'Total' }}</th>
                                        <th>{{ ($lang =='ar') ? 'الإجراءات' : 'Actions' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $cartItem)
                                        <tr>
                                            <td style="width:60px;">
                                                <img src="{{ $cartItem->product->image ? asset('storage/'.$cartItem->product->image) : asset('images/default-product.png') }}" style="width:48px;height:48px;border-radius:10px;object-fit:cover;box-shadow:0 2px 8px #eee;">
                                            </td>
                                            <td class="product-title">{{ $cartItem->product->title }}</td>
                                            <td class="fw-bold text-warning">{{ $cartItem->product->price }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }}</td>
                                            <td>
                                                <form action="{{ route('pro_cart.update', $cartItem->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" class="form-control form-control-sm rounded-pill text-center" style="width: 70px;">
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3"><i class="fas fa-sync-alt"></i></button>
                                                </form>
                                            </td>
                                            <td class="fw-bold">{{ $cartItem->product->price * $cartItem->quantity }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }}</td>
                                            <td>
                                                <form action="{{ route('pro_cart.remove', $cartItem->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-6">
                        <div class="card shadow border-0 rounded-4 mb-4">
                            <div class="card-body">
                                <form action="{{ route('pro_cart.checkout') }}" method="POST" id="payment-form">
                                    @csrf
                                    <div class="form-group mb-3 text-center">
                                        <label for="payment_method" class="fw-semibold mb-2"><i class="fas fa-credit-card text-primary me-1"></i> {{ ($lang =='ar') ? 'اختر وسيلة الدفع' : 'Payment Method' }}</label>
                                        <select name="payment_method" id="payment_method" class="form-select rounded-pill w-auto mx-auto text-center" style="max-width:220px;">
                                            <option value="cash">{{ ($lang =='ar') ? 'الدفع نقدًا' : 'Cash On Delivery' }}</option>
                                            <option value="visa">{{ ($lang =='ar') ? 'الدفع ببطاقة فيزا' : 'Payment With Credit' }}</option>
                                        </select>
                                    </div>
                                    <div class="stripe-payment-container">
                                        <div id="card-element-container" class="card-element-container" style="display: none;">
                                            <label for="card-element" class="form-label">{{ ($lang =='ar') ? 'بطاقة الائتمان /الخصم' : 'Payment With Credit' }}</label>
                                            <div id="card-element" class="form-control mb-3"></div>
                                            <div id="card-errors" class="text-danger mb-2"></div>
                                        </div>
                                        <input type="hidden" name="stripeToken" id="stripeToken">
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow"><i class="fas fa-credit-card me-2"></i>{{ ($lang =='ar') ? 'إتمام الطلب' : 'Submit' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="card shadow border-0 rounded-4 p-5 my-5 text-center">
                        <div class="py-4">
                            <i class="fas fa-shopping-basket fa-3x text-warning mb-3"></i>
                            <h4 class="fw-bold mb-2" style="color:#007bff;">{{ ($lang =='ar') ? 'لا توجد منتجات في السلة.' : 'No products in the cart.' }}</h4>
                            <p class="text-muted mb-0">{{ ($lang =='ar') ? 'ابدأ التسوق وأضف منتجاتك المفضلة.' : 'Start shopping and add your favorite products.' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
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
