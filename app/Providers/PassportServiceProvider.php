<?php
namespace App\Providers;

use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use App\Http\Laravel\Passport\Bridge as PassportBridge;
use Laravel\Passport\Bridge;
use League\OAuth2\Server\AuthorizationServer;

class PassportServiceProvider extends \Laravel\Passport\PassportServiceProvider
{
    /**
     * Make the authorization service instance.
     *
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            $this->app->make(Bridge\ClientRepository::class),
            $this->app->make(PassportBridge\AccessTokenRepository::class),
            $this->app->make(Bridge\ScopeRepository::class),
            $this->makeCryptKey('private'),
            app('encrypter')->getKey(),
            new BearerTokenResponse()
        );
    }
}
