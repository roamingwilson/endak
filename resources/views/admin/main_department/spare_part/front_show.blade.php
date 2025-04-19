@extends('layouts.home')
@section('title')
    <?php
    $lang = config('app.locale');
    ?>
    {{ $lang == 'ar' ? 'قطع غيار' : "spare part" }}
@endsection
<style>
    .stars-card {
        min-height: 20px;
    }

    .stars-card svg {
        margin-right: 3px;
        color: #818894;
    }

    .stars-card svg.active {
        color: #ffc600;
        fill: #ffc600;
    }

    .stars-card i.active svg {
        color: #ffc600;
        fill: #ffc600;
    }

    .myform {
        max-width: 500px;
        margin: 0 auto;
    }

    .input-group {
        width: 100%;
    }

    .form-control {
        height: 60px;
        font-size: 1.2rem;
        padding: 15px;
        border-radius: 5px 0 0 5px;
        border: 1px solid #ced4da;
    }

    .btn-primary {
        height: 60px;
        font-size: 1.2rem;
        border-radius: 0 5px 5px 0;
    }

    .card-custom {
        width: 100%;
        height: 200px;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: rgba(169, 169, 169, 0.3);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .card-custom img {
        width: 80%;
        height: auto;
        object-fit: contain;
        max-height: 150px;
    }

    .card-custom .card-body {
        text-align: center;
        padding: 10px;
    }

    .cards-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1"> {{ $lang == 'ar' ? 'قطع غيار' : "spare part" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (auth()->check() && auth()->user()->role_id == 3)
        <section class="section mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="cards-container">
                                @foreach ($spare_parts as $item)
                                    <div class="card card-custom ">
                                        @if ($item->image)
                                            <a href="{{ route('spare_part_sub_show' , $item->id) }}" class="d-flex justify-content-center align-items-center">
                                                <img src="{{ $item->image_url }}" class="card-img-top mt-2 "
                                                    alt="{{ $item->name_ar }}">
                                            </a>
                                        @endif
                                        <div class="card-body">
                                            <a href="{{ route('spare_part_sub_show' , $item->id) }}">
                                                <p class="card-text">{{ $lang == 'ar' ? $item->name_ar : $item->name_en }}</p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach



                            </div>

                        </div>
                        {{-- {!! $services->links() !!} --}}
                    </div>
                </div>
        </section>
    @elseif(auth()->check() && auth()->user()->role_id == 1)
        <section class="profile-cover-container mb-2 mt-3">

            <div class="profile-content pt-40">
                <div class="container position-relative d-flex justify-content-center ">
                    <?php $user = auth()->user(); ?>
                    <div class="cards-container">
                        @foreach ($spare_parts as $item)
                            <div class="card card-custom ">
                                @if ($item->image)
                                    <a href="{{ route('spare_part_sub_show', $item->id) }}" class="d-flex justify-content-center align-items-center">
                                        <img src="{{ $item->image_url }}" class="card-img-top mt-2 "
                                            alt="{{ $item->name_ar }}">
                                    </a>
                                @endif
                                <div class="card-body">
                                    <a href="{{ route('spare_part_sub_show', $item->id) }}">
                                        <p class="card-text">{{ $lang == 'ar' ? $item->name_ar : $item->name_en }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach



                    </div>

                </div>


            </div>


        </section>
    @else
        <section class="section overflow-hidden mt-3">
            <div class="container">
                <div class="row">
                    <div class="heading-section">
                        <div class="heading-subtitle">
                            <span class="tx-primary tx-16 fw-semibold"
                                style="color: black;">{{ __('department.departments') }}</span>
                        </div>
                    </div>

                    <div class="cards-container">
                        @foreach ($spare_parts as $item)
                            <div class="card card-custom ">
                                @if ($item->image)
                                    <a href="{{ route('spare_part_sub_show' , $item->id) }}" class="d-flex justify-content-center align-items-center">
                                        <img src="{{ $item->image_url }}" class="card-img-top mt-2 "
                                            alt="{{ $item->name_ar }}">
                                    </a>
                                @endif
                                <div class="card-body">
                                    <a href="{{ route('spare_part_sub_show' , $item->id) }}">
                                        <p class="card-text">{{ $lang == 'ar' ? $item->name_ar : $item->name_en }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach



                    </div>


                </div>


        </section>
    @endif
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
