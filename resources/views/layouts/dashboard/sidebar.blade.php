<?php $settings = App\Models\Settings::first();
        $departments = App\Models\Department::get();
$lang = config('app.locale');
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $settin }}</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->first_name ?? 'Adminadmin' }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">


                @can('admin_general_dashboard')
                    <li class="nav-item has-treeview">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i>
                            {{-- <i class="fa fa-th-large"></i> --}}

                            <p>
                                {{ __('general.dashboard') }}
                            </p>
                        </a>


                    </li>
                @endcan


                @can('Admin_Roles')

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                {{ __('roles.User_Roles') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.roles') }}"
                                    class="nav-link {{ request()->is('admin/roles') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('roles.All_Roles') }}</p>
                                </a>
                            </li>
                            @can('Create_Admin_Roles')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.create') }}"
                                        class="nav-link {{ request()->is('admin/roles/create') ? 'active' : '' }}">

                                        {{-- <a href="{{ route('admin.roles.create') }}" class="nav-link {{ @if (request()->is('admin/roles/create')) 'active' @elseif ( request()->is('admin/roles/edit') ) 'active' @else '' @endif }}"> --}}
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('roles.New_Roles') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('Admin_Roles')

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link {{ request()->is('admin/user_management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                {{ __('user.user_management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.user_management') }}"
                                    class="nav-link {{ request()->is('admin/user_management') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('user.All_User') }}</p>
                                </a>
                            </li>
                            {{-- @can('Create_Admin_Roles')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.create') }}"
                                        class="nav-link {{ request()->is('admin/user_management/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('roles.New_Roles') }}</p>
                                    </a>
                                </li>
                            @endcan --}}
                        </ul>
                    </li>
                @endcan
                @can('Admin_Departments')
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link {{ request()->is('admin.pro_orders.manage*') ? 'active' : '' }}">
                        {{-- <i class="nav-icon fas fa-cog"></i> --}}
                        <i class="fas fa-couch nav-icon"></i>

                        <p>
                            {{ ($lang == 'ar')? ' ادارة الطلبات' : ' Products orders ' }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    {{-- @can('Edit_Admin_Settings') --}}
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.pro_orders.manage') }}" class="nav-link {{ request()->is('admin/furniture_transportations') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{ ($lang == 'ar')? ' ادارة الطلبات' : 'Manage' }}
                                </a>
                            </li>


                        </ul>
                </li>
            @endcan

                @can('Admin_Departments')
                @foreach($departments as $department)
                @if ( $department->name_en == 'furniture transport' )

                <li class="nav-item has-treeview">
                    <a href="" class="nav-link {{ request()->is('admin/furniture_transportations*') ? 'active' : '' }}">
                        {{-- <i class="nav-icon fas fa-cog"></i> --}}
                        <i class="fas fa-couch nav-icon"></i>

                        <p>
                            {{ ($lang == 'ar')? 'قسم نقل عفش' : 'Furniture Transportations' }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    {{-- @can('Edit_Admin_Settings') --}}
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.department.show', $department->id)  }}" class="nav-link {{ request()->is('admin/furniture_transportations') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{ ($lang == 'ar')? 'نقل عفش' : 'Furniture Transportations' }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('main_furniture_transportations.product') }}" class="nav-link {{ request()->is('admin/furniture_transportations/products') ? 'active' : '' }}">                                    <i class="far fa-circle nav-icon"></i>
                                    {{ ($lang == 'ar')? 'منتجات قسم نقل عفش' : 'Product Furniture Transportations' }}
                                </a>
                            </li>

                            {{-- <li class="nav-item">
                                <a href="" class="nav-link {{ request()->is('admin/departments/edit') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> {{ __('department.edit_department') }} </p>
                                </a>
                            </li> --}}

                        </ul>
                    {{-- @endcan --}}

                </li>
                    @elseif ($department->name_en == 'maintenance')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/maintenance*') ? 'active' : '' }}">
                            <i class="fas fa-tools nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'صيانة السيارات' : 'Car Maintenance' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.maintenance') }}" class="nav-link {{ request()->is('admin/maintenance') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'صيانة السيارات' : 'Car Maintenance' }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                    @elseif ($department->name_en == 'spare parts')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/spare_part*') ? 'active' : '' }}">
                            <i class="	fas fa-cogs nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'قطع غيار' : 'spare parts' }}


                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.spare_part') }}" class="nav-link {{ request()->is('admin.spare_part') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'قطع غيار' : 'spare parts' }}


                                    </a>
                                </li>

                            </ul>

                    </li>
                    @elseif ($department->name_en == 'truks')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/van_truck*') ? 'active' : '' }}">
                            <i class="fas fa-truck-moving nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'شاحنات' : 'van_truck' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.van_truck') }}" class="nav-link {{ request()->is('admin/van_truck') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'شاحنات' : 'van_truck' }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                    @elseif ($department->name_en == 'contracting')

                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/contracting*') ? 'active' : '' }}">
                            <i class="far fa-building nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'المقاولات' : 'Contracting' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.contracting') }}" class="nav-link {{ request()->is('admin/contracting') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'المقاولات' : 'Contracting' }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                    @elseif ($department->name_en == 'heavy equipment')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/heavy_equip*') ? 'active' : '' }}">
                            <i class="fas fa-dumpster nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'معدات ثقيلة' : 'Heavy equipment' }}


                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.heavy_equip') }}" class="nav-link {{ request()->is('admin.heavy_equip') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'معدات ثقيلة' : 'Heavy equipment' }}


                                    </a>
                                </li>

                            </ul>

                    </li>
                    @elseif ($department->name_en == 'plastic')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('indscategories.index*') ? 'active' : '' }}">
                            {{-- <i class="nav-icon fas fa-cog"></i> --}}
                            <i class="fas fa-car nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'صناغة البلاستيك' : 'Industry' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('indsustry.index') }}" class="nav-link {{ request()->is('indscategories.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'صناعة البلاستيك ' : 'plastic' }}
                                    </a>


                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/departments/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('department.edit_department') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>

                            @else
                            @php
                            $icons = [
                                'furniture'       => 'fas fa-couch',
                                'maintenance'     => 'fas fa-tools',
                                'spare parts'           => 'fas fa-cogs',
                                'Truks'           => 'fas fa-truck-moving',
                                'big car'         => 'fas fa-truck-monster',
                                'air condition'    => 'fas fa-wind',
                                'car water'       => 'fas fa-tint',
                                'family'          => 'fas fa-home',
                                'cleaning'        => 'fas fa-broom',
                                'teacher'         => 'fas fa-chalkboard-teacher',
                                'Security Camera'         => 'fas fa-video',
                                'party'           => 'fas fa-glass-cheers',
                                'garden'          => 'fas fa-tree',
                                'contracting'     => 'fas fa-hard-hat',
                                'workers'         => 'fas fa-people-carry',
                                'public ge'          => 'fas fa-city',
                                'insect'          => 'fas fa-bug',
                                'plastic'         => 'fas fa-industry',
                                'ads'             => 'fas fa-bullhorn',
                                'water'           => 'fas fa-water',
                                'heavy'           => 'fas fa-dumpster',
                            ];
                            @endphp

                            <li class="nav-item has-treeview">
                            <a href="#" class="nav-link ">
                                <i class="{{ $icons[$department->name_en] ?? 'fas fa-snowplow' }} nav-icon"></i>
                                <p>
                                    {{ ($lang == 'ar')? $department->name_ar :$department->name_en }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.department.show', $department->id) }}"
                                    class="nav-link {{ request()->is('admin/categories/' . $department->id) ? 'active' : '' }}">
                                        <i class=" far fa-circle nav-icon"></i>
                                        <p>{{ ($lang == 'ar')? $department->name_ar :$department->name_en }}</p>
                                    </a>
                                </li>
                            </ul>
                            </li>
                    @endif
                    @endforeach

            @endcan







                @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('add_conutry') ? 'active' : '' }}">
                            {{-- <i class="nav-icon fas fa-cog"></i> --}}
                            <i class="fas fa-couch nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'إضافة دولة ومحافظاتها ': "add country" }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('add_country') }}" class="nav-link {{ request()->is('add_country') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'إضافة دولة ومحافظاتها ': "add country" }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('add_gover') }}" class="nav-link {{ request()->is('add_gover') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'إضافة  محافظات ': "add gover" }}
                                    </a>
                                </li>


                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/departments/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('department.edit_department') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                @endcan



                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/van_truck*') ? 'active' : '' }}">
                            <i class="fas fa-truck-moving nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'شاحنات' : 'van_truck' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.van_truck') }}" class="nav-link {{ request()->is('admin/van_truck') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'شاحنات' : 'van_truck' }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/maintenance*') ? 'active' : '' }}">
                            <i class="fas fa-wrench nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'صيانة السيارات' : 'Car Maintenance' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.maintenance') }}" class="nav-link {{ request()->is('admin/maintenance') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'صيانة السيارات' : 'Car Maintenance' }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/party_preparation*') ? 'active' : '' }}">
                            <i class="fas fa-glass-cheers nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'قسم تجهيز حفلات ' : 'Party preparation' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.party_preparation') }}" class="nav-link {{ request()->is('admin/party_preparation') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'قسم تجهيز حفلات ' : 'Party preparation' }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/counter_insects*') ? 'active' : '' }}">
                            <i class="fas fa-spider nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'مكافحة الحشرات' : 'Counter Insects' }}


                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.counter_insects') }}" class="nav-link {{ request()->is('admin/counter_insects') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'مكافحة الحشرات' : 'Counter Insects' }}


                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/garden*') ? 'active' : '' }}">
                            <i class="fas fa-tree nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'تنسيق حدائق وزراعة' : 'Garden and Agriculture Coordination' }}

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.garden') }}" class="nav-link {{ request()->is('admin/garden') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'تنسيق حدائق وزراعة' : 'Garden and Agriculture Coordination' }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/cleaning*') ? 'active' : '' }}">
                            <i class="fas fa-car nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'خدمات تنظيف' : "Cleaning Services" }}

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.cleaning') }}" class="nav-link {{ request()->is('admin/cleaning') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'خدمات تنظيف' : "Cleaning Services" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/teacher*') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'دروس خصوصي' : "Private Teacher" }}

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.teacher') }}" class="nav-link {{ request()->is('admin/teacher') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'دروس خصوصي' : "Private Teacher" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/family*') ? 'active' : '' }}">
                            <i class="fas fa-hamburger nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'أسر منتجة' : "Productive Families" }}

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.family') }}" class="nav-link {{ request()->is('admin/family') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'أسر منتجة' : "Productive Families" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/worker*') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'عمال وحرفيين باليومية' : "Worker By Days" }}

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.worker') }}" class="nav-link {{ request()->is('admin/worker') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'عمال وحرفيين باليومية' : "Worker By Days" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/public_ge*') ? 'active' : '' }}">
                            <i class="fas fa-car nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'خدمات عامة' : "General Services" }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.public_ge') }}" class="nav-link {{ request()->is('admin/public_ge') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'خدمات عامة' : "General Services" }}
                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/ads*') ? 'active' : '' }}">
                            <i class="fas fa-ad nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'دعاية واعلان' : "Advertising" }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.ads') }}" class="nav-link {{ request()->is('admin/ads') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'دعاية واعلان' : "Advertising" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/water*') ? 'active' : '' }}">
                            <i class="fas fa-car nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'فلاتر مياة شرب' : "Drinking water filters" }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.water') }}" class="nav-link {{ request()->is('admin/water') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'فلاتر مياة شرب' : "Drinking water filters" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/car_water*') ? 'active' : '' }}">
                            <i class="fas fa-shuttle-van nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'صهريج مياة' : "Water Tank" }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.car_water') }}" class="nav-link {{ request()->is('admin/car_water') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'صهريج مياة' : "Water Tank" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/big_car*') ? 'active' : '' }}">
                            <i class="fas fa-truck-monster nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'سطحه' : "Big Car" }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'سطحه' : "Big Car" }}

                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/heavy_equip*') ? 'active' : '' }}">
                            <i class="fas fa-snowplow nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'معدات ثقيلة' : 'Heavy equipment' }}


                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.heavy_equip') }}" class="nav-link {{ request()->is('admin.heavy_equip') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'معدات ثقيلة' : 'Heavy equipment' }}


                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/spare_part*') ? 'active' : '' }}">
                            <i class="	fas fa-oil-can nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'قطع غيار' : 'spare parts' }}


                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.spare_part') }}" class="nav-link {{ request()->is('admin.spare_part') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'قطع غيار' : 'spare parts' }}


                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}
                {{-- @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/air_con*') ? 'active' : '' }}">
                            <i class="fas fa-wind nav-icon"></i>

                            <p>
                                {{ ($lang == 'ar')? 'تصليح تكييفات' : 'ِair condition' }}


                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.air_con') }}" class="nav-link {{ request()->is('admin.air_con') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        {{ ($lang == 'ar')? 'تصليح تكييفات' : 'ِair condition' }}


                                    </a>
                                </li>

                            </ul>

                    </li>
                @endcan --}}


                @can('Admin_Departments')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/departments*') ? 'active' : '' }}">
                            {{-- <i class="nav-icon fas fa-cog"></i> --}}
                            <i class="fas fa-layer-group nav-icon"></i>

                            <p>
                                {{ __('department.departments') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.departments') }}" class="nav-link {{ request()->is('admin/departments') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('department.departments') }} </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.departments.create') }}" class="nav-link {{ request()->is('admin/departments/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('department.create_department') }} </p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/departments/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('department.edit_department') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                @endcan
                @can('Admin_Categories')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                            <i class="fas fa-tags nav-icon"></i>
                            <p>
                                {{ __('category.categories') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->is('admin/categories') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('category.categories') }} </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.create') }}" class="nav-link {{ request()->is('admin/categories/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('category.create_category') }} </p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/categories/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('category.edit_category') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                @endcan
                @can('Admin_Categories')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>
                                {{ __('products.products') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.products') }}" class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('products.products') }} </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.products.store') }}" class="nav-link {{ request()->is('admin/products/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('products.create_product') }} </p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/categories/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('category.edit_category') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                @endcan

                    <li class="nav-item has-treeview">
                        <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i>
                            <p>
                                {{ __('order.orders') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>


                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link {{ request()->is('admin/whatsapp-senders*') || request()->is('admin/whatsapp-recipients*') ? 'active' : '' }}">
                            <i class="nav-icon fab fa-whatsapp"></i>
                            <p>
                                إدارة أرقام الواتساب
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.whatsapp_senders.create') }}" class="nav-link {{ request()->is('admin/whatsapp-senders*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>أرقام الإرسال</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.whatsapp_recipients.create') }}" class="nav-link {{ request()->is('admin/whatsapp-recipients*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>أرقام الاستقبال</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                @can('Admin_Pages')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>
                                {{ __('page.pages') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages') }}" class="nav-link {{ request()->is('admin/pages') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.pages') }} </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.pages.create') }}" class="nav-link {{ request()->is('admin/pages/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.page_create') }} </p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/pages/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.edit_page') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                @endcan
                {{-- @can('Admin_Pages') --}}
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>
                                {{ __('posts.posts') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.posts') }}" class="nav-link {{ request()->is('admin/posts') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('posts.posts') }} </p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('admin.pages.create') }}" class="nav-link {{ request()->is('admin/pages/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.page_create') }} </p>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/pages/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.edit_page') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>
                                {{ ($lang == 'ar')? ' طلبات الخدمات' : 'Services order' }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        {{-- @can('Edit_Admin_Settings') --}}
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.service.order') }}" class="nav-link {{ request()->is('admin/posts') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>  {{ ($lang == 'ar')? ' طلبات الخدمات' : 'Services order' }}</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('admin.pages.create') }}" class="nav-link {{ request()->is('admin/pages/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.page_create') }} </p>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/pages/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('page.edit_page') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        {{-- @endcan --}}

                    </li>
                {{-- @endcan --}}
                    <li class="nav-item has-treeview">
                        <a href="{{ route('admin.inputs') }}" class="nav-link {{ request()->is('admin/inputs*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>
                                {{ __('department.inputs') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>


                    </li>

                @can('Admin_Settings')
                    <li class="nav-item has-treeview">
                        <a href="" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                {{ __('settings.settings') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @can('Edit_Admin_Settings')
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('settings.settings') }} </p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="" class="nav-link {{ request()->is('admin/settings/edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> {{ __('settings.edit_settings') }} </p>
                                    </a>
                                </li> --}}

                            </ul>
                        @endcan

                    </li>
                @endcan











                <li class="nav-item has-treeview">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            {{ __('general.Front_Office') }}
                            {{-- <i class="right fas fa-angle-left"></i> --}}
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            {{ __('general.Logout') }}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

