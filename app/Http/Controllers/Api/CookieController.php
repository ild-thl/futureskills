<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieController extends Controller
{
    private $cookieName;
    public function __construct()
    {
        $this->cookieName = config('bot.uuid_cookie_name');
    }

    /**
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Incoming Request to set and update User-Cookie
     */
    public function setCookie(Request $request)
    {
        $minutes = (60*24*60);
        $currentCookie = $request->cookie($this->cookieName);

        if (empty($currentCookie)) {
            $uuid = Str::uuid()->toString();
        } else {
            $uuid = $currentCookie;
        }
        $cookie = cookie($this->cookieName, $uuid, $minutes);
        return response()->json(['ok'])->withCookie($cookie);
    }

    // Testing
    public function getCookie(Request $request)
    {
        echo $request->cookie($this->cookieName);
    }
}
