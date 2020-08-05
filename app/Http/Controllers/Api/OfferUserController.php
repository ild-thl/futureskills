<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OfferUserStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Offer;

class OfferUserController extends Controller
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

}
