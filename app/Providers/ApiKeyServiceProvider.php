<?php
namespace App\Providers;


use App\Http\Middleware\AuthorizeApiKey;
use App\Models\ApiKey;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Ejarnutowski\LaravelApiKey\Providers\ApiKeyServiceProvider as ApiKeySP;


class ApiKeyServiceProvider extends ApiKeySP
{


    /**
     * Register middleware
     *
     * Support added for different Laravel versions
     *
     * @param Router $router
     */
    protected function registerMiddleware(Router $router)
    {
        $versionComparison = version_compare(app()->version(), '5.4.0');

        if ($versionComparison >= 0) {
            $router->aliasMiddleware('auth.apikey', AuthorizeApiKey::class);
        } else {
            $router->middleware('auth.apikey', AuthorizeApiKey::class);
        }
    }

}
