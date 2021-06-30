<?php

namespace App\Http\Laravel\Passport\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use App\Http\Laravel\Passport\Bridge\AccessToken;

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
