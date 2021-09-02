<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Http\League\Entities\Traits;

use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use League\OAuth2\Server\CryptKey;
use App\Models\User;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait as Access_Token_Trait;


trait AccessTokenTrait
{

    use Access_Token_Trait;

    /**
     * Generate a JWT from the access token
     *
     * @param CryptKey $privateKey
     *
     * @return Token
     */
    private function convertToJWT() #CryptKey $privateKey
    {

        if ( User::select('name')->where( ['id'=> $this->getUserIdentifier()])->first()['name'] == 'admin' ) {
            $user_Role='admin';
        } else {
            $user_Role='default';
        }

        $this->initJwtConfiguration();

        return $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string) $this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->withClaim('user_id',$this->getUserIdentifier())
            ->withClaim('user_name', User::select('name')->where(['id'=> $this->getUserIdentifier()])->first()['name'])
            ->withClaim('user_role', $user_Role)

            ->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }

}
