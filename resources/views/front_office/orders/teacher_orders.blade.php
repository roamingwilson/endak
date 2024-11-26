@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); 
        $user = auth()->user();
    ?>
    {{ $lang == 'ar' ? 'خدمة' : 'Order' }}
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
                                <p class="mb-3 content-1 h5 fs-1 " style="color: black">{{ $lang == 'ar' ? 'خدمة' : 'Order' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section class="profile-cover-container scrollable mt-5" >
        <div class="profile-content pt-40">
            <div class="container scroll-container">
                @forelse ($orders as $order)
                    <form action="" method="POST" enctype="multipart/form-data" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
                        <div class="profile-content pt-40">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                                        <span class="badge {{ $order->status == 'completed' ? 'bg-primary-transparent text-black' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0">{{ ($order->status == 'completed') ? __('order.complete') : __('order.pending')}}</span>
                                        <h6 class="mb-0"><a href="#"> </a>{{ $order->title }}</h6>
                                    </div>
                                    <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2"> 
                                         @if ($user->role_id == 1)
                                            <h6 class="mb-0">
                                                    {{ $order->service_provider_teacher->first_name . ' ' . $order->service_provider_teacher->last_name }}
                                            </h6>
                                        @elseif ($user->role_id == 3)
                                            <h6 class="mb-0">{{ $order->customer_teacher->first_name . ' ' . $order->customer_teacher->last_name }}
                                            </h6>
                                        @endif
                                        <hr>
                                        
                                        <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                                            
                                            <a href="{{ route('show_order_teacher', $order->id) }}"
                                                class="btn btn-primary">{{ __('general.show') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @empty
                {!! no_data() !!}

                @endforelse
            </div>
        </div>
    </section>
    {{-- @forelse ($orders as $order)
        


    <section class="profile-cover-container mb-2">

        <div class="profile-content pt-40">
            <div class="container position-relative d-flex justify-content-center ">
                <?php $user = auth()->user(); ?>
                <div style="width:700px" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30">

                    @foreach ($order->service_furniture_transportation->products as $product)
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
                            {{ $order->service_furniture_transportation->from_city }} ||
                            {{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :
                            {{ $order->service_furniture_transportation->from_neighborhood }} ||
                            {{ $lang == 'ar' ? 'الدور' : 'Home' }} :
                            {{ $order->service_furniture_transportation->from_home }}</label>

                    </div>
                    <hr>
                    <label for="name" class="mb-1">{{ $lang == 'ar' ? 'الي' : 'To' }} : </label>
                    <div class="form-group mt-2">
                        <label for="name" class="mb-1">{{ $lang == 'ar' ? 'المدينة' : 'City' }} :
                            {{ $order->service_furniture_transportation->to_city }} ||
                            {{ $lang == 'ar' ? 'الحي' : 'Neighborhood' }} :
                            {{ $order->service_furniture_transportation->to_neighborhood }} ||
                            {{ $lang == 'ar' ? 'الدور' : 'Home' }} :
                            {{ $order->service_furniture_transportation->to_home }}</label>

                    </div>
                    <div class="form-group">
                        <label for=""
                            class="mb-1">{{ $lang == 'ar' ? 'ملاحظة عن العمل المطلوب' : 'Note About Work' }}
                            :</label>
                        @if (isset($order->service_furniture_transportation->notes))
                            <p>{{ $order->service_furniture_transportation->notes }}</p>
                        @else
                            {{ $lang == 'ar' ? 'لا يوجد ملاحظات' : 'No Notes' }}
                        @endif
                    </div>

                </div>
            </div>

        </div>
        </div>
    </section>
    @empty --}}
        
    {{-- @endforelse --}}
    {{-- <?php $offer = $order->service_furniture_transportation->comments->where('service_provider', $order->service_provider_id)->first();
            $rating_furniture_transportations = App\Models\Rating::where('order_id' , $order->id)->where('department_name' , 'furniture_transportations')->first(); 
    ?>
    <section class="section d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card">


                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="border mb-4 p-4 br-5" style="flex: 1 1 calc(33.333% - 1rem); margin: 0.5rem;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mt-0 mr-3">
                                        {{ $offer->user->first_name . ' ' . $offer->user->last_name }}
                                    </h5>
                                    <div>
                                        @if ($user->id == $order->customer_id)
                                            @if ($order->status == 'pending')
                                                <a href="{{ route('accept_project_furniture_transportation', $order->id) }}"
                                                    class="btn btn-primary">{{ $lang == 'ar' ? 'أستلام المشروع' : 'Confirm Project' }}</a>
                                            @elseif($order->status == 'completed' && !$rating_furniture_transportations)
                                                <a href="{{ route('web.add_rate', $order->id) }}"
                                                    class="btn btn-primary">{{ $lang == 'ar' ? 'تقييم العمل' : 'Rate Work' }}</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                @if (isset($offer->price))
                                    <p>{{ __('general.price') . ' : ' . $offer->price }}</p>
                                @endif
                                @if (isset($offer->body))
                                    <p>{{ 'Body : ' . $offer->body }}</p>
                                @endif
                                @if (isset($offer->date))
                                    <p>{{ __('general.date') . ' : ' . $offer->date }}</p>
                                @endif
                                @if (isset($offer->time))
                                    <p>{{ __('general.time') . ' : ' . $offer->time }}</p>
                                @endif
                                @if (isset($offer->city))
                                    <p>{{ ($lang == 'ar' ? 'المدينة' : 'City') . ' : ' . $offer->city }}
                                    </p>
                                @endif
                                @if (isset($offer->neighborhood))
                                    <p>{{ ($lang == 'ar' ? 'الحي' : 'Neighborhood') . ' : ' . $offer->neighborhood }}
                                    </p>
                                @endif
                                @if (isset($offer->location))
                                    <p>{{ ($lang == 'ar' ? 'الموقع' : 'Location') . ' : ' . $offer->location }}
                                    </p>
                                @endif
                                @if (isset($offer->day))
                                    <p>{{ __('general.day') . ' : ' . $offer->day }}</p>
                                @endif
                                @if (isset($offer->number_of_days_of_warranty))
                                    <p>{{ ($lang == 'ar' ? 'عدد ايام الضمان' : 'Number of Days of Warranty') . ' : ' . $offer->number_of_days_of_warranty }}
                                    </p>
                                @endif
                                @if (isset($offer->notes))
                                    <p>{{ ($lang == 'ar' ? 'ملاحظات عن العمل المطلوب' : 'Notes') . ' : ' . $offer->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>






        </div>

    </section> --}}
@endsection
