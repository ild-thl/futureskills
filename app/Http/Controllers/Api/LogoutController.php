<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class LogoutController extends Controller
{
    public function logout (Request $request) {

        $tokenParts = explode(".", $request->bearerToken());
        $jsonTokenPayload = json_decode(base64_decode($tokenParts[1]));
        $tokenid = $jsonTokenPayload->jti;

        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenid);
        $accessTokenRepository = app('Laravel\Passport\TokenRepository');
        $accessTokenRepository->revokeAccessToken($tokenid);

        Token::where('revoked',true)->delete();
        RefreshToken::where('revoked',true)->delete();
        Token::where('expires_at', '<=', Carbon::now())->delete();
        RefreshToken::where( 'expires_at', '<=', Carbon::now())->delete();

        $response = ['message' => 'You have been successfully logged out'];
        return response($response, 200);
    }

}
