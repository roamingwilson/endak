@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
    {{ ($lang == 'ar')?  'تصليح تكييف' : "air condithion" }}

@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">    {{ ($lang == 'ar')? 'تصليح تكييف' : "air condithion" }}

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (auth()->check() && auth()->user()->role_id == 3)
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            @forelse ($services as $service)
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="position-relative">
                                            <a href="{{ route('main_spare_part_show_my_service', $service->id) }}">
                                                @if ($service->image)
                                                    <img class="card-img-top" src="{{ $service->image_url }}" alt="img"
                                                        width="300" height="300">
                                                @else
                                                    <img class="card-img-top" src="{{ $main->image_url }}" alt="nn"
                                                        width="300" height="300">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5><a href="{{ route('main_spare_part_show_my_service', $service->id) }}">
                                                    {{ $lang == 'ar' ? $service->name_ar : $service->name_en }}</a></h5>
                                            <div class="tx-muted">
                                                {{ $service->user->full_name }}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @empty
                                {!! no_data() !!}
                            @endforelse
                        </div>
                        {!! $services->links() !!}
                    </div>
                </div>
        </section>
    @elseif(auth()->check() && auth()->user()->role_id == 1)
        <section class="profile-cover-container mb-2">

            <div class="profile-content pt-40">
                <div class="container position-relative d-flex justify-content-center ">
                    <?php $user = auth()->user(); ?>
                    <form action="{{ route('air_con_store_service') }}" method="POST" enctype="multipart/form-data"
                        style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">


                        <div class="form-group">
                            <label for="name" class="mb-1"> {{ $lang == 'ar' ? ' نوع المكيف' : 'Aircon name' }}</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="split" name="split" value="1">
                                    <label for="split" class="mb-1"> {{ $lang == 'ar' ? '  سبليت' : 'split' }}</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="window" name="window" value="1">
                                    <label for="window" class="mb-1"> {{ $lang == 'ar' ? '  شباك' : 'window' }}</label>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="name" class="mb-1"> {{ $lang == 'ar' ? ' نوع الخدمة المطلوبة' : 'service' }}</label>
                                <div class="radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="clean" name="clean" value="1">
                                        <label for="clean" class="mb-1"> {{ $lang == 'ar' ? '  تنظيف' : 'clean' }}</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="feryoun" name="feryoun" value="1">
                                        <label for="feryoun" class="mb-1"> {{ $lang == 'ar' ? '  فريون' : 'feryoun' }}</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="maintance" name="maintance" value="1">
                                        <label for="maintance" class="mb-1"> {{ $lang == 'ar' ? '  اصلاح عطل' : 'maintance' }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="model" class="mb-1"> {{ $lang == 'ar' ? 'الماركة' : 'model' }}</label>
                                        <input type="text" id="model" name="model" placeholder="أدخل ماركة المكيف">
                                    </div>

                                    <div class="form-group">
                                        <label for="quantity" class="mb-1"> {{ $lang == 'ar' ? 'العدد' : 'quantity' }}</label>
                                        <input type="number" id="quantity" min="1" value="1" name="quantity">
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}
                                :</label>
                            <input class="form-control" name="images[]" type="file" multiple>
                        </div>

                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="neighborhood">

                        </div>
                        <div class="form-group mt-2">
                            <label for="time" class="mb-1">{{ $lang == 'ar' ? 'الوقت' : 'Time' }} : </label>
                            <input type="time" class="form-control" name="time">
                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'التاريخ' : 'data' }} : </label>
                            <input type="date" class="form-control" name="date">
                        </div>


                        <hr>

                        <div class="form-group mt-2" style="text-align: right;margin-right:10px">
                            <button class="btn mt-2 form-control"
                                style="background-color: #fdca3d">{{ $lang == 'ar' ? 'ارسال' : 'Send' }}</button>
                        </div>
                    </form>


                </div>


            </div>


        </section>
    @else
    <section class="profile-cover-container mb-2">

        <div class="profile-content pt-40">
            <div class="container position-relative d-flex justify-content-center ">
                <form action="{{ route('register-page') }}" method="get" enctype="multipart/form-data"
                    style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="mb-1"> {{ $lang == 'ar' ? ' نوع المكيف' : 'Aircon name' }}</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="split" name="ac-type" value="1">
                                <label for="split" class="mb-1"> {{ $lang == 'ar' ? '  سبليت' : 'split' }}</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="window" name="ac-type" value="1">
                                <label for="window" class="mb-1"> {{ $lang == 'ar' ? '  شباك' : 'window' }}</label>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="name" class="mb-1"> {{ $lang == 'ar' ? ' نوع الخدمة المطلوبة' : 'service' }}</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="clean" name="clean" value="1">
                                    <label for="clean" class="mb-1"> {{ $lang == 'ar' ? '  تنظيف' : 'clean' }}</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="feryoun" name="feryoun" value="1" >
                                    <label for="feryoun" class="mb-1"> {{ $lang == 'ar' ? '  فريون' : 'feryoun' }}</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="maintance" name="maintance"  value="1" >
                                    <label for="maintance" class="mb-1"> {{ $lang == 'ar' ? '  اصلاح عطل' : 'maintance' }}</label>
                                </div>
                                <div class="form-group">
                                    <label for="model" class="mb-1"> {{ $lang == 'ar' ? 'الماركة' : 'model' }}</label>
                                    <input type="text" id="model" name="model" placeholder="أدخل ماركة المكيف">
                                </div>

                                <div class="form-group">
                                    <label for="quantity" class="mb-1"> {{ $lang == 'ar' ? 'العدد' : 'quantity' }}</label>
                                    <input type="number" id="quantity" min="1" value="1" name="quantity">
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="form-group mt-2">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}
                            :</label>
                        <input class="form-control" name="images[]" type="file" multiple>
                    </div>

                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                        <input type="text" class="form-control" name="city">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                        <input type="text" class="form-control" name="neighborhood">

                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الوقت' : 'Time' }} : </label>
                        <input type="time" class="form-control" name="time">
                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'التاريخ' : 'data' }} : </label>
                        <input type=" datetime" class="form-control" name="date">
                    </div>


                    <hr>

                    <div class="form-group mt-2" style="text-align: right;margin-right:10px">
                        <button class="btn mt-2 form-control"
                            style="background-color: #fdca3d">{{ $lang == 'ar' ? 'ارسال' : 'Send' }}</button>
                    </div>
                </form>


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
