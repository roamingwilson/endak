
@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
    {{ ($lang == 'ar')?  ' صناعة البلاستيك' : "air condithion" }}

@endsection


@section('content')
<style>
    .filter-bar {
        margin-bottom: 20px;
    }
    .filter-bar h4 {
        margin-bottom: 10px;
    }
    .filter-bar a {
        display: inline-block;
        margin: 3px 8px;
        padding: 5px 10px;
        border: 1px solid #ccc;
        text-decoration: none;
        border-radius: 4px;
        color: #333;
    }
    .filter-bar a.active {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
</style>

    {{-- -------- --}}
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

{{-- فلتر السعر (خيارات جاهزة مثلاً) --}}
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

{{-- ---------- --}}
<div class="container my-4">
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->title }}">
                    @else
                        <img src="{{ asset('images/default-product.png') }}" class="card-img-top" alt="No Image">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 80) }}</p>
                        <p class="card-text"><strong>السعر:</strong> {{ $product->price }} جنيه</p>

                        {{-- روابط الفلتر لو تحب --}}
                        <p class="card-text">
                            <small class="text-muted">
                                التصنيف: {{ $product->category->name ?? '-' }} /
                                الفرعي: {{ $product->subcategory->name ?? '-' }}
                            </small>
                        </p>

                        {{-- <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary w-100">عرض التفاصيل</a> --}}
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
