<?php
namespace App\Http\ResponseTypes;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use App\User;

class BearerTokenResponse extends \League\OAuth2\Server\ResponseTypes\BearerTokenResponse
{
    /**
     * Add custom fields to your Bearer Token response here, then override
     * AuthorizationServer::getResponseType() to pull in your version of
     * this class rather than the default.
     *
     * @param AccessTokenEntityInterface $accessToken
     *
     * @return array
     */
    protected function getExtraParams(AccessTokenEntityInterface $accessToken): array
    {
        return [
            'user_id' => $this->accessToken->getUserIdentifier(),
            'user_name' => User::find( $this->accessToken->getUserIdentifier())->value('name'),
        ];
    }
}
