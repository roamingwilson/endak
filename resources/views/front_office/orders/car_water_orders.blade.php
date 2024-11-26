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
                                                    {{ $order->service_provider_car_water->first_name . ' ' . $order->service_provider_car_water->last_name }}
                                            </h6>
                                        @elseif ($user->role_id == 3)
                                            <h6 class="mb-0">{{ $order->customer_car_water->first_name . ' ' . $order->customer_car_water->last_name }}
                                            </h6>
                                        @endif
                                        <hr>
                                        
                                        <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                                            
                                            <a href="{{ route('show_order_car_water', $order->id) }}"
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
 
@endsection
