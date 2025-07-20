@extends('layouts.home')
@section('content')
<?php
    $lang = config('app.locale');
    $furniture_transportation_services = App\Models\FurnitureTransportationService::whereStatus('open')->get();
    $follow_camera_services = App\Models\FollowCameraService::all();
    $party_preparationservices = App\Models\PartyPreparationService::all();
    $garden_services = App\Models\GardenService::all();
    $counter_insects_services = App\Models\CounterInsectsService::all();
    $teacher_services = App\Models\TeacherService::all();
    $cleaning_services = App\Models\CleaningService::all();
    $family_services = App\Models\FamilyService::all();
    $worker_services = App\Models\WorkerService::all();
    $public_ge_services = App\Models\PublicGeService::all();
    $ads_services = App\Models\AdsService::all();
    $car_water_services = App\Models\CarWaterService::all();
    $water_services = App\Models\WaterService::all();
    $big_car_services = App\Models\BigCarService::all();
    $contracting_services = App\Models\ContractingService::all();
    $air_condition = App\Models\AirConditionService::all();
    $van_truck = App\Models\VanTruckService::all();
    $heavy_equip = App\Models\HeavyEquipmentService::all();
    $spare_part = App\Models\SparePartServices::all();
    $maintenance_services= App\Models\MaintenanceService::all();
    $departments = [
        ['key' => 'furniture_transportation_services', 'ar' => 'نقل عفش', 'en' => 'Furniture Transportations'],
        ['key' => 'follow_camera_services', 'ar' => 'كاميرات مراقبة', 'en' => 'Surveillance Cameras'],
        ['key' => 'party_preparationservices', 'ar' => 'تجهيز حفلات', 'en' => 'Party Preparation'],
        ['key' => 'garden_services', 'ar' => 'تنسيق حدائق', 'en' => 'Garden'],
        ['key' => 'counter_insects_services', 'ar' => 'مكافحة الحشرات', 'en' => 'Counter Insects'],
        ['key' => 'teacher_services', 'ar' => 'مدرس خصوصي', 'en' => 'Teacher'],
        ['key' => 'cleaning_services', 'ar' => 'خدمات تنظيف', 'en' => 'Cleaning'],
        ['key' => 'family_services', 'ar' => 'أسر منتجة', 'en' => 'Productive Families'],
        ['key' => 'worker_services', 'ar' => 'عمال وحرفيين', 'en' => 'Workers'],
        ['key' => 'public_ge_services', 'ar' => 'خدمات عامة', 'en' => 'General Services'],
        ['key' => 'ads_services', 'ar' => 'دعاية واعلان', 'en' => 'Advertising'],
        ['key' => 'car_water_services', 'ar' => 'صهريج مياة', 'en' => 'Water Tank'],
        ['key' => 'water_services', 'ar' => 'فلاتر مياة', 'en' => 'Water Filters'],
        ['key' => 'big_car_services', 'ar' => 'سطحه', 'en' => 'Big Car'],
        ['key' => 'contracting_services', 'ar' => 'مقاولات', 'en' => 'Contracting'],
        ['key' => 'maintenance_services', 'ar' => 'صيانة سيارات', 'en' => 'Maintenance'],
        ['key' => 'van_truck', 'ar' => 'شاحنات', 'en' => 'Van Truck'],
        ['key' => 'heavy_equip', 'ar' => 'معدات ثقيلة', 'en' => 'Heavy Equipment'],
        ['key' => 'spare_part', 'ar' => 'قطع غيار', 'en' => 'Spare Parts'],
        ['key' => 'air_condition', 'ar' => 'تكييفات', 'en' => 'Air Condition'],
    ];
    $selected = request('department', $departments[0]['key']);
