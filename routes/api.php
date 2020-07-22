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
Route::post('putOrder', 'OrderController@putOrder');
Route::post('register', 'UserController@register');
Route::post('assignRider', 'OrderController@assignRider');

Route::post('login', 'UserController@login');
Route::get('profile', 'UserController@getAuthenticatedUser');
Route::get('logout', 'UserController@logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});