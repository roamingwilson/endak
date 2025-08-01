<?php

use App\Http\Controllers\Api\LocationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;

use App\Models\FurnitureTransportationOrder;
use App\Http\Controllers\OrderUserController;
use App\Http\Controllers\RatingUserController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\MessageUserController;

use App\Http\Controllers\GeneralCommentsController;

use App\Http\Controllers\departments\ContractingController;
use App\Http\Controllers\departments\MaintenanceController;

use App\Http\Controllers\departments\HeavyEquipmentController;
use App\Http\Controllers\departments\Industry\indsProductController;

use App\Http\Controllers\departments\SpareParts\SparePartController;
use App\Http\Controllers\departments\VanTruck\VanTruckController;
use App\Http\Controllers\departments\VanTruck\VanTruckOrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Furniture\FurnitureTransportationsController;

use App\Http\Controllers\Furniture\OrderFurnitureTransportationsController;
use App\Http\Controllers\GeneralOrderController;
use App\Http\Controllers\GovernementsController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductCartController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\ProductOrderitemsController;
use App\Http\Controllers\ServiceController;

use App\Models\GeneralOrder;
use App\Models\ProductOrder;
use App\Models\ProductOrderitems;
use App\Models\VanTruckOrder;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\departments\PartyPreparationController;
use App\Http\Controllers\departments\OrderPartyPreparationController;
use App\Models\PartyPreparationOrder;

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

