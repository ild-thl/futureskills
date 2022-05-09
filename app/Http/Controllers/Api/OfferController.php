<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AbstractOfferController;
use App\Http\Requests\Api\OfferStoreRequest;
use App\Http\Requests\Api\FilterRequest;
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
use App\Models\Textsearch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator ;
use Illuminate\Support\Facades\DB;



class OfferController extends AbstractOfferController
{

    /**
     * Display a paginated listing of the resource.
     *
     * @param int $offerCount
     * @return \Illuminate\Http\Response
     */
    public function paginatedOffers(int $offerCount, FilterRequest $request)
    {
        $request->validated();
        $pageWithOffers = $this->restructurePaginateResponse([$this,'restructureJsonOutput'],
        $this->buildFilterTextsearchQuery($offerCount, $request));
        return response()->json($pageWithOffers, 200);
    }

    /**
     * Display a reduced paginated listing for tiles.
     *
     * @param int $offerCount
     * @return \Illuminate\Http\Response
     */
    public function paginatedReducedOffers(int $offerCount, FilterRequest $request)
    {
        $request->validated();
        $pageWithOffers = $this->restructurePaginateResponse([$this,'getReducedOfferJson'],
        $this->buildFilterTextsearchQuery($offerCount, $request));
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
     * Display a mini listing for offers in management-site.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOfferMiniDataSet()
    {
        $offers = Offer::all();
        $offers = $offers->values()->all();
        $output = array();
        $sort = array();
        foreach ( $offers as $offer ) {
            $output[] = array(
                "id" => $offer->id,
                "title" => $offer->title,
                "image_path" => $offer->image_path,
                "visible" => isset( $offer->hubOffer) ? $offer->hubOffer->visible : 0,
                "sortflag" => isset( $offer->hubOffer) ? $offer->hubOffer->sort_flag : null,
            );
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
    public function latestForTiles()
    {
        $offers = DB::table('offers')->latest()->limit(20)->get();
        $output = array();
        foreach ( $offers as $offer ) {
            $offer = Offer::find($offer->id);
            $output[] = $this->getReducedOfferJson($offer);
        }
        shuffle($output);

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
        $offer = Offer::find($offer->id);
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
        return response()->json($this->restructureJsonOutput($offer, true), 200);
    }

    /**
     * Returns a Sublist of offer-short List (filter on keyword)
     *
     * @param String $type
     * @return \Illuminate\Http\Response
     */
    public function getOfferSubListWithKeyword( String $keyword ) {

        $keyword= preg_replace('/\s+/', '', $keyword);
        $offers = Offer::select('offers.*')
            ->leftJoin('huboffers','offers.id', '=', 'huboffers.offer_id')
            ->whereRaw("FIND_IN_SET(?, huboffers.keywords) > 0", $keyword)->get();
        $output = array();
        foreach ( $offers as $offer ) {
            $output[] = $this->getReducedOfferJson($offer);
        }
        return response()->json($output, 200);
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
    private function restructureJsonOutput( Offer $offer, Bool $showRelatedOffersDetail = false ) {

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
                if ( $showRelatedOffersDetail ) {
                    $ret["relatedOfferData"][] = array (
                        "id" => $relation["id"],
                        "title" => $relation["title"],
                        "image" => $relation["image_path"]
                    );
                }
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
     * Remodel the output of an offer for short data list
     *
     * @param  int $offerCount
     * @param  \App\Http\Requests\Api\FilterRequest  $request
     * @return  Offer $offerQuery
     */
    private function buildFilterTextsearchQuery(int $offerCount, FilterRequest $request){
        $offerQueryFilter = Offer::query();
        $offerQuery = Offer::query();

        $textsearchScores=[];
        $data = $request->except('_token');
        $searchString ="";

        if(array_key_exists("textsearch", $data) && $data["textsearch"] != null && !preg_match('/^[^a-zA-Z0-9]+$/', $data["textsearch"])){

            $substrings = explode(" ", strval($data["textsearch"]));

            foreach($substrings as $substr){
                if(preg_match('/^[^A-Za-z0-9]+$/',$substr)) {
                    $substr = preg_replace('/^[^A-Za-z0-9]+$/', "", $substr);
                }
                #replaces one or more special characters with "* "
                $substr = preg_replace('/[^A-Za-z0-9]+/', "* ", $substr);
                #removes spaces and special characters at the beginning of substring
                $substr = preg_replace('/^[^A-Za-z0-9]+/', "", $substr);
                #adds "* " at end of substring if not already there
                if(! preg_match('/[\*]$/',$substr))
                    $substr = $substr."* ";;
                $searchString .= $substr;
            }
            #replaces one or more "*" with "* "
            $searchString = preg_replace('/[\*\s]+/', "* ", $searchString);
            #remove single "*"
            $searchString = preg_replace('/^\*/', "", $searchString);

            $textsearchTitle = DB::table('offers')->selectRaw("id, MATCH(title) AGAINST (? IN BOOLEAN MODE) AS 'score'", [$searchString])
               ->whereRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE) > 0 ", $searchString)->get();
            $textsearchAuthor = DB::table('offers')->selectRaw("id, MATCH(author) AGAINST (? IN BOOLEAN MODE) AS 'score'", [$searchString])
               ->whereRaw("MATCH(author) AGAINST(? IN BOOLEAN MODE) > 0 ", $searchString)->get();
            $textsearchDescription = DB::table('offers')->selectRaw("id, MATCH(description) AGAINST (? IN BOOLEAN MODE) AS 'score'", [$searchString])
               ->whereRaw("MATCH(description) AGAINST(? IN BOOLEAN MODE) > 0 ", $searchString)->get();

             $textsearchParameters = [];
             $textsearchParameters[] = json_decode($textsearchTitle, true);
             $textsearchParameters[] = json_decode($textsearchAuthor, true);
             $textsearchParameters[] = json_decode($textsearchDescription, true);

             foreach($textsearchParameters as $key => $val){
                 foreach($textsearchParameters[$key] as $offerColumn ){
                    if(!array_key_exists($offerColumn['id'],$textsearchScores)){
                        $textsearchScores[$offerColumn['id']] = $offerColumn['score'];
                      }else{
                        $textsearchScores[$offerColumn['id']] += $offerColumn['score'];
                      }
                 }
             }
        }

        unset($data['page']);
        unset($data['textsearch']);

        #get filtervalues
        foreach($data as $key => $array){
                if(Schema::hasColumn('offers', $key)){
                        $offerQueryFilter = $offerQueryFilter->whereIn($key,$data[$key]);
                    }
                    elseif( method_exists(Offer::class, $key) ){
                    $offerQueryFilter = $offerQueryFilter->whereHas($key, function($q) use ($key , $data){
                        $q->whereIn($key.'.id', $data[$key]);
                            });
                    }
            }

        $offerQueryFilterIds = $offerQueryFilter->pluck("id");
        #sync filter with textsearch
        $filterIdsWithTextsearch=[];
        if(array_key_exists("textsearch", $request->except('_token'))){
            foreach($offerQueryFilterIds as $filter_ids => $id){
                if(array_key_exists($id, $textsearchScores)){
                    $filterIdsWithTextsearch[$id]= $textsearchScores[$id];
                }
            }
        }

        #sort by score
        arsort($filterIdsWithTextsearch);
        $sortedIdsString = implode(',', array_keys($filterIdsWithTextsearch));

        if(array_key_exists("textsearch", $request->except('_token'))){
            $offerQuery = Offer::whereIn('id',array_keys($filterIdsWithTextsearch))->orderByRaw("FIELD(id,$sortedIdsString)");
        }else{
            $offerQuery = Offer::whereIn('id',$offerQueryFilterIds);
            //Get sort_flag from huboffers table
            $sortFlags = HubOffer::query()->select('offer_id', 'sort_flag');
            $offerQuery->joinSub($sortFlags, 'huboffers', function($join) {
                $join->on('offers.id', '=', 'huboffers.offer_id');
            });
            $offerQuery = $offerQuery->orderBy('sort_flag', 'desc');
        }
        #store searchstring in database
        if($searchString!="")
        $this->storeTextsearch($searchString);
        #return paginated offers
        return $offerQuery->Paginate($offerCount);
    }

    /**
     * Store textsearch in database
     * @param  String $textsearch
     */

    private function storeTextsearch(String $searchString){

        $editedSearchString = preg_replace("/\*/", "", $searchString);
        $editedSearchString = preg_replace("/^\s/","",$editedSearchString);

        $textsearch = Textsearch::getByTextsearch($editedSearchString);
        if(! \is_object( $textsearch)){
            $inputs = ['textsearch' => $editedSearchString, 'count' => 1];
            Textsearch::create($inputs);
        }
        else{
            $textsearch = Textsearch::getByTextsearch($editedSearchString);
            $textsearch->count += 1;
            $textsearch->save();
        }
    }
}
