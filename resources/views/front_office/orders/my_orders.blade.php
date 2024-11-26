@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale');
    $user = auth()->user();
    ?>
    {{ $lang == 'ar' ? 'المشاريع' : 'Projects' }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/video-js.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <style>
        .profile-cover-container {
            position: relative;
            width: 100%;
            height: 400px;
            background-color: #f5f5f5;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            /* لتكون الصورة دائرية */
            margin: 0 auto;
            display: block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-content {
            padding-top: 20px;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-info .user-name {
            font-size: 22px;
            color: #333;
            margin-top: 15px;
        }

        .user-info .user-role {
            font-size: 16px;
            color: #777;
        }

        .user-stats .stat-item {
            text-align: center;
            margin-right: 20px;
        }

        .user-stats .stat-value {
            font-size: 24px;
            color: #333;
        }

        .user-stats .stat-label {
            font-size: 14px;
            color: #777;
        }

        .stars-card {
            min-height: 20px;
        }

        .stars-card svg {
            margin-right: 3px;
            color: #818894;
        }

        .stars-card svg.active {
            color: #ffc600;
            fill: #ffc600;
        }

        .stars-card i.active svg {
            color: #ffc600;
            fill: #ffc600;
        }

        .scroll-container {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
    </style>
@endsection
@section('content')
<?php
$settings = App\Models\Settings::first();
$departments = App\Models\Department::where('department_id',0)->get();
$posts_count = App\Models\Post::count();
$order_count = App\Models\Order::count();
$users_count = App\Models\User::count();
$providers_count = App\Models\User::where('role_id', 3)->count();
$users = App\Models\User::where('role_id', 3)->limit(6)->get();
$furniture_transportations = App\Models\FurnitureTransportation::first();
$Follow_cameras = App\Models\FollowCamera::first();
$PartyPreparation = App\Models\PartyPreparation::first();
$garden = App\Models\Garden::first();
$counter_insects = App\Models\CounterInsects::first();
$cleaning = App\Models\Cleaning::first();
$teacher = App\Models\Teacher::first();
$family = App\Models\Family::first();
$worker = App\Models\Worker::first();
$public_ge = App\Models\PublicGe::first();
$ads = App\Models\Ads::first();
$water = App\Models\Water::first();
$car_water = App\Models\CarWater::first();
$big_car = App\Models\BigCar::first();
?>

<section class="section overflow-hidden">
    <img src="../assets/images/patterns/2.png" alt="img" class="patterns-6 op-1 z-index-0 top-14p">
    <img src="../assets/images/patterns/7.png" alt="img" class="patterns-5 left-0 transform-rotate-180 z-index-0">
    <div class="container">
        <div class="row">
            <div class="heading-section">
                <div class="heading-subtitle">
                    <span class="tx-primary tx-16 fw-semibold" style="color: black;">{{ __('department.departments') }}</span>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_furniture') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($furniture_transportations->image)
                            <img src="{{ $furniture_transportations->image_url }}" class="card-img-top"
                                alt="{{ $furniture_transportations->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $furniture_transportations->name_ar : $furniture_transportations->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_surveillance_cameras') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($Follow_cameras->image)
                            <img src="{{ $Follow_cameras->image_url }}" class="card-img-top"
                                alt="{{ $Follow_cameras->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $Follow_cameras->name_ar : $Follow_cameras->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_party_preparation') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($PartyPreparation->image)
                            <img src="{{ $PartyPreparation->image_url }}" class="card-img-top"
                                alt="{{ $PartyPreparation->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $PartyPreparation->name_ar : $PartyPreparation->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_garden') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($garden->image)
                            <img src="{{ $garden->image_url }}" class="card-img-top"
                                alt="{{ $garden->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $garden->name_ar : $garden->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_counter_insects') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($counter_insects->image)
                            <img src="{{ $counter_insects->image_url }}" class="card-img-top"
                                alt="{{ $counter_insects->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $counter_insects->name_ar : $counter_insects->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_cleaning') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($cleaning->image)
                            <img src="{{ $cleaning->image_url }}" class="card-img-top"
                                alt="{{ $cleaning->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $cleaning->name_ar : $cleaning->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_teacher') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($teacher->image)
                            <img src="{{ $teacher->image_url }}" class="card-img-top"
                                alt="{{ $teacher->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $teacher->name_ar : $teacher->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_family') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($family->image)
                            <img src="{{ $family->image_url }}" class="card-img-top"
                                alt="{{ $family->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $family->name_ar : $family->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_worker') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($worker->image)
                            <img src="{{ $worker->image_url }}" class="card-img-top"
                                alt="{{ $worker->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $worker->name_ar : $worker->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_public_ge') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($public_ge->image)
                            <img src="{{ $public_ge->image_url }}" class="card-img-top"
                                alt="{{ $public_ge->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $public_ge->name_ar : $public_ge->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_ads') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($ads->image)
                            <img src="{{ $ads->image_url }}" class="card-img-top"
                                alt="{{ $ads->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $ads->name_ar : $ads->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_water') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($water->image)
                            <img src="{{ $water->image_url }}" class="card-img-top"
                                alt="{{ $water->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $water->name_ar : $water->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_car_water') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($car_water->image)
                            <img src="{{ $car_water->image_url }}" class="card-img-top"
                                alt="{{ $car_water->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $car_water->name_ar : $car_water->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <a href="{{ route('show_orders_big_car') }}" class="text-decoration-none">
                    <div class="card border feature-card-15 mb-xl-0 position-relative"
                        style="width: 100%; height: 200px;">
                        @if ($big_car->image)
                            <img src="{{ $big_car->image_url }}" class="card-img-top"
                                alt="{{ $big_car->name_en }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                style="height: 100%; width: 100%;">
                                <i class="bi bi-gem tx-22 text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                            style="width: 100%;  padding: 10px; z-index: 100;">
                            <h5 class="mb-0 bg-primary" style="font-size: 40px;">{{ $lang == 'ar' ? $big_car->name_ar : $big_car->name_en }}
                            </h5>
                        </div>
                    </div>
                </a>
            </div>


            @forelse ($departments as $department)
                <div class="col-xl-3 col-sm-6 mb-4">
                    <a href="{{ route('departments.show', $department->id) }}" class="text-decoration-none">
                        <div class="card border feature-card-15 mb-xl-0 position-relative"
                            style="width: 100%; height: 200px;">
                            @if ($department->image)
                                <img src="{{ $department->image_url }}" class="card-img-top"
                                    alt="{{ $department->name_en }}"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                                    style="height: 100%; width: 100%;">
                                    <i class="bi bi-gem tx-22 text-white"></i>
                                </div>
                            @endif
                             <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
                                style="width: 100%;  padding: 10px; z-index: 100;">
                                <h5 class="mb-0">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}
                                </h5>
                            </div>
                        </div>
                    </a>
                </div>
                
            @empty





                {{-- <p>{{ __('department.no_departments_found') }}</p> --}}
            @endforelse

        </div>
    </div>
</section>
@endsection
