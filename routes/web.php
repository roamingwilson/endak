<?php

use App\Http\Controllers\departments\OrderHeavyEquipController;
use App\Models\AdsOrder;
use App\Models\Department;
use App\Models\WaterOrder;
use App\Models\BigCarOrder;
use App\Models\FamilyOrder;
use App\Models\GardenOrder;
use App\Models\WorkerOrder;
use App\Models\TeacherOrder;
use App\Models\CarWaterOrder;
use App\Models\CleaningOrder;
use App\Models\PublicGeOrder;
use App\Models\ContractingOrder;
use App\Models\MaintenanceOrder;
use App\Models\FollowCameraOrder;
use App\Models\CounterInsectsOrder;
use App\Models\PartyPreparationOrder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CleaningController;
use App\Models\FurnitureTransportationOrder;
use App\Http\Controllers\OrderUserController;
use App\Http\Controllers\RatingUserController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\MessageUserController;
use App\Http\Controllers\OrderCleaningController;
use App\Http\Controllers\departments\AdsController;
use App\Http\Controllers\GeneralCommentsController;
use App\Http\Controllers\departments\WaterController;
use App\Http\Controllers\departments\BigCarController;
use App\Http\Controllers\departments\FamilyController;
use App\Http\Controllers\departments\GardenController;
use App\Http\Controllers\departments\WorkerController;
use App\Http\Controllers\departments\TeacherController;
use App\Http\Controllers\departments\CarWaterController;
use App\Http\Controllers\departments\OrderAdsController;
use App\Http\Controllers\departments\PublicGeController;
use App\Http\Controllers\departments\OrderWaterController;
use App\Http\Controllers\departments\ContractingController;
use App\Http\Controllers\departments\MaintenanceController;
use App\Http\Controllers\departments\OrderBigCarController;
use App\Http\Controllers\departments\OrderFamilyController;
use App\Http\Controllers\departments\OrderGardenController;
use App\Http\Controllers\departments\OrderWorkerController;
use App\Http\Controllers\departments\OrderTeacherController;
use App\Http\Controllers\departments\OrderCarWaterController;
use App\Http\Controllers\departments\OrderPublicGeController;
use App\Http\Controllers\departments\CounterInsectsController;
use App\Http\Controllers\departments\HeavyEquipmentController;
use App\Http\Controllers\departments\OrderContractingController;
use App\Http\Controllers\departments\OrderMaintenanceController;
use App\Http\Controllers\departments\PartyPreparationController;
use App\Http\Controllers\departments\OrderCounterInsectsController;
use App\Http\Controllers\Surveillance\SurveillanceCamerasController;
use App\Http\Controllers\departments\OrderPartyPreparationController;
use App\Http\Controllers\Furniture\FurnitureTransportationsController;
use App\Http\Controllers\Surveillance\OrderSurveillanceCamerasController;
use App\Http\Controllers\Furniture\OrderFurnitureTransportationsController;
use App\Models\HeavyEquipment;

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

Route::get('/', function () {
    return view('front_office.home');
})->name('home');
Route::get('/page/{slug}', [PageController::class , 'pageSingle'])->name('page');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// // Translation

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
    session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');


// login & register & logout

Route::get('/login-page' , [AuthController::class , 'loginPage'])->middleware('guest')->name('login-page');
Route::get('/register-page' , [AuthController::class , 'registerPage'])->middleware('guest')->name('register-page');
Route::post('/login' , [AuthController::class , 'login'])->middleware('guest')->name('login');
Route::post('/register' , [AuthController::class , 'register'])->middleware('guest')->name('register');
Route::get('logout' , [AuthController::class , 'logout'])->middleware('auth')->name('logout');
Route::get('/forgot-password', [AuthController::class , 'forgotPassword'])->name('forgot-password');


// // Departments
Route::get('/departments', [DepartmentsController::class , 'index'])->name('departments');
Route::get('/departments/{id}', [DepartmentsController::class , 'show'])->name('departments.show');


// Posts

Route::get('posts/{id}', [PostController::class , 'index' ])->name('web.posts');
Route::get('posts/show/{id}', [PostController::class , 'show' ])->name('web.posts.show');
Route::get('posts/{id}/create', [PostController::class , 'create' ])->name('web.posts.create');
Route::get('posts/upload_video', [PostController::class , 'uploadLargeFiles' ])->name('web.files.upload.large');
Route::post('posts/store', [PostController::class , 'store' ])->name('web.posts.store');
Route::get('posts/my_posts/{id}', [PostController::class , 'my_posts' ])->name('web.posts.my_posts');

