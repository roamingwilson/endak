<?php

use App\Http\Controllers\departments\Industry\indSubCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\departments\AdsController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\departments\WaterController;
use App\Http\Controllers\departments\BigCarController;
use App\Http\Controllers\departments\FamilyController;
use App\Http\Controllers\departments\GardenController;
use App\Http\Controllers\departments\WorkerController;
use App\Http\Controllers\departments\TeacherController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\departments\Aircondition\AirconController;
use App\Http\Controllers\departments\CarWaterController;
use App\Http\Controllers\departments\PublicGeController;
use App\Http\Controllers\departments\ContractingController;
use App\Http\Controllers\departments\MaintenanceController;
use App\Http\Controllers\departments\CounterInsectsController;
use App\Http\Controllers\departments\HeavyEquipmentController;
use App\Http\Controllers\departments\Industry\indsCategoryController;
use App\Http\Controllers\departments\Industry\indsProductController;
use App\Http\Controllers\departments\PartyPreparationController;
use App\Http\Controllers\departments\SpareParts\SparePartController;
use App\Http\Controllers\departments\VanTruck\VanTruckController;
use App\Http\Controllers\Surveillance\SurveillanceCamerasController;
use App\Http\Controllers\Furniture\FurnitureTransportationsController;
use App\Http\Controllers\Furniture\ProductFurnitureTransportationsController;
use App\Http\Controllers\ProductOrderController;
use App\Models\HeavyEquipment;
use App\Models\indsProduct;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// roles

