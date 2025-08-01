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
                <i class="fas fa-user-circle fa-2x text-light"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block text-light">{{ auth()->user()->first_name ?? 'Admin' }}</a>
                <small class="text-light-50">{{ auth()->user()->email ?? '' }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                @can('admin_general_dashboard')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>{{ __('general.dashboard') }}</p>
                    </a>
                </li>
                @endcan

                <!-- User Management -->
                @can('Admin_Roles')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('admin/user_management*') || request()->is('admin/roles*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ ($lang == 'ar')? 'إدارة المستخدمين': "User Management" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.user_management') }}" class="nav-link {{ request()->is('admin/user_management') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>{{ __('user.All_User') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles') }}" class="nav-link {{ request()->is('admin/roles') ? 'active' : '' }}">
                                <i class="fas fa-user-shield nav-icon"></i>
                                <p>{{ __('roles.All_Roles') }}</p>
                            </a>
                        </li>
                        @can('Create_Admin_Roles')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.create') }}" class="nav-link {{ request()->is('admin/roles/create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>{{ __('roles.New_Roles') }}</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <!-- Location Management -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('countries*') || request()->is('governorates*') || request()->is('add/country*') || request()->is('add/gover*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>
                            {{ ($lang == 'ar')? 'إدارة المواقع': "Location Management" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('countries.dashboard') }}" class="nav-link {{ request()->is('countries/dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'لوحة التحكم': "Dashboard" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('countries.index') }}" class="nav-link {{ request()->is('countries') && !request()->is('countries/dashboard') ? 'active' : '' }}">
                                <i class="fas fa-globe nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إدارة الدول': "Manage Countries" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('add_country') }}" class="nav-link {{ request()->is('add/country') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إضافة دولة': "Add Country" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('governorates.index') }}" class="nav-link {{ request()->is('governorates') ? 'active' : '' }}">
                                <i class="fas fa-map nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إدارة المحافظات': "Manage Governorates" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('add_gover') }}" class="nav-link {{ request()->is('add/gover') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إضافة محافظة': "Add Governorate" }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Department Management -->
                @can('Admin_Departments')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('admin/departments*') || request()->is('admin/sub_departments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>
                            {{ ($lang == 'ar')? 'إدارة الأقسام': "Department Management" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.departments') }}" class="nav-link {{ request()->is('admin/departments') ? 'active' : '' }}">
                                <i class="fas fa-sitemap nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'الأقسام الرئيسية': "Main Departments" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.departments.create') }}" class="nav-link {{ request()->is('admin/departments/create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إضافة قسم': "Add Department" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sub_departments.index') }}" class="nav-link {{ request()->is('admin/sub_departments') ? 'active' : '' }}">
                                <i class="fas fa-code-branch nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'الأقسام الفرعية': "Sub-Departments" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sub_departments.create') }}" class="nav-link {{ request()->is('admin/sub_departments/create') ? 'active' : '' }}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إضافة قسم فرعي': "Add Sub-Department" }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- System Management -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('admin/settings*') || request()->is('admin/products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            {{ ($lang == 'ar')? 'إدارة النظام': "System Management" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('Admin_Categories')
                        <li class="nav-item">
                            <a href="{{ route('admin.products') }}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'إدارة المنتجات': "Manage Products" }}</p>
                            </a>
                        </li>
                        @endcan

                        @can('Admin_Settings')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}">
                                <i class="fas fa-cog nav-icon"></i>
                                <p>{{ __('settings.settings') }}</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <!-- Service Management -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('admin/service-management*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            {{ ($lang == 'ar')? 'إدارة الخدمات': "Service Management" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.service_management.dashboard') }}" class="nav-link {{ request()->is('admin/service-management/dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'لوحة التحكم': "Dashboard" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.service_management.services') }}" class="nav-link {{ request()->is('admin/service-management/services*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'الخدمات المطلوبة': "Service Requests" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.service_management.orders') }}" class="nav-link {{ request()->is('admin/service-management/orders*') ? 'active' : '' }}">
                                <i class="fas fa-handshake nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'العروض المقدمة': "Service Offers" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.service_management.providers') }}" class="nav-link {{ request()->is('admin/service-management/providers*') ? 'active' : '' }}">
                                <i class="fas fa-user-tie nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'مقدمو الخدمات': "Service Providers" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.service_management.statistics') }}" class="nav-link {{ request()->is('admin/service-management/statistics') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'الإحصائيات': "Statistics" }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Orders & Services -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('admin/orders*') || request()->is('admin/service*') || request()->is('admin/pro_orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            {{ ($lang == 'ar')? 'الطلبات والخدمات': "Orders & Services" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart nav-icon"></i>
                                <p>{{ __('order.orders') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.service.order') }}" class="nav-link {{ request()->is('admin/service*') ? 'active' : '' }}">
                                <i class="fas fa-tools nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'طلبات الخدمات': "Service Orders" }}</p>
                            </a>
                        </li>
                        @can('Admin_Departments')
                        <li class="nav-item">
                            <a href="{{ route('admin.pro_orders.manage') }}" class="nav-link {{ request()->is('admin/pro_orders*') ? 'active' : '' }}">
                                <i class="fas fa-box nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'طلبات المنتجات': "Product Orders" }}</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <!-- Communication -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ request()->is('admin/whatsapp*') || request()->is('admin/posts*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>
                            {{ ($lang == 'ar')? 'التواصل والاتصالات': "Communication" }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.whatsapp_senders.create') }}" class="nav-link {{ request()->is('admin/whatsapp-senders*') ? 'active' : '' }}">
                                <i class="fab fa-whatsapp nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'أرقام الإرسال': "Sender Numbers" }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.whatsapp_recipients.create') }}" class="nav-link {{ request()->is('admin/whatsapp-recipients*') ? 'active' : '' }}">
                                <i class="fas fa-phone nav-icon"></i>
                                <p>{{ ($lang == 'ar')? 'أرقام الاستقبال': "Recipient Numbers" }}</p>
                            </a>
                        </li>
                        @can('Admin_Pages')
                        <li class="nav-item">
                            <a href="{{ route('admin.pages') }}" class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt nav-icon"></i>
                                <p>{{ __('page.pages') }}</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <!-- Front Office & Logout -->
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>{{ __('general.Front_Office') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>{{ __('general.Logout') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

