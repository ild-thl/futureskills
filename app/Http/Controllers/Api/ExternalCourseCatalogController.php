<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Institution;
use App\Models\Offertype;
use App\Models\Language;
use App\Models\Competence;
use App\Models\Meta;
use App\Models\Huboffer;
use App\Models\Timestamp;
use App\Http\Requests\Api\OfferExternalUpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\ExternalCourseCatalogUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;

class ExternalCourseCatalogController extends Controller
{
    /**
     * Delete offer from database if it's not in the catalog
     *
     * @param  Array $catalogOffers
     * @param  Array $dbOffers
     * @param  String $externalIdColumnName
     */
    private function synchDBwithCatalog($catalogOffers, $dbOffers, $externalIdColumnName){
        $dbOfferIds = [];
        $catalogOfferIds = [];
        foreach($catalogOffers as $offer){
            $catalogOfferIds[] = $offer[$externalIdColumnName];
        }
        foreach ($dbOffers as $offer) {
            $dbOfferIds[] = $offer->getAttributes()['externalId'];
        }
        foreach($dbOfferIds as $offerId){
            if (! in_array( $offerId, $catalogOfferIds ) ) {
                if(! is_null($offerId)){
                    Offer::where('externalId', $offerId)->first()->delete();
                }
            }
        }
    }


    public function updateExternalCatalogs(ExternalCourseCatalogUpdateRequest $request, int $inst_id){
        $this->validateArray();
        if(!Institution::where('id', '=', $inst_id)->exists()){
            $response = ['message' => 'Institution does not exists'];
        }else{
            switch ($inst_id) {
                case 1:
                    $response = $this->importFutureskillsLMS();
                    break;
                case 2:
                    $response = $this->importMicrosoft();
                    break;
                case 3:
                    $response = $this->importOpenVhb();
                    break;
                case 6:
                    $response = $this->importOpenCampus();
                    break;
                default :
                    $response = ['message' => 'Institution_Id does not exists'];
                    break;
            }
        }

        return response($response, 200);
    }

    private function importFutureskillsLMS(){
        $jsonUrl = 'https://lms.futureskills-sh.de/courses.json';
        $institutionId = 1;
        #$jsonUrl = "C:/FSkills/fs_api/futureskills/app/Http/Controllers/Api/courses.json";
        $catalog = json_decode(file_get_contents($jsonUrl),true);
        $courses = $catalog['data'];
        $this->validateArray($courses);
        $offers = Offer::where('institution_id',$institutionId )->get();

        $this-> synchDBwithCatalog($courses,$offers,"id");

        foreach ($courses as $course) {
            $params = [
                'title' => addslashes($course['attributes']['name']),
                'description' => addslashes($course['attributes']['teasertext']),
                'image_path' => $course['attributes']['productImage'],
                'institution_id' => $institutionId,
                'url' => $course['attributes']['url'],
                'author' => $course['attributes']['publisher'],
                'language_id' => $course['attributes']['languages'] == "Deutsch" ? 1 : 2,
                'time_requirement' => $course['attributes']['processingtime'],
                'sort_flag' => 100,
                'externalId' => $course["id"],
                #'offertype_id' => "error",
                'offertype_id' => 5,
            ];
                $inst = Institution::getById($institutionId);
                $this->updateExternalCatalog($params,$inst,$params['externalId']);
        }
        return   ['message' => 'The Futureskills course catalog has been updated'];

    }


    private function importMicrosoft(){
        $jsonUrl = 'https://docs.microsoft.com/api/learn/catalog/?locale=de-de';
        $institutionId = 2;
        #$jsonUrl = 'C:/FSkills/fs_api/futureskills/app/Http/Controllers/Api/microsoftCourses.json';
        $catalog = json_decode(file_get_contents($jsonUrl),true);
        $courses = $catalog['modules'];
        $offers = Offer::where('institution_id',$institutionId )->get();

        $this-> synchDBwithCatalog($courses,$offers,"uid");

        foreach ($courses as $course) {
	        $params = [
                'title' => addslashes($course['title']),
                'description' => addslashes($course['summary']),
                'image_path' => $course['icon_url'],
                'url' => $course['url'],
                'institution_id' => $institutionId,
                'language_id' => $course['locale'] == 'de-de' ? 1 : 2,
                'time_requirement' => strval($course['duration_in_minutes']) . " Minuten",
                'externalId' => $course["uid"],
                'offertype_id' => 5,
                'niveau' => $course["levels"][0] === "beginner" ? "Anfänger" : ($course["levels"][0] === "intermediate" ? "Fortgeschrittene Anfänger" : "Fortgeschrittene"),
	        ];
            $inst = Institution::getById($institutionId);
            $this->updateExternalCatalog($params,$inst,$params['externalId']);
        }
        return  ['message' => 'The Microsoft course catalog has been updated'];

    }

