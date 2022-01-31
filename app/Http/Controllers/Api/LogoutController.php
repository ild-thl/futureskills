<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Passport\RefreshToken;

class LogoutController extends Controller
{
    public function logout (Request $request) {
        $userTokens = $request->user()->tokens;
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        foreach($userTokens as $token) {
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
            $token->delete();
        }

        $date  = Carbon::now();
        RefreshToken::where( 'expires_at', '<=', $date )->delete();
        RefreshToken::where('revoked',true)->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);

    }
}