?>
<div class="container my-4">
    <h2 class="mb-4 text-center">{{ $lang == 'ar' ? 'كل الطلبات' : 'All Requests' }}</h2>
    <form method="get" class="mb-4 text-center">
        <select name="department" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            @foreach($departments as $dep)
                <option value="{{ $dep['key'] }}" {{ $selected == $dep['key'] ? 'selected' : '' }}>
                    {{ $lang == 'ar' ? $dep['ar'] : $dep['en'] }}
                </option>
            @endforeach
        </select>
    </form>
    <div class="row d-flex justify-content-center">
        <div class="col-xl-10 mx-auto">
            <div class="row justify-content-center">
                @php
                    $all = [
                        'furniture_transportation_services' => $furniture_transportation_services,
                        'follow_camera_services' => $follow_camera_services,
                        'party_preparationservices' => $party_preparationservices,
                        'garden_services' => $garden_services,
                        'counter_insects_services' => $counter_insects_services,
                        'teacher_services' => $teacher_services,
                        'cleaning_services' => $cleaning_services,
                        'family_services' => $family_services,
                        'worker_services' => $worker_services,
                        'public_ge_services' => $public_ge_services,
                        'ads_services' => $ads_services,
                        'car_water_services' => $car_water_services,
                        'water_services' => $water_services,
                        'big_car_services' => $big_car_services,
                        'contracting_services' => $contracting_services,
                        'maintenance_services' => $maintenance_services,
                        'van_truck' => $van_truck,
                        'heavy_equip' => $heavy_equip,
                        'spare_part' => $spare_part,
                        'air_condition' => $air_condition,
                    ];
                    $list = $all[$selected] ?? collect();
                @endphp
                @forelse($list as $item)
                    <div class="col-md-10 mb-4">
                        <div class="card mx-auto shadow-sm">
                            <div class="position-relative">
                                <span class="badge bg-secondary blog-badge">{{ $item->add_order ?? '' }}</span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5>
                                    <a href="#">{{ $item->title ?? ($item->name_ar ?? ($item->name ?? '')) }}</a>
                                </h5>
                                <div class="tx-muted">{{ $item->description ?? ($item->name_ar ?? ($item->name ?? '')) }}</div>
                                <div class="d-flex align-items-center pt-4 mt-auto">
                                    <div class="avatar me-3 cover-image rounded-circle">
                                        <img src="{{ $item->user->image ?? asset('images/user.png') }}" class="rounded-circle" alt="img" width="40">
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);" class="h6">{{ $item->user->full_name ?? ($item->user->first_name . ' ' . $item->user->last_name) }}</a>
                                        <small class="d-block tx-muted">{{ $item->created_at->shortAbsoluteDiffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-content-center">
                                    {{-- زر التفاصيل حسب القسم --}}
                                    @if($selected == 'furniture_transportation_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_furniture_transportations_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'follow_camera_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_surveillance_cameras_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'party_preparationservices')
                                        <a class="btn btn-success btn-sm" href="{{  route('main_party_preparation_show_my_service' , $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'garden_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_garden_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'counter_insects_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_counter_insects_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'teacher_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_teacher_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'cleaning_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_cleaning_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'family_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_family_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'worker_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_worker_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'public_ge_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_public_ge_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'ads_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_ads_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'car_water_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_car_water_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'water_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_water_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'big_car_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_big_car_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'contracting_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_contracting_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'maintenance_services')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_maintenance_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'van_truck')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_van_truck_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'heavy_equip')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_heavy_equip_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'spare_part')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_spare_part_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @elseif($selected == 'air_condition')
                                        <a class="btn btn-success btn-sm" href="{{ route('main_air_con_show_my_service', $item->id) }}">
                                            <i class="fe fe-check-circle"></i> {{ $lang == 'ar' ? 'تفاصيل' : 'Details' }}
                                        </a>
                                    @endif
                                    {{-- أزرار تعديل وحذف إذا كان الطلب يخص المستخدم --}}
                                    @if(auth()->id() == $item->user_id)
                                        {{-- زر تعديل وحذف حسب القسم --}}
                                        {{-- مثال لنقل العفش --}}
                                        @if($selected == 'furniture_transportation_services')
                                            <a class="btn btn-primary btn-sm mx-1" href="{{ route('service_furniture_transportations.edit', $item->id) }}">
                                                <i class="fe fe-edit"></i> {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('service_furniture_transportations.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('{{ $lang == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete?' }}')">
                                                    <i class="fe fe-trash-2"></i> {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                                </button>
                                            </form>
                                        @endif
                                        {{-- كرر نفس منطق التعديل والحذف لباقي الأقسام حسب الحاجة --}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">{{ $lang == 'ar' ? 'لا توجد طلبات في هذا القسم' : 'No requests in this department' }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
