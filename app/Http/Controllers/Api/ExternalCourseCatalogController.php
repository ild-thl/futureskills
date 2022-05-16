<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractOfferController;
use App\Models\Offer;
use App\Models\Institution;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\ExternalCourseCatalogUpdateRequest;
use Illuminate\Support\Facades\Log;

class ExternalCourseCatalogController extends AbstractOfferController
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
                #if external id is null offer wont be deleted (internal futureskills offers)
                if(! is_null($offerId)){
                    Offer::where('externalId', $offerId)->first()->delete();
                }
            }
        }
    }

    /**
     * Update the specified external catalog by passing institution id.
     *
     * @param  \App\Http\Requests\Api\ExternalCourseCatalogUpdateRequest  $request
     * @param  Integer $inst_id
     * @return \Illuminate\Http\Response
     */
    public function updateExternalCatalogs(ExternalCourseCatalogUpdateRequest $request, int $inst_id){

        $updateViaRequest = true;
        if(!Institution::where('id', '=', $inst_id)->exists()){
            $response = response(['message' => 'Institution does not exists'], 404);
        }else{
            switch ($inst_id) {
                case 1:
                    $response = $this->updateFutureskillsLMS($updateViaRequest);
                    break;
                case 2:
                    $response = $this->updateMicrosoft($updateViaRequest);
                    break;
                case 3:
                    $response = $this->updateOpenVhb($updateViaRequest);
                    break;
                case 6:
                    $response = $this->updateOpenCampus($updateViaRequest);
                    break;
                default :
                    $response = response(['message' => 'Institution does not exists'], 404);
                    break;
            }
        }
        return $response;
    }

    /**
     * Update external Futurskills catalog.
     *
     * @return Array
     */
     function updateFutureskillsLMS($updateViaRequest=false){
        $jsonUrl = 'https://lms.futureskills-sh.de/courses.json';
        $institutionId = 1;
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
                'sort_flag' => 100,
                'externalId' => $course["id"],
                'offertype_id' => 5,
            ];
                if($this->validateArray($params)){
                    if($updateViaRequest){
                        return   response(['message' => 'invalid offer parameters'], 422);
                    }else{
                        Log::error('futureskills update failed : invalid offer parameters');
                    }

                }else{
                    $inst = Institution::getById($institutionId);
                    $this->updateExternalCatalog($params,$inst,$params['externalId']);
                }
        }
        if($updateViaRequest){
            return   response(['message' => 'The Futureskills course catalog has been updated'], 200);
        }


    }


    /**
     * Update Microsoft catalog.
     *
     * @return Array
     */
     function updateMicrosoft($updateViaRequest=false){
        $jsonUrl = 'https://docs.microsoft.com/api/learn/catalog/?locale=de-de';
        $institutionId = 2;
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
            if($this->validateArray($params)){
                if($updateViaRequest){
                    return   response(['message' => 'invalid offer parameters'], 422);
                }else{
                    Log::error('microsoft update failed : invalid offer parameters');
                }

            }else{
                $inst = Institution::getById($institutionId);
                $this->updateExternalCatalog($params,$inst,$params['externalId']);
            }
        }
        if($updateViaRequest){
            return  response(['message' => 'The Microsoft course catalog has been updated'], 200);
        }


    }

    /**
     * Update OpenVhb catalog.
     *
     * @return Array
     */
     function updateOpenVhb($updateViaRequest=false){

        $institutionId = 3;
        $jsonUrl = 'https://open.vhb.org/courses.json';
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
                'offertype_id' => "5",
                ];
                if($this->validateArray($params)){
                    if($updateViaRequest){
                        return   response(['message' => 'invalid offer parameters'], 422);
                    }else{
                        Log::error('openvhb : invalid offer parameters');
                    }

                }else{
                    $inst = Institution::getById($institutionId);
                    $this->updateExternalCatalog($params,$inst,$params['externalId']);
                }
                #KEINE NEUEN KURSE HINZUFÜGEN
        }
        if($updateViaRequest){
            return  response(['message' => 'The OpenVHB course catalog has been updated'], 200) ;
        }
        }



    /**
     * Update OpenCampus catalog.
     *
     * @return Array
     */
     function updateOpenCampus($updateViaRequest=false){
        $jsonUrl = 'https://edu.opencampus.sh/futureskills';
        $institutionId = 6;
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

            if($this->validateArray($params)){
                if($updateViaRequest){
                    return   response(['message' => 'invalid offer parameters'], 422);
                }else{
                    Log::error('opencampus update failed : invalid offer parameters');
                }

            }else{
                $inst = Institution::getById($institutionId);
                $this->updateExternalCatalog($params,$inst,$params['externalId']);
            }

        }
        if($updateViaRequest){
            return response(['message' => 'The OpenCampus course catalog has been updated'], 200);
        }
    }

    /**
     * Insert/Update Offer to Database
     *
     * @param  Array $data
     * @param  Institution $institution
     * @param  String $externalId
     */
    private function updateExternalCatalog(Array $data, Institution $institution, String $externalId){

        $validatedData = $this->validateRedundantInput( $data );
        $offer = Offer::where(["institution_id" => $institution->id, "externalId" => $externalId ])->first();

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
     * Validate catalog
     *
     * @param  Array $offer
     * @param  Institution $institution
     * @param  String $externalId
     * @return Boolean
     */
    private function validateArray($offer){
        $rules = [
            'title' => 'max:255',
            'offertype_id' => 'integer',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'language_id' => 'integer',
            'hashtag' => 'nullable|string',
            'author' => 'nullable|string',
            'target_group' => 'nullable|string',
            'url' => 'nullable|url',

            #COMPETENCES table
            'competence_tech' => 'nullable|boolean',
            'competence_digital' => 'nullable|boolean',
            'competence_classic' => 'nullable|boolean',

            #METAS table
            'ects' => 'nullable|integer',
            'time_requirement' => 'nullable|string',
            'sponsor' => 'nullable|string',
            'exam' => 'nullable|string',
            'requirements' => 'nullable|string',
            'niveau' => 'nullable|string',
            'location' => 'nullable|string',

            #TIMESTAMPS table
            'executed_from' => 'date',
            'executed_until' => 'nullable|date',
            'listed_from' => 'date',
            'listed_until' => 'nullable|date',
            'active' => 'nullable|boolean',

            #Backwards compatibility
            'type' => 'string',
            'language' => 'string',
        ];


        $validator = Validator::make(
            $offer, $rules
        );

        if ($validator->fails()) {
            return true;
        }
        else{
            return false;
        }
    }



}
