<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\BodytypeController;
use App\Http\Controllers\UsePurposeController;
use App\Http\Controllers\IntroBadgeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LikesListController;
use App\Http\Controllers\BlockListController;
use App\Http\Controllers\FootPrintController;
use App\Http\Controllers\RecommCustomerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\MatchingDataController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user', [UserController::class, 'login']);

Route::group(['middleware' => ['verifyJwt']], function () {
    // Your protected routes go here
});

// profile
Route::get('/nickname_validation', [CustomerController::class, 'nickNameValidation'])->name('api.nickname_validation');
Route::post('/register_action', [CustomerController::class, 'store'])->name('api.register_action');
Route::get('/introduce_validation', [CustomerController::class, 'introduceValidation'])->name('api.introduce_validation');
Route::get('/isIdentityVerifed', [CustomerController::class, 'isIdentityVerifed'])->name('api.isIndetityVerifed');
Route::post('/introduce_update', [CustomerController::class, 'introduceUpdate'])->name("api.introduce_update");
Route::post('/nickname_update', [CustomerController::class, 'nickNameUpdate'])->name("api.nickname_update");
Route::post('/residence_update', [CustomerController::class, 'residenceUpdate'])->name("api.residence_update");
Route::post('/height_update', [CustomerController::class, 'heightUpdate'])->name("api.height_update");
Route::post('/bodytype_update', [CustomerController::class, 'bodyTypeUpdate'])->name("api.bodytype_update");
Route::post('/bloodtype_update', [CustomerController::class, 'bloodTypeUpdate'])->name("api.bloodtype_update");
Route::post('/education_update', [CustomerController::class, 'educationUpdate'])->name("api.education_update");
Route::post('/annualincome_update', [CustomerController::class, 'annualIncomeUpdate'])->name("api.annualincome_update");
Route::post('/smoking_update', [CustomerController::class, 'smokingUpdate'])->name("api.smoking_update");
Route::post('/alcohol_update', [CustomerController::class, 'alcoholUpdate'])->name("api.alcohol_update");
Route::post('/holiday_update', [CustomerController::class, 'holidayUpdate'])->name("api.holiday_update");
Route::post('/introbadge_update', [CustomerController::class, 'introBadgeUpdate'])->name("api.introbadge_update");
Route::get('/introduce_validation', [CustomerController::class, 'introduceValidation'])->name('api.introduce_validation');
Route::get('/get_user', [CustomerController::class, 'getUserInfo'])->name("api.get_user");
Route::get('/get_user1', [CustomerController::class, 'getUserInfo1'])->name("api.get_user");
Route::get('/get_user_phone', [CustomerController::class, 'getUserPhoneInfo'])->name("api.get_user");
Route::get('/get_like_random_data/{UserId}', [CustomerController::class, 'getLikeData1'])->name("api.get_user");
Route::get('/get_other_random_data/{UserId}/{sub_id}', [CustomerController::class, 'getLikeData2'])->name("api.get_user");
Route::get('/get_user_list_random_data/{UserId}', [CustomerController::class, 'getLikeData4'])->name("api.get_user");
Route::get('/get_follow_random_data/{UserId}', [CustomerController::class, 'getLikeData3'])->name("api.get_user");
Route::post('/user_remove', [CustomerController::class, 'destroy'])->name("api.user_remove");
Route::get('/get_introdata/{uid}', [CustomerController::class, 'getIntroInfo'])->name("api.get_user");
Route::post('/upload_avatar', [CustomerController::class, 'uploadAvatar'])->name("api.user_remove");
Route::post('/upload_likes', [CustomerController::class, 'uploadLikesRate'])->name("api.user_remove");
Route::post('/phone_valid', [CustomerController::class, 'phoneValidation'])->name("api.user_remove");
Route::post('/use_purpose', [CustomerController::class, 'userPurpose'])->name("api.user_remove");

// settings
Route::post('/showUnverifiedAge', [CustomerController::class, 'showUnverifiedAge'])->name("api.showUnverifiedAge");
Route::post('/showUnmatchedPerson', [CustomerController::class, 'showUnmatchedPerson'])->name("api.showUnmatchedPerson");

// likes
Route::post('/likes_store', [LikesListController::class, 'store'])->name('api.likes_store');
Route::get('/get_likeslist', [LikesListController::class, 'getLikesList'])->name('api.get_likeslist');

// Block
Route::get('/get_blocklist', [BlockListController::class, 'getBlockList'])->name('api.get_blocklist');
Route::post('/block_user_store', [BlockListController::class, 'store'])->name('api.block_user_store');
Route::post('/block_user_remove', [BlockListController::class, 'destroy'])->name('api.block_user_remove');

// Recomm Customer
Route::get('/get_recomm_customer', [RecommCustomerController::class, 'getRecommCusomters'])->name('api.get_recomm_customer');

// message
Route::get('/get_message_list', [MessageController::class, 'getTitleList'])->name('api.get_message_list');

// Footprint
Route::post('/footprint_store', [FootPrintController::class, 'store'])->name('api.footprint_store');

// get residence
Route::get('/get_residence', [ResidenceController::class, 'getResidence'])->name('api.get_residence');

// get community
Route::get('/get_community', [CommunityController::class, 'getCommunity'])->name('api.get_community');

// get body type
Route::get('/get_bodytype', [BodytypeController::class, 'getBodytype'])->name('api.get_bodytype');

