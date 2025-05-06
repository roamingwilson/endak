@extends('layouts.home')
@php  $lang = config('app.locale'); @endphp
@section('content')
    <div class="container-fluid" style="margin-top: auto">
        <h1>   {{ ($lang =='ar') ? 'سلة المشتريات ' : 'Cart' }}</h1>

        @if($cartItems->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
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
                            <td>{{ $cartItem->product->title }}</td>
                            <td>{{ $cartItem->product->price }} {{ $lang == 'ar' ? 'ر.س' : 'SAR' }} </td>
                            <td>
                                <form action="{{ route('pro_cart.update', $cartItem->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" class="form-control" style="width: auto;">
                                    <button type="submit" class="btn btn-success mt-2">{{ ($lang =='ar') ? 'تحديث' : 'update' }}</button>
                                </form>
                            </td>
                            <td>{{ $cartItem->product->price * $cartItem->quantity }}   {{ $lang == 'ar' ? 'ر.س' : 'SAR' }} </td>
                            <td>
                                <form action="{{ route('pro_cart.remove', $cartItem->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ ($lang =='ar') ? 'حذف' : 'Delete' }}</button>
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
                    <button type="submit" class="btn btn-primary">  {{ ($lang =='ar') ? '  إتمام الطلب  ' : 'Submit' }}</button>
                </div>
            </form>
        @else
            <p>   {{ ($lang =='ar') ? '  لا توجد منتجات في السلة.   ' : 'No products' }}</p>
        @endif
    </div>
@endsection

@section('style')
<style>
    /* تصميم سلة المشتريات */
    .container-fluid {
    margin-top: 20px; /* تعديل مهم */
    padding-top: 10px;
}

    h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    /* الجدول */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: auto;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
        font-size: auto;
    }

    .table th {
        background-color: #f4f4f4;
    }

    .table td {
        background-color: #fff;
    }

    .table input[type="number"] {
        width: auto;
        padding: 5px;
        font-size: auto;
        text-align: center;
    }

    /* أزرار التحديث والحذف */
    .btn {
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
        border: none;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }

    /* تصميم Stripe Container */
    .stripe-payment-container {
        max-width: auto;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-size: 16px;
        color: #333;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-control {
        height: 45px;
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 0 15px;
        font-size: 16px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
    }

    .card-element-container {
        margin-bottom: 20px;
    }

    #card-errors {
        font-size: 14px;
        color: #dc3545;
    }

    .btn {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        font-size: 18px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .btn:active {
        transform: translateY(2px);
    } .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* تحسين التصميم للموبايل الصغير */
    @media (max-width: 400px) {
        h1 {
            font-size: 18px;
        }
        .container-fluid {
            padding-top: 100px;
    }

        .table th, .table td {
            font-size: 12px;
            padding: 6px;
        }

        .form-control {
            font-size: 14px;
            padding: 8px;
            width: 100%;
        }

        .btn {
            font-size: 14px;
            padding: 10px;
            width: 100%;
        }

        .stripe-payment-container {
            padding: 10px;
        }

        .card-element-container {
            margin-bottom: 15px;
        }
    }

    /* لضبط عرض عناصر Stripe والمحدد */
    #card-element, #payment_method {
        width: 100% !important;
    }
    @media (max-width: 400px) {
    h1 {
        font-size: 18px;
    }

    .table th, .table td {
        font-size: 12px;
        padding: 8px;
    }

    .btn {
        font-size: 14px;
        padding: 10px;
    }

    .form-control {
        font-size: 14px;
        padding: 10px;
        width: 100%;
    }

    .stripe-payment-container {
        padding: 10px;
    }

    .text-center {
        text-align: center;
    }
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
