<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OfferStoreRequest;
use App\Http\Requests\Api\OfferUpdateRequest;
use App\Http\Requests\Api\OfferExternalUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Institution;
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
        $offers = $offers->sortByDesc("sort_flag");
        $offers = $offers->values()->all();

        #todo

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
        # The response would only contain the entered data. Create new complete object for response
        $id = $offer->id;
        $offer = new Offer;
        $offer = $offer->find($id);

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
        return response()->json($this->restructureJsonOutput($offer), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfferUpdateRequest  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(OfferUpdateRequest $request, int $id)
    {
        $offer = Offer::find($id);
        $validatedData = $request->validated();

        $offer->fill($validatedData);
        $offer->save();
        return response()->json($offer, 201);
    }

    /**
     * Update the specified resource by given external id in storage.
     *
     * @param  \App\Http\Requests\Api\OfferUpdateRequest  $request
     * @param  String $externalId
     * @return \Illuminate\Http\Response
     */
    public function updateByExternalId(OfferExternalUpdateRequest $request, Institution $institution, String $externalId)
    {
        $offer = Offer::where(["institution_id" => $institution->id, "externalId" => $externalId ])->firstOrFail();
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

    public function restructureJsonOutput( Offer $offer ) {
        $arr = $offer->toArray();
        $arr["language"] = $arr["language"]["identifier"];
        $arr["sort_flag"] = $arr["huboffer"]["sort_flag"];
        $arr["keywords"] = $arr["huboffer"]["keywords"];
        $arr["visible"] = $arr["huboffer"]["visible"];
        $arr["type"] = $arr["offertype"]["identifier"];
        $arr["meta"] = [];
        foreach ( $arr["metas"] as $meta ) {
            $arr["meta"][$meta["description"]] = $meta["pivot"]["value"];
        }
        $arr["competence_tech"] = 0;
        $arr["competence_digital"] = 0;
        $arr["competence_classic"] = 0;
        foreach ( $arr["competences"] as $competence ) {
            $arr["competence_".$competence["title"]] = 1;
        }
        $arr["relations"] = [];
        foreach ( $arr["original_relations"] as $relation ) {
            $arr["relations"][] = $relation["id"];
        }

        unset(
            $arr["competences"],
            $arr["metas"],
            $arr["huboffer"],
            $arr["offertype"],
            $arr["original_relations"],
            $arr["timestamps"]["id"],
            $arr["timestamps"]["id"],
            $arr["timestamps"]["created_at"],
            $arr["timestamps"]["updated_at"],
            $arr["institution"]["created_at"],
            $arr["institution"]["updated_at"],
            $arr["institution"]["json_url"]
        );

        return $arr;
    }
}
