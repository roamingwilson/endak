@extends('layouts.home')
@section('title')
<?php
$lang = config('app.locale');
?>
    {{ $lang == 'ar' ? 'قسم كاميرات مراقبة ' : 'Surveillance Cameras' }}
@endsection

@section('content')

    <div class="main-content app-content">
        <section>
            <div class="section banner-4 banner-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 text-center">
                            <div class="">
                                <p class="mb-3 content-1 h5 fs-1">        {{ $lang == 'ar' ? 'قسم كاميرات مراقبة ' : 'Surveillance Cameras' }}

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
                        {{-- @dd($cars) --}}





                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="department_id" value="{{ $service->departments->id }}">
                        <input type="hidden" name="type" value="{{ $service->departments->name_en }}">


                        <input type="checkbox" name="finger"
                        value="1"
                        class="m-2">{{ $lang == 'ar' ? 'بصمة' : 'Finger' }}
                </label>
                <label for="">

                    <input type="checkbox" name="camera"
                        value="1"
                        class="m-2">{{ $lang == 'ar' ? 'كاميرات مراقبة' : 'Surveillance Cameras' }}
                </label>
                <label for="">

                    <input type="checkbox" name="smart"
                        value="1"
                        class="m-2">{{ $lang == 'ar' ? 'سمارت' : 'Smart' }}
                </label>
                <label for="">

                    <input type="checkbox" name="fire_system"
                        value="1"
                        class="m-2">{{ $lang == 'ar' ? 'انظمة اطفاء حرائق' : 'Fire System' }}
                </label>
                <label for="">

                    <input type="checkbox" name="security_system"
                        value="1"
                        class="m-2">{{ $lang == 'ar' ? 'انظمة امنية'  : 'Security System' }}
                </label>
                <label for="">

                    <input type="checkbox" name="network"
                        value="1"
                        class="m-2">{{ $lang == 'ar' ? 'شبكات' : 'Networks' }}
                </label>
                <div class="form-group mt-2">
                    <label for="" class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }} :</label>
                    <textarea class="form-control" name="notes" cols="30" rows="5"></textarea>
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

