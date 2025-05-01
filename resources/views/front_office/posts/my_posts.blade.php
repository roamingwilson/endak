@extends('layouts.home')
@section('title')
    <?php $lang = config('app.locale'); ?>
    {{ $lang == 'ar' ? 'منشوراتي' : 'Posts' }}
@endsection
@section('content')
    <div class="page">

        <?php $current_url = url()->current();
        $url = explode('/', $current_url);
        $id = (int) end($url);
        $department = App\Models\Department::where('id', $id)->first();
        $lang = config('app.locale');
        $furniture_transportation_services = App\Models\FurnitureTransportationService::where('user_id' , auth()->id())->whereStatus('open')->get();
        $follow_camera_services = App\Models\FollowCameraService::where('user_id' , auth()->id())->get();
        $party_preparationservices = App\Models\PartyPreparationService::where('user_id' , auth()->id())->get();
        $garden_services = App\Models\GardenService::where('user_id' , auth()->id())->get();
        $counter_insects_services = App\Models\CounterInsectsService::where('user_id' , auth()->id())->get();
        $teacher_services = App\Models\TeacherService::where('user_id' , auth()->id())->get();
        $cleaning_services = App\Models\CleaningService::where('user_id' , auth()->id())->get();
        $family_services = App\Models\FamilyService::where('user_id' , auth()->id())->get();
        $worker_services = App\Models\WorkerService::where('user_id' , auth()->id())->get();
        $public_ge_services = App\Models\PublicGeService::where('user_id' , auth()->id())->get();
        $ads_services = App\Models\AdsService::where('user_id' , auth()->id())->get();
        $car_water_services = App\Models\CarWaterService::where('user_id' , auth()->id())->get();
        $water_services = App\Models\WaterService::where('user_id' , auth()->id())->get();
        $big_car_services = App\Models\BigCarService::where('user_id' , auth()->id())->get();
        $contracting_services = App\Models\ContractingService::where('user_id' , auth()->id())->get();
        $air_condition = App\Models\AirConditionService::where('user_id' , auth()->id())->get();
        $van_truck = App\Models\VanTruckService::where('user_id' , auth()->id())->get();
        $heavy_equip = App\Models\HeavyEquipmentService::where('user_id' , auth()->id())->get();
        $spare_part = App\Models\SparePartServices::where('user_id' , auth()->id())->get();
        $maintenance_services= App\Models\MaintenanceService::where('user_id' , auth()->id())->get();

        ?>

        <div class="main-content app-content">
            <section>
                <div class="section banner-4 banner-section">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <div class="">
                                    <p class="mb-3 content-1 h5 text-white">{{ __('posts.my_posts') }}
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <section class="section">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-10 mx-auto">
                            <div class="row justify-content-center">
                                @forelse ($posts as $post)
                                    <div class="col-md-10"> <!-- عرض الكارد -->
                                        <div class="card mx-auto"> <!-- توسيط الكارد -->
                                            <div class="position-relative">
                                                <span class="badge bg-secondary blog-badge">{{ $post->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('web.posts.show' , $post->id) }}">{{ $post->title }}</a></h5>
                                                <div class="tx-muted">{{ $post->description }}</div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $post->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $post->user->first_name . ' ' . $post->user->last_name }}</a>
                                                        <small class="d-block tx-muted">{{ $post->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center">لا توجد منشورات متاحة</p>
                                @endforelse


                                @forelse ($furniture_transportation_services as $furniture_transportation_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">

                                                <span class="badge bg-secondary blog-badge">{{ $furniture_transportation_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_furniture_transportations_show_my_service' , $furniture_transportation_service->id) }}">    {{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }} </a></h5>
                                                <div class="tx-muted">    {{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $furniture_transportation_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $furniture_transportation_service->user->first_name . ' ' . $furniture_transportation_service->user->last_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $furniture_transportation_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_furniture_transportations.edit',$furniture_transportation_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_furniture_transportations.destroy', $furniture_transportation_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($follow_camera_services as $follow_camera_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $follow_camera_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_surveillance_cameras_show_my_service' , $follow_camera_service->id) }}">    {{ ($lang == 'ar')? 'قسم كاميرات مراقبة ' : 'Surveillance Cameras' }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'قسم كاميرات مراقبة ' : 'Surveillance Cameras' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $follow_camera_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $follow_camera_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $follow_camera_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_surveillance_cameras.edit',$follow_camera_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_surveillance_cameras.destroy', $follow_camera_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($party_preparationservices as $party_preparationservice)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $party_preparationservice->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_party_preparation_show_my_service' , $party_preparationservice->id) }}">    {{ ($lang == 'ar')? 'قسم تجهيز حفلات ' : 'Party preparation' }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'قسم تجهيز حفلات ' : 'Party preparation' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $party_preparationservice->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $party_preparationservice->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $party_preparationservice->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_party_preparation.edit',$party_preparationservice->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_party_preparation.destroy', $party_preparationservice->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($garden_services as $garden_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $garden_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_garden_show_my_service' , $garden_service->id) }}">{{ ($lang == 'ar')? 'تنسيق حدائق وزراعة' : 'Garden and Agriculture Coordination' }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'تنسيق حدائق وزراعة' : 'Garden and Agriculture Coordination' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $garden_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $garden_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $garden_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_garden.edit',$garden_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_garden.destroy', $garden_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($counter_insects_services as $counter_insects_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $counter_insects_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_counter_insects_show_my_service' , $counter_insects_service->id) }}">    {{ ($lang == 'ar')? 'مكافحة الحشرات' : 'Counter Insects' }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'مكافحة الحشرات' : 'Counter Insects' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $counter_insects_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $counter_insects_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $counter_insects_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="mt-4 flex justify-content-center" >

                                                <a class="btn btn-success btn-sm" href="{{route('service_counter_insects.edit',$counter_insects_service->id)}}">
                                                    <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('service_counter_insects.destroy', $counter_insects_service->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                        <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($teacher_services as $teacher_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $teacher_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_teacher_show_my_service' , $teacher_service->id) }}">    {{ ($lang == 'ar')? 'قسم تجهيز حفلات ' : 'Party preparation' }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? ' مدرس خصوصي ' : ' teacher' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $teacher_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $teacher_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $teacher_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_teacher.edit',$teacher_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_teacher.destroy', $teacher_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($cleaning_services as $cleaning_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $cleaning_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_cleaning_show_my_service' , $cleaning_service->id) }}">    {{ ($lang == 'ar')? 'قسم تجهيز حفلات ' : 'Party preparation' }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? '  خدمات تنظيف ' : 'cleaning ' }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $cleaning_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $cleaning_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $cleaning_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_cleaning.edit',$cleaning_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_cleaning.destroy', $cleaning_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($family_services as $family_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $family_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_family_show_my_service' , $family_service->id) }}">  {{ ($lang == 'ar')? 'أسر منتجة' : "Productive Families" }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'أسر منتجة' : "Productive Families" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $family_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $family_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $family_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_family.edit',$family_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_family.destroy', $family_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($worker_services as $worker_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $worker_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_worker_show_my_service' , $worker_service->id) }}"> {{ ($lang == 'ar')? 'عمال وحرفيين باليومية' : "Worker By Days" }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'عمال وحرفيين باليومية' : "Worker By Days" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $worker_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $worker_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $worker_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_worker.edit',$worker_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_worker.destroy', $worker_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </div>

                                                    </form>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($public_ge_services as $public_ge_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $public_ge_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_public_ge_show_my_service' , $public_ge_service->id) }}"> {{ ($lang == 'ar')? 'خدمات عامة' : "General Services" }}</a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'خدمات عامة' : "General Services" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $public_ge_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $public_ge_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $public_ge_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_public_ge.edit',$public_ge_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_public_ge.destroy', $public_ge_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($ads_services as $ads_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $ads_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_ads_show_my_service' , $ads_service->id) }}">
                                                    {{ ($lang == 'ar')? 'دعاية واعلان' : "Advertising" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'دعاية واعلان' : "Advertising" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $ads_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $ads_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $ads_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_ads.edit',$ads_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_ads.destroy', $ads_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </div>
                                                    </form>

                                            </div>

                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($car_water_services as $car_water_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $car_water_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_car_water_show_my_service' , $car_water_service->id) }}">
                                                    {{ ($lang == 'ar')? 'صهريج مياة' : "Water Tank" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'صهريج مياة' : "Water Tank" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $car_water_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $car_water_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $car_water_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_car_water.edit',$car_water_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_car_water.destroy', $car_water_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($water_services as $water_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $water_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_water_show_my_service' , $water_service->id) }}">
                                                    {{ ($lang == 'ar')? 'فلاتر مياة شرب' : "Drinking water filters" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'فلاتر مياة شرب' : "Drinking water filters" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $water_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $water_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $water_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_water.edit',$water_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_water.destroy', $water_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </div>
                                                    </form>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($big_car_services as $big_car_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $big_car_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_big_car_show_my_service' , $big_car_service->id) }}">
                                                    {{ ($lang == 'ar')? 'سطحه' : "Big Car" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')? 'سطحه' : "Big Car" }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $big_car_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $big_car_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $big_car_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_big_car.edit',$big_car_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_big_car.destroy', $big_car_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($contracting_services as $contracting_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $contracting_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_contracting_show_my_service' , $contracting_service->id) }}">
                                                    {{ ($lang == 'ar')? 'مقاولات' : "contracting" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')?  $contracting_service->contracting->name_ar : $contracting_service->contracting->name_en }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $contracting_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $contracting_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $contracting_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_contracting.edit',$contracting_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_contracting.destroy', $contracting_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($maintenance_services as $maintenance_service)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $maintenance_service->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_maintenance_show_my_service' , $maintenance_service->id) }}">
                                                    {{ ($lang == 'ar')? 'صيانة سيارات' : "maintenance" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')?  $maintenance_service->maintenance->name_ar : $maintenance_service->maintenance->name_en }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $maintenance_service->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $maintenance_service->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $maintenance_service->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_maintenance.edit',$maintenance_service->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_maintenance.destroy', $maintenance_service->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($van_truck as $van_truck_items)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $van_truck_items->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_van_truck_show_my_service' , $van_truck_items->id) }}">
                                                    {{ ($lang == 'ar')? 'شاحنات' : "Van Truck" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')?  $van_truck_items->name_ar : $van_truck_items->name_en }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $van_truck_items->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $van_truck_items->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $van_truck_items->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>


                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_van_truck.edit',$van_truck_items->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_van_truck.destroy', $van_truck_items->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($heavy_equip as $heavy_equip_items)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $heavy_equip_items->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_heavy_equip_show_my_service' , $heavy_equip_items->id) }}">
                                                    {{ ($lang == 'ar')? 'معدات ثقيلة' : "heavy equip" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')?  $heavy_equip_items->name_ar : $heavy_equip_items->name_en }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $heavy_equip_items->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $heavy_equip_items->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $heavy_equip_items->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_heavy_equip.edit',$heavy_equip_items->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_heavy_equip.destroy', $heavy_equip_items->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($spare_part as $spare_part_items)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $spare_part_items->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_spare_part_show_my_service' , $spare_part_items->id) }}">
                                                    {{ ($lang == 'ar')? 'قطع غيار' : "spare part"  }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')?  $spare_part_items->name_ar : $spare_part_items->name_en }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $spare_part_items->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $spare_part_items->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $spare_part_items->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_spare_part.edit',$spare_part_items->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_spare_part.destroy', $spare_part_items->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                @forelse ($air_condition as $air_condition_items)
                                    <div class="col-md-10">
                                        <div class="card mx-auto">
                                            <div class="position-relative">


                                                <span class="badge bg-secondary blog-badge">{{ $air_condition_items->add_order }}</span>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h5><a href="{{ route('main_air_con_show_my_service' , $air_condition_items->id) }}">
                                                    {{ ($lang == 'ar')? 'تصلييح تكييفات' : "ِAir condition" }}
                                                </a></h5>
                                                <div class="tx-muted">   {{ ($lang == 'ar')?  $air_condition_items->name_ar : $air_condition_items->name_en }}
                                                </div>
                                                <div class="d-flex align-items-center pt-4 mt-auto">
                                                    <div class="avatar me-3 cover-image rounded-circle">
                                                        <img src="{{ $air_condition_items->user->image ?? asset('images/user.png') }}"
                                                            class="rounded-circle" alt="img" width="40">
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0);"
                                                            class="h6">{{ $air_condition_items->user->full_name }}</a>
                                                        <small
                                                            class="d-block tx-muted">{{ $air_condition_items->created_at->shortAbsoluteDiffForHumans() }}</small>
                                                    </div>

                                                </div>
                                                <div class="mt-4 flex justify-content-center" >

                                                    <a class="btn btn-success btn-sm" href="{{route('service_air_con.edit',$air_condition_items->id)}}">
                                                        <i class="fe fe-check-circle"></i> {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('service_air_con.destroy', $air_condition_items->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                            <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse

                            </div>

                        </div>
                        {!! $posts->links() !!}

                    </div>
                </div>
            </section>
        </div>




    </div>
@endsection
