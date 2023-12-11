<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\BodytypeController;
use App\Http\Controllers\UsePurposeController;
use App\Http\Controllers\IntroBadgeController;
use App\Http\Controllers\PaidPlanTypeController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MatchingDataController;
use App\Http\Controllers\ViolationReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\BoardController;

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
    return view('login');
});

//Auth
Route::resource('login', UserController::class);
// Route::post('/login_action', UserController::class, 'loginAction')->name('android.membership_app.register_action');
Route::post('/login_action', [UserController::class, 'loginAction'])->name('android.membership_app.register_action');

// residence
Route::resource('residence', ResidenceController::class);
Route::get('/residence', [ResidenceController::class,'index'])->name('residence');
Route::post('/residence_store', [ResidenceController::class, 'store']);
Route::post('/residence_update', [ResidenceController::class, 'update']);
Route::post('/residence_remove', [ResidenceController::class, 'destroy']);

// bodytype
Route::resource('bodytype', BodytypeController::class);
Route::get('/bodytype', [BodytypeController::class, 'index'])->name('bodytype');
Route::post('/bodytype_store', [BodytypeController::class, 'store']);
Route::post('/bodytype_update', [BodytypeController::class, 'update']);
Route::post('/bodytype_remove', [BodytypeController::class, 'destroy']);

// use purpose
Route::resource('usepurpose', UsePurposeController::class);
Route::get('/usepurpose', [UsePurposeController::class, 'index'])->name('usepurpose');
Route::post('/usepurpose_store', [UsePurposeController::class, 'store']);
Route::post('/usepurpose_update', [UsePurposeController::class, 'update']);
Route::post('/usepurpose_remove', [UsePurposeController::class, 'destroy']);

// intro badge
Route::resource('introbadge', IntroBadgeController::class);
Route::get('/introbadge', [IntroBadgeController::class, 'index'])->name('introbadge');
Route::post('/introbadge_store', [IntroBadgeController::class, 'store']);
Route::post('/introbadge_update', [IntroBadgeController::class, 'update']);
Route::post('/introbadge_remove', [IntroBadgeController::class, 'destroy']);

// paid plan type
Route::resource('paidplantype', PaidPlanTypeController::class);
Route::get('/paidplantype', [PaidPlanTypeController::class, 'index'])->name('paidplantype');
Route::post('/paidplantype_store', [PaidPlanTypeController::class, 'store']);
Route::post('/paidplantype_update', [PaidPlanTypeController::class, 'update']);
Route::post('/paidplantype_remove', [PaidPlanTypeController::class, 'destroy']);

// community
Route::resource('community', CommunityController::class);
Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::post('/community_store', [CommunityController::class, 'store']);
Route::post('/community_remove', [CommunityController::class, 'destroy']);

// customer
Route::resource('customer', CustomerController::class);
Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
Route::post('/customer_show', [CustomerController::class, 'show']);
Route::post('/register_action', [CustomerController::class, 'customerStore']);
Route::post('/remove_customer', [CustomerController::class, 'removeCustomer']);
Route::get('/admin', [CustomerController::class, 'admin_manager']);
Route::post('/get_admin_info', [CustomerController::class, 'get_admin_info']);
Route::post('/admin_save_data', [CustomerController::class, 'admin_save_data']);
Route::post('/remove_admin_data', [CustomerController::class, 'remove_admin_data']);


// matching
Route::resource('matching', MatchingDataController::class);
Route::get('/matching', [MatchingDataController::class, 'index'])->name('matching');
Route::post('/matching_show', [MatchingDataController::class, 'show']);
Route::post('/matching_remove', [MatchingDataController::class, 'destroy']);

// violation report
Route::resource('violation', ViolationReportController::class);
Route::get('/violation', [ViolationReportController::class, 'index'])->name('violation');
Route::post('/violation_show', [ViolationReportController::class, 'show']);
Route::post('/violation_remove', [ViolationReportController::class, 'destroy']);

// community category
Route::resource('category', CategoryController::class);
Route::get('/category', [CategoryController::class, 'index'])->name('category');
Route::post('/category_store', [CategoryController::class, 'store']);
Route::post('/category_update', [CategoryController::class, 'update']);
Route::post('/category_remove', [CategoryController::class, 'destroy']);

// identify
Route::resource('identify', IdentityController::class);
Route::get('/identify', [IdentityController::class, 'index'])->name('identify');
Route::post('/identify_show', [IdentityController::class, 'show']);
Route::post('/identify_update', [IdentityController::class, 'update']);

// message
Route::resource('message', MessageController::class);
Route::get('/message', [MessageController::class, 'index'])->name('message');
Route::post('/message_store', [MessageController::class, 'store']);
Route::get('/message_show', [MessageController::class, 'show']);
Route::post('/message_update', [MessageController::class, 'update']);
Route::post('/message_remove', [MessageController::class, 'destroy']);

// Route::get('/dashboard', function () {
//     return view('admin/content/dashboard');
// })->name('dashboard');
Route::get('/dashboard', [DashBoardController::class, 'getData'])->name('dashboard');

Route::get('/todayrecomm', function () {
    return view('admin/content/todayrecomm');
})->name('todayrecomm');

Route::get('/actboard', [BoardController::class, 'getData'])->name('actboard');
Route::post('/remove_actboard', [BoardController::class, 'removeActboard'])->name('actboard');

Route::get('/resboard', [BoardController::class, 'getResData'])->name('resboard');
Route::post('/remove_resboard', [BoardController::class, 'removeResboard'])->name('actboard');

Route::get('/user', function () {
    return view('admin/content/user');
})->name('user');

Route::get('delete_user/{id}', [UserController::class, 'destroy']);

Route::get('/logout', function () {
    session()->forget('userInfo');
    return view('login');
});
