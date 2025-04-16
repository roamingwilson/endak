@extends('layouts.home')
@section('title')
    <?php
    $lang = config('app.locale');
    ?>
    {{ $lang == 'ar' ? $main->name_ar . ' - ' . $sub->name_ar : $main->name_en . ' - ' . $sub->name_en }}
@endsection
@section('style')
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .card-price {
            color: #007bff;
            font-size: 1rem;
        }

        .btn-chat {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }

        .btn-chat:hover {
            background-color: #e0a800;
        }
    </style>
@endsection
@section('content')
    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">
                                    {{ $lang == 'ar' ? $main->name_ar . ' - ' . $sub->name_ar : $main->name_en . ' - ' . $sub->name_en }}

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="section overflow-hidden">
        {{-- <img src="../assets/images/patterns/2.png" alt="img" class="patterns-6 op-1 z-index-0 top-14p">
        <img src="../assets/images/patterns/7.png" alt="img" class="patterns-5 left-0 transform-rotate-180 z-index-0"> --}}
        <div class="container mt-5">
            <div class="row g-4">
                @forelse ($products as $product)
                    <div class="col-6 col-sm-6 col-lg-3">
                        <div class="card text-center h-100">
                            <img src="{{ asset($product->image_url) }}" class="card-img-top" alt="المواد الخام">
                            <div class="card-body">
                                <h5 class="card-title">{{ $lang == 'ar' ? $product->name_ar : $product->name_en }}</h5>
                                <p class="card-price">{{ ($product->price ?? 5 ) . " ريال"}} </p>
                                <a href="#" class="btn btn-chat">
                                    <i class="bi bi-chat"></i>
                                    ابدأ المحادثة مع صاحب المنتج
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">لا توجد منتجات لعرضها</p>
                @endforelse
            </div>
        </div>
        


    </section>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script>
        document.querySelectorAll('input[type=checkbox][name="selected_products[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const quantityInput = document.getElementById(`quantity-${this.value}`);
                if (this.checked) {
                    quantityInput.style.display = 'block';
                } else {
                    quantityInput.style.display = 'none';
                    quantityInput.value = '';
                }
            });
        });
    </script>
@endsection
