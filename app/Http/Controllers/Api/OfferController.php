<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OfferStoreRequest;
use App\Http\Requests\Api\OfferUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Offer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::all();
        return response()->json($offers, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfferStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferStoreRequest $request)
    {

        $validatedData = $request->validated();

        $offer = Offer::create($validatedData);
        $offer->save();

        return response()->json($offer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return response()->json($offer, 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfferUpdateRequest  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(OfferUpdateRequest $request, Offer $offer)
    {
        $validatedData = $request->validated();

        $offer->fill($validatedData);
        $offer->save();

        return response()->json($offer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();
        return response(null, 204);
    }
}