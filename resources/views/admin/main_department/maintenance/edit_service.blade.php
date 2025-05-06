@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
   {{ ($lang == 'ar')? 'صيانة السيارات' : "Maintenance" }}
@endsection
@php
    $mains =APP\Models\Maintenance::where('maintenance_id',!0)->get();
@endphp
@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">       {{ ($lang == 'ar')? 'صيانة السيارات' : "Maintenance" }}

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


                <div class="container position-relative d-flex justify-content-center mt-4">
                    <?php $user = auth()->user(); ?>
                    <form action="{{route('services.update',$service->id)}}" method="POST" enctype="multipart/form-data"
                        style="width: 100%;" class="profile-card rounded-lg shadow-xs bg-white p-4">
                        @csrf
                        @method('PUT')

                        @foreach ($service->images as $item)
                        <img width="80px" height="80px" src="{{ asset('storage/' . $item->path) }}"
                            alt="">
                    @endforeach





                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="department_id" value="{{ $service->departments->id }}">
                        <input type="hidden" name="type" value="{{ $service->departments->name_en }}">


                        <div class="form-group mt-2">
                            <label for="" class="mb-1">{{ $lang == 'ar' ? 'شرح عن الخلل' : 'Note About Issues' }} :</label>
                            <textarea class="form-control" name="notes" cols="30" rows="5">{{ old('notes', $service->notes) }}</textarea>
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الموديل' : 'Model' }} : </label>
                            <input type="text" class="form-control" name="model" value="{{ old('model', $service->model) }}">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'سنه الصنع' : 'Facility Year' }} : </label>
                            <input type="number" class="form-control" name="year" value="{{ old('year', $service->year) }}">
                        </div>



                        <div class="form-group mt-3">
                            <label class="mb-2">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                            <div class="form-group mt-2">
                                <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                                <input type="text" class="form-control" name="city" value="{{ old('city', $service->city) }}">
                                <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                                <input type="text" class="form-control" name="neighborhood" value="{{ old('neighborhood', $service->neighborhood) }}">

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
