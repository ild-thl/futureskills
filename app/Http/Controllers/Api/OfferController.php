<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OfferStoreRequest;
use App\Http\Requests\Api\OfferUpdateRequest;
use App\Http\Requests\Api\OfferExternalUpdateRequest;
use App\Models\Offer;
use App\Models\Institution;
use App\Models\Competence;
use App\Models\Meta;
use App\Models\Language;
use App\Models\Huboffer;
use App\Models\Offertype;
use App\Models\Timestamp;
use Illuminate\Pagination\LengthAwarePaginator ;
use Illuminate\Support\Facades\DB;



class OfferController extends Controller
{

    /**
     * Display a paginated listing of the resource.
     *
     * @param int $offerCount
     * @return \Illuminate\Http\Response
     */
    public function paginatedOffers(int $offerCount)
    {
        $paginatedOffers = Offer::orderBy('id')->Paginate($offerCount);
        $pageWithOffers = $this->restructurePaginateResponse([$this,'restructureJsonOutput'], $paginatedOffers);

        return response()->json($pageWithOffers, 200);
    }

    /**
     * Display a reduced paginated listing for tiles.
     *
     * @param int $offerCount
     * @return \Illuminate\Http\Response
     */
    public function paginatedReducedOffers(int $offerCount)
    {
        $paginatedOffers = Offer::orderBy('id')->Paginate($offerCount);
        $pageWithOffers = $this->restructurePaginateResponse([$this,'getReducedOfferJson'], $paginatedOffers);

        return response()->json($pageWithOffers, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::all();
        $offers = $offers->values()->all();
        $sort = array();
        $output = array();
        foreach ( $offers as $offer ) {
            $output[] = $this->restructureJsonOutput($offer);
            $sort[$offer->id] = null;
            if ( is_object( $offer->huboffer ) ) {
                $sort[$offer->id] = $offer->huboffer->sort_flag;
            }
        }
        array_multisort($sort, SORT_DESC, $output);

        return response()->json($output, 200);
    }

    /**
     * Display a reduced listing for tiles.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForTiles()
    {
        $offers = Offer::all();
        $offers = $offers->values()->all();
        $sort = array();
        $output = array();
        foreach ( $offers as $offer ) {
            $output[] = $this->getReducedOfferJson($offer);
            $sort[$offer->id] = null;
            if ( is_object( $offer->huboffer ) ) {
                $sort[$offer->id] = $offer->huboffer->sort_flag;
            }
        }
        array_multisort($sort, SORT_DESC, $output);

        return response()->json($output, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfferStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferStoreRequest $request)
    {
        $validatedData = $this->validateRedundantInput( $request->validated() );
        $offer = Offer::create($validatedData);
        $offer->save();
        $this->saveRelatedData( $offer, $validatedData );

        return response()->json($this->restructureJsonOutput($offer), 201);
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
     * @param  \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function update(OfferUpdateRequest $request, Offer $offer)
    {
        $validatedData = $this->validateRedundantInput( $request->validated() );

        $offer->fill($validatedData);
        $offer->save();
        $this->saveRelatedData( $offer, $validatedData );
        $offer = Offer::find($offer->id);

        return response()->json($this->restructureJsonOutput($offer), 201);
    }

    /**
     * Update the specified resource by given external id in storage.
     *
     * @param  \App\Http\Requests\Api\OfferUpdateRequest  $request
     * @param  Institution $institution
     * @param  String $externalId
     * @return \Illuminate\Http\Response
     */
    public function updateExternal(OfferExternalUpdateRequest $request, Institution $institution, String $externalId)
    {

        $validatedData = $this->validateRedundantInput( $request->validated() );

        $offer = Offer::where(["institution_id" => $institution->id, "externalId" => $externalId ])->first();

        # If the offer is not found, create. ID and Institution are set.
        if ( ! \is_object( $offer ) ) {
            $validatedData["externalId"] = $externalId;
            $validatedData["institution_id"] = $institution->id;
            $offer = Offer::create($validatedData);
        } else {
            # Update offer
            $offer->fill($validatedData);
        }

        $offer->save();
        $this->saveRelatedData( $offer, $validatedData );
        $offer = Offer::find($offer->id);

        return response()->json($this->restructureJsonOutput($offer), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Institution $institution
     * @param  String $externalId
     * @return \Illuminate\Http\Response
     */
    public function showExternal( Institution $institution, String $externalId )
    {
        $offer = Offer::where(["institution_id" => $institution->id, "externalId" => $externalId ])->first();
        if ( \is_object( $offer ) ) {
            return response()->json($this->restructureJsonOutput($offer), 200);
        }

        return response()->json(null, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfferUpdateRequest $request, Offer $offer)
    {
        $request->validated();
        $offer->delete();
        return response(null, 204);
    }

    /**
     * Display users subscribed to an offer.
     *
     * @param  String $offer_id
     * @return \Illuminate\Http\Response
     */
    public function users( String $offer_id ) {
        $offer = Offer::find($offer_id);
        return response()->json($offer->users()->get(), 200);
    }

    /**
     * Display a specific user/offer subscription.
     *
     * @param  String $offer_id
     * @return \Illuminate\Http\Response
     */
    public function subscription( String $user_id, String $offer_id ) {
        $subscription = DB::table('offer_user')->where(['offer_id' => $offer_id, 'user_id' => $user_id ])->get();
        return response()->json($subscription, 200);
    }

    /**
     * Remove "data" and "links" from page
     *
     * @param  \Illuminate\Pagination\LengthAwarePaginator $offer
     * @return Array $page
     */
     private function restructurePageMetadata( LengthAwarePaginator $offer ) {
        $page = $offer->toArray();
        unset(
            $page["data"],
            $page["links"],
        );
        return $page;
    }

    /**
     * Restructure paginated response
     *
     * @param  \Illuminate\Pagination\LengthAwarePaginator $paginated_offers
     * @return Array array_merge($data,$page)
     */
    private function restructurePaginateResponse($resturctureOffer,LengthAwarePaginator $paginatedOffers)
    {
        $transformedOffers = $paginatedOffers
        ->getCollection()
        ->transform(function($offer) use($resturctureOffer) {
            return $resturctureOffer($offer);
        });

        $data = array("data" => $transformedOffers );
        $page = $this->restructurePageMetadata($paginatedOffers);
        return array_merge($data,$page);
    }




    /**
     * Remodel the output of an offer
     *
     * @param  \App\Models\Offer $offer
     * @return Array $ret
     */
    private function restructureJsonOutput( Offer $offer ) {

        $ret = $offer->toArray();

        if ( array_key_exists( "language", $ret ) ) {
            $ret["language"] = $ret["language"]["identifier"];
        }

        if (  array_key_exists( "huboffer", $ret ) && is_array( $ret["huboffer"] ) ) {
            $ret["sort_flag"] = $ret["huboffer"]["sort_flag"];
            $ret["keywords"] = $ret["huboffer"]["keywords"];
            $ret["visible"] = $ret["huboffer"]["visible"];
        }

        if ( array_key_exists( "offertype", $ret ) ) {
            $ret["type"] = $ret["offertype"]["identifier"];
        }

        $ret["meta"] = [];

        if ( array_key_exists( "metas", $ret ) ) {
            foreach ( $ret["metas"] as $meta ) {
                $ret["meta"][$meta["description"]] = $meta["pivot"]["value"];
            }
        }

        $ret["competence_tech"] = 0;
        $ret["competence_digital"] = 0;
        $ret["competence_classic"] = 0;
        if ( array_key_exists( "competences", $ret ) ) {
            $tmp_competences = $ret["competences"];
            $ret["competences"] = array();

            foreach ( $tmp_competences as $competence ) {
                $ret["competence_".$competence["identifier"]] = 1;
                $ret["competences"][] = $competence["id"];
            }
        }

        $ret["relatedOffers"] = [];
        if ( array_key_exists( "original_relations", $ret ) ) {
            foreach ( $ret["original_relations"] as $relation ) {
                $ret["relatedOffers"][] = $relation["id"];
            }
        }

        unset(
            $ret["metas"],
            $ret["huboffer"],
            $ret["offertype"],
            $ret["original_relations"],
            $ret["timestamps"]["id"],
            $ret["timestamps"]["id"],
            $ret["timestamps"]["created_at"],
            $ret["timestamps"]["updated_at"],
            $ret["timestamps"]["offer_id"],
            $ret["institution"]["created_at"],
            $ret["institution"]["updated_at"],
            $ret["institution"]["json_url"]
        );

        return $ret;
    }

    /**
     * Remodel the output of an offer for short data list
     *
     * @param  \App\Models\Offer $offer
     * @return Array $ret
     */
    private function getReducedOfferJson( Offer $offer ) {

        $compeptences = array();
        foreach ( $offer->competences as $competence ) {
            $compeptences[] =$competence->id;
        }
        $ret = array(
            "id" => $offer->id,
            "title" => $offer->title,
            "image_path" => $offer->image_path,
            "institution_id" => $offer->institution_id,
            "offertype_id" => $offer->offertype_id,
            "language_id" => $offer->language_id,
            "competences" => $compeptences,
            "keywords" =>  isset( $offer->hubOffer) ? $offer->hubOffer->keywords : null
        );

        return $ret;
    }

    /**
     * Save an offer's data in other tables
     *
     * @param  \App\Models\Offer $offer
     * @param  Array $validatedData
     */
    private function saveRelatedData( Offer $offer, Array $validatedData ) {

        # Sync pivot tables
        $competences = Competence::all();
        $competence_sync = array();
        foreach ( $competences as $c ) {
            if ( \key_exists( "competence_".$c->identifier, $validatedData ) && $validatedData["competence_".$c->identifier] == true ) {
                $competence_sync[] = $c->id;
            }
        }
        $offer->competences()->sync($competence_sync);

        $metas = Meta::all();
        $meta_sync = array();
        foreach ( $metas as $m ) {
            if ( \key_exists( $m->description, $validatedData ) && !empty ( $validatedData[$m->description] ) ) {
                $meta_sync[ $m->id ] = [ "value" => $validatedData[$m->description] ];
            }
        }
        $offer->metas()->sync($meta_sync);

        # Fill other related tables
        $hubOffer = Huboffer::where([ "offer_id" => $offer->id ])->first();
        if ( is_object( $hubOffer ) ) {
            $hubOffer->fill($validatedData);
            $hubOffer->save();
        }  else {
            $validatedData["offer_id"] = $offer->id;
            $hubOffer = Huboffer::create($validatedData);
        }

        $timestamp = Timestamp::where([ "offer_id" => $offer->id ])->first();
        if ( is_object( $timestamp ) ) {
            $timestamp->fill($validatedData);
            $timestamp->save();
        }  else {
            $validatedData["offer_id"] = $offer->id;
            $timestamp = Timestamp::create($validatedData);
        }

        if ( array_key_exists( "relatedOffers", $validatedData ) ) {
            $relations = $validatedData["relatedOffers"];
            $relations_sync = array();
            foreach ( $relations as $relation ) {
                # empty array [ 0 => null ]
                if ( $relation === null && count( $relations ) == 1 ) {
                    $offer->originalRelations()->detach();
                } else {
                    $relations_sync[] = intval($relation);
                }
            }
            $offer->originalRelations()->sync($relations_sync);
        }
    }

    /**
     * Support for using API readable parameterd
     * 'type' instead of 'offertype_id'
     * 'language' instead of 'language_id'
     *
     * @param  Array $validatedData
     * @return  Array $validatedData
     */
    private function validateRedundantInput( Array $validatedData ) {

        if ( !key_exists ( "offertype_id", $validatedData ) && !empty ( $validatedData["type"] ) ) {
            $offertype = Offertype::where([ "identifier" => $validatedData["type"] ])->first();
            if ( is_object( $offertype ) ) {
                $validatedData["offertype_id"] = $offertype->id;
            }
        }

        if ( !key_exists ( "language_id", $validatedData ) && !empty ( $validatedData["language"] ) ) {
            $language = Language::where([ "identifier" => $validatedData["language"] ])->first();
            if ( is_object( $language ) ) {
                $validatedData["language_id"] = $language->id;
            }
        }
        return $validatedData;

    }

}
