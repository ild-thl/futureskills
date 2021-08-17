<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use App\Models\Institution;
use Ejarnutowski\LaravelApiKey\Models\ApiKeyAccessEvent;
use Illuminate\Http\Request;
use Ejarnutowski\LaravelApiKey\Http\Middleware\AuthorizeApiKey as AuthApiKey;
use Error;

class AuthorizeApiKey extends AuthApiKey
{


    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $request_apikey = $request->get('key');
        $request_institution_id = $request->route()->parameters['institution']['id'];

        $apiKey = ApiKey::getByKey($request_apikey);

        #check if apikey exists and if it belongs to institution
        if ($apiKey instanceof ApiKey) {
          if($apiKey->institutions->contains('id',$request_institution_id )){
            $this->logAccessEvent($request, $apiKey);
            return $next($request);
          }
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);
    }


}
