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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('offer', 'Api\OfferController');
    Route::resource('institution', 'Api\InstitutionController');
    Route::get('subscription', 'Api\SubscriptionController@index'); # get all subscriptions
    Route::get('subscription/user/{user_id}', 'Api\SubscriptionController@indexOffersForUser'); # get all subscriptions of user
    Route::get('subscription/offer/{offer_id}', 'Api\SubscriptionController@indexUsersForOffer'); # get all subscriptions of offer
    Route::get('subscription/{offer_id}/{user_id}', 'Api\SubscriptionController@showFromIds'); # get specific subscription
    Route::resource('subscription', 'Api\SubscriptionController');


});
