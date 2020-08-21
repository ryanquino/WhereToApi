<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('getFeaturedRestaurant', 'RestaurantController@getFeaturedRestaurant');
Route::get('getMenuPerRestaurant/{id}', 'MenuController@getMenuPerRestaurant');
Route::get('getCategories', 'CategoryController@getCaregoryList');
Route::get('getMenu/{id}', 'RestaurantController@getRestaurantMenu');
Route::get('getMenuCategory/{id}', 'MenuController@getMenuCategory');
Route::get('getMenuPerTransaction/{id}', 'OrderController@getOrdersPerTransaction');
Route::get('getTransactionDetails/{id}', 'OrderController@getTransactionDetails');
Route::get('viewUserOrders/{id}', 'OrderController@viewUserOrders');
Route::get('getAllPlayerId', 'UserController@getAllRiderPlayerId');
Route::get('getBarangayList', 'BaranggayController@getBarangayList');
Route::get('getRiderComments/{id}', 'UserController@getRiderComments');
Route::get('getRiderRating/{id}', 'UserController@getRiderRating');
Route::get('getRiderDetails/{id}', 'UserController@getRiderDetails');
Route::get('getUserDeviceId/{id}', 'UserController@getUserDeviceId');
Route::get('getAllMenu', 'MenuController@getAllMenu');
Route::post('putOrder', 'OrderController@putOrder');
Route::post('goOffline/{id}', 'UserController@goOffline');
Route::post('register', 'UserController@register');
Route::post('assignRider', 'OrderController@assignRider');
Route::post('assignPlayerId', 'UserController@assignPlayerId');
Route::post('login', 'UserController@login');
Route::post('transactionComplete/{id}', 'OrderController@transactionComplete');
Route::post('transactionBuying/{id}', 'OrderController@transactionBuying');
Route::post('transactionDelivery/{id}', 'OrderController@transactionDelivery');
Route::post('updateOrder', 'OrderController@updateOrder');
Route::post('addRiderComment', 'UserController@commentRider');
Route::post('rateRider', 'UserController@rateRider');
Route::post('riderRemit', 'RemitController@riderRemit');
//admin side
Route::post('addRider', 'UserController@addRider');
Route::post('addRestaurant', 'RestaurantController@store');
Route::post('addMenu', 'MenuController@addMenu');
Route::post('submitVerification', 'VerificationController@submitVerification');
Route::post('verifyUser/{id}', 'VerificationController@verifyUser');
Route::post('suspendAccount/{id}', 'VerificationController@suspendAccount');
Route::get('isAccountSuspended/{id}', 'VerificationController@isAccountSuspended');
Route::post('approveRemittance/{id}', 'RemitController@approveRemittance');
Route::get('getUnverifiedList', 'VerificationController@getUnverifiedList');
Route::get('viewUserVerification/{id}', 'VerificationController@viewUserVerification');
Route::get('viewRiderRemittance/{id}', 'RemitController@viewRiderRemittance');


Route::get('profile', 'UserController@getAuthenticatedUser');
Route::get('logout', 'UserController@logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});