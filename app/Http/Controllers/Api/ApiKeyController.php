<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\Language;
use Facade\FlareClient\Http\Response;
use App\Http\Requests\Api\ApiKeyStoreRequest;
use App\Http\Requests\Api\ApiKeyUpdateRequest;

class ApiKeyController extends Controller
{

    /**
     * Generate apikey to create/update external offers
     *
     * @param  String $institutionId
     * @return \Illuminate\Http\Response
     */
    public function generateApiKey(ApiKeyStoreRequest $request, String $institutionId)
    {

        $request->validated();

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

    /**
     * Deactivate apikey
     *
     * @param  String $institutionId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deactivateApikey(ApiKeyUpdateRequest $request, String $institutionId){

        $request->validated();

        $request_apikey = $request->get('key');
        $key = ApiKey::where('key', $request_apikey)->first();

             #check if apikey exists and if it belongs to institution
             if ($key instanceof ApiKey) {
                if($key->institutions->contains('id',$institutionId)){
                   if($key->institutions()->count()==1){
                        if (!$key->active) {
                            return response()->json($key->key." is already deactivated", 200);
                            }
                            $key->active = 0;
                            $key->save();
                            return response()->json($key->key." deactivated", 200);
                        }
                        return $this->return_error_message('Not authorized to deactivate apikey');
                   }
              }
              return $this->return_error_message('Apikey does not exists');
    }

    /**
     * Activate apikey
     *
     * @param  String $institutionId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activateApikey(String $institutionId, ApiKeyUpdateRequest $request){

        $request->validated();

        $request_apikey = $request->get('key');
        $key = ApiKey::where('key', $request_apikey)->first();

         #check if apikey exists and if it belongs to institution
         if ($key instanceof ApiKey) {
            if($key->institutions->contains('id',$institutionId)){
               if($key->institutions()->count()==1){
                    if ($key->active) {
                        return response()->json($key->key." is already active", 200);
                        }
                        $key->active = 1;
                        $key->save();
                        return response()->json($key->key." activated", 200);
                    }
                    return $this-> return_error_message('Not authorized to activate apikey');
               }
          }
         return $this->return_error_message('Apikey does not exists');
    }


    /**
     * Return error when not authorized
     *
     * @param  String $message
     * @return \Illuminate\Http\Response
     */
    private function return_error_message(String $message){
        return response([
            'errors' => [[
                'message' => $message
            ]]
        ], 401);
    }

}
