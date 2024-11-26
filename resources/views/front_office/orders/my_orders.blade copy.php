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
    <?php $orderFurnitures = App\Models\FurnitureTransportationOrder::where('customer_id', $user->id)
        ->orWhere('service_provider_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    $follow_cameras = App\Models\FollowCameraOrder::where('customer_id', $user->id)
        ->orWhere('service_provider_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    $party_preparations = App\Models\PartyPreparationOrder::where('customer_id', $user->id)
        ->orWhere('service_provider_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    $gardens = App\Models\GardenOrder::where('customer_id', $user->id)
        ->orWhere('service_provider_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    ?>
    <section class="profile-cover-container scrollable">
        {{-- <div class="profile-content pt-40">
            <div class="container scroll-container">
                @forelse ($orders as $order)
                    <form action="" method="POST" enctype="multipart/form-data" class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
                        <div class="profile-content pt-40">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                                        <span class="badge {{ $order->status == 'complete' ? 'bg-primary-transparent tx-primary' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0">{{ ($order->status == 'complete') ? __('order.complete') : __('order.pending')}}</span>
                                        <h6 class="mb-0"><a href="#"> </a>{{ $order->title }}</h6>
                                    </div>
                                    <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2"> 
                                        <p>{{ $order->description }}</p>
                                        <hr>
                                        <a href="{{ route('web.order.view', $order->id) }}" class="btn btn-primary">{{ __('general.show') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @empty
                @endforelse
            </div>
        </div> --}}
        <div class="profile-content pt-40">
            <div class="container scroll-container">

                <h1> {{ $lang == 'ar' ? 'نقل عفش' : 'Furniture Transportations' }} </h1>
                @forelse ($orderFurnitures as $orderFurniture)
                    <form action="" method="POST" enctype="multipart/form-data"
                        class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
                        <div class="profile-content pt-40">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                                        <span
                                            class="badge {{ $orderFurniture->status == 'completed' ? 'bg-primary-transparent tx-primary' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0"
                                            style="color: black">{{ $orderFurniture->status == 'completed' ? __('order.complete') : __('order.pending') }}</span>
                                        <h6 class="mb-0"><a href="#">
                                                @if ($user->role_id == 1)
                                            </a>{{ $orderFurniture->service_provider_furniture_transportation->first_name . ' ' . $orderFurniture->service_provider_furniture_transportation->last_name }}
                                        </h6>
                                    @elseif ($user->role_id == 3)
                                        </a>{{ $orderFurniture->customer_furniture_transportation->first_name . ' ' . $orderFurniture->customer_furniture_transportation->last_name }} 
                @endif
            </div>
            <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                <p>{{ $orderFurniture->description }}</p>
                <hr>
                <a href="{{ route('show_order_furniture', $orderFurniture->id) }}"
                    class="btn btn-primary">{{ __('general.show') }}</a>
            </div>
        </div>
        </div>
        </div>
        </form>
    @empty
        @endforelse
        </div>
        </div>
    </section>
    <section class="profile-cover-container scrollable">

        <div class="profile-content pt-40">
            <div class="container scroll-container">
                <h1>{{ $lang == 'ar' ? 'قسم انظمة و كاميرات مراقبة ' : 'Systems and Surveillance Cameras' }}
                </h1>
                @forelse ($follow_cameras as $follow_camera)
                    <form action="" method="POST" enctype="multipart/form-data"
                        class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
                        <div class="profile-content pt-40">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                                        <span
                                            class="badge {{ $follow_camera->status == 'completed' ? 'bg-primary-transparent tx-primary' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0"
                                            style="color: black">{{ $follow_camera->status == 'completed' ? __('order.complete') : __('order.pending') }}</span>
                                        <h6 class="mb-0"><a href="#">
                                                @if ($user->role_id == 1)
                                            </a>{{ $follow_camera->service_provider_follow_camera->full_name }}</h6>
                                    @elseif ($user->role_id == 3)
                                        </a>{{ $follow_camera->customer_follow_camera->first_name . ' ' . $follow_camera->customer_follow_camera->last_name }}
                                        </h6>
                @endif
            </div>
            <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                <p>{{ $follow_camera->description }}</p>
                <hr>
                <a href="{{ route('show_order_surveillance_cameras', $follow_camera->id) }}"
                    class="btn btn-primary">{{ __('general.show') }}</a>
            </div>
        </div>
        </div>
        </div>

        </form>
    @empty
        @endforelse

        </div>
        </div>


    </section>
    <section class="profile-cover-container scrollable">

        <div class="profile-content pt-40">
            <div class="container scroll-container">
                <h1>{{ ($lang == 'ar')? 'تنسيق حدائق وزراعة' : 'Garden and Agriculture Coordination' }}
                </h1>
                @forelse ($gardens as $garden)
                    <form action="" method="POST" enctype="multipart/form-data"
                        class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
                        <div class="profile-content pt-40">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                                        <span
                                            class="badge {{ $garden->status == 'completed' ? 'bg-primary-transparent tx-primary' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0"
                                            style="color: black">{{ $garden->status == 'completed' ? __('order.complete') : __('order.pending') }}</span>
                                        <h6 class="mb-0"><a href="#">
                                                @if ($user->role_id == 1)
                                            </a>{{ $garden->service_provider_garden->full_name }}</h6>
                                    @elseif ($user->role_id == 3)
                                        </a>{{ $garden->customer_garden->first_name . ' ' . $garden->customer_garden->last_name }}
                                        </h6>
                @endif
            </div>
            <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                <p>{{ $garden->description }}</p>
                <hr>
                <a href="{{ route('show_order_garden', $garden->id) }}"
                    class="btn btn-primary">{{ __('general.show') }}</a>
            </div>
        </div>
        </div>
        </div>

        </form>
    @empty
        @endforelse

        </div>
        </div>


    </section>
    <section class="profile-cover-container scrollable">

        <div class="profile-content pt-40">
            <div class="container scroll-container">
                <h1>{{ $lang == 'ar' ? 'قسم تجهيز حفلات ' : 'Party preparation' }}</h1>
                @forelse ($party_preparations as $party_preparation)
                    <form action="" method="POST" enctype="multipart/form-data"
                        class="profile-card rounded-lg shadow-xs bg-white p-15 p-md-30 w-100 mb-3">
                        <div class="profile-content pt-40">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4 col-lg-9 col-md-8 col-sm-8 mb-2">
                                        <span
                                            class="badge {{ $party_preparation->status == 'completed' ? 'bg-primary-transparent tx-primary' : 'bg-danger-transparent tx-danger' }} me-1 mb-1 mt-3 mt-sm-0"
                                            style="color: black">{{ $party_preparation->status == 'completed' ? __('order.complete') : __('order.pending') }}</span>
                                        <h6 class="mb-0"><a href="#">
                                                @if ($user->role_id == 1)
                                            </a>{{ $party_preparation->service_provider_party_preparation->full_name }}
                                        </h6>
                                    @elseif ($user->role_id == 3)
                                        </a>{{ $party_preparation->customer_party_preparation->first_name . ' ' . $party_preparation->customer_party_preparation->last_name }}
                                        </h6>
                @endif
            </div>
            <div class="col-12 col-xl-8 col-lg-9 col-md-8 col-sm-8 mb-2">
                <p>{{ $party_preparation->description }}</p>
                <hr>
                <a href="{{ route('show_order_party_preparation', $party_preparation->id) }}"
                    class="btn btn-primary">{{ __('general.show') }}</a>
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
