@extends('layouts.home')

@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar') ? 'صناعة البلاستيك' : 'Industry' }}
@endsection

@section('page_name')
{{ ($lang == 'ar') ? 'صناعة البلاستيك' : 'Industry' }}
@endsection

@section('content')
@php
    $priceRanges = [
        'under_100' => ['label' => 'أقل من 100', 'min' => 0, 'max' => 100],
        '100_500' => ['label' => 'من 100 إلى 500', 'min' => 100, 'max' => 500],
        'above_500' => ['label' => 'أكثر من 500', 'min' => 500, 'max' => null],
    ];
@endphp

<div class="container ">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold mb-0" style="color:#ff9800;"><i class="fas fa-store me-2"></i>{{ $lang == 'ar' ? 'متجر البلاستيك' : 'Plastic Store' }}</h2>
        <span class="badge bg-warning text-dark fs-6">{{ $products->count() }} {{ $lang == 'ar' ? 'منتج' : 'Products' }}</span>
    </div>
    {{-- الفلاتر --}}
    <div class="filter-cards mb-4 w-100 position-relative" style="left:50%;transform:translateX(-50%);width:100vw;max-width:100vw;padding-left:calc((100vw - 100%) / 2);padding-right:calc((100vw - 100%) / 2);">
        {{-- فلتر الأقسام الرئيسية --}}
        <div class="filter-section mb-4 w-100">
            <h6 class="filter-title mb-2 text-center">{{ ($lang == 'ar')? 'الأقسام الرئيسية' : 'Main Sections' }}</h6>
            <div class="filter-items d-flex flex-wrap gap-2 justify-content-center">
                @foreach($categories as $cat)
                    <div class="filter-card {{ request('inds_category_id') == $cat->id ? 'active' : '' }}"
                        onclick="window.location.href='{{ route('indsproducts.index', array_merge(request()->except('page'), ['inds_category_id' => $cat->id])) }}'">
                        <i class="fas fa-box"></i>
                        <span>{{ $cat->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- الأقسام الفرعية --}}
        <div class="filter-section mb-4 w-100">
            <h6 class="filter-title mb-2 text-center">{{ ($lang == 'ar')? 'الأقسام الفرعية' : 'Subcategories' }}</h6>
            <div class="filter-items d-flex flex-wrap gap-2 justify-content-center">
                <div class="filter-card {{ is_null(request('ind_sub_category_id')) ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('indsproducts.index', request()->except('ind_sub_category_id', 'page')) }}'">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ ($lang == 'ar')? 'كل الفرعيات' : 'All Subcategories' }}</span>
                </div>
                @foreach($subcategories as $sub)
                    <div class="filter-card {{ request('ind_sub_category_id') == $sub->id ? 'active' : '' }}"
                        onclick="window.location.href='{{ route('indsproducts.index', array_merge(request()->except('page'), ['ind_sub_category_id' => $sub->id])) }}'">
                        <i class="fas fa-cube"></i>
                        <span>{{ $sub->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- فلتر السعر --}}
        <div class="filter-section w-100">
            <h6 class="filter-title mb-2 text-center">{{ ($lang == 'ar')? 'السعر' : 'Price' }}</h6>
            <div class="filter-items d-flex flex-wrap gap-2 justify-content-center">
                <div class="filter-card {{ is_null(request('price_range')) ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('indsproducts.index', request()->except('price_range', 'min_price', 'max_price', 'page')) }}'">
                    <i class="fas fa-dollar-sign"></i>
                    <span>{{ $lang == 'ar' ? 'كل الأسعار' : 'All Prices' }}</span>
                </div>
                @foreach($priceRanges as $key => $range)
                    <div class="filter-card {{ request('price_range') == $key ? 'active' : '' }}"
                        onclick="window.location.href='{{ route('indsproducts.index', array_merge(request()->except('page'), [
                            'price_range' => $key,
                            'min_price' => $range['min'],
                            'max_price' => $range['max'],
                        ])) }}'">
                        <i class="fas fa-tags"></i>
                        <span>{{ $range['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- عرض المنتجات --}}
    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-xl-3 d-flex">
                <div class="card shadow-sm border-0 flex-fill d-flex flex-column align-items-center p-3" style="background-color: #f5f5f5; border-radius: 14px; min-height: 340px;">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}"
                        alt="{{ $product->title }}" style="width: 100%; height: 170px; object-fit: contain; background: #f8f8f8; border-radius: 10px;">
                    <div class="product-info w-100 text-center mt-2 flex-grow-1 d-flex flex-column justify-content-between">
                        <h5 class="fw-bold small text-truncate mb-1">{{ $product->title }}</h5>
                        <p class="fw-bold text-warning fs-5 mb-2">{{ $product->price }} {{ ($lang == 'ar')? 'ر.س' : 'SAR' }}</p>
                        @if (auth()->check())
                            <form action="{{ route('pro_cart.add', $product->id) }}" method="POST" class="w-100 mt-auto">
                                @csrf
                                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 70px;">
                                    <button type="submit" class="btn btn-warning btn-sm px-3 rounded-pill fw-bold"><i class="fas fa-cart-plus"></i> {{ ($lang == 'ar')? 'إضافة إلى السلة' : 'Add to cart' }}</button>
                                </div>
                            </form>
                        @else
                            <form action="{{ route('register-page') }}" method="get" class="w-100 mt-auto">
                                @csrf
                                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="max-width: 70px;">
                                    <button type="submit" class="btn btn-warning btn-sm px-3 rounded-pill fw-bold"><i class="fas fa-user-plus"></i> {{ ($lang == 'ar')? 'سجل لتتمكن من الشراء' : 'Register to buy' }}</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-5 fs-5 rounded-4 shadow-sm">
                    <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                    {{ ($lang == 'ar')? 'لا توجد منتجات مطابقة للبحث الحالي' : 'No products found.' }}
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('style')
<style>
    /* إعدادات عامة */
    body {
        background: #fff;
        margin: 0;
        font-family: 'Tajawal', sans-serif;
    }

    .container {
        padding: 10px;
    }

    /* التابات */
    .tabs {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        background: #ffcc00;
        padding: 10px;
        border-radius: 10px;
        gap: 10px;
    }
    .tab {
        color: #000;
        font-weight: bold;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 14px;
    }
    .tab.active {
        background: #fff;
        color: #000;
    }

    /* الفلاتر */
    .filter-cards {
        background: linear-gradient(90deg, #fffbe7 60%, #fff7c2 100%);
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(255, 193, 7, 0.10), 0 1.5px 6px rgba(0,0,0,0.04);
        padding: 12px 6px 8px 6px;
        margin-bottom: 1.2rem;
    }
    .filter-section {
        margin-bottom: 1rem;
    }
    .filter-title {
        font-weight: bold;
        font-size: 1rem;
        letter-spacing: 0.2px;
        color: #ff9800;
        margin-bottom: 0.5rem;
        text-shadow: 0 1px 0 #fffbe7;
    }
    .filter-items {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
    .filter-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.08), 0 1.5px 6px rgba(0,0,0,0.03);
        text-align: center;
        padding: 7px 10px 5px 10px;
        cursor: pointer;
        transition: all 0.18s cubic-bezier(.4,2,.6,1);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 65px;
        min-height: 38px;
        border: 2px solid transparent;
        position: relative;
    }
    .filter-card i {
        font-size: 1.05rem;
        margin-bottom: 3px;
        color: #ff9800;
        transition: color 0.2s;
        filter: drop-shadow(0 1px 0 #fffbe7);
    }
    .filter-card span {
        font-size: 0.92rem;
        font-weight: 500;
        color: #333;
        letter-spacing: 0.1px;
    }
    .filter-card.active, .filter-card:hover {
        background: linear-gradient(90deg, #ffe0a3 60%, #ffd180 100%);
        border-color: #ff9800;
        box-shadow: 0 4px 18px rgba(255, 193, 7, 0.13), 0 1.5px 6px rgba(0,0,0,0.05);
        transform: translateY(-2px) scale(1.025);
    }
    .filter-card.active i, .filter-card:hover i {
        color: #d17b00;
        filter: drop-shadow(0 2px 0 #fffbe7);
    }
    .filter-card.active span, .filter-card:hover span {
        color: #d17b00;
    }
    @media (max-width: 991px) {
        .filter-cards {
            padding: 7px 2px 4px 2px;
        }
        .filter-card {
            min-width: 55px;
            padding: 5px 6px 3px 6px;
        }
        .filter-title {
            font-size: 0.92rem;
        }
    }
    @media (max-width: 600px) {
        .filter-cards {
            padding: 4px 1px 2px 1px;
        }
        .filter-section {
            margin-bottom: 0.6rem;
        }
        .filter-card {
            min-width: 45px;
            padding: 3px 3px 2px 3px;
        }
        .filter-title {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 68px) {
        .filter-card {
            flex: 1 1 calc(50% - 10px);
        }
    }
    @media (max-width: 50px) {
        .filter-card {
            flex: 1 1 100%;
        }
    }
    @media (max-width: 768px) {
    body {
        padding-top: 90px; /* عشان الـ navbar ما يغطيش الصفحة */
    }
}

@media (min-width: 769px) {
    body {
        padding-top: 70px;
         /* أو حسب ارتفاع الـ navbar */
    }

}
@media (max-width: 576px) {
    .d-flex.align-items-center.mt-2 {
        flex-direction: column;
        align-items: stretch;
    }

    .d-flex.align-items-center.mt-2 input,
    .d-flex.align-items-center.mt-2 button {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 10px;
    }
}

    /* المنتجات */
    /* ترتيب المنتجات */
/* عرض المنتجات */
/* .products-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
} */

/* .product-item {
    display: flex;
    justify-content: center;
    align-items: stretch;
    width: 23%;
} */

/* .product-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease-in-out;
} */

/* .product-card:hover {
    transform: scale(1.05);
}

.product-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.product-info {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
}

.product-title {
    font-size: 16px;
    font-weight: bold;
}

.product-price {
    font-size: 14px;
    color: #000;
    margin: 10px 0;
    font-weight: 600;
}

.chat-btn {
    background: #ffcc00;
    border: none;
    border-radius: 25px;
    padding: 8px;
    font-size: 13px;
    font-weight: bold;
    width: 100%;
    cursor: pointer;
    margin-bottom: 10px;
}

.btn-warning {
    background-color: #ffc107;
    border: none;
    padding: 8px 12px;
    font-size: 13px;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.form-control {
    font-size: 13px;
    padding: 5px 8px; */
/* } */


@media (min-width: 1200px) {
    .product-item {
        width: 18%;
    }
}

/* استجابة الشاشات المتوسطة */
@media (max-width: 1200px) and (min-width: 992px) {
    .product-item {
        width: 23%;
    }
}

/* استجابة الشاشات الصغيرة */
@media (max-width: 768px) {
    .product-item {
        width: 48%; /* عرض عمودين في الشاشات الصغيرة */
    }
}

/* استجابة الشاشات الصغيرة جدًا */
@media (max-width: 500px) {
    .product-item {
        width: 100%; /* عرض عمود واحد في الشاشات الصغيرة جدًا */
    }
}


</style>
@endsection
