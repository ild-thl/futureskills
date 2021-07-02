<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\Institution;

use App\Models\Language;


class ApiKeyController extends Controller
{

    /**
     * Generate apikey to create/update external offers
     *
     * @param  String $institutionId
     * @return \Illuminate\Http\Response
     */
    public function generateApiKey(String $institutionId)
    {

        $apiKey       = new ApiKey;

        //get name of institution
        $apiKey_name_institutionName = Institution::find($institutionId)["title"];
        $key_count = Institution::getById($institutionId)->api_keys->count()+1;

        //apikey name = Institution+number of api keys within institution
        $apiKey->name = $apiKey_name_institutionName.$key_count;

        //genearte apikey and attach to institution in DB
        $apiKey->key  = ApiKey::generate();
        $apiKey->save();
        $instituition = Institution::getById($institutionId);
        $apiKey->institutions()->attach($instituition);

        //return apikey
        return response()->json($apiKey, 201);
    }



}
