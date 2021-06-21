<?php

namespace App\Laravel\Passport\Bridgeapp;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use App\League\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

use Laravel\Passport\Bridge\AccessToken as Access_Token;

class AccessToken extends Access_Token implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
}