// Comments

Route::post('/comments/create' , [CommentController::class , 'store'])->name('comments.store');
Route::get('comments/my_comments/{id}', [CommentController::class , 'comments' ])->name('web.comments.my_comments');

// Orders  my_orders
Route::get('order/my_orders/{id}' , [OrderUserController::class , 'my_orders'])->name('web.order.my_orders');
Route::post('/order/create' , [OrderUserController::class , 'store'])->name('web.order.save');
Route::get('/order/{id}' , [OrderUserController::class , 'show_order'])->name('web.order.view');
Route::post('/order/complete' , [OrderUserController::class , 'finish'])->name('web.order.finish');
// Rating
Route::get('/profile/{id}' ,[ProfileController::class , 'show'] )->name('web.profile');
Route::get('/profile/edit/{id}' ,[ProfileController::class , 'edit'] )->name('web.profile.edit');
Route::post('/profile/update' ,[ProfileController::class , 'update'] )->name('web.profile.update');

// Users

Route::get('/service_provider' , [ProfileController::class , 'users'])->name('web.user.service_provider');

// Order Rating

Route::get('/add_rate/{id}' , [RatingUserController::class , 'add_rate'])->name('web.add_rate');
Route::post('/web-rate/store' , [RatingUserController::class , 'store'])->name('web.add_rate.store');

// Messages web.send_message

Route::get('/send_message/{id}' , [MessageUserController::class , 'send'])->name('web.send_message');
Route::post('/send' , [MessageUserController::class , 'store'])->name('messages.store')->middleware('auth');
// order item

Route::post('/order/items' , [OrderUserController::class , 'product_order'])->name('order_item');

// furniture_transportations
Route::group(['prefix' => "furniture_transportations"], function(){
    Route::get('/show' , [FurnitureTransportationsController::class , 'show'])->name('furniture_transportations_show');
    Route::post('/add_service' , [FurnitureTransportationsController::class , 'store_service'])->name('furniture_transportations_store_service');
    Route::get('/',[FurnitureTransportationsController::class , 'index'])->name('main_furniture_transportations');
    Route::get('/service/{id}',[FurnitureTransportationsController::class , 'show_my_service'])->name('main_furniture_transportations_show_my_service');
    Route::get('/edit/{id}',[FurnitureTransportationsController::class , 'edit'])->name('main_furniture_transportations.edit');
    Route::patch('/update/{id}',[FurnitureTransportationsController::class , 'update'])->name('main_furniture_transportations.update');
    Route::post('/accept_offer' , [OrderFurnitureTransportationsController::class , 'store'])->name('accept_offer_furniture');
    Route::get('/order' , [OrderFurnitureTransportationsController::class , 'show_orders'])->name('show_orders_furniture');
    Route::get('/order/{id}' , [OrderFurnitureTransportationsController::class , 'show'])->name('show_order_furniture');
    Route::get('/accept_project_furniture_transportation/{id}' , function($id) {
        FurnitureTransportationOrder::find($id)->update(['status' => "completed"]);
    })->name('accept_project_furniture_transportation');
});

