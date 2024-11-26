@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? 'أسر منتجة' : "Productive Families" }}
@endsection
 
@section('content')
 
    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">{{ ($lang == 'ar')? 'أسر منتجة' : "Productive Families" }}
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
                                        <a href="{{ route('main_family_show_my_service', $service->id) }}">
                                            @if ($service->image)
                                                <img class="card-img-top" src="{{ $service->image_url }}" alt="img"
                                                    width="300" height="300">
                                            @else
                                                <img class="card-img-top" src="{{ $main->image_url }}"
                                                    alt="nn" width="300" height="300">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5><a href="{{ route('main_family_show_my_service', $service->id) }}">
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
        <section class="profile-cover-container mb-2" >

            <div class="profile-content pt-40">
                <div class="container position-relative d-flex justify-content-center ">
                    <?php $user = auth()->user(); ?>
                    <form action="{{ route('family_store_service') }}" method="POST" enctype="multipart/form-data"
                        style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="form-group mt-2"> 
                            <label for="name" class="mb-1 mt-2">{{ $lang == 'ar' ? 'نوع الاكل' : 'Food Type' }} : </label>
                            <input type="text" class="form-control" name="type">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الوقت' : 'Time' }} : </label>
                            <input type="time" class="form-control" name="time">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'التاريخ' : 'Date' }} : </label>
                            <input type="date" class="form-control" name="date">
                        </div>

                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }} :</label>
                            <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
                        </div>
                        
                        
                        <div class="form-group mt-2"> 
                            <label for="name" class="mb-1 mt-2">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="neighborhood">
                        </div>
                        <hr>
                        <div class="form-group mt-2" style="text-align: right;margin-right:10px">
                            <button class="btn mt-2 form-control"  style="background-color: #fdca3d">{{ $lang == 'ar' ? 'ارسال' : 'Send' }}</button>
                        </div>
                    </form>


                </div>


            </div>


        </section>
    @else
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        @forelse ($services as $service)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="position-relative">
                                        <a href="{{ route('main_family_show_my_service', $service->id) }}">
                                            @if ($service->image)
                                                <img class="card-img-top" src="{{ $service->image_url }}" alt="img"
                                                    width="300" height="300">
                                            @else
                                                <img class="card-img-top" src="{{ asset('images/logo2.jpg') }}"
                                                    alt="nn" width="300" height="300">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5><a href="{{ route('main_family_show_my_service', $service->id) }}">
                                                {{ $lang == 'ar' ? $service->name_ar : $service->name_en }}</a></h5>
                                        <div class="tx-muted">
                                            {{ $service->user->first_name .' '. $service->user->first_name }}
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
