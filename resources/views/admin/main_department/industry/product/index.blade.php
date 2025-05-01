@extends('layouts.home')
@section('title')
<?php $lang = config('app.locale'); ?>
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('page_name')
{{ ($lang == 'ar')? 'صناعة البلاستيك' : "Industry" }}
@endsection
@section('content')

    <div class="container my-4">
        <div class="filter-container">
            {{-- فلتر الأقسام الرئيسية --}}
            <div class="filter-bar">
                <h4>القسم الرئيسي:</h4>
                @php
                    $currentCat = request('inds_category_id');
                @endphp
                <a href="{{ route('indsproducts.index', request()->except('inds_category_id', 'page')) }}"
                   class="{{ is_null($currentCat) ? 'active' : '' }}">
                    الكل
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('indsproducts.index', array_merge(request()->except('page'), ['inds_category_id' => $cat->id])) }}"
                       class="{{ $currentCat == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            {{-- فلتر الأقسام الفرعية --}}
            <div class="filter-bar">
                <h4>القسم الفرعي:</h4>
                @php
                    $currentSub = request('ind_sub_category_id');
                @endphp
                <a href="{{ route('indsproducts.index', request()->except('ind_sub_category_id', 'page')) }}"
                   class="{{ is_null($currentSub) ? 'active' : '' }}">
                    الكل
                </a>

                @foreach($subcategories as $sub)
                    <a href="{{ route('indsproducts.index', array_merge(request()->except('page'), ['ind_sub_category_id' => $sub->id])) }}"
                       class="{{ $currentSub == $sub->id ? 'active' : '' }}">
                        {{ $sub->name }}
                    </a>
                @endforeach
            </div>

            {{-- فلتر السعر --}}
            <div class="filter-bar">
                <h4>السعر:</h4>
                @php
                    $priceRanges = [
                        'under_100' => ['label' => 'أقل من 100', 'min' => 0, 'max' => 100],
                        '100_500' => ['label' => 'من 100 إلى 500', 'min' => 100, 'max' => 500],
                        'above_500' => ['label' => 'أكثر من 500', 'min' => 500, 'max' => null],
                    ];
                    $selectedPrice = request('price_range');
                @endphp

                <a href="{{ route('indsproducts.index', request()->except('price_range', 'min_price', 'max_price', 'page')) }}"
                   class="{{ is_null($selectedPrice) ? 'active' : '' }}">
                    الكل
                </a>

                @foreach($priceRanges as $key => $range)
                    <a href="{{ route('indsproducts.index', array_merge(request()->except('page'), [
                        'price_range' => $key,
                        'min_price' => $range['min'],
                        'max_price' => $range['max'],
                    ])) }}"
                       class="{{ $selectedPrice == $key ? 'active' : '' }}">
                        {{ $range['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- عرض المنتجات --}}
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-lg">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.png') }}"
                             class="card-img-top" alt="{{ $product->title }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 80) }}</p>
                            <p class="card-text"><strong>السعر:</strong> {{ $product->price }} جنيه</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    التصنيف: {{ $product->category->name ?? '-' }} /
                                    الفرعي: {{ $product->subcategory->name ?? '-' }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">لا توجد منتجات لعرضها.</div>
                </div>
            @endforelse
        </div>
    </div>

    @if (Session::has('success'))
    <script>
        swal("Message", "{{ Session::get('success') }}", 'success', {
            button: true,
            button: "Ok",
            timer: 3000,
        })
    </script>
    @endif
    @if (Session::has('info'))
    <script>
        swal("Message", "{{ Session::get('info') }}", 'info', {
            button: true,
            button: "Ok",
            timer: 3000,
        })
    </script>
    @endif
@endsection

@section('style')
<style>
    .filter-container {
        margin-bottom: 30px;
    }

    .filter-bar {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .filter-bar h4 {
        margin-bottom: 10px;
        font-size: 18px;
    }

    .filter-bar a {
        display: inline-block;
        margin: 5px 10px;
        padding: 8px 15px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 500;
        color: #333;
        border: 1px solid #ccc;
        transition: background-color 0.3s ease;
    }

    .filter-bar a.active,
    .filter-bar a:hover {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }

    .card {
        border: none;
        border-radius: 8px;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
        object-fit: cover;
        height: 200px;
        border-radius: 8px 8px 0 0;
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
    }

    .card-text {
        font-size: 14px;
    }

    .alert {
        font-size: 16px;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        border-radius: 30px;
        padding: 10px 20px;
        text-align: center;
    }
</style>
@endsection