// Route::get('/', function () {
//     return view('front_office.home');
// })->name('home');
Route::get(
    '/',
    function () {

        return view('front_office.home');
    }

)->name('home');
Route::middleware('auth')->group(function () {
    // مسار لتعيين النوتيفيكاشن كـ "مقروء"
    Route::get('/notification/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('notification.read');
    Route::get('/notifications', [notificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-as-read', [notificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // مسار لتعليم جميع الرسائل كمقروءة
    Route::post('/messages/mark-all-as-read', [MessageController::class, 'markAllAsRead'])->name('messages.markAllAsRead');
});

Route::get('/page/{slug}', [PageController::class, 'pageSingle'])->name('page');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// // Translation

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');


// login & register & logout

Route::middleware('guest')->group(function () {
    Route::get('/login-page', [AuthController::class, 'loginPage'])->name('login-page');
    Route::get('/register-page', [AuthController::class, 'registerPage'])->name('register-page');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

    // OTP Routes
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/register/resend-otp', [AuthController::class, 'resendOtp'])->name('register.resend_otp');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

// // Departments
Route::get('/departments', [DepartmentsController::class, 'index'])->name('departments');
Route::get('/departments/{id}', [DepartmentsController::class, 'show'])->name('departments.show');

// Get governorates by country
Route::get('/get-governorates', [GovernementsController::class, 'getByCountry'])->name('get.governorates');


// Posts

Route::get('posts/{id}', [PostController::class, 'index'])->name('web.posts');
Route::get('posts/show/{id}', [PostController::class, 'show'])->name('web.posts.show');
Route::get('posts/{id}/create', [PostController::class, 'create'])->name('web.posts.create');
Route::get('posts/upload_video', [PostController::class, 'uploadLargeFiles'])->name('web.files.upload.large');
Route::post('posts/store', [PostController::class, 'store'])->name('web.posts.store');
Route::get('posts/my_posts/{id}', [PostController::class, 'my_posts'])->name('web.posts.my_posts');

// Comments

Route::post('/comments/create', [CommentController::class, 'store'])->name('comments.store');
Route::get('comments/my_comments/{id}', [CommentController::class, 'comments'])->name('web.comments.my_comments');

// Orders  my_orders
Route::get('order/my_orders/{id}', [OrderUserController::class, 'my_orders'])->name('web.order.my_orders');
Route::post('/order/create', [OrderUserController::class, 'store'])->name('web.order.save');
Route::get('/order/{id}', [OrderUserController::class, 'show_order'])->name('web.order.view');
Route::post('/order/complete', [OrderUserController::class, 'finish'])->name('web.order.finish');
// Rating
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('web.profile');
Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('web.profile.edit');
Route::get('/feedback', [ProfileController::class, 'user_note'])->name('feedback');
Route::get('/FAQ', [ProfileController::class, 'FAQ'])->name('faq');
Route::get('/privcy', [ProfileController::class, 'privcy'])->name('privcy');
Route::get('/terms', [ProfileController::class, 'terms'])->name('terms');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('web.profile.update');

// Users

Route::get('/service_provider', [ProfileController::class, 'users'])->name('web.user.service_provider');

// Order Rating

Route::get('/add_rate/{id}', [RatingUserController::class, 'add_rate'])->name('web.add_rate');
Route::post('/web-rate/store/{id}', [RatingUserController::class, 'store'])->name('web.add_rate.store');

// Messages web.send_message

Route::get('/send_message/{id}', [MessageUserController::class, 'send'])->name('web.send_message');
Route::post('/send', [MessageUserController::class, 'store'])->name('messages.store')->middleware('auth');
// order item

Route::post('/order/items', [OrderUserController::class, 'product_order'])->name('order_item');

// furniture_transportations
Route::group(['prefix' => "furniture_transportations"], function () {
    Route::get('/show', [FurnitureTransportationsController::class, 'show'])->name('furniture_transportations_show');
    // Route::post('/add_service' , [FurnitureTransportationsController::class , 'store_service'])->name('furniture_transportations_store_service');
    // Route::get('/',[FurnitureTransportationsController::class , 'index'])->name('main_furniture_transportations');
    Route::get('/service/{id}', [FurnitureTransportationsController::class, 'show_my_service'])->name('main_furniture_transportations_show_my_service');
    // Route::get('/edit/{id}',[FurnitureTransportationsController::class , 'edit'])->name('main_furniture_transportations.edit');
    // Route::patch('/update/{id}',[FurnitureTransportationsController::class , 'update'])->name('main_furniture_transportations.update');
    Route::post('/accept_offer', [OrderFurnitureTransportationsController::class, 'store'])->name('accept_offer_furniture');
    Route::get('/order', [OrderFurnitureTransportationsController::class, 'show_orders'])->name('show_orders_furniture');
    Route::get('/order/{id}', [OrderFurnitureTransportationsController::class, 'show'])->name('show_order_furniture');
    Route::get('/accept_project_furniture_transportation/{id}', function ($id) {
        FurnitureTransportationOrder::find($id)->update(['status' => "completed"]);
    })->name('accept_project_furniture_transportation');
    Route::get('service/edit/{id}', [FurnitureTransportationsController::class, 'edit_service'])->name('service_furniture_transportations.edit');
    Route::put('service/update/{id}', [FurnitureTransportationsController::class, 'update_service'])->name('service_furniture_transportations.update');
    Route::delete('/van-truck-services/{id}', [FurnitureTransportationsController::class, 'destroy_service'])->name('service_furniture_transportations.destroy');
});

// Surveillance Cameras
// Route::group(['prefix' => "surveillance_cameras"], function(){
//     Route::get('/show' , [SurveillanceCamerasController::class , 'show'])->name('surveillance_cameras_show');
//     Route::post('/add_service' , [SurveillanceCamerasController::class , 'store_service'])->name('surveillance_cameras_store_service');
//     Route::get('/',[SurveillanceCamerasController::class , 'index'])->name('main_surveillance_cameras');
//     Route::get('/service/{id}',[SurveillanceCamerasController::class , 'show_my_service'])->name('main_surveillance_cameras_show_my_service');
//     Route::get('/edit/{id}',[SurveillanceCamerasController::class , 'edit'])->name('main_surveillance_cameras.edit');
//     Route::patch('/update/{id}',[SurveillanceCamerasController::class , 'update'])->name('main_surveillance_cameras.update');
//     Route::post('/accept_offer' , [OrderSurveillanceCamerasController::class , 'store'])->name('accept_offer_surveillance_cameras');
//     Route::get('/order' , [OrderSurveillanceCamerasController::class , 'show_orders'])->name('show_orders_surveillance_cameras');
//     Route::get('/order/{id}' , [OrderSurveillanceCamerasController::class , 'show'])->name('show_order_surveillance_cameras');
//     Route::get('/accept_project_surveillance_cameras/{id}' , function($id) {
//         FollowCameraOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_surveillance_cameras');
//     Route::get('service/edit/{id}',[SurveillanceCamerasController::class , 'edit_service'])->name('service_surveillance_cameras.edit');
//     Route::put('service/update/{id}',[SurveillanceCamerasController::class , 'update_service'])->name('service_surveillance_cameras.update');
//     Route::delete('/van-truck-services/{id}', [SurveillanceCamerasController::class, 'destroy_service'])->name('service_surveillance_cameras.destroy');
// });


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
    Route::get('service/edit/{id}',[PartyPreparationController::class , 'edit_service'])->name('service_party_preparation.edit');
    Route::put('service/update/{id}',[PartyPreparationController::class , 'update_service'])->name('service_party_preparation.update');
    Route::delete('/van-truck-services/{id}', [PartyPreparationController::class, 'destroy_service'])->name('service_party_preparation.destroy');
});


// Garden
// Route::group(['prefix' => "garden"], function(){
//     Route::get('/show' , [GardenController::class , 'show'])->name('garden_show');
//     Route::post('/add_service' , [GardenController::class , 'store_service'])->name('garden_store_service');
//     Route::get('/',[GardenController::class , 'index'])->name('main_garden');
//     Route::get('/service/{id}',[GardenController::class , 'show_my_service'])->name('main_garden_show_my_service');
//     Route::get('/edit/{id}',[GardenController::class , 'edit'])->name('main_garden.edit');
//     Route::patch('/update/{id}',[GardenController::class , 'update'])->name('main_garden.update');
//     Route::post('/accept_offer' , [OrderGardenController::class , 'store'])->name('accept_offer_garden');
//     Route::get('/order' , [OrderGardenController::class , 'show_orders'])->name('show_orders_garden');
//     Route::get('/order/{id}' , [OrderGardenController::class , 'show'])->name('show_order_garden');
//     Route::get('/accept_project_garden/{id}' , function($id) {
//         GardenOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_garden');
//     Route::get('service/edit/{id}',[GardenController::class , 'edit_service'])->name('service_garden.edit');
//     Route::put('service/update/{id}',[GardenController::class , 'update_service'])->name('service_garden.update');
//     Route::delete('/van-truck-services/{id}', [GardenController::class, 'destroy_service'])->name('service_garden.destroy');
// });

// // Counter insects
// Route::group(['prefix' => "counter_insects"], function(){
//     Route::get('/show' , [CounterInsectsController::class , 'show'])->name('counter_insects_show');
//     Route::post('/add_service' , [CounterInsectsController::class , 'store_service'])->name('counter_insects_store_service');
//     Route::get('/',[CounterInsectsController::class , 'index'])->name('main_counter_insects');
//     Route::get('/service/{id}',[CounterInsectsController::class , 'show_my_service'])->name('main_counter_insects_show_my_service');
//     Route::get('/edit/{id}',[CounterInsectsController::class , 'edit'])->name('main_counter_insects.edit');
//     Route::patch('/update/{id}',[CounterInsectsController::class , 'update'])->name('main_counter_insects.update');
//     Route::post('/accept_offer' , [OrderCounterInsectsController::class , 'store'])->name('accept_offer_counter_insects');
//     Route::get('/order' , [OrderCounterInsectsController::class , 'show_orders'])->name('show_orders_counter_insects');
//     Route::get('/order/{id}' , [OrderCounterInsectsController::class , 'show'])->name('show_order_counter_insects');
//     Route::get('/accept_project_counter_insects/{id}' , function($id) {
//         CounterInsectsOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_counter_insects');
//     Route::get('service/edit/{id}',[CounterInsectsController::class , 'edit_service'])->name('service_counter_insects.edit');
//     Route::put('service/update/{id}',[CounterInsectsController::class , 'update_service'])->name('service_counter_insects.update');
//     Route::delete('/van-truck-services/{id}', [CounterInsectsController::class, 'destroy_service'])->name('service_counter_insects.destroy');
// });

// // Cleaning Service
// Route::group(['prefix' => "cleaning"], function(){
//     Route::get('/show' , [CleaningController::class , 'show'])->name('cleaning_show');
//     Route::post('/add_service' , [CleaningController::class , 'store_service'])->name('cleaning_store_service');
//     Route::get('/',[CleaningController::class , 'index'])->name('main_cleaning');
//     Route::get('/service/{id}',[CleaningController::class , 'show_my_service'])->name('main_cleaning_show_my_service');
//     Route::get('/edit/{id}',[CleaningController::class , 'edit'])->name('main_cleaning.edit');
//     Route::patch('/update/{id}',[CleaningController::class , 'update'])->name('main_cleaning.update');
//     Route::post('/accept_offer' , [OrderCleaningController::class , 'store'])->name('accept_offer_cleaning');
//     Route::get('/order' , [OrderCleaningController::class , 'show_orders'])->name('show_orders_cleaning');
//     Route::get('/order/{id}' , [OrderCleaningController::class , 'show'])->name('show_order_cleaning');
//     Route::get('/accept_project_cleaning/{id}' , function($id) {
//         CleaningOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_cleaning');
//     Route::get('service/edit/{id}',[CleaningController::class , 'edit_service'])->name('service_cleaning.edit');
//     Route::put('service/update/{id}',[CleaningController::class , 'update_service'])->name('service_cleaning.update');
//     Route::delete('/van-truck-services/{id}', [CleaningController::class, 'destroy_service'])->name('service_cleaning.destroy');
// });


// // Teacher
// Route::group(['prefix' => "teacher"], function(){
//     Route::get('/show' , [TeacherController::class , 'show'])->name('teacher_show');
//     Route::post('/add_service' , [TeacherController::class , 'store_service'])->name('teacher_store_service');
//     Route::get('/',[TeacherController::class , 'index'])->name('main_teacher');
//     Route::get('/service/{id}',[TeacherController::class , 'show_my_service'])->name('main_teacher_show_my_service');
//     Route::get('/edit/{id}',[TeacherController::class , 'edit'])->name('main_teacher.edit');
//     Route::patch('/update/{id}',[TeacherController::class , 'update'])->name('main_teacher.update');
//     Route::post('/accept_offer' , [OrderTeacherController::class , 'store'])->name('accept_offer_teacher');
//     Route::get('/order' , [OrderTeacherController::class , 'show_orders'])->name('show_orders_teacher');
//     Route::get('/order/{id}' , [OrderTeacherController::class , 'show'])->name('show_order_teacher');
//     Route::get('/accept_project_teacher/{id}' , function($id) {
//         TeacherOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_teacher');
//     Route::get('service/edit/{id}',[TeacherController::class , 'edit_service'])->name('service_teacher.edit');
//     Route::put('service/update/{id}',[TeacherController::class , 'update_service'])->name('service_teacher.update');
//     Route::delete('/van-truck-services/{id}', [TeacherController::class, 'destroy_service'])->name('service_teacher.destroy');
// });

// // Family
// Route::group(['prefix' => "family"], function(){
//     Route::get('/show' , [FamilyController::class , 'show'])->name('family_show');
//     Route::post('/add_service' , [FamilyController::class , 'store_service'])->name('family_store_service');
//     Route::get('/',[FamilyController::class , 'index'])->name('main_family');
//     Route::get('/service/{id}',[FamilyController::class , 'show_my_service'])->name('main_family_show_my_service');
//     Route::get('/edit/{id}',[FamilyController::class , 'edit'])->name('main_family.edit');
//     Route::patch('/update/{id}',[FamilyController::class , 'update'])->name('main_family.update');
//     Route::post('/accept_offer' , [OrderFamilyController::class , 'store'])->name('accept_offer_family');
//     Route::get('/order' , [OrderFamilyController::class , 'show_orders'])->name('show_orders_family');
//     Route::get('/order/{id}' , [OrderFamilyController::class , 'show'])->name('show_order_family');
//     Route::get('/accept_project_family/{id}' , function($id) {
//         FamilyOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_family');
//     Route::get('service/edit/{id}',[FamilyController::class , 'edit_service'])->name('service_family.edit');
//     Route::put('service/update/{id}',[FamilyController::class , 'update_service'])->name('service_family.update');
//     Route::delete('/van-truck-services/{id}', [FamilyController::class, 'destroy_service'])->name('service_family.destroy');
// });

// // Worker
// Route::group(['prefix' => "worker"], function(){
//     Route::get('/show' , [WorkerController::class , 'show'])->name('worker_show');
//     Route::post('/add_service' , [WorkerController::class , 'store_service'])->name('worker_store_service');
//     Route::get('/',[WorkerController::class , 'index'])->name('main_worker');
//     Route::get('/service/{id}',[WorkerController::class , 'show_my_service'])->name('main_worker_show_my_service');
//     Route::get('/edit/{id}',[WorkerController::class , 'edit'])->name('main_worker.edit');
//     Route::patch('/update/{id}',[WorkerController::class , 'update'])->name('main_worker.update');
//     Route::post('/accept_offer' , [OrderWorkerController::class , 'store'])->name('accept_offer_worker');
//     Route::get('/order' , [OrderWorkerController::class , 'show_orders'])->name('show_orders_worker');
//     Route::get('/order/{id}' , [OrderWorkerController::class , 'show'])->name('show_order_worker');
//     Route::get('/accept_project_worker/{id}' , function($id) {
//         WorkerOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_worker');
//     Route::get('service/edit/{id}',[WorkerController::class , 'edit_service'])->name('service_worker.edit');
//     Route::put('service/update/{id}',[WorkerController::class , 'update_service'])->name('service_worker.update');
//     Route::delete('/van-truck-services/{id}', [WorkerController::class, 'destroy_service'])->name('service_worker.destroy');
// });

// // Public Services
// Route::group(['prefix' => "public_ge"], function(){
//     Route::get('/show' , [PublicGeController::class , 'show'])->name('public_ge_show');
//     Route::post('/add_service' , [PublicGeController::class , 'store_service'])->name('public_ge_store_service');
//     Route::get('/',[PublicGeController::class , 'index'])->name('main_public_ge');
//     Route::get('/service/{id}',[PublicGeController::class , 'show_my_service'])->name('main_public_ge_show_my_service');
//     Route::get('/edit/{id}',[PublicGeController::class , 'edit'])->name('main_public_ge.edit');
//     Route::patch('/update/{id}',[PublicGeController::class , 'update'])->name('main_public_ge.update');
//     Route::post('/accept_offer' , [OrderPublicGeController::class , 'store'])->name('accept_offer_public_ge');
//     Route::get('/order' , [OrderPublicGeController::class , 'show_orders'])->name('show_orders_public_ge');
//     Route::get('/order/{id}' , [OrderPublicGeController::class , 'show'])->name('show_order_public_ge');
//     Route::get('/accept_project_public_ge/{id}' , function($id) {
//         PublicGeOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_public_ge');
//     Route::get('service/edit/{id}',[PublicGeController::class , 'edit_service'])->name('service_public_ge.edit');
//     Route::put('service/update/{id}',[PublicGeController::class , 'update_service'])->name('service_public_ge.update');
//     Route::delete('/van-truck-services/{id}', [PublicGeController::class, 'destroy_service'])->name('service_public_ge.destroy');
// });

// // Ads
// Route::group(['prefix' => "ads"], function(){
//     Route::get('/show' , [AdsController::class , 'show'])->name('ads_show');
//     Route::post('/add_service' , [AdsController::class , 'store_service'])->name('ads_store_service');
//     Route::get('/',[AdsController::class , 'index'])->name('main_ads');
//     Route::get('/service/{id}',[AdsController::class , 'show_my_service'])->name('main_ads_show_my_service');
//     Route::get('/edit/{id}',[AdsController::class , 'edit'])->name('main_ads.edit');
//     Route::patch('/update/{id}',[AdsController::class , 'update'])->name('main_ads.update');
//     Route::post('/accept_offer' , [OrderAdsController::class , 'store'])->name('accept_offer_ads');
//     Route::get('/order' , [OrderAdsController::class , 'show_orders'])->name('show_orders_ads');
//     Route::get('/order/{id}' , [OrderAdsController::class , 'show'])->name('show_order_ads');
//     Route::get('/accept_project_ads/{id}' , function($id) {
//         AdsOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_ads');
//     Route::get('service/edit/{id}',[AdsController::class , 'edit_service'])->name('service_ads.edit');
//     Route::put('service/update/{id}',[AdsController::class , 'update_service'])->name('service_ads.update');
//     Route::delete('/van-truck-services/{id}', [AdsController::class, 'destroy_service'])->name('service_ads.destroy');
// });

// // Water
// Route::group(['prefix' => "water"], function(){
//     Route::get('/show' , [WaterController::class , 'show'])->name('water_show');
//     Route::post('/add_service' , [WaterController::class , 'store_service'])->name('water_store_service');
//     Route::get('/',[WaterController::class , 'index'])->name('main_water');
//     Route::get('/service/{id}',[WaterController::class , 'show_my_service'])->name('main_water_show_my_service');
//     Route::get('/edit/{id}',[WaterController::class , 'edit'])->name('main_water.edit');
//     Route::patch('/update/{id}',[WaterController::class , 'update'])->name('main_water.update');
//     Route::post('/accept_offer' , [OrderWaterController::class , 'store'])->name('accept_offer_water');
//     Route::get('/order' , [OrderWaterController::class , 'show_orders'])->name('show_orders_water');
//     Route::get('/order/{id}' , [OrderWaterController::class , 'show'])->name('show_order_water');
//     Route::get('/accept_project_water/{id}' , function($id) {
//         WaterOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_water');
//     Route::get('service/edit/{id}',[WaterController::class , 'edit_service'])->name('service_water.edit');
//     Route::put('service/update/{id}',[WaterController::class , 'update_service'])->name('service_water.update');
//     Route::delete('/van-truck-services/{id}', [WaterController::class, 'destroy_service'])->name('service_water.destroy');
// });

// // Car Water
// Route::group(['prefix' => "car_water"], function(){
//     Route::get('/show' , [CarWaterController::class , 'show'])->name('car_water_show');
//     Route::post('/add_service' , [CarWaterController::class , 'store_service'])->name('car_water_store_service');
//     Route::get('/',[CarWaterController::class , 'index'])->name('main_car_water');
//     Route::get('/service/{id}',[CarWaterController::class , 'show_my_service'])->name('main_car_water_show_my_service');
//     Route::get('/edit/{id}',[CarWaterController::class , 'edit'])->name('main_car_water.edit');
//     Route::patch('/update/{id}',[CarWaterController::class , 'update'])->name('main_car_water.update');
//     Route::post('/accept_offer' , [OrderCarWaterController::class , 'store'])->name('accept_offer_car_water');
//     Route::get('/order' , [OrderCarWaterController::class , 'show_orders'])->name('show_orders_car_water');
//     Route::get('/order/{id}' , [OrderCarWaterController::class , 'show'])->name('show_order_car_water');
//     Route::get('/accept_project_car_water/{id}' , function($id) {
//         CarWaterOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_car_water');
//     Route::get('service/edit/{id}',[CarWaterController::class , 'edit_service'])->name('service_car_water.edit');
//     Route::put('service/update/{id}',[CarWaterController::class , 'update_service'])->name('service_car_water.update');
//     Route::delete('/van-truck-services/{id}', [CarWaterController::class, 'destroy_service'])->name('service_car_water.destroy');
// });


// // Big Car
// Route::group(['prefix' => "big_car"], function(){
//     Route::get('/show' , [BigCarController::class , 'show'])->name('big_car_show');
//     Route::post('/add_service' , [BigCarController::class , 'store_service'])->name('big_car_store_service');
//     Route::get('/',[BigCarController::class , 'index'])->name('main_big_car');
//     Route::get('/service/{id}',[BigCarController::class , 'show_my_service'])->name('main_big_car_show_my_service');
//     Route::get('/edit/{id}',[BigCarController::class , 'edit'])->name('main_big_car.edit');
//     Route::patch('/update/{id}',[BigCarController::class , 'update'])->name('main_big_car.update');
//     Route::post('/accept_offer' , [OrderBigCarController::class , 'store'])->name('accept_offer_big_car');
//     Route::get('/order' , [OrderBigCarController::class , 'show_orders'])->name('show_orders_big_car');
//     Route::get('/order/{id}' , [OrderBigCarController::class , 'show'])->name('show_order_big_car');
//     Route::get('/accept_project_big_car/{id}' , function($id) {
//         BigCarOrder::find($id)->update(['status' => "completed"]);
//         return redirect()->back();
//     })->name('accept_project_big_car');
//     Route::get('service/edit/{id}',[BigCarController::class , 'edit_service'])->name('service_big_car.edit');
//     Route::put('service/update/{id}',[BigCarController::class , 'update_service'])->name('service_big_car.update');
//     Route::delete('/van-truck-services/{id}', [BigCarController::class, 'destroy_service'])->name('service_big_car.destroy');
// });



// Contractings
Route::group(['prefix' => "contracting"], function () {

    Route::get('/contracting_sub_show/{id}', [ContractingController::class, 'contracting_sub_show'])->name('contracting_sub_show');
});

// Car Maintenance
Route::group(['prefix' => "maintenance"], function () {

    Route::get('/maintenance_sub_show/{id}', [MaintenanceController::class, 'maintenance_sub_show'])->name('maintenance_sub_show');
});

Route::group(['prefix' => "spare_part"], function () {

    Route::get('/spare_part_sub_show/{id}', [SparePartController::class, 'spare_part_sub_show'])->name('spare_part_sub_show');
});

// Vans
Route::group(['prefix' => "van_truck"], function () {

    Route::get('/van_truck_sub_show/{id}', [VanTruckController::class, 'van_truck_sub_show'])->name('van_truck_sub_show');
});
// heavy equip
Route::group(['prefix' => "heavy_equip"], function () {

    Route::get('/heavy_equip_sub_show/{id}', [HeavyEquipmentController::class, 'heavy_equip_sub_show'])->name('heavy_equip_sub_show');
});
//industry
Route::group(['prefix' => "plastic"], function () {
    Route::get('/products', [indsProductController::class, 'index'])->name('indsproducts.index');
    Route::get('/products/{id}', [indsProductController::class, 'show'])->name('indsproducts.show');
    Route::get('/Viewproducts', [indsProductController::class, 'viewProduct'])->name('indsproducts.products');


    Route::get('/accept_project_plastic/{id}', function ($id) {
        VanTruckOrder::find($id)->update(['status' => "completed"]);
        return redirect()->back();
    })->name('accept_project_plastic');
});
Route::middleware('auth')->group(function () {
    Route::get('/cart', [ProductCartController::class, 'index'])->name('pro_cart.index');
    Route::post('/cart/add/{productId}', [ProductCartController::class, 'add'])->name('pro_cart.add');
    Route::put('/cart/update/{id}', [ProductCartController::class, 'update'])->name('pro_cart.update');
    Route::delete('/cart/remove/{id}', [ProductCartController::class, 'remove'])->name('pro_cart.remove');
    Route::post('/cart/checkout', [ProductCartController::class, 'checkout'])->name('pro_cart.checkout');
});
Route::middleware('auth')->group(function () {
    // الطلبات للمستخدم
    Route::get('product/orders', [ProductOrderController::class, 'index'])->name('orders.index');
    Route::get('product/orders/{id}', [ProductOrderController::class, 'show'])->name('orders.show');

    // إدارة الطلبات للمشرف Admin



});
Route::prefix('orders/{orderId}/items')->middleware('auth')->group(function () {
    Route::get('/', [ProductOrderitemsController::class, 'index'])->name('pro_order_items.index');
    Route::get('/create', [ProductOrderitemsController::class, 'create'])->name('pro_order_items.create');
    Route::post('/', [ProductOrderitemsController::class, 'store'])->name('pro_order_items.store');
});

Route::prefix('order-items')->middleware('auth')->group(function () {
    Route::get('/{id}/edit', [ProductOrderitemsController::class, 'edit'])->name('pro_order_items.edit');
    Route::put('/{id}', [ProductOrderitemsController::class, 'update'])->name('pro_order_items.update');
    Route::delete('/{id}', [ProductOrderitemsController::class, 'destroy'])->name('pro_order_items.destroy');
});





// general_comments
Route::post('/comments/create', [GeneralCommentsController::class, 'store'])->name('general_comments.store');
Route::get('/comments/show/{id}', [GeneralCommentsController::class, 'index'])->name('general_comments.show');
Route::get('general_comments/{id}/edit', [GeneralCommentsController::class, 'edit'])->name('general_comments.edit');

Route::put('general_comments/{id}', [GeneralCommentsController::class, 'update'])->name('general_comments.update');
Route::delete('general_comments/{id}', [GeneralCommentsController::class, 'destroy'])->name('general_comments.destroy');





Route::post('orders', [GeneralOrderController::class, 'store'])->name('general_orders.store');

// Optionally, you can have a route to view a specific order
Route::get('orders/{order}', [GeneralOrderController::class, 'show'])->name('general_orders.show');
Route::delete('/orders/{id}', [GeneralOrderController::class, 'destroy'])->name('general_orders.destroy');
// List orders for a customer (optional)
Route::get('orders', [GeneralOrderController::class, 'index'])->name('general_orders.customer.index');
Route::get('pre/order', [GeneralOrderController::class, 'previous'])->name('pre_order.customer');
Route::get('/accept_project/{id}', function ($id) {
    GeneralOrder::find($id)->update(['status' => "completed"]);
    return redirect()->back();
})->name('accept_project');

// route::resource('services', ServiceController::class); // تم التعليق حتى لا يتعارض مع روت العرض اليدوي
route::get('show/myservices/{id}', [ServiceController::class, 'show_services'])->name('show_myservice')->where('id', '[0-9]+');
// route::get('sub_show/myservices/{id}', [ServiceController::class,'sub_show'])->name('service_sub_show');

// Countries Routes
route::get('countries/dashboard', [CountryController::class, 'dashboard'])->name('countries.dashboard');
route::get('countries', [CountryController::class, 'index'])->name('countries.index');
route::get('add/country', [CountryController::class, 'create'])->name('add_country');
route::post('country', [CountryController::class, 'store'])->name('store_country');
route::get('countries/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit');
route::put('countries/{id}', [CountryController::class, 'update'])->name('countries.update');
route::delete('countries/{id}', [CountryController::class, 'destroy'])->name('countries.destroy');

// Governorates Routes
route::get('governorates', [GovernementsController::class, 'index'])->name('governorates.index');
route::get('add/gover', [GovernementsController::class, 'create'])->name('add_gover');
route::post('gover', [GovernementsController::class, 'store'])->name('store_gover');
route::get('governorates/{id}/edit', [GovernementsController::class, 'edit'])->name('governorates.edit');
route::put('governorates/{id}', [GovernementsController::class, 'update'])->name('governorates.update');
route::delete('governorates/{id}', [GovernementsController::class, 'destroy'])->name('governorates.destroy');
route::get('get/governorates', [GovernementsController::class, 'getGovernorates'])->name('get.governorates');

Route::get('/products', [IndustryController::class, 'index'])->name('indsustry.index');
Route::get('/category', [IndustryController::class, 'show_cat'])->name('indsustry.cat');
Route::get('/subcat', [IndustryController::class, 'show_sub_cat'])->name('indsustry.subcat');
Route::get('/showproduct', [IndustryController::class, 'show_product'])->name('indsustry.pro');
Route::get('/get-countries', [LocationController::class, 'getCountriesWithGovernements']);

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle/{departmentId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});



use App\Http\Controllers\Admin\DepartmentController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('departments', DepartmentController::class);
});

