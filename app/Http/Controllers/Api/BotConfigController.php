<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

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
        $config = '';
        try {
            switch (env('APP_ENV')) {
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
        } catch (\ErrorException $e) {
            return response()->json(["maintenance" => false]);
        }
        return response($config)->header('Content-Type', 'application/json');
    }

    /**
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createToken(Request $request)
    {
        $currentCookieName = config('bot.uuid_cookie_name');
        $currentCookie = $request->cookie($currentCookieName);

        if (empty($currentCookie)) {
            abort(400, 'No Cookie found');
        } else {
            $token = $this->createJwtToken($currentCookie);
        }
        return response()->json(['token' => $token]);
    }

    private function createJwtToken($id)
    {
        $now   = new \DateTimeImmutable();
        $privateKeyURL = env('BOT_PRIVATE_KEY_URL');

        $environment = env('APP_URL');
        if ($privateKeyURL == NULL || !file_exists($privateKeyURL)) {
            abort(400, 'No Key Found');
        } else {
            $key_file = \file_get_contents($privateKeyURL);
            $payload = array(
                "iss" => $environment,
                "sub" => $id,
                "exp" => $now->modify('+20 minutes')->getTimestamp()
            );

            $jwt = JWT::encode($payload, $key_file, 'RS256');
            return $jwt;
        }
    }


    // Only for testing
    private function printToken($token)
    {

        echo "<pre>", "Encode:\n" . print_r($token, true), "</pre>";

        $decoded = $this->decodeJwtToken($token);
        $decoded_array = (array) $decoded;


        echo "<pre>", "\nDecode:\n" . print_r($decoded_array, true), "</pre>";
    }
    // Only for testing
    private function decodeJwtToken($token)
    {
        $publicKeyURL = env('BOT_PUBLIC_KEY_URL');
        if ($publicKeyURL == NULL || !file_exists($publicKeyURL)) {
            abort(400, 'No Key Found');
        } else {
            $key_file = \file_get_contents($publicKeyURL);
            $decoded = JWT::decode($token, $key_file, array('RS256'));
            return ($decoded);
        }
    }
}
