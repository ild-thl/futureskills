<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriptionStoreRequest;
use App\Http\Requests\Api\SubscriptionUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\Offer;

class SubscriptionController extends Controller
{

    public function attachUser( $offer_id, $user_id ) {

        $offer = Offer::find( $offer_id );

        if ( is_object( $offer ) ) {

            $attachedIds = $offer->users()->whereIn('id', [$user_id])->pluck('id');
            $newIds = array_diff([$user_id], $attachedIds->all());

            if ( !empty($newIds) ) {
                $offer->users()->attach($newIds);
                $offer->save();
                return response()->json($$attachedIds, 201);
            } else {
                # User is already attached
                return response()->json(true, 200);
            }
        } else {
            # Offer not found
            return response()->json(false, 200);
        }

    }

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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\SubscriptionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionStoreRequest $request)
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
    public function update( SubscriptionUpdateRequest $request, $user_id )
    {
        $validatedData = $request->validated();
        dd($validatedData, $request, $user_id, $offer_id );

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
