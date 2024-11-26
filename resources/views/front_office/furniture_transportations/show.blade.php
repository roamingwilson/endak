@extends('layouts.home')
@section('title')
    {{ __('department.departments') }}
@endsection

@section('style')
 
@endsection
@section('content')
    <?php
    $lang = config('app.locale');
    ?>
    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">{{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }} </p>
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
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="position-relative">
                                        <a href="{{ route('main_furniture_transportations_show_my_service', $service->id) }}">
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
                                        <h5><a href="{{ route('main_furniture_transportations_show_my_service', $service->id) }}">
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
    @elseif(auth()->check() && auth()->user()->role_id == 1)
        <section class="profile-cover-container mb-2" >

            <div class="profile-content pt-40">
                <div class="container position-relative d-flex justify-content-center ">
                    <?php $user = auth()->user(); ?>
                    <form action="{{ route('furniture_transportations_store_service') }}" method="POST" enctype="multipart/form-data"
                        style="width:700px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                        @csrf
                        @foreach ($products as $product)
                            <div class="form-group mt-2 d-flex align-items-center">
                                <input type="checkbox" name="selected_products[]" id="product-{{ $product->id }}"
                                    value="{{ $product->id }}" class="m-2">

                                <div class="d-flex align-items-center justify-content-between m-2">
                                    <label for="product-{{ $product->id }}" class="ml-2 mr-3" style="min-width: 150px;">
                                        {{ $lang == 'ar' ? $product->name_ar : $product->name_en }}
                                    </label>
                                    <img src="{{ $product->image_url }}" width="50px" height="50px" alt=""
                                        style="margin-right: 15px;">
                                    <input max="10" class="form-control m-2" type="number"
                                        name="quantities[{{ $product->id }}]" placeholder="الكمية"
                                        style="display: none; width: 100px;" id="quantity-{{ $product->id }}"
                                        min="1">
                                </div>
                                <div>
                                    <label for="">
                                        <input type="checkbox" name="disassembly[{{ $product->id }}]" id="work_type-{{ $product->id }}"
                                            value="1" class="m-2">{{ $lang == 'ar' ? 'فك' : 'Disassembly' }}
                                    </label>
                                    <label for="">

                                        <input type="checkbox" name="installation[{{ $product->id }}]" id="work_type-{{ $product->id }}"
                                            value="1"
                                            class="m-2">{{ $lang == 'ar' ? 'تركيب' : 'Installation' }}
                                    </label>
                                </div>
                            </div>
                        @endforeach

                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'من' : 'From' }} : </label>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="from_city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="from_neighborhood">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الدور' : 'Home' }} : </label>
                            <input type="text" class="form-control" name="from_home">
                        </div>
                        <hr>
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الي' : 'To' }} : </label>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="to_city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :
                            </label>
                            <input type="text" class="form-control" name="to_neighborhood">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الدور' : 'Home' }} : </label>
                            <input type="text" class="form-control" name="to_home">
                        </div>
                        <div class="form-group">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }} :</label>
                            <textarea class="form-control" name="notes" id="" cols="30" rows="5"></textarea>
                        </div>
                        <div class="form-group mt-2" style="text-align: right;margin-right:10px">
                            <button class="btn mt-2 form-control"  style="background-color: #fdca3d">{{ __('general.save') }}</button>
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
                                        <a href="{{ route('main_furniture_transportations_show_my_service', $service->id) }}">
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
                                        <h5><a href="{{ route('main_furniture_transportations_show_my_service', $service->id) }}">
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
