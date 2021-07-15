<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Api\BotConfigController;

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
    Route::resource('offer', 'Api\OfferController')->except(['index', 'show']);
    Route::resource('institution', 'Api\InstitutionController')->except(['index', 'show']);
    Route::get('subscription', 'Api\SubscriptionController@index'); # get all subscriptions
    Route::get('subscription/user/{user_id}', 'Api\UserController@offers'); # get all subscriptions of user
    Route::get('subscription/offer/{offer_id}', 'Api\OfferController@users'); # get all subscriptions of offer
    Route::get('subscription/{user_id}/{offer_id}', 'Api\OfferController@subscription'); # get a specific subscription
    Route::get('user/email', 'Api\UserController@showFromEmail'); # get user by email
    Route::resource('user', 'Api\UserController');

    Route::post('apikey/deactivatekey/{institution}', 'Api\ApiKeyController@deactivateApiKey'); #deactivate apikey for external offers
    Route::post('apikey/activatekey/{institution}', 'Api\ApiKeyController@activateApiKey'); #activate apikey for external offers
});

Route::put('apikey/generate/{institution}', 'Api\ApiKeyController@generateApiKey'); #generate apikey for external offers

// Additional routes that don't require authentication
Route::get('offer', 'Api\OfferController@index');
Route::get('offer/{offer}', 'Api\OfferController@show');
Route::get('list/offer/short', 'Api\OfferController@indexForTiles');
Route::get('offer/ext/{institution}/{offer}', 'Api\OfferController@showExternal');
Route::get('institution', 'Api\InstitutionController@index');
Route::get('institution/{institution}', 'Api\InstitutionController@show');

// Bot-Routes
Route::get('botconfig', BotConfigController::class); // deprecated, use bot/config
Route::get('bot/config', BotConfigController::class);
Route::get('bot/token', 'Api\BotConfigController@createToken');
Route::get('bot/cookie/set', 'Api\CookieController@setCookie');

// Filter-Tags
Route::get('filter/tags', 'Api\FilterController@getTags');

// Routes that require API Key Authentification
Route::put('offer/ext/{institution}/{offer}', 'Api\OfferController@updateExternal')->middleware('auth.apikey');