// روت عرض الخدمة مع دعم المعاملات الإضافية
Route::get('/services/{id}', [App\Http\Controllers\ServiceController::class, 'showService'])
    ->name('services.show')
    ->where('id', '[0-9]+');

// روت عرض الخدمة مع قسم فرعي محدد (يدعم query parameters)
Route::get('/services/{id}/sub-department/{subDepartmentId}', [App\Http\Controllers\ServiceController::class, 'showService'])
    ->name('services.show.subdepartment')
    ->where(['id' => '[0-9]+', 'subDepartmentId' => '[0-9]+']);

// Registration multi-step
// Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
// Route::post('/register/resend-otp', [\App\Http\Controllers\Auth\RegisterController::class, 'resendOtp'])->name('register.resend_otp');

// Route::get('/otp', [\App\Http\Controllers\Auth\RegisterController::class, 'showOtpForm'])->name('otp.form');
// Route::post('/otp', [\App\Http\Controllers\Auth\RegisterController::class, 'verifyOtp'])->name('otp.verify');

// Route::get('/choose-role', [\App\Http\Controllers\Auth\RegisterController::class, 'showRoleForm'])->name('choose.role');
// Route::post('/choose-role', [\App\Http\Controllers\Auth\RegisterController::class, 'saveRole'])->name('choose.role.save');

