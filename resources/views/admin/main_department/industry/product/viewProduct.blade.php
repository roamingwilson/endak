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

<div class="container my-4">

    {{-- الفلاتر --}}
    <div class="filter-cards mb-4">

        {{-- فلتر الأقسام الرئيسية --}}
        <div class="filter-section mb-3">
            <h6 class="filter-title">{{ ($lang == 'ar')? ' الأقسام الرئيسية ' : 'Main Sections' }}</h6>
            <div class="filter-items">


                @foreach($categories as $cat)
                    <div class="filter-card {{ request('inds_category_id') == $cat->id ? 'active' : '' }}"
                        onclick="window.location.href='{{ route('indsproducts.index', array_merge(request()->except('page'), ['inds_category_id' => $cat->id])) }}'">
                        <i class="fas fa-box"></i>
                        <span>{{ $cat->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- الأقسام فلتر الفرعية --}}
        <div class="filter-section mb-3">
            <h6 class="filter-title"> {{ ($lang == 'ar')? ' الأقسام  الفرعية ' : 'Subcategories' }}</h6>
            <div class="filter-items">
                <div class="filter-card {{ is_null(request('ind_sub_category_id')) ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('indsproducts.index', request()->except('ind_sub_category_id', 'page')) }}'">
                    <i class="fas fa-layer-group"></i>
                    <span> {{ ($lang == 'ar')? ' كل الفرعيات ' : 'Subcategories' }}</span>
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
        <div class="filter-section">
            <h6 class="filter-title">{{ ($lang == 'ar')? ' السعر' : 'price' }}</h6>
            <div class="filter-items">
                <div class="filter-card {{ is_null(request('price_range')) ? 'active' : '' }}"
                    onclick="window.location.href='{{ route('indsproducts.index', request()->except('price_range', 'min_price', 'max_price', 'page')) }}'">
                    <i class="fas fa-dollar-sign"></i>
                    <span>كل الأسعار</span>
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
    <div class="row g-2">
        @forelse($products as $product)
        <div class="col-4 col-md-2 text-center">
                <div class="card shadow-sm p-2" style="background-color: #f5f5f5; border-radius: 12px;">
                    <img  src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}"
                         class="" alt="{{ $product->title }}" style="width: 100px; height: 100px; object-fit: contain; margin: auto;">
                    <div class="product-info">
                        <h5 class="mt-2 small fw-bold">{{ $product->title }}</h5>
                        <p class="mt-2 small fw-bold">{{ $product->price }} ريال</p>


                        @if (auth()->check())


                        <form action="{{ route('pro_cart.add', $product->id) }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center mt-2">
                                <input type="number" name="quantity" value="1" min="1" class="form-control w-50" style="max-width: 100px;">
                                <button type="submit" class="btn btn-warning btn-sm ml-2"> {{ ($lang == 'ar')? ' إضافة إلى السلة' : 'Add to cart' }}</button>
                            </div>
                        </form>
                        @else
                        <form action="{{ route('register-page') }}" method="get">
                            @csrf
                            <div class="d-flex align-items-center mt-2">
                                <input type="number" name="quantity" value="1" min="1" class="form-control w-50" style="max-width: 100px;">
                                <button type="submit" class="btn btn-warning btn-sm ml-2"> {{ ($lang == 'ar')? ' إضافة إلى السلة' : 'Add to cart' }}</button>
                            </div>
                        </form>

                                @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">   {{ ($lang == 'ar')? ' لا توجد منتجات مطابقة للبحث الحالي ' : 'Add to cart' }}</div>
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

        background: #f9f9f9;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .filter-section {
        margin-bottom: 20px;
    }
    .filter-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 10px;
        color: #333;
    }
    .filter-items {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .filter-card {

        flex: 1 1 calc(5% - 1px);
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        text-align: center;
        padding: 1px 1px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 1px;
    }
    .filter-card i {
        font-size: 15px;
        margin-top: 5px;
        margin-bottom: 8px;
        color: #0d6efd;
    }
    .filter-card span {
        font-size: 14px;
        font-weight: 500;
    }
    .filter-card:hover,
    .filter-card.active {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .filter-card.active i,
    .filter-card:hover i {
        color: #fff;
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