Route::group(['prefix' => 'roles'], function () {
    Route::get('/', [RoleController::class, 'index'])->name('admin.roles');
    Route::get('/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/store', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::post('/{id}/update', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/delete', [RoleController::class, 'destroy'])->name('admin.roles.delete');
});


// Settings

Route::group(['prefix' => 'settings'], function () {

    Route::get('/', [SettingsController::class, 'index'])->name('admin.settings');
    Route::get('/edit/{setting}', [SettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::put('/update/{setting}', [SettingsController::class, 'update'])->name('admin.settings.update');
});

// Categories
Route::group(['prefix' => 'categories'], function () {

    Route::get('/', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/create', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('admin.department.edit');
    Route::get('/show/{id}', [DepartmentController::class, 'show'])->name('admin.department.show');
    Route::post('/edit/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('update_top_categories', 'CategoriesController@update_top_categories')->name('update_top_categories');
    Route::get('/delete/{slug}', [CategoryController::class, 'destroy'])->name('admin.categories.delete');
});



// Pages
Route::group(['prefix' => 'pages'], function () {

    Route::get('/', [PageController::class, 'index'])->name('admin.pages');
    Route::get('/create', [PageController::class, 'create'])->name('admin.pages.create');
    Route::post('/create', [PageController::class, 'store'])->name('admin.pages.store');
    Route::get('/show/{slug}', [PageController::class, 'show'])->name('admin.pages.show');
    Route::get('/edit/{setting}', [PageController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/update/{id}', [PageController::class, 'update'])->name('admin.pages.update');
    Route::delete('/delete', [PageController::class, 'destroy'])->name('admin.pages.delete');
});

// Departments
Route::group(['prefix' => 'departments'], function () {

    Route::get('/', [DepartmentController::class, 'index'])->name('admin.departments');
    Route::get('/create', [DepartmentController::class, 'create'])->name('admin.departments.create');
    Route::post('/create', [DepartmentController::class, 'store'])->name('admin.departments.store');
    Route::get('/show/{slug}', [DepartmentController::class, 'show'])->name('admin.departments.show');
    Route::get('/edit/{category}', [DepartmentController::class, 'edit'])->name('admin.departments.edit');
    Route::put('/edit/{id}', [DepartmentController::class, 'update'])->name('admin.departments.update');
    Route::post('update_top_departments', [DepartmentController::class, 'update_top_departments'])->name('update_top_departments');
    Route::delete('/{id}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');
});
Route::group(['prefix' => 'orders'], function () {

    Route::get('/', [OrderController::class, 'index'])->name('admin.orders');
    // Route::get('/show/{id}' , [OrderController::class , 'show'])->name('admin.orders.show');
    Route::get('/delete/{id}', [OrderController::class, 'destroy'])->name('admin.orders.delete');
});

// Posts

Route::group(['prefix' => 'posts'], function () {

    Route::get('/', [PostController::class, 'index'])->name('admin.posts');
    // Route::get('/', 'AdminController@adminOffers')->name('admin_offers');

    // Route::post('post-stats', [PostController::class , 'post_stats'])->name('post-stats');
    Route::post('status-update', [PostController::class, 'status_update'])->name('admin.posts.status-update');

    // Route::get('/create', [PageController::class ,'create'])->name('admin.pages.create');
    // Route::post('/create', [PageController::class ,'store'])->name('admin.pages.store');
    Route::get('/posts/{slug}', [PageController::class, 'show'])->name('admin.posts.show');
    // Route::get('/edit/{setting}' , [PageController::class , 'edit'])->name('admin.pages.edit');
    // Route::put('/update/{id}' , [PageController::class , 'update'])->name('admin.pages.update');
    // Route::delete('/delete', [PageController::class ,'destroy'])->name('admin.pages.delete');

});
Route::group(['prefix' => 'products'], function () {

    Route::get('/', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/create', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/show/{slug}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/edit/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::get('/delete/{slug}', [ProductController::class, 'destroy'])->name('admin.products.delete');
});

// User Management
Route::get('/inputs', [UserManagementController::class, 'inputs'])->name('admin.inputs');

Route::group(['prefix' => 'user_management'], function () {

    Route::get('/', [UserManagementController::class, 'index'])->name('admin.user_management');
    Route::get('/show/{id}', [UserManagementController::class, 'show'])->name('admin.user_management.show');
    Route::get('/show_user_conversation/{id}', [UserManagementController::class, 'show_user_conversation'])->name('show_user_conversation');
    // Route::get('/create', [ProductController::class , 'create'])->name('admin.products.create');
    // Route::post('/create', [ProductController::class , 'store'])->name('admin.products.store');
    // Route::get('/edit/{product}' , [ProductController::class , 'edit'])->name('admin.products.edit');
    // Route::post('/edit/{product}' , [ProductController::class , 'update'])->name('admin.products.update');
    // Route::get('/delete/{slug}', [ProductController::class ,'destroy'])->name('admin.products.delete');

});

// furniture transportations

Route::group(['prefix' => "furniture_transportations"], function () {
    Route::get('/service_provider', [FurnitureTransportationsController::class, 'service_provider'])->name('furniture_transportations_service');
    Route::get('/add_service', [FurnitureTransportationsController::class, 'add_service'])->name('furniture_transportations_add_service');
    Route::post('/add_service', [FurnitureTransportationsController::class, 'store_service'])->name('furniture_transportations_store_service');
    Route::get('/', [FurnitureTransportationsController::class, 'index'])->name('main_furniture_transportations');
    Route::get('/edit/{id}', [FurnitureTransportationsController::class, 'edit'])->name('main_furniture_transportations.edit');
    Route::patch('/update/{id}', [FurnitureTransportationsController::class, 'update'])->name('main_furniture_transportations.update');
    // Products
    Route::get('/products', [ProductFurnitureTransportationsController::class, 'index'])->name('main_furniture_transportations.product');
    Route::get('/products/create', [ProductFurnitureTransportationsController::class, 'create'])->name('main_furniture_transportations.product.create');
    Route::post('/products/store', [ProductFurnitureTransportationsController::class, 'store'])->name('main_furniture_transportations.product.store');
    Route::get('/products/edit/{id}', [ProductFurnitureTransportationsController::class, 'edit'])->name('main_furniture_transportations.product.edit');
    Route::patch('/products/update/{id}', [ProductFurnitureTransportationsController::class, 'update'])->name('main_furniture_transportations.product.update');
    Route::get('/products/delete/{id}', [ProductFurnitureTransportationsController::class, 'destroy'])->name('main_furniture_transportations.product.delete');
    // Route::get('/products',[ProductFurnitureTransportationsController::class , 'index'])->name('main_furniture_transportations.product.status');
});

// Surveillance Cameras

Route::group(['prefix' => "surveillance_cameras"], function () {
    Route::get('/', [SurveillanceCamerasController::class, 'index'])->name('admin.surveillance');
    Route::get('/edit/{id}', [SurveillanceCamerasController::class, 'edit'])->name('admin.surveillance.edit');
    Route::patch('/update/{id}', [SurveillanceCamerasController::class, 'update'])->name('admin.surveillance.update');
});

//  Party preparation

Route::group(['prefix' => "party_preparation"], function () {
    Route::get('/', [PartyPreparationController::class, 'index'])->name('admin.party_preparation');
    Route::get('/edit/{id}', [PartyPreparationController::class, 'edit'])->name('admin.party_preparation.edit');
    Route::patch('/update/{id}', [PartyPreparationController::class, 'update'])->name('admin.party_preparation.update');
});
//  Garden

Route::group(['prefix' => "garden"], function () {
    Route::get('/', [GardenController::class, 'index'])->name('admin.garden');
    Route::get('/edit/{id}', [GardenController::class, 'edit'])->name('admin.garden.edit');
    Route::patch('/update/{id}', [GardenController::class, 'update'])->name('admin.garden.update');
});

// counter_insects
Route::group(['prefix' => "counter_insects"], function () {
    Route::get('/', [CounterInsectsController::class, 'index'])->name('admin.counter_insects');
    Route::get('/edit/{id}', [CounterInsectsController::class, 'edit'])->name('admin.counter_insects.edit');
    Route::patch('/update/{id}', [CounterInsectsController::class, 'update'])->name('admin.counter_insects.update');
});


// cleaning
Route::group(['prefix' => "cleaning"], function () {
    Route::get('/', [CleaningController::class, 'index'])->name('admin.cleaning');
    Route::get('/edit/{id}', [CleaningController::class, 'edit'])->name('admin.cleaning.edit');
    Route::patch('/update/{id}', [CleaningController::class, 'update'])->name('admin.cleaning.update');
});


// Teacher
Route::group(['prefix' => "teacher"], function () {
    Route::get('/', [TeacherController::class, 'index'])->name('admin.teacher');
    Route::get('/edit/{id}', [TeacherController::class, 'edit'])->name('admin.teacher.edit');
    Route::patch('/update/{id}', [TeacherController::class, 'update'])->name('admin.teacher.update');
});


// Family
Route::group(['prefix' => "family"], function () {
    Route::get('/', [FamilyController::class, 'index'])->name('admin.family');
    Route::get('/edit/{id}', [FamilyController::class, 'edit'])->name('admin.family.edit');
    Route::patch('/update/{id}', [FamilyController::class, 'update'])->name('admin.family.update');
});

// Worker
Route::group(['prefix' => "worker"], function () {
    Route::get('/', [WorkerController::class, 'index'])->name('admin.worker');
    Route::get('/edit/{id}', [WorkerController::class, 'edit'])->name('admin.worker.edit');
    Route::patch('/update/{id}', [WorkerController::class, 'update'])->name('admin.worker.update');
});

// Public Service
Route::group(['prefix' => "public_ge"], function () {
    Route::get('/', [PublicGeController::class, 'index'])->name('admin.public_ge');
    Route::get('/edit/{id}', [PublicGeController::class, 'edit'])->name('admin.public_ge.edit');
    Route::patch('/update/{id}', [PublicGeController::class, 'update'])->name('admin.public_ge.update');
});

// Ads
Route::group(['prefix' => "ads"], function () {
    Route::get('/', [AdsController::class, 'index'])->name('admin.ads');
    Route::get('/edit/{id}', [AdsController::class, 'edit'])->name('admin.ads.edit');
    Route::patch('/update/{id}', [AdsController::class, 'update'])->name('admin.ads.update');
});

// Water
Route::group(['prefix' => "water"], function () {
    Route::get('/', [WaterController::class, 'index'])->name('admin.water');
    Route::get('/edit/{id}', [WaterController::class, 'edit'])->name('admin.water.edit');
    Route::patch('/update/{id}', [WaterController::class, 'update'])->name('admin.water.update');
});
// Car Water
Route::group(['prefix' => "car_water"], function () {
    Route::get('/', [CarWaterController::class, 'index'])->name('admin.car_water');
    Route::get('/edit/{id}', [CarWaterController::class, 'edit'])->name('admin.car_water.edit');
    Route::patch('/update/{id}', [CarWaterController::class, 'update'])->name('admin.car_water.update');
});


// Big Car
Route::group(['prefix' => "big_car"], function () {
    Route::get('/', [BigCarController::class, 'index'])->name('admin.big_car');
    Route::get('/edit/{id}', [BigCarController::class, 'edit'])->name('admin.big_car.edit');
    Route::patch('/update/{id}', [BigCarController::class, 'update'])->name('admin.big_car.update');
});


// Contracting
Route::group(['prefix' => "contracting"], function () {
    Route::get('/', [ContractingController::class, 'index'])->name('admin.contracting');
    Route::get('/edit/{id}', [ContractingController::class, 'edit'])->name('admin.contracting.edit');
    Route::patch('/update/{id}', [ContractingController::class, 'update'])->name('admin.contracting.update');
    Route::get('/add_sub_department', [ContractingController::class, 'add_sub_department'])->name('admin.contracting.add_sub_department');
    Route::post('/store_sub_department', [ContractingController::class, 'store_sub_department'])->name('admin.contracting.store_sub_department');
    Route::get('/show_sub_departments_list', [ContractingController::class, 'show_sub_departments_list'])->name('admin.contracting.show_sub_departments_list');
    Route::get('/show_sub_department/{id}', [ContractingController::class, 'show_sub_department'])->name('admin.contracting.show_sub_department');
    Route::get('/delete/{id}', [ContractingController::class, 'delete'])->name('admin.contracting.delete');
});

// Car Maintenance
Route::group(['prefix' => "maintenance"], function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('admin.maintenance');
    Route::get('/edit/{id}', [MaintenanceController::class, 'edit'])->name('admin.maintenance.edit');
    Route::patch('/update/{id}', [MaintenanceController::class, 'update'])->name('admin.maintenance.update');
    Route::get('/add_sub_department', [MaintenanceController::class, 'add_sub_department'])->name('admin.maintenance.add_sub_department');
    Route::post('/store_sub_department', [MaintenanceController::class, 'store_sub_department'])->name('admin.maintenance.store_sub_department');
    Route::get('/show_sub_departments_list', [MaintenanceController::class, 'show_sub_departments_list'])->name('admin.maintenance.show_sub_departments_list');
    Route::get('/show_sub_department/{id}', [MaintenanceController::class, 'show_sub_department'])->name('admin.maintenance.show_sub_department');
    Route::get('/delete/{id}', [MaintenanceController::class, 'delete'])->name('admin.maintenance.delete');
});
//Heavy Equipment
Route::group(['prefix' => "heavy_equip"], function () {
    Route::get('/', [HeavyEquipmentController::class, 'index'])->name('admin.heavy_equip');
    Route::get('/edit/{id}', [HeavyEquipmentController::class, 'edit'])->name('admin.heavy_equip.edit');
    Route::patch('/update/{id}', [HeavyEquipmentController::class, 'update'])->name('admin.heavy_equip.update');
    Route::get('/add_sub_department', [HeavyEquipmentController::class, 'add_sub_department'])->name('admin.heavy_equip.add_sub_department');
    Route::post('/store_sub_department', [HeavyEquipmentController::class, 'store_sub_department'])->name('admin.heavy_equip.store_sub_department');
    Route::get('/show_sub_departments_list', [HeavyEquipmentController::class, 'show_sub_departments_list'])->name('admin.heavy_equip.show_sub_departments_list');
    Route::get('/show_sub_department/{id}', [HeavyEquipmentController::class, 'show_sub_department'])->name('admin.heavy_equip.show_sub_department');
    Route::get('/delete/{id}', [HeavyEquipmentController::class, 'delete'])->name('admin.heavy_equip.delete');
});
//spare_part
Route::group(['prefix' => "spare_part"], function () {
    Route::get('/', [SparePartController::class, 'index'])->name('admin.spare_part');
    Route::get('/edit/{id}', [SparePartController::class, 'edit'])->name('admin.spare_part.edit');
    Route::patch('/update/{id}', [SparePartController::class, 'update'])->name('admin.spare_part.update');
    Route::get('/add_sub_department', [SparePartController::class, 'add_sub_department'])->name('admin.spare_part.add_sub_department');
    Route::post('/store_sub_department', [SparePartController::class, 'store_sub_department'])->name('admin.spare_part.store_sub_department');
    Route::get('/show_sub_departments_list', [SparePartController::class, 'show_sub_departments_list'])->name('admin.spare_part.show_sub_departments_list');
    Route::get('/show_sub_department/{id}', [SparePartController::class, 'show_sub_department'])->name('admin.spare_part.show_sub_department');
    Route::get('/delete/{id}', [SparePartController::class, 'delete'])->name('admin.spare_part.delete');
});
Route::group(['prefix' => "air_con"], function () {
    Route::get('/', [AirconController::class, 'index'])->name('admin.air_con');
    Route::get('/edit/{id}', [AirconController::class, 'edit'])->name('admin.air_con.edit');
    Route::patch('/update/{id}', [AirconController::class, 'update'])->name('admin.air_con.update');
});
Route::group(['prefix' => "van_truck"], function () {
    Route::get('/', [VanTruckController::class, 'index'])->name('admin.van_truck');
    Route::get('/edit/{id}', [VanTruckController::class, 'edit'])->name('admin.van_truck.edit');
    Route::patch('/update/{id}', [VanTruckController::class, 'update'])->name('admin.van_truck.update');
    Route::get('/add_sub_department', [VanTruckController::class, 'add_sub_department'])->name('admin.van_truck.add_sub_department');
    Route::post('/store_sub_department', [VanTruckController::class, 'store_sub_department'])->name('admin.van_truck.store_sub_department');
    Route::get('/show_sub_departments_list', [VanTruckController::class, 'show_sub_departments_list'])->name('admin.van_truck.show_sub_departments_list');
    Route::get('/show_sub_department/{id}', [VanTruckController::class, 'show_sub_department'])->name('admin.van_truck.show_sub_department');
    Route::get('/delete/{id}', [VanTruckController::class, 'delete'])->name('admin.van_truck.delete');
});
Route::group(['prefix' => "plastic"], function () {
    Route::resource('indscategories', indsCategoryController::class);
    Route::resource('indsubcategories', indSubCategoryController::class);
    Route::resource('indproducts', indsProductController::class);
});

Route::get('/admin/orders', [ProductOrderController::class, 'manage'])->name('admin.pro_orders.manage');
Route::post('/admin/orders/{id}/update-status', [ProductOrderController::class, 'updateStatus'])->name('admin.pro_orders.updateStatus');
Route::delete('/admin/orders/{id}', [ProductOrderController::class, 'destroy'])->name('admin.pro_orders.destroy');
Route::get('admin/pro_orders/show/{id}', [ProductOrderController::class, 'adminShow'])->name('admin.pro_orders.show');
Route::post('orders/bulk-action', [ProductOrderController::class, 'bulkAction'])->name('admin.orders.bulk_action');



Route::get('/admin/service/orders', [PostController::class, 'showOrdersForservice'])->name('admin.service.order');
