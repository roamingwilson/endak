@extends('layouts.home')
@section('title')
    <?php
    $lang = config('app.locale');
    ?>
    {{ $lang == 'ar' ? 'معدات ثقيلة' : 'Heavy equipment' }}
@endsection
@php
    $mains = APP\Models\HeavyEquipment::where('heavy_equip_id', !0)->get();
@endphp
@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1"> {{ $lang == 'ar' ? 'معدات ثقيلة' : 'Heavy equipment' }}

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="profile-cover-container mb-2">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-content pt-40">


            <div class="container position-relative d-flex justify-content-center mt-4">
                <?php $user = auth()->user(); ?>
                <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data"
                    style="width: 100%;" class="profile-card rounded-lg shadow-xs bg-white p-4">
                    @csrf
                    @method('PUT')
                    {{-- @dd($cars) --}}
                    @foreach ($service->images as $item)
                        <img width="80px" height="80px" src="{{ asset('storage/' . $item->path) }}" alt="">
                    @endforeach



                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="department_id" value="{{ $service->departments->id }}">
                    <input type="hidden" name="type" value="{{ $main->name_en }}">


                    <div class="form-group">
                        <label class="mb-2">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }}</label>
                        <textarea class="form-control" name="notes" rows="4">{{ old('notes', $service->notes) }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label class="mb-2">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        @if ($service->images)
                            <div class="mt-2">
                                <label class="fw-bold">{{ $lang == 'ar' ? 'الصور الحالية:' : 'Current Images:' }}</label>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @foreach ($service->images as $image)
                                        <img src="{{ asset($image->image_url) }}" width="80" height="80"
                                            class="rounded border" />
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>



                    <div class="form-group mt-3">

                        <div class="form-group mt-2">
                            <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الموقع' : 'Location' }} : </label>
                            <select name="from_city" class="form-control js-select2-custom">
                                <option value="">{{ __('اختر المدينة') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">
                                        {{ $lang == 'ar' ? $city->name_ar : $city->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label>{{ $lang == 'ar' ? 'الوقت' : 'Time' }}</label>
                        <input type="time" class="form-control" name="time"
                            value="{{ old('time', \Carbon\Carbon::parse($service->time)->format('H:i')) }}" required>
                    </div>
                    <div class="voice-note-container">
                        <div id="recordingStatus" style="margin-bottom: 8px; color: #d9534f; display: none;"></div>
                        <button id="startRecord" class="btn btn-primary">{{ $lang == 'ar' ? 'بدء التسجيل' : 'Start Recording' }}</button>
                        <button id="stopRecord" class="btn btn-danger" disabled>{{ $lang == 'ar' ? 'ايقاف التسجيل' : 'Stop Recording' }}</button>
                        <button id="resetRecord" class="btn btn-secondary" style="display:none;">{{ $lang == 'ar' ? 'إعادة التسجيل' : 'Reset Recording' }}</button>
                        <span id="recordingTimer" style="margin-left: 10px; font-weight: bold; display:none;">00:00</span>
                        <audio id="audioPlayback" controls style="display: none; margin-top: 10px;"></audio>
                        <a id="downloadLink" style="display: none; margin-top: 10px;" class="btn btn-success">{{ $lang == 'ar' ? 'تنزيل التسجيل' : 'Download Recording' }}</a>
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
