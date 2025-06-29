@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'صهريج مياة' : 'Water Tank' }}
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
                                <p class="mb-3 content-1 h5 text-white">
                                    {{ $lang == 'ar' ? 'صهريج مياة' : 'Water Tank' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>



    <section class="profile-cover-container mb-2">

        <div class="profile-content pt-40">
            <div class="container position-relative d-flex justify-content-center ">
                <?php $user = auth()->user(); ?>
                <div style="width:100%" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">
                    <div class="form-group mt-2">
                        @if (isset($service->images))
                            @foreach ($service->images as $item)
                                <img width="80px" height="80px" src="{{ asset('storage/' . $item->path) }}"
                                    alt="">
                            @endforeach
                        @endif
                    </div>

                    @if ($service->drink_width != 0)
                        <div class="form-group mt-2 d-flex align-items-center">


                            <div class="d-flex align-items-center justify-content-between m-2">
                                <label class="ml-2 mr-3" style="min-width: 150px;">
                                    {{ $lang == 'ar' ? 'مياة صالحة للشرب' : 'Water Drink' }}
                                </label>
                                <img src="{{ asset('images/66.jpg') }}" width="100px" height="100px" alt=""
                                    style="margin-right: 15px;">

                            </div>

                            @if ($service->drink_width == 18)
                                <label for="">
                                    {{ $lang == 'ar' ? '18 طن' : '18 Tons' }}
                                </label>
                            @else
                                <label for=""> {{ $lang == 'ar' ? '32 طن' : '32 Tons' }}
                                </label>
                            @endif
                        </div>
                    @endif
                    @if ($service->wall_width != 0)
                        <div class="form-group mt-2 d-flex align-items-center">


                            <div class="d-flex align-items-center justify-content-between m-2">
                                <label class="ml-2 mr-3" style="min-width: 150px;">
                                    {{ $lang == 'ar' ? 'مياة الصرف الصحي' : 'Wall Water' }}
                                </label>
                                <img src="{{ asset('images/67.jpg') }}" width="100px" height="100px" alt=""
                                    style="margin-right: 15px;">

                            </div>

                            <div>
                                @if ($service->wall_width == 18)
                                    <label for=""> {{ $lang == 'ar' ? '18 طن' : '18 Tons' }}
                                    </label>
                                @else
                                    <label for=""> {{ $lang == 'ar' ? '32 طن' : '32 Tons' }}
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endif
                    <hr>
                    <div class="form-group">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'الموقع' : 'Location' }}
                            :</label>
                        @if (isset($service->from_city))
                            <p>{{ $lang == 'ar' ? $form_city->name_ar : $form_city->name_en }}</p>
                        @endif
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
                    @if (!empty($service->notes_voice))
                    <div class="form-group">
                        <label class="mb-1">{{ $lang == 'ar' ? 'ملاحظة صوتية' : 'Voice Note' }} :</label>
                        <audio controls style="width:100%">
                            <source src="{{ asset('storage/' . $service->notes_voice) }}" type="audio/wav">
                            <source src="{{ asset('storage/' . $service->notes_voice) }}" type="audio/mpeg">
                            <source src="{{ asset('storage/' . $service->notes_voice) }}" type="audio/ogg">
                            {{ $lang == 'ar' ? 'متصفحك لا يدعم تشغيل الصوت' : 'Your browser does not support the audio element.' }}
                        </audio>
                    </div>
                @endif
                    <div class="form-group">
                        <label for="" class="mb-1">{{ $lang == 'ar' ? 'صاحب العمل' : 'Customer' }}
                            :</label>
                        <span class="">{{ $service->user->full_name }} </span>
                        @if (isset($service->user->image))
                            <img width="250px" height="250px" src="{{ asset($service->user->image_url) }}" alt="user">
                        @endif
                    </div>
                    <div class="mt-4">
                        @if (auth()->id() === $service->user_id)
                            <a class="btn btn-success btn-sm" href="{{ route('services.edit', $service->id) }}">
                                <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تعديل' : 'Edit' }} </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"
                                    onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                    <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>

        </div>


    </section>
    <section class="section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pb-0 align-items-center" style="height: 100%;">
                            <h5 class="mb-4 d-flex align-items-center justify-content-center">
                                {{ $lang == 'ar' ? 'العروض' : 'Offers' }}</h5>
                            <div class="d-block mb-4 overflow-visible d-block d-sm-flex">
                                {{-- <div class="row"> --}}
                                <div class="container">
                                    @forelse ($service->comments as $comment)
                                        <div class="col-12 border mb-4 p-4 br-5">
                                            <div class="d-flex align-items-center">
                                                <h5 class="mt-0 mr-3">
                                                    {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                                                </h5>
                                                @if (auth()->check() && auth()->id() == $service->user_id)
                                                    <a class="dropdown-item mb-2"
                                                        href="{{ route('web.send_message', $comment->user->id) }}">
                                                        <i class="fe fe-mail mx-1"></i> {{ __('messages.send_message') }}
                                                    </a>
                                                    <form action="{{ route('general_orders.store') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="service_id"
                                                            value="{{ $service->id }}">
                                                        <input type="hidden" name="service_provider_id"
                                                            value="{{ $comment->user->id }}">
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $service->user_id }}">
                                                        <button class="btn btn-primary" type="submit">
                                                            {{ $lang == 'ar' ? 'قبول العرض' : 'Accept Offer' }}
                                                        </button>
                                                    </form>
                                                @endif
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
                                                <p>{{ __('general.time') . ' : ' . \Carbon\Carbon::parse($comment->time)->format('h:i A') }}
                                                </p>
                                            @endif
                                            @if (isset($comment->city))
                                                <p>{{ ($lang == 'ar' ? 'المدينة' : 'City') . ' : ' . $comment->city }}</p>
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

                        <?php
                        $user = auth()->user();
                        if ($user) {
                            $is_add = App\Models\GeneralComments::where('commentable_id', $service->id)->where('commentable_type', 'App\Models\CarWaterService')->where('service_provider', $user->id)->first();
                        }

                        ?>

                    </div>
                    @if ($user && $user->id != $service->user_id && $service->status == 'open' && $is_add == null)
                        <div class="card">
                            <div class="card-body">
                                <p class="h5 mb-4">{{ $lang == 'ar' ? 'اضافة عرض' : 'Add Offer' }}</p>
                                <form class="form-horizontal  m-t-20" action="{{ route('general_comments.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{ $service->id }}" name="service_id">

                                    <div>
                                        <label class="mb-2" for="">{{ __('general.price') }}</label>
                                        <input class="form-control mb-2" type="number" name="price">
                                    </div>

                                    <div>
                                        <label class="mb-2"
                                            for="">{{ ($lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes') . ' : ' }}</label>
                                        <textarea class="form-control mb-2" cols="5" rows="5" name="notes"></textarea>
                                    </div>
                                    <div class="">
                                        <button type="submit" class="btn btn-primary">{{ $lang == 'ar' ? 'قدم عرض' : 'Add Offer' }}</button>
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