// Surveillance Cameras
Route::group(['prefix' => "surveillance_cameras"], function(){
    Route::get('/show' , [SurveillanceCamerasController::class , 'show'])->name('surveillance_cameras_show');
    Route::post('/add_service' , [SurveillanceCamerasController::class , 'store_service'])->name('surveillance_cameras_store_service');
    Route::get('/',[SurveillanceCamerasController::class , 'index'])->name('main_surveillance_cameras');
    Route::get('/service/{id}',[SurveillanceCamerasController::class , 'show_my_service'])->name('main_surveillance_cameras_show_my_service');
    Route::get('/edit/{id}',[SurveillanceCamerasController::class , 'edit'])->name('main_surveillance_cameras.edit');
    Route::patch('/update/{id}',[SurveillanceCamerasController::class , 'update'])->name('main_surveillance_cameras.update');
    Route::post('/accept_offer' , [OrderSurveillanceCamerasController::class , 'store'])->name('accept_offer_surveillance_cameras');
    Route::get('/order' , [OrderSurveillanceCamerasController::class , 'show_orders'])->name('show_orders_surveillance_cameras');
    Route::get('/order/{id}' , [OrderSurveillanceCamerasController::class , 'show'])->name('show_order_surveillance_cameras');
    Route::get('/accept_project_surveillance_cameras/{id}' , function($id) {
        FollowCameraOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_surveillance_cameras');
});


// Party Preparation
Route::group(['prefix' => "party_preparation"], function(){
    Route::get('/show' , [PartyPreparationController::class , 'show'])->name('party_preparation_show');
    Route::post('/add_service' , [PartyPreparationController::class , 'store_service'])->name('party_preparation_store_service');
    Route::get('/',[PartyPreparationController::class , 'index'])->name('main_party_preparation');
    Route::get('/service/{id}',[PartyPreparationController::class , 'show_my_service'])->name('main_party_preparation_show_my_service');
    Route::get('/edit/{id}',[PartyPreparationController::class , 'edit'])->name('main_party_preparation.edit');
    Route::patch('/update/{id}',[PartyPreparationController::class , 'update'])->name('main_party_preparation.update');
    Route::post('/accept_offer' , [OrderPartyPreparationController::class , 'store'])->name('accept_offer_party_preparation');
    Route::get('/order' , [OrderPartyPreparationController::class , 'show_orders'])->name('show_orders_party_preparation');
    Route::get('/order/{id}' , [OrderPartyPreparationController::class , 'show'])->name('show_order_party_preparation');
    Route::get('/accept_project_party_preparation/{id}' , function($id) {
        PartyPreparationOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_party_preparation');
});


// Garden
Route::group(['prefix' => "garden"], function(){
    Route::get('/show' , [GardenController::class , 'show'])->name('garden_show');
    Route::post('/add_service' , [GardenController::class , 'store_service'])->name('garden_store_service');
    Route::get('/',[GardenController::class , 'index'])->name('main_garden');
    Route::get('/service/{id}',[GardenController::class , 'show_my_service'])->name('main_garden_show_my_service');
    Route::get('/edit/{id}',[GardenController::class , 'edit'])->name('main_garden.edit');
    Route::patch('/update/{id}',[GardenController::class , 'update'])->name('main_garden.update');
    Route::post('/accept_offer' , [OrderGardenController::class , 'store'])->name('accept_offer_garden');
    Route::get('/order' , [OrderGardenController::class , 'show_orders'])->name('show_orders_garden');
    Route::get('/order/{id}' , [OrderGardenController::class , 'show'])->name('show_order_garden');
    Route::get('/accept_project_garden/{id}' , function($id) {
        GardenOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_garden');
});

// Counter insects
Route::group(['prefix' => "counter_insects"], function(){
    Route::get('/show' , [CounterInsectsController::class , 'show'])->name('counter_insects_show');
    Route::post('/add_service' , [CounterInsectsController::class , 'store_service'])->name('counter_insects_store_service');
    Route::get('/',[CounterInsectsController::class , 'index'])->name('main_counter_insects');
    Route::get('/service/{id}',[CounterInsectsController::class , 'show_my_service'])->name('main_counter_insects_show_my_service');
    Route::get('/edit/{id}',[CounterInsectsController::class , 'edit'])->name('main_counter_insects.edit');
    Route::patch('/update/{id}',[CounterInsectsController::class , 'update'])->name('main_counter_insects.update');
    Route::post('/accept_offer' , [OrderCounterInsectsController::class , 'store'])->name('accept_offer_counter_insects');
    Route::get('/order' , [OrderCounterInsectsController::class , 'show_orders'])->name('show_orders_counter_insects');
    Route::get('/order/{id}' , [OrderCounterInsectsController::class , 'show'])->name('show_order_counter_insects');
    Route::get('/accept_project_counter_insects/{id}' , function($id) {
        CounterInsectsOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_counter_insects');
});

// Cleaning Service
Route::group(['prefix' => "cleaning"], function(){
    Route::get('/show' , [CleaningController::class , 'show'])->name('cleaning_show');
    Route::post('/add_service' , [CleaningController::class , 'store_service'])->name('cleaning_store_service');
    Route::get('/',[CleaningController::class , 'index'])->name('main_cleaning');
    Route::get('/service/{id}',[CleaningController::class , 'show_my_service'])->name('main_cleaning_show_my_service');
    Route::get('/edit/{id}',[CleaningController::class , 'edit'])->name('main_cleaning.edit');
    Route::patch('/update/{id}',[CleaningController::class , 'update'])->name('main_cleaning.update');
    Route::post('/accept_offer' , [OrderCleaningController::class , 'store'])->name('accept_offer_cleaning');
    Route::get('/order' , [OrderCleaningController::class , 'show_orders'])->name('show_orders_cleaning');
    Route::get('/order/{id}' , [OrderCleaningController::class , 'show'])->name('show_order_cleaning');
    Route::get('/accept_project_cleaning/{id}' , function($id) {
        CleaningOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_cleaning');
});


// Teacher
Route::group(['prefix' => "teacher"], function(){
    Route::get('/show' , [TeacherController::class , 'show'])->name('teacher_show');
    Route::post('/add_service' , [TeacherController::class , 'store_service'])->name('teacher_store_service');
    Route::get('/',[TeacherController::class , 'index'])->name('main_teacher');
    Route::get('/service/{id}',[TeacherController::class , 'show_my_service'])->name('main_teacher_show_my_service');
    Route::get('/edit/{id}',[TeacherController::class , 'edit'])->name('main_teacher.edit');
    Route::patch('/update/{id}',[TeacherController::class , 'update'])->name('main_teacher.update');
    Route::post('/accept_offer' , [OrderTeacherController::class , 'store'])->name('accept_offer_teacher');
    Route::get('/order' , [OrderTeacherController::class , 'show_orders'])->name('show_orders_teacher');
    Route::get('/order/{id}' , [OrderTeacherController::class , 'show'])->name('show_order_teacher');
    Route::get('/accept_project_teacher/{id}' , function($id) {
        TeacherOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_teacher');
});

// Family
Route::group(['prefix' => "family"], function(){
    Route::get('/show' , [FamilyController::class , 'show'])->name('family_show');
    Route::post('/add_service' , [FamilyController::class , 'store_service'])->name('family_store_service');
    Route::get('/',[FamilyController::class , 'index'])->name('main_family');
    Route::get('/service/{id}',[FamilyController::class , 'show_my_service'])->name('main_family_show_my_service');
    Route::get('/edit/{id}',[FamilyController::class , 'edit'])->name('main_family.edit');
    Route::patch('/update/{id}',[FamilyController::class , 'update'])->name('main_family.update');
    Route::post('/accept_offer' , [OrderFamilyController::class , 'store'])->name('accept_offer_family');
    Route::get('/order' , [OrderFamilyController::class , 'show_orders'])->name('show_orders_family');
    Route::get('/order/{id}' , [OrderFamilyController::class , 'show'])->name('show_order_family');
    Route::get('/accept_project_family/{id}' , function($id) {
        FamilyOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_family');
});

// Worker
Route::group(['prefix' => "worker"], function(){
    Route::get('/show' , [WorkerController::class , 'show'])->name('worker_show');
    Route::post('/add_service' , [WorkerController::class , 'store_service'])->name('worker_store_service');
    Route::get('/',[WorkerController::class , 'index'])->name('main_worker');
    Route::get('/service/{id}',[WorkerController::class , 'show_my_service'])->name('main_worker_show_my_service');
    Route::get('/edit/{id}',[WorkerController::class , 'edit'])->name('main_worker.edit');
    Route::patch('/update/{id}',[WorkerController::class , 'update'])->name('main_worker.update');
    Route::post('/accept_offer' , [OrderWorkerController::class , 'store'])->name('accept_offer_worker');
    Route::get('/order' , [OrderWorkerController::class , 'show_orders'])->name('show_orders_worker');
    Route::get('/order/{id}' , [OrderWorkerController::class , 'show'])->name('show_order_worker');
    Route::get('/accept_project_worker/{id}' , function($id) {
        WorkerOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_worker');
});

// Public Services
Route::group(['prefix' => "public_ge"], function(){
    Route::get('/show' , [PublicGeController::class , 'show'])->name('public_ge_show');
    Route::post('/add_service' , [PublicGeController::class , 'store_service'])->name('public_ge_store_service');
    Route::get('/',[PublicGeController::class , 'index'])->name('main_public_ge');
    Route::get('/service/{id}',[PublicGeController::class , 'show_my_service'])->name('main_public_ge_show_my_service');
    Route::get('/edit/{id}',[PublicGeController::class , 'edit'])->name('main_public_ge.edit');
    Route::patch('/update/{id}',[PublicGeController::class , 'update'])->name('main_public_ge.update');
    Route::post('/accept_offer' , [OrderPublicGeController::class , 'store'])->name('accept_offer_public_ge');
    Route::get('/order' , [OrderPublicGeController::class , 'show_orders'])->name('show_orders_public_ge');
    Route::get('/order/{id}' , [OrderPublicGeController::class , 'show'])->name('show_order_public_ge');
    Route::get('/accept_project_public_ge/{id}' , function($id) {
        PublicGeOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_public_ge');
});

// Ads
Route::group(['prefix' => "ads"], function(){
    Route::get('/show' , [AdsController::class , 'show'])->name('ads_show');
    Route::post('/add_service' , [AdsController::class , 'store_service'])->name('ads_store_service');
    Route::get('/',[AdsController::class , 'index'])->name('main_ads');
    Route::get('/service/{id}',[AdsController::class , 'show_my_service'])->name('main_ads_show_my_service');
    Route::get('/edit/{id}',[AdsController::class , 'edit'])->name('main_ads.edit');
    Route::patch('/update/{id}',[AdsController::class , 'update'])->name('main_ads.update');
    Route::post('/accept_offer' , [OrderAdsController::class , 'store'])->name('accept_offer_ads');
    Route::get('/order' , [OrderAdsController::class , 'show_orders'])->name('show_orders_ads');
    Route::get('/order/{id}' , [OrderAdsController::class , 'show'])->name('show_order_ads');
    Route::get('/accept_project_ads/{id}' , function($id) {
        AdsOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_ads');
});

// Water
Route::group(['prefix' => "water"], function(){
    Route::get('/show' , [WaterController::class , 'show'])->name('water_show');
    Route::post('/add_service' , [WaterController::class , 'store_service'])->name('water_store_service');
    Route::get('/',[WaterController::class , 'index'])->name('main_water');
    Route::get('/service/{id}',[WaterController::class , 'show_my_service'])->name('main_water_show_my_service');
    Route::get('/edit/{id}',[WaterController::class , 'edit'])->name('main_water.edit');
    Route::patch('/update/{id}',[WaterController::class , 'update'])->name('main_water.update');
    Route::post('/accept_offer' , [OrderWaterController::class , 'store'])->name('accept_offer_water');
    Route::get('/order' , [OrderWaterController::class , 'show_orders'])->name('show_orders_water');
    Route::get('/order/{id}' , [OrderWaterController::class , 'show'])->name('show_order_water');
    Route::get('/accept_project_water/{id}' , function($id) {
        WaterOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_water');
});

// Car Water
Route::group(['prefix' => "car_water"], function(){
    Route::get('/show' , [CarWaterController::class , 'show'])->name('car_water_show');
    Route::post('/add_service' , [CarWaterController::class , 'store_service'])->name('car_water_store_service');
    Route::get('/',[CarWaterController::class , 'index'])->name('main_car_water');
    Route::get('/service/{id}',[CarWaterController::class , 'show_my_service'])->name('main_car_water_show_my_service');
    Route::get('/edit/{id}',[CarWaterController::class , 'edit'])->name('main_car_water.edit');
    Route::patch('/update/{id}',[CarWaterController::class , 'update'])->name('main_car_water.update');
    Route::post('/accept_offer' , [OrderCarWaterController::class , 'store'])->name('accept_offer_car_water');
    Route::get('/order' , [OrderCarWaterController::class , 'show_orders'])->name('show_orders_car_water');
    Route::get('/order/{id}' , [OrderCarWaterController::class , 'show'])->name('show_order_car_water');
    Route::get('/accept_project_car_water/{id}' , function($id) {
        CarWaterOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_car_water');
});


// Big Car
Route::group(['prefix' => "big_car"], function(){
    Route::get('/show' , [BigCarController::class , 'show'])->name('big_car_show');
    Route::post('/add_service' , [BigCarController::class , 'store_service'])->name('big_car_store_service');
    Route::get('/',[BigCarController::class , 'index'])->name('main_big_car');
    Route::get('/service/{id}',[BigCarController::class , 'show_my_service'])->name('main_big_car_show_my_service');
    Route::get('/edit/{id}',[BigCarController::class , 'edit'])->name('main_big_car.edit');
    Route::patch('/update/{id}',[BigCarController::class , 'update'])->name('main_big_car.update');
    Route::post('/accept_offer' , [OrderBigCarController::class , 'store'])->name('accept_offer_big_car');
    Route::get('/order' , [OrderBigCarController::class , 'show_orders'])->name('show_orders_big_car');
    Route::get('/order/{id}' , [OrderBigCarController::class , 'show'])->name('show_order_big_car');
    Route::get('/accept_project_big_car/{id}' , function($id) {
        BigCarOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_big_car');
});



// Contractings
Route::group(['prefix' => "contracting"], function(){
    Route::get('/show' , [ContractingController::class , 'show'])->name('contracting_show');
    Route::get('/contracting_sub_show/{id}' , [ContractingController::class , 'contracting_sub_show'])->name('contracting_sub_show');
    Route::post('/add_service' , [ContractingController::class , 'store_service'])->name('contracting_store_service');
    Route::get('/',[ContractingController::class , 'index'])->name('main_contracting');
    Route::get('/service/{id}',[ContractingController::class , 'show_my_service'])->name('main_contracting_show_my_service');
    Route::get('/edit/{id}',[ContractingController::class , 'edit'])->name('main_contracting.edit');
    Route::patch('/update/{id}',[ContractingController::class , 'update'])->name('main_contracting.update');
    Route::post('/accept_offer' , [OrderContractingController::class , 'store'])->name('accept_offer_contracting');
    Route::get('/order' , [OrderContractingController::class , 'show_orders'])->name('show_orders_contracting');
    Route::get('/order/{id}' , [OrderContractingController::class , 'show'])->name('show_order_contracting');
    Route::get('/accept_project_contracting/{id}' , function($id) {
        ContractingOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_contracting');
});

// Car Maintenance
Route::group(['prefix' => "maintenance"], function(){
    Route::get('/show' , [MaintenanceController::class , 'show'])->name('maintenance_show');
    Route::get('/maintenance_sub_show/{id}' , [MaintenanceController::class , 'maintenance_sub_show'])->name('maintenance_sub_show');
    Route::post('/add_service' , [MaintenanceController::class , 'store_service'])->name('maintenance_store_service');
    Route::get('/',[MaintenanceController::class , 'index'])->name('main_maintenance');
    Route::get('/service/{id}',[MaintenanceController::class , 'show_my_service'])->name('main_maintenance_show_my_service');
    Route::get('/edit/{id}',[MaintenanceController::class , 'edit'])->name('main_maintenance.edit');
    Route::patch('/update/{id}',[MaintenanceController::class , 'update'])->name('main_maintenance.update');
    Route::post('/accept_offer' , [OrderMaintenanceController::class , 'store'])->name('accept_offer_maintenance');
    Route::get('/order' , [OrderMaintenanceController::class , 'show_orders'])->name('show_orders_maintenance');
    Route::get('/order/{id}' , [OrderMaintenanceController::class , 'show'])->name('show_order_maintenance');
    Route::get('/accept_project_maintenance/{id}' , function($id) {
        MaintenanceOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_maintenance');
});
Route::group(['prefix' => "heavy_equip"], function(){
    Route::get('/show' , [HeavyEquipmentController::class , 'show'])->name('heavy_equip_show');
    Route::post('/add_service' , [HeavyEquipmentController::class , 'store_service'])->name('heavy_equip_store_service');
    Route::get('/',[HeavyEquipmentController::class , 'index'])->name('main_heavy_equip');
    Route::get('/service/{id}',[HeavyEquipmentController::class , 'show_my_service'])->name('main_heavy_equip_show_my_service');
    Route::get('/edit/{id}',[HeavyEquipmentController::class , 'edit'])->name('main_heavy_equip.edit');
    Route::patch('/update/{id}',[HeavyEquipmentController::class , 'update'])->name('main_heavy_equip.update');
    Route::post('/accept_offer' , [OrderHeavyEquipController::class , 'store'])->name('accept_offer_heavy_equip');
    Route::get('/order' , [OrderHeavyEquipController::class , 'show_orders'])->name('show_orders_heavy_equip');
    Route::get('/order/{id}' , [OrderHeavyEquipController::class , 'show'])->name('show_order_heavy_equip');
    Route::get('/accept_project_heavy_equip/{id}' , function($id) {
        BigCarOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_heavy_equip');
});




// general_comments
Route::post('/comments/create' , [GeneralCommentsController::class , 'store'])->name('general_comments.store');

