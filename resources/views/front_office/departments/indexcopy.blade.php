@extends('layouts.home')
@section('title')
    {{ __('general.home') }}
    <?php $lang = config('app.locale'); ?>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/css-stars.css') }}">
    <style>
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

        .myform {
            max-width: 500px;
            margin: 0 auto;
        }

        .input-group {
            width: 100%;
        }

        .form-control {
            height: 60px;
            font-size: 1.2rem;
            padding: 15px;
            border-radius: 5px 0 0 5px;
            border: 1px solid #ced4da;
        }

        .btn-primary {
            height: 60px;
            font-size: 1.2rem;
            border-radius: 0 5px 5px 0;
        }

        .card-custom {
            width: 100%;
            height: 200px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: rgba(169, 169, 169, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card-custom img {
            width: 80%;
            height: auto;
            object-fit: contain;
            max-height: 150px;
        }

        .card-custom .card-body {
            text-align: center;
            padding: 10px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection
@section('content')
    <?php
    $minutes = 60;
    $settings = Illuminate\Support\Facades\Cache::remember('settings', $minutes, function () {
        return App\Models\Settings::first();
    });
    $departments = Illuminate\Support\Facades\Cache::remember('departments', $minutes, function () {
        return App\Models\Department::where('department_id', 0)->get();
    });
    $posts_count = Illuminate\Support\Facades\Cache::remember('posts_count', $minutes, function () {
        return App\Models\Post::count();
    });
    $order_count = Illuminate\Support\Facades\Cache::remember('order_count', $minutes, function () {
        return App\Models\Order::count();
    });
    $user = auth()->user();
    // $userDepartments = $user->departments()->with('commentable')->get();
    // $contracting = $userDepartments->first(function ($department) {
    //     return $department->commentable_type === \App\Models\Contracting::class;
    // });
    if(isset($user->departments)){

        $userDepartments = $user->departments()->with('commentable')->get();
        $contracting = $userDepartments->first(function ($department) {
            if($department->commentable_type === \App\Models\Contracting::class){
                return  \App\Models\Contracting::where('id' , $department->commentable_id)->first(); 
            } 
            return false;
        });
     
    $Follow_cameras = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\FollowCamera::class;
    });
    $furniture_transportations = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\FurnitureTransportation::class;
    });
    $PartyPreparation = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\PartyPreparation::class;
    });
    $counter_insects = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\CounterInsects::class;
    });
    $cleaning = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Cleaning::class;
    });
    $teacher = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Teacher::class;
    });
    $family = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Family::class;
    });
    $worker = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Worker::class;
    });
    $general_service = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\PublicGe::class;
    });
    $ads = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Ads::class;
    });
    $water = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Water::class;
    });
    $car_water = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\CarWater::class;
    });
    $big_car = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\BigCar::class;
    });
    $garden = $userDepartments->first(function ($department) {
        return $department->commentable_type === \App\Models\Garden::class;
    });
}else{
    $Follow_cameras =  \App\Models\FollowCamera::first(); 
    $furniture_transportations =  \App\Models\FurnitureTransportation::first();
    $PartyPreparation =   \App\Models\PartyPreparation::first();
    $counter_insects = \App\Models\CounterInsects::first();
    $cleaning = \App\Models\Cleaning::first();
    $teacher = \App\Models\Teacher::first();
    $family = \App\Models\Family::first();
    $worker = \App\Models\Worker::first();
    $general_service = \App\Models\PublicGe::first();
    $ads = \App\Models\Ads::first();
    $water = \App\Models\Water::first();
    $car_water = \App\Models\CarWater::first();
    $big_car = \App\Models\BigCar::first();
    $garden = \App\Models\Garden::first();
    $contracting =   \App\Models\Contracting::first();
                 
}
 
    
    $users_count = App\Models\User::count();
    $providers_count = App\Models\User::where('role_id', 3)->count();
    $users = App\Models\User::where('role_id', 3)->limit(6)->get();
   
    ?>
    

    <section class="section overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="heading-section">
                    <div class="heading-subtitle">
                        <span class="tx-primary tx-16 fw-semibold"
                            style="color: black;">{{ __('department.departments') }}</span>
                    </div>
                </div>

                <div class="cards-container">
                    @if ($furniture_transportations)
                        <div class="card card-custom">
                            @if ($furniture_transportations->image)
                                <a href="{{ route('furniture_transportations_show') }}">
                                    <img src="{{ $furniture_transportations->image_url }}" class="card-img-top"
                                        alt="{{ $furniture_transportations->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('furniture_transportations_show') }}">
                                    <p class="card-text">
                                        {{ $lang == 'ar' ? $furniture_transportations->name_ar : $furniture_transportations->name_en }}
                                    </p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($car_water)
                        <div class="card card-custom">
                            @if ($car_water->image)
                                <a href="{{ route('car_water_show') }}">
                                    <img src="{{ $car_water->image_url }}" class="card-img-top"
                                        alt="{{ $car_water->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('car_water_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $car_water->name_ar : $car_water->name_en }}
                                    </p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($big_car)
                        <div class="card card-custom">
                            @if ($big_car->image)
                                <a href="{{ route('big_car_show') }}">
                                    <img src="{{ $big_car->image_url }}" class="card-img-top"
                                        alt="{{ $big_car->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('big_car_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $big_car->name_ar : $big_car->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($family)
                        <div class="card card-custom">
                            @if ($family->image)
                                <a href="{{ route('family_show') }}">
                                    <img src="{{ $family->image_url }}" class="card-img-top" alt="{{ $family->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('family_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $family->name_ar : $family->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($cleaning)
                        <div class="card card-custom">
                            @if ($cleaning->image)
                                <a href="{{ route('cleaning_show') }}">
                                    <img src="{{ $cleaning->image_url }}" class="card-img-top"
                                        alt="{{ $cleaning->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('cleaning_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $cleaning->name_ar : $cleaning->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($teacher)
                        <div class="card card-custom">
                            @if ($teacher->image)
                                <a href="{{ route('teacher_show') }}">
                                    <img src="{{ $teacher->image_url }}" class="card-img-top"
                                        alt="{{ $teacher->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('teacher_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $teacher->name_ar : $teacher->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($Follow_cameras)
                        <div class="card card-custom">
                            @if ($Follow_cameras->image)
                                <a href="{{ route('surveillance_cameras_show') }}">
                                    <img src="{{ $Follow_cameras->image_url }}" class="card-img-top"
                                        alt="{{ $Follow_cameras->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('surveillance_cameras_show') }}">
                                    <p class="card-text">
                                        {{ $lang == 'ar' ? $Follow_cameras->name_ar : $Follow_cameras->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($PartyPreparation)
                        <div class="card card-custom">
                            @if ($PartyPreparation->image)
                                <a href="{{ route('party_preparation_show') }}">
                                    <img src="{{ $PartyPreparation->image_url }}" class="card-img-top"
                                        alt="{{ $PartyPreparation->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('party_preparation_show') }}">
                                    <p class="card-text">
                                        {{ $lang == 'ar' ? $PartyPreparation->name_ar : $PartyPreparation->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($garden)
                        <div class="card card-custom">
                            @if ($garden->image)
                                <a href="{{ route('garden_show') }}">
                                    <img src="{{ $garden->image_url }}" class="card-img-top" alt="{{ $garden->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('garden_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $garden->name_ar : $garden->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($worker)
                        <div class="card card-custom">
                            @if ($worker->image)
                                <a href="{{ route('worker_show') }}">
                                    <img src="{{ $worker->image_url }}" class="card-img-top" alt="{{ $worker->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('worker_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $worker->name_ar : $worker->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif


                    @if ($general_service)
                        <div class="card card-custom">
                            @if ($general_service->image)
                                <a href="{{ route('public_ge_show') }}">
                                    <img src="{{ $general_service->image_url }}" class="card-img-top"
                                        alt="{{ $general_service->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('public_ge_show') }}">
                                    <p class="card-text">
                                        {{ $lang == 'ar' ? $general_service->name_ar : $general_service->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($counter_insects)
                        <div class="card card-custom">
                            @if ($counter_insects->image)
                                <a href="{{ route('counter_insects_show') }}">
                                    <img src="{{ $counter_insects->image_url }}" class="card-img-top"
                                        alt="{{ $counter_insects->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('counter_insects_show') }}">
                                    <p class="card-text">
                                        {{ $lang == 'ar' ? $counter_insects->name_ar : $counter_insects->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($ads)
                        <div class="card card-custom">
                            @if ($ads->image)
                                <a href="{{ route('ads_show') }}">
                                    <img src="{{ $ads->image_url }}" class="card-img-top" alt="{{ $ads->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('ads_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $ads->name_ar : $ads->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif


                    @if ($water)
                        <div class="card card-custom">
                            @if ($water->image)
                                <a href="{{ route('water_show') }}">
                                    <img src="{{ $water->image_url }}" class="card-img-top mt-2"
                                        alt="{{ $water->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('water_show') }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $water->name_ar : $water->name_en }}</p>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($contracting)
                        <div class="card card-custom">
                            @if ($contracting->image)
                                <a href="{{ route('contracting_show') }}">
                                    <img src="{{ $contracting->image_url }}" class="card-img-top mt-2"
                                        alt="{{ $contracting->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('contracting_show') }}">
                                    <p class="card-text">
                                        {{ $lang == 'ar' ? $contracting->name_ar : $contracting->name_en }}
                                    </p>
                                </a>
                            </div>
                        </div>
                    @endif

                    @forelse ($departments as $department)
                        <div class="card card-custom">
                            @if ($department->image)
                                <a href="{{ route('departments.show', $department->id) }}">
                                    <img src="{{ $department->image_url }}" class="card-img-top"
                                        alt="{{ $department->name_ar }}">
                                </a>
                            @endif
                            <div class="card-body">
                                <a href="{{ route('departments.show', $department->id) }}">
                                    <p class="card-text">{{ $lang == 'ar' ? $department->name_ar : $department->name_en }}
                                    </p>
                                </a>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>




            </div>
        </div>
    </section>

@endsection
