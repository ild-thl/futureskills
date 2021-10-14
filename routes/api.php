<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApiKeyController;
use App\Http\Controllers\Api\FilterController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('offer', OfferController::class )->except(['index', 'show']);
    Route::resource('institution', InstitutionController::class )->except(['index', 'show']);
    Route::get('subscription', [ SubscriptionController::class, 'index']); # get all subscriptions
    Route::get('subscription/user/{user_id}', [ UserController::class, 'offers']); # get all subscriptions of user
    Route::get('subscription/offer/{offer_id}', [ OfferController::class, 'users']); # get all subscriptions of offer
    Route::get('subscription/{user_id}/{offer_id}', [ OfferController::class, 'subscription']); # get a specific subscription
    Route::get('user/email', [ UserController::class, 'showFromEmail']); # get user by email
    Route::resource('user', UserController::class);
    Route::put('apikey/generate/{institution}', [ ApiKeyController::class, 'generateApiKey']); #generate apikey for external offers
    Route::post('apikey/deactivatekey/{institution}', [ ApiKeyController::class, 'deactivateApiKey']); #deactivate apikey for external offers
    Route::post('apikey/activatekey/{institution}', [ ApiKeyController::class, 'activateApiKey']); #activate apikey for external offers
});

//test routes for pagination
Route::post('offer/paginated/{offerCount}', [OfferController::class, 'paginatedOffers']);
Route::post('list/offer/short/paginated/{offerCount}', [ OfferController::class, 'paginatedReducedOffers']);

// Additional routes that don't require authentication
Route::get('offer', [ OfferController::class, 'index']);
Route::get('offer/{offer}', [ OfferController::class, 'show']);
Route::get('list/offer/short', [ OfferController::class, 'indexForTiles']);
Route::get('offer/ext/{institution}/{offer}', [ OfferController::class, 'showExternal']);
Route::get('institution', [ InstitutionController::class, 'index']);
Route::get('institution/{institution}', [ InstitutionController::class, 'show']);

// Filter-Tags
Route::get('filter/tags', [ FilterController::class, 'getTags']);

// Routes that require API Key Authentification
Route::put('offer/ext/{institution}/{offer}', [ OfferController::class, 'updateExternal'])->middleware('auth.apikey');

