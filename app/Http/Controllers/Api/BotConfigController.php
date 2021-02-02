<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BotConfigController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $config ='';
        try {
            switch(env('APP_ENV')){
                case 'local':
                case 'staging':      
                    $config = \file_get_contents(config('bot.static_stage_url')); 
                    break;
                case 'production':
                    $config = \file_get_contents(config('bot.static_production_url')); 
                    break;
                default: 
                    $config = \json_encode(["maintenance" => false]);            
            }   
        } catch(\ErrorException $e) {
            return response()->json(["maintenance" => false]);
        }
        return response($config)->header('Content-Type', 'application/json');
    }
}
