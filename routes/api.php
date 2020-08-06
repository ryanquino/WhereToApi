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
Route::get('getCategories', 'CategoryController@getCaregoryList');
Route::get('getMenu/{id}', 'RestaurantController@getRestaurantMenu');
Route::get('getMenuCategory/{id}', 'MenuController@getMenuCategory');
Route::get('getMenuPerTransaction/{id}', 'OrderController@getOrdersPerTransaction');
Route::get('getTransactionDetails/{id}', 'OrderController@getTransactionDetails');
Route::get('viewCurrentOrders/{id}', 'OrderController@viewCurrentOrders');
Route::get('getAllPlayerId', 'UserController@getAllRiderPlayerId');
Route::get('getBarangayList', 'BaranggayController@getBarangayList');
Route::get('getRiderComments/{id}', 'UserController@getRiderComments');
Route::get('getRiderRating/{id}', 'UserController@getRiderRating');
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

Route::get('profile', 'UserController@getAuthenticatedUser');
Route::get('logout', 'UserController@logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});