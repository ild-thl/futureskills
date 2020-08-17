<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriptionStoreRequest;
use App\Http\Requests\Api\SubscriptionUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        return response()->json($subscriptions, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOffersForUser( $user_id )
    {
        $subscriptions = Subscription::where( 'user_id', $user_id )->get();
        return response()->json($subscriptions, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUsersForOffer( $offer_id )
    {
        $subscriptions = Subscription::where( 'offer_id', $offer_id )->get();
        return response()->json($subscriptions, 200);
    }

    /**
     * Display a specific subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function showFromIds( $user_id, $offer_id )
    {
        $subscriptions = Subscription::where( ['offer_id' => $offer_id, 'user_id' => $user_id ] )->get();
        return response()->json($subscriptions, 200);
    }

    /**
     * Display a specific subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function show( $subscription_id )
    {
        $subscription = Subscription::where( ['id' => $subscription_id] )->get();
        return response()->json($subscription, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\SubscriptionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store( SubscriptionStoreRequest $request )
    {

        $validatedData = $request->validated();

        $subscription = Subscription::create($validatedData);
        $subscription->save();

        return response()->json($subscription, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\SubscriptionUpdateRequest  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update( SubscriptionUpdateRequest $request, Subscription $subscription )
    {
        $validatedData = $request->validated();
        $subscription->fill($validatedData);
        $subscription->save();

        return response()->json($subscription, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response(null, 204);
    }

}
