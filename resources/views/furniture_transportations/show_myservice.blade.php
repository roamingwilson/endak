@extends('layouts.home')
@section('title')
    {{ __('department.departments') }}
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
                                <p class="mb-3 content-1 h5 text-white">{{ __('department.departments') }} <span
                                        class="tx-info-dark">{{ __('general.center') }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- @if (auth()->user()->id == $service->user_id) --}} {{-- @endif --}}

    <section class="profile-cover-container mb-2">

        <div class="profile-content pt-40">
            <div class="container position-relative d-flex justify-content-center ">
                <?php $user = auth()->user(); ?>
                <div style="width:100%" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">

                    @foreach ($products as $product)
                        <div class="form-group mt-2 d-flex align-items-center justify-content-between"
                            style="gap: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                            <label class="ml-2 mr-3" style="min-width: 150px;">
                                {{ $lang == 'ar' ? $product->product_item->name_ar : $product->product_item->name_en }}
                            </label>
                            <img src="{{ $product->product_item->image_url }}" width="50px" height="50px" alt=""
                                style="margin-right: 15px;">
                            <label>
                                {{ $lang == 'ar' ? 'الكمية' : 'Quantity' }}: {{ $product->quantity }}
                            </label>
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                @if (isset($product->disassembly) && $product->disassembly == 1)
                                    <label>{{ $lang == 'ar' ? 'فك' : 'disassembly' }}</label>
                                @endif
                                @if (isset($product->disassembly) &&
                                        $product->disassembly == 1 &&
                                        isset($product->installation) &&
                                        $product->installation == 1)
                                    <label>{{ $lang == 'ar' ? ' و ' : ' And ' }}</label>
                                @endif
                                @if (isset($product->installation) && $product->installation == 1)
                                    <label>{{ $lang == 'ar' ? 'تركيب' : 'installation' }}</label>
                                @endif
                            </div>
                        </div>
                    @endforeach


                    <label for="name" class="mb-1">{{ $lang == 'ar' ? 'من' : 'From' }} : </label>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} :
                            {{ $service->from_city }} || {{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :
                            {{ $service->from_neighborhood }} || {{ $lang == 'ar' ? 'الدور' : 'Home' }} :
                            {{ $service->from_home }}</label>

                    </div>
                    <hr>
                    <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الي' : 'To' }} : </label>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} :
                            {{ $service->to_city }} || {{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :
                            {{ $service->to_neighborhood }} || {{ $lang == 'ar' ? 'الدور' : 'Home' }} :
                            {{ $service->to_home }}</label>

                    </div>
                    <div class="form-group">
                        <label for=""
                            class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }}
                            :</label>
                        @if (isset($service->notes))
                            <p>{{ $service->notes }}</p>
                        @else
                            {{ $lang == 'ar' ? 'لا يوجد ملاحظات' : 'No Notes' }}
                        @endif
                    </div>

                </div>
            </div>

        </div>
        </div>


    </section>
    <section class="section d-flex align-items-center justify-content-center">
        <div class="container" >
            <div class="row justify-content-center" >
                <div class="col-xl-8" >
                    <div class="card" >
                        <div class="card-body pb-0 align-items-center" style="height: 100%;">
                            <h5 class="mb-4 d-flex align-items-center justify-content-center">
                                {{ $lang == 'ar' ? 'العروض' : 'Offers' }}</h5>
                            <div class="d-block mb-4 overflow-visible d-block d-sm-flex">

                                <div class="row">
                                    <div class="container">
                                        <div class="d-flex flex-wrap justify-content-between">
                                            @forelse ($service->comments as $comment)
                                                <div class="border mb-4 p-4 br-5"
                                                    style="flex: 1 1 calc(33.333% - 1rem); margin: 0.5rem;">
                                                    <div class="d-flex align-items-center">
                                                        <h5 class="mt-0 mr-3">
                                                            {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                                                        </h5>
                                                        <a class="dropdown-item mb-2" href="{{ route('web.send_message', $comment->user->id) }}">
                                                            <i class="fe fe-mail mx-1"></i> {{ __('messages.send_message') }}
                                                        </a>
                                                    </div>

                                                    @if (isset($comment->price))
                                                        <p>{{ __('general.price') . ' : ' . $comment->price }}</p>
                                                    @endif
                                                    @if (isset($comment->body))
                                                        <p>{{ 'Body : ' . $comment->body }}</p>
                                                    @endif
                                                    @if (isset($comment->date))
                                                        <p>{{ __('general.date') . ' : ' . $comment->date }}</p>
                                                    @endif
                                                    @if (isset($comment->time))
                                                        <p>{{ __('general.time') . ' : ' . \Carbon\Carbon::parse($comment->time)->format('h:i A') }}</p>
                                                    @endif
                                                    @if (isset($comment->city))
                                                        <p>{{ ($lang == 'ar' ? 'المدينة' : 'City') . ' : ' . $comment->city }}
                                                        </p>
                                                    @endif
                                                    @if (isset($comment->neighborhood))
                                                        <p>{{ ($lang == 'ar' ? 'الحي' : 'Neighborhood') . ' : ' . $comment->neighborhood }}
                                                        </p>
                                                    @endif
                                                    @if (isset($comment->location))
                                                        <p>{{ ($lang == 'ar' ? 'الموقع' : 'Location') . ' : ' . $comment->location }}
                                                        </p>
                                                    @endif
                                                    @if (isset($comment->day))
                                                        <p>{{ __('general.day') . ' : ' . $comment->day }}</p>
                                                    @endif
                                                    @if (isset($comment->number_of_days_of_warranty))
                                                        <p>{{ ($lang == 'ar' ? 'عدد ايام الضمان' : 'Number of Days of Warranty') . ' : ' . $comment->number_of_days_of_warranty }}
                                                        </p>
                                                    @endif
                                                    @if (isset($comment->notes))
                                                        <p>{{ ($lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes') . ' : ' . $comment->notes }}
                                                        </p>
                                                    @endif
                                                </div>

                                            @empty
                                                {!! no_data() !!}
                                            @endforelse
                                        </div>
                                    </div>

                                </div>
                            </div>




                        </div>

                        <?php
                        $user = auth()->user();
                        $is_add = App\Models\GeneralComments::where('commentable_type' , 'App\Models\FurnitureTransportationService')->where('commentable_id', $service->id)
                            ->where('service_provider', $user->id)
                            ->first();
                        ?>

                    </div>
                    @if ($user->id != $service->user_id && $service->status == 'open' && $is_add == null)
                        <div class="card">
                            <div class="card-body">
                                <p class="h5 mb-4">{{ $lang == 'ar' ? 'اضافة عرض' : 'Add Offer' }}</p>
                                <form class="form-horizontal  m-t-20" action="{{ route('general_comments.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{ $service->id }}" name="service_id">

                                    <div>
                                        <label class="mb-2" for="">{{ __('general.price') }}</label>
                                        <input class="form-control mb-2" type="text" name="price">
                                    </div>
                                    <div>
                                        <label class="mb-2" for="">{{ __('general.day') }}</label>
                                        <input class="form-control mb-2" type="text" name="day">
                                    </div>
                                    <div>
                                        <label class="mb-2" for="">{{ __('general.time') }}</label>
                                        <input class="form-control mb-2" type="time" name="time">
                                    </div>
                                    <div class="">
                                        <button type="submit" class="btn btn-primary">{{ __('general.save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
{{-- @section('script')
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


@endsection --}}
<div class="me-3 mb-3">
    {{-- <a href="javascript:void(0);"> <img class="avatar avatar-lg rounded-circle thumb-sm"
alt="64x64" src="../assets/images/profile/2.jpg"> </a> --}}
</div>
<div class="overflow-visible">
    {{-- @forelse ($post->comments as $comment)
<div class="border mb-4 p-4 br-5">


<h5 class="mt-0">
{{ $comment->user->first_name . ' ' . $comment->user->last_name }}</h5>
<p class="tx-muted"> {{ $comment->description }}</p>
@if (isset($comment->files))
@foreach ($comment->files as $item)

<img width="100px" height="100px" src="{{ Storage::url( $item->file) }}" alt="">
<a href="{{ Storage::url($item->file) }}" target="_blank">Download</a>
@endforeach
@endif

@if ($user->id == $post->user_id)
<form action="{{ route('web.order.save') }}" method="post">
    @csrf

    <button type="submit"
        class="btn btn-primary-transparent btn-sm rounded-pill"
        role="button" data-bs-toggle="reply-form"
        data-bs-target="comment-1">
        <i
            class="fe fe-corner-up-left me-2"></i>{{ __('order.accept_offer') }}
    </button>
</form>
@endif
<a class="dropdown-item  mb-2"
href=" "><i
    class="fe fe-mail mx-1"></i> {{ __('messages.send_message') }}</a>

</div>

@empty
{!! no_data() !!}
@endforelse --}}