Route::get('/all-service-requests', [App\Http\Controllers\OrderUserController::class, 'allServiceRequests'])->name('services.requests.all');
Route::get('/all-services', [\App\Http\Controllers\ServiceController::class, 'allServices'])->name('all_services');



Route::get('/services', [App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [App\Http\Controllers\ServiceController::class, 'create'])->name('services.create');
Route::post('/services', [App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
Route::get('/services/{id}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show')->where('id', '[0-9]+');
// Route::get('/services/{id}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show')->where('id', '[0-9]+');
Route::get('/services/{id}/edit', [App\Http\Controllers\ServiceController::class, 'edit'])->name('services.edit')->where('id', '[0-9]+');
Route::put('/services/{id}', [App\Http\Controllers\ServiceController::class, 'update'])->name('services.update')->where('id', '[0-9]+');
Route::delete('/services/{id}', [App\Http\Controllers\ServiceController::class, 'destroy'])->name('services.destroy')->where('id', '[0-9]+');
Route::get('show/myservices/{id}', [App\Http\Controllers\ServiceController::class, 'show_services'])->name('show_myservice')->where('id', '[0-9]+');

// Routes لتعديل وحذف طلبات المنتجات (ProductOrder)
Route::get('/product_orders/{order}/edit', [\App\Http\Controllers\ProductOrderController::class, 'edit'])->name('product_orders.edit');
Route::delete('/product_orders/{order}', [\App\Http\Controllers\ProductOrderController::class, 'destroy'])->name('product_orders.destroy');
Route::put('/product_orders/{order}', [\App\Http\Controllers\ProductOrderController::class, 'update'])->name('product_orders.update');
Route::get('/product_orders/{order}', [\App\Http\Controllers\ProductOrderController::class, 'show'])->name('product_orders.show');
