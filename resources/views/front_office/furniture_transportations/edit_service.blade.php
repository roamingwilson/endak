@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
{{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">   {{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }}

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

                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="department_id" value="{{ $service->departments->id }}">
                        <input type="hidden" name="type" value="{{ $service->departments->name_en }}">

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

                                    <input type="checkbox" name="installation[{{ $product->id }}]" id="work_type-installation{{ $product->id }}"
                                        value="1"
                                        class="m-2">{{ $lang == 'ar' ? 'تركيب' : 'Installation' }}
                                </label>
                            </div>
                        </div>
                    @endforeach

                        <label class="mb-1">{{ $lang == 'ar' ? 'من' : 'From' }} :</label>
                        <div class="form-group mt-2">
                            <label class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} :</label>
                            <input type="text" class="form-control" name="from_city" value="{{ old('from_city', $service->from_city) }}">
                            <label class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :</label>
                            <input type="text" class="form-control" name="from_neighborhood" value="{{ old('from_neighborhood', $service->from_neighborhood) }}">
                            <label class="mb-1">{{ $lang == 'ar' ? 'الدور' : 'Home' }} :</label>
                            <input type="text" class="form-control" name="from_home" value="{{ old('from_home', $service->from_home) }}">
                        </div>

                        <hr>

                        <label class="mb-1">{{ $lang == 'ar' ? 'الي' : 'To' }} :</label>
                        <div class="form-group mt-2">
                            <label class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} :</label>
                            <input type="text" class="form-control" name="to_city" value="{{ old('to_city', $service->to_city) }}">
                            <label class="mb-1">{{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :</label>
                            <input type="text" class="form-control" name="to_neighborhood" value="{{ old('to_neighborhood', $service->to_neighborhood) }}">
                            <label class="mb-1">{{ $lang == 'ar' ? 'الدور' : 'Home' }} :</label>
                            <input type="text" class="form-control" name="to_home" value="{{ old('to_home', $service->to_home) }}">
                        </div>

                        <div class="form-group">
                            <label class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }} :</label>
                            <textarea class="form-control" name="notes" cols="30" rows="5">{{ old('notes', $service->notes) }}</textarea>
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
