<?php

namespace App\Http\Laravel\Passport\Bridge;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use App\Http\League\Entities\Traits\AccessTokenTrait;

use Laravel\Passport\Bridge\AccessToken as Access_Token;

class AccessToken extends Access_Token implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
}
