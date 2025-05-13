@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? $main->name_ar : $main->name_en }}
@endsection

@section('content')
@php
use App\Models\Department;
    $departments = Department::where('name_en', 'maintenance')->first();
    use App\Models\Services;

$services = Services::where('department_id', $departments->id)->latest()->paginate(5);
@endphp
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
                            @forelse ($services as $item)
                             @if (auth()->user()->governement == $item->from_city)
                            <div class="col-md-4">
                                <div class="card">

                                    <div class="position-relative">
                                        <a href="{{ route('show_myservice', $item->id) }}">
                                            @if ($item->image)
                                                <img class="card-img-top" src="{{ $item->image_url }}" alt="img"
                                                    width="300" height="300">
                                            @else
                                                <img class="card-img-top" src="{{ $main->image_url }}"
                                                    alt="nn" width="300" height="300">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5><a href="{{ route('show_myservice', $item->id) }}">
                                                {{ $lang == 'ar' ? $item->name_ar : $item->name_en }}</a></h5>
                                        <div class="tx-muted">
                                            {{ $item->user->full_name }}
                                        </div>
                                        <div class="tx-muted">
                                            {{ $item->created_at->diffForHumans() }}
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @endif
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
                    <form action="{{ route('services.store' ) }}" method="POST" enctype="multipart/form-data"
                        style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <input type="hidden" name="department_id" value="{{ $departments->id }}">
                        <input type="hidden" name="type" value="{{ $departments->name_en }}">
                        <input type="hidden" name="equip_type" value="{{$main->name_ar}}">


                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'شرح عن الخلل' : 'Note About Issues' }} :</label>
                            <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الموديل' : 'Model' }} : </label>
                            <input type="text" class="form-control" name="model">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'سنه الصنع' : 'Facility Year' }} : </label>
                            <input type="number" class="form-control" name="year">
                        </div>

                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }} :</label>
                            <input class="form-control" name="images[]" type="file" multiple>
                        </div>
                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                              <select name="from_city" class="form-control js-select2-custom">
                            <option value="">{{ __('اختر المدينة') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $lang == 'ar' ?  $city->name_ar :$city->name_en  }}
                                </option>
                            @endforeach
                                 </select>
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
    <section class="profile-cover-container mb-2" >

        <div class="profile-content pt-40">
            <div class="container position-relative d-flex justify-content-center ">
                <form action="{{ route('register-page') }}" method="get" enctype="multipart/form-data"
                    style="width:600px;margin-top:10px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'شرح عن الخلل' : 'Note About Issues' }} :</label>
                        <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الموديل' : 'Model' }} : </label>
                        <input type="text" class="form-control" name="model">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'سنه الصنع' : 'Facility Year' }} : </label>
                        <input type="number" class="form-control" name="year">
                    </div>


                    <div class="form-group mt-2">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }} :</label>
                        <input class="form-control" name="images[]" type="file" multiple>
                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
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
