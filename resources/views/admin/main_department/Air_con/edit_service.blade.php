@extends('layouts.home')
@section('title')
    <?php
    $lang = config('app.locale');
    ?>
    {{ $lang == 'ar' ? 'تصليح تكييفات' : 'air condition' }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1"> {{ $lang == 'ar' ? 'تصليح تكييفات' : 'air condition' }}

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

                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="department_id" value="{{ $service->departments->id }}">
                    <input type="hidden" name="type" value="{{ $service->departments->name_en }}">


                    @foreach ($service->images as $item)
                        <img width="80px" height="80px" src="{{ asset('storage/' . $item->path) }}" alt="">
                    @endforeach




                    <div class="mb-3">
                        <label class="form-label">{{ $lang == 'ar' ? ' نوع المكيف' : 'Aircon name' }} </label>
                        <div class="radio-group" role="group" aria-label="Aircon Type">
                            <input type="radio" class="radio-groupk" name="split" id="split" value="1"
                                autocomplete="off">
                            <label for="split">{{ $lang == 'ar' ? 'سبليت' : 'Split' }}</label>

                            <input type="radio" class="radio-groupk" name="window" id="window" value="1"
                                autocomplete="off">
                            <label for="window">{{ $lang == 'ar' ? 'شباك' : 'Window' }}</label>
                        </div>
                    </div>

                    <!-- نوع الخدمة -->
                    <div class="mb-3">
                        <label class="form-label"> {{ $lang == 'ar' ? ' نوع الخدمة المطلوبة' : 'service' }}</label>
                        <div class="d-flex flex-wrap gap-3 checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="clean" name="clean"
                                    value="1">
                                <label class="form-check-label"
                                    for="clean">{{ $lang == 'ar' ? '  تنظيف' : 'clean' }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="freon" name="feryoun"
                                    value="1">
                                <label class="form-check-label" for="freon">
                                    {{ $lang == 'ar' ? '  فريون' : 'feryoun' }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="repair" name="maintance"
                                    value="1">
                                <label class="form-check-label" for="repair">
                                    {{ $lang == 'ar' ? '  اصلاح عطل' : 'maintance' }}</label>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">

                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الماركة' : 'model' }}: </label>
                        <input type="text" class="form-control" name="model">
                    </div>


                    <div class="mb-3">
                        <label class="form-label">{{ $lang == 'ar' ? 'العدد' : 'quantity' }}</label>
                        <div class="input-group" style="width: 140px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty()">-</button>
                            <input type="number" id="quantity" name="quantity" class="form-control text-center"
                                value="1" min="1" max="50" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseQty()">+</button>
                        </div>
                    </div>


                    <div class="form-group mt-2">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'ارفاق صور' : 'Share Photos' }}
                            :</label>
                        <input class="form-control" name="images[]" type="file" multiple>
                    </div>

                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} : </label>
                        <select name="from_city" class="form-control js-select2-custom">
                            <option value="">{{ __('اختر المدينة') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $lang == 'ar' ? $city->name_ar : $city->name_en }}
                                </option>
                            @endforeach
                        </select>
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} : </label>
                        <input type="text" class="form-control" name="neighborhood">

                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الوقت' : 'Time' }} : </label>
                        <input type="time" class="form-control" name="time">
                    </div>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'التاريخ' : 'date' }} : </label>
                        <input type="date" class="form-control" name="date">
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

        function increaseQty() {
            let qtyInput = document.getElementById("quantity");
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQty() {
            let qtyInput = document.getElementById("quantity");
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }
    </script>
@endsection