    private function importOpenVhb(){

        #$jsonUrl = 'https://open.vhb.org/courses.json';
        $institutionId = 3;
        $jsonUrl = 'C:/FSkills/fs_api/futureskills/app/Http/Controllers/Api/openVHB.json';
        $catalog = json_decode(file_get_contents($jsonUrl),true);
        $courses = $catalog['data'];
        $offers = Offer::where('institution_id',$institutionId )->get();

        $this-> synchDBwithCatalog($courses,$offers,"id");

        foreach ($courses as $course) {
            $params = [
                'title' => addslashes($course['attributes']['name']),
                'description' => addslashes($course['attributes']['teasertext']),
                'image_path' => $course['attributes']['productImage'],
                'institution_id' => $institutionId,
                'url' => $course['attributes']['url'],
                'author' => $course['attributes']['publisher'],
                'language_id' => $course['attributes']['languages'] == "Deutsch" ? 1 : 2,
                'time_requirement' => $course['attributes']['processingtime'],
                //'sort_flag' => 100,
                'externalId' => $course["id"],
                'offertype_id' => 5,
                ];
                $inst = Institution::getById($institutionId);
                $this->updateExternalCatalog($params,$inst,$params['externalId']);
                #KEINE NEUEN KURSE HINZUFÜGEN
        }
        return ['message' => 'The OpenVHB course catalog has been updated'];

        }




    private function importOpenCampus(){
        $jsonUrl = 'https://edu.opencampus.sh/futureskills';
        $institutionId = 6;
        #$jsonUrl = 'C:/FSkills/fs_api/futureskills/app/Http/Controllers/Api/openCampusCourses.json';
        $catalog = json_decode(file_get_contents($jsonUrl),true);
        $courses = $catalog['courses'];
        $offers = Offer::where('institution_id',$institutionId )->get();

        $this-> synchDBwithCatalog($courses,$offers,"externalId");

        foreach ($courses as $course) {
            $params = [
                'title' => $course['title'],
                'description' => $course['description'],
                'image_path' => $course['image_path'],
                'institution_id' => $institutionId,
                'url' => $course['url'],
                'language_id' => $course['language'] == "de" ? 1 : 2,
                'author' => $course["author"],
                //'time_requirement' => $course['attributes']['processingtime'],
                'sort_flag' => 95,
                'externalId' => $course["externalId"],
                'executed_from' => $course["executed_from"],
                'executed_until' => $course["executed_until"],
                'listed_from' => $course["listed_from"],
                'listed_until' => $course["listed_until"],
                'type' => $course["type"]
            ];

            $inst = Institution::getById($institutionId);
            $this->updateExternalCatalog($params,$inst,$params['externalId']);

        }
        return ['message' => 'The OpenCampus course catalog has been updated'];

    }

    /**
     * Update external catalogs via json.
     *
     * @param  Array $data
     * @param  Institution $institution
     */
    private function updateExternalCatalog(Array $data, Institution $institution, String $externalId){

        $validatedData = $this->validateRedundantInput( $data );
        $offer = Offer::where(["institution_id" => $institution->id, "externalId" => $externalId ])->first();
        ## If the offer is not found, create. ID and Institution are set.
        if ( ! \is_object( $offer ) ) {
            $offer = Offer::create($validatedData);
        } else {
            ## Update offer
            $offer->fill($validatedData);
        }
        $offer->save();
        $this->saveRelatedData( $offer, $validatedData );
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
    #validation
    private function validateArray(){
        $input = [
            'user' => [
                'name' => 'Taylor Otwell',
                'username' => 'taylorotwell',
                'admin' => true,
            ],
        ];

        $validator = Validator::make($input, [
            'user' => 'array:username',
            'user' => 'array:id',
            'user' => 'array:name',
            'user' => 'array:admin',
        ]);

        $res = $validator->validated($input);
        print_r($res);
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
        $competences_sync = array();

        foreach ( $competences as $c ) {

            if ( \key_exists( "competence_".$c->identifier, $validatedData ) && $validatedData["competence_".$c->identifier]){
                    $competences_sync[] = $c->id;
                    $offer->competences()->sync($competences_sync, false);

            }elseif(\key_exists( "competence_".$c->identifier, $validatedData ) && !$validatedData["competence_".$c->identifier]){
                $offer->competences()->detach($c->id);
                $offer->competences()->sync($competences_sync, false);
            }
        }

        $metas = Meta::all();
        $meta_sync = array();

        foreach ( $metas as $m ) {
            if ( \key_exists( $m->description, $validatedData )  ) {

                if(empty ( $validatedData[$m->description] )){
                    $offer->metas()->detach($m->id);
                }else{
                    $meta_sync[ $m->id ] = [ "value" => $validatedData[$m->description] ];
                }
            }
        }
        $offer->metas()->sync($meta_sync,false);

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
}
