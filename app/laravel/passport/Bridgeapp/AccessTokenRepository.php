<?php

namespace App\Laravel\Passport\Bridgeapp;

use DateTime;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use App\Laravel\Passport\Bridgeapp\AccessToken;
//use Laravel\Passport\Bridge\AccessToken;

class AccessTokenRepository extends \Laravel\Passport\Bridge\AccessTokenRepository implements AccessTokenRepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessToken($userIdentifier, $scopes, $clientEntity);
    }



}
