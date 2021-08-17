<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Http\League\Entities\Traits;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use League\OAuth2\Server\CryptKey;
use App\User;
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
    private function convertToJWT(CryptKey $privateKey)
    {


        if(User::find( $this->getUserIdentifier())->value('name')=='admin'){
            $User_Role='admin';
        }else{
            $User_Role='default';
        }




        return (new Builder())
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(\time())
            ->canOnlyBeUsedAfter(\time())
            ->expiresAt($this->getExpiryDateTime()->getTimestamp())
            ->relatedTo((string) $this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->withClaim('user_id',$this->getUserIdentifier())
            ->withClaim('user_name', User::find( $this->getUserIdentifier())->value('name'))
            ->withClaim('user_role', $User_Role)

            ->getToken(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()));
    }

}
