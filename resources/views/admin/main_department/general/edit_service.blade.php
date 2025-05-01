@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? 'شاحنات' : "Van Truck" }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">    {{ ($lang == 'ar')? 'شاحنات' : "Van Truck" }}

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

        <section class="profile-cover-container mb-2">
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

                {{-- <div class="container position-relative d-flex justify-content-center ">

                    <form action="{{route('service_van_truck.update')}} " method="POST" enctype="multipart/form-data"
                        style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" value="{{ $user->id }}">


                        <div class="form-group mt-2">
                            <label for=""
                                class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }}
                                :</label>
                            <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
                        </div>

                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}
                                :</label>
                            <input class="form-control" name="images[]" type="file" multiple>
                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="to_city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="to_neighborhood">

                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                            <input type="text" class="form-control" name="from_city">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                            <input type="text" class="form-control" name="from_neighborhood">

                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'ألموفع' : 'location' }} : </label>
                            <input type="text" class="form-control" name="location">
                        </div>
                        <hr>
                        <div class="form-group mt-2" style="text-align: right;margin-right:10px">
                            <button class="btn mt-2 form-control"
                                style="background-color: #fdca3d">{{ $lang == 'ar' ? 'ارسال' : 'Send' }}</button>
                        </div>
                    </form>


                </div> --}}
                <div class="container position-relative d-flex justify-content-center mt-4">
                    <?php $user = auth()->user(); ?>
                    <form action="{{route('service_van_truck.update',$service->id)}}" method="POST" enctype="multipart/form-data"
                        style="width: 600px;" class="profile-card rounded-lg shadow-xs bg-white p-4">
                        @csrf
                        @method('PUT')
                        {{-- @dd($cars) --}}

                        <div class="form-group mt-2">
                            <label>{{ $lang == 'ar' ? 'نوع السيارة' : 'Truck Type' }}</label>
                            <select name="vantruck_id" class="form-control" required>
                                <option value="">{{ $lang == 'ar' ? 'اختر نوع السيارة' : 'Choose Truck Type' }}</option>
                                @foreach ($cars as $van)
                                    <option value="{{ $van->id }}" {{ $service->vantruck_id == $van->id ? 'selected' : '' }}>
                                        {{ $lang == 'ar' ? $van->name_ar : $van->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="form-group">
                            <label class="mb-2">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }}</label>
                            <textarea class="form-control" name="notes" rows="4">{{ old('notes', $service->notes) }}</textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label class="mb-2">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                            @if($service->images)
                                <div class="mt-2">
                                    <label class="fw-bold">{{ $lang == 'ar' ? 'الصور الحالية:' : 'Current Images:' }}</label>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        @foreach ($service->images as $image)
                                            <img src="{{ asset($image->image_url) }}" width="80" height="80" class="rounded border" />
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mt-3">
                            <label class="mb-2">{{ $lang == 'ar' ? 'الى المدينة' : 'To City' }}</label>
                            <input type="text" name="to_city" class="form-control" value="{{ old('to_city', $service->to_city) }}">

                            <label class="mb-2 mt-2">{{ $lang == 'ar' ? 'الى الحي' : 'To Neighborhood' }}</label>
                            <input type="text" name="to_neighborhood" class="form-control" value="{{ old('to_neighborhood', $service->to_neighborhood) }}">
                        </div>

                        <div class="form-group mt-3">
                            <label class="mb-2">{{ $lang == 'ar' ? 'من المدينة' : 'From City' }}</label>
                            <input type="text" name="from_city" class="form-control" value="{{ old('from_city', $service->from_city) }}">

                            <label class="mb-2 mt-2">{{ $lang == 'ar' ? 'من الحي' : 'From Neighborhood' }}</label>
                            <input type="text" name="from_neighborhood" class="form-control" value="{{ old('from_neighborhood', $service->from_neighborhood) }}">
                        </div>

                        <div class="form-group mt-3">
                            <label class="mb-2">{{ $lang == 'ar' ? 'الموقع' : 'Location' }}</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $service->location) }}">
                        </div>
                        <div class="form-group mt-2">
                            <label>{{ $lang == 'ar' ? 'الوقت' : 'Time' }}</label>
                            <input type="time" class="form-control" name="time" value="{{ old('time', \Carbon\Carbon::parse($service->time)->format('H:i')) }}" required>
                        </div>

                        <hr>

                        <div class="form-group mt-3 text-end">
                            <button type="submit" class="btn btn-warning w-100">
                                {{ $lang == 'ar' ? 'تحديث' : 'Update' }}
                            </button>
                        </div>
                    </form>
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