// get use purpose
Route::get('/get_usepurpose', [UsePurposeController::class, 'getUsepurpose'])->name('api.get_usepurpose');

// get intro badge
Route::get('/get_introbadge', [IntroBadgeController::class, 'getIntrobadge'])->name('api.get_introbadge');

// !-----> identify verify api

Route::post('/identify_show', [IdentityController::class, 'show']);
Route::post('/identify_verify', [IdentityController::class, 'uploadIdentifyImage'])->name('api.identify_verify');

// !-----> Board Data List
Route::get('/get_board_data/{uid}', [BoardController::class, 'getBoardData'])->name('api.get_board_list');

Route::post('/post_board_data', [BoardController::class, 'postBoardData'])->name('api.post_board_list');

Route::post('/active_board_data', [BoardController::class, 'activeBoardData'])->name('api.active_board_data');

Route::get('/get_res_board/{uid}', [BoardController::class, 'getResBoardData'])->name('api.post_board_list');

Route::get('/get_res_count', [BoardController::class, 'getResBoardData'])->name('api.post_board_list');

Route::get('/get_res_detail/{dataValue}', [BoardController::class, 'getResDetail'])->name('api.post_board_list');

Route::post('/add_matching_data', [BoardController::class, 'addMatchingData'])->name('api.post_board_list');

Route::get('/get_communication_data/{user_id}', [CommunityController::class, 'getCommunicationData'])->name('api.get_board_list');

Route::get('/get_people_data/{sub_id}/{user_id}', [CommunityController::class, 'getPeopleData'])->name('api.get_board_list');

Route::post('/add_like_data', [CommunityController::class, 'addLikeData'])->name('api.post_board_list');

Route::post('/add_like_data1', [CommunityController::class, 'addLikeData1'])->name('api.post_board_list');

Route::get('/get_like_data/{user_id}', [CommunityController::class, 'getLikeData'])->name('api.get_board_list');

Route::get('/get_matching_data/{user_id}', [CommunityController::class, 'getMatchingData'])->name('api.get_board_list');

Route::get('/get_preview_data/{user_id}', [CommunityController::class, 'getPreviewData'])->name('api.get_board_list');

Route::get('/get_brock_data/{user_id}', [CommunityController::class, 'getBrockData'])->name('api.get_board_list');

Route::get('/get_userlist_data/{user_id}', [CommunityController::class, 'getUserList'])->name('api.get_board_list');

Route::get('/get_chatting_group/{user_id}', [MatchingDataController::class, 'getChattingGroup'])->name('api.get_board_list');

Route::post('/update_like_data', [CommunityController::class, 'updateLikeData'])->name('api.post_board_list');

Route::post('/account_logout', [CustomerController::class, 'logout'])->name('api.post_board_list');

Route::post('/update_message_data', [CommunityController::class, 'updateMessageData'])->name('api.post_board_list');

Route::post('/add_user_like', [CommunityController::class, 'addUserLike'])->name('api.post_board_list');

Route::post('/add_user_today_like', [CommunityController::class, 'addUserTodayLike'])->name('api.post_board_list');

Route::post('/add_matching', [CommunityController::class, 'addMatching'])->name('api.post_board_list');

Route::post('/add_matching1', [CommunityController::class, 'addMatching1'])->name('api.post_board_list');

Route::post('/remove_matching', [CommunityController::class, 'removeMatching'])->name('api.post_board_list');

Route::post('/user_report', [CommunityController::class, 'userReport'])->name('api.post_board_list');

Route::post('/change_block', [CommunityController::class, 'changeBlock'])->name('api.post_board_list');

Route::post('/change_good_luck', [CommunityController::class, 'changeGoodLuck'])->name('api.post_board_list');

Route::post('/close_account', [CustomerController::class, 'closeAccount'])->name('api.post_board_list');

Route::post('/change_private', [CustomerController::class, 'changePrivate'])->name('api.post_board_list');

Route::post('/change_preview', [CustomerController::class, 'changePreview'])->name('api.post_board_list');

Route::post('/do_payment', [CustomerController::class, 'doPayment'])->name('api.post_board_list');

Route::post('/remove_board_data', [CustomerController::class, 'removeBoardData'])->name('api.post_board_list');

Route::post('/apple_login', [CustomerController::class, 'appleLogin'])->name('api.post_board_list');

Route::post('/get_phone_validation', [CustomerController::class, 'getPhoneValidation'])->name('api.post_board_list');

Route::post('/update_profile_ads', [CustomerController::class, 'updateProfileAdsData'])->name('api.post_board_list');

Route::post('/valid_phone_number', [CustomerController::class, 'validPhoneNumber'])->name('api.post_board_list');

Route::post('/pay_log', [CustomerController::class, 'payLog'])->name('api.post_board_list');

Route::post('/pay_coin', [CustomerController::class, 'payCoin'])->name('api.post_board_list');

Route::post('/review_data', [CustomerController::class, 'reviewSaveData'])->name('api.post_board_list');

Route::post('/join_community', [CommunityController::class, 'joinCommunity'])->name('api.post_board_list');

Route::get('/get_admin_notif/{user_id}', [MessageController::class, 'getAdminNotif'])->name('api.get_board_list');

Route::get('/get_favorite_data/{user_id}', [CommunityController::class, 'getFavoriteData'])->name('api.get_board_list');
