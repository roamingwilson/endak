@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? $main->name_ar : $main->name_en }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">{{ ($lang == 'ar')? $main->name_ar : $main->name_en }}
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
                                        <a href="{{ route('main_contracting_show_my_service', $service->id) }}">
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
                                        <h5><a href="{{ route('main_contracting_show_my_service', $service->id) }}">
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
             @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
                @endif
            <div class="profile-content pt-40">
                <div class="container position-relative d-flex justify-content-center ">
                    <?php $user = auth()->user(); ?>
                    <form action="{{ route('van_truck_store_service' ) }}" method="POST" enctype="multipart/form-data"
                        style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="vantruck_id" value="{{ $main->id }}">





                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? ' من المدينة ' : 'City' }} : </label>
                            <input type="text" class="form-control" name="from_city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? ' من الحي ' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="from_neighborhood">


                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? ' الي المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="to_city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? ' الي الحي' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="to_neighborhood">


                        </div>
                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }} :</label>
                            <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الموقع' : 'Location' }} : </label>
                            <input type="text" class="form-control" name="location">
                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? ' التاريخ والوقت' : 'Time' }} : </label>
                            <input type="time" class="form-control" name="time">


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
    <section class="profile-cover-container mb-2" >

        <div class="profile-content pt-40">
            <div class="container position-relative d-flex justify-content-center ">
                <form action="{{ route('register-page') }}" method="get" enctype="multipart/form-data"
                    style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                    @csrf

                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? ' من المدينة ' : 'from City' }} : </label>
                        <input type="text" class="form-control" name="from_city">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? ' من الحي ' : 'from Neighborhood' }} : </label>
                        <input type="text" class="form-control" name="from_neighborhood">


                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? ' الي المدينة' : 'to City' }} : </label>
                        <input type="text" class="form-control" name="to_city">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? ' الي الحي' : 'to Neighborhood' }} : </label>
                        <input type="text" class="form-control" name="to_neighborhood">


                    </div>
                    <div class="form-group mt-2">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }} :</label>
                        <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الموقع' : 'Location' }} : </label>
                        <input type="text" class="form-control" name="location">
                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? ' التاريخ والوقت' : 'Time' }} : </label>
                        <input type="time" class="form-control" name="time">


                    </div>
                    <hr>
                    <div class="form-group mt-2" style="text-align: right;margin-right:10px">
                        <button class="btn mt-2 form-control"  style="background-color: #fdca3d">{{ $lang == 'ar' ? 'ارسال' : 'Send' }}</button>
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
