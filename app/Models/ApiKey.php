<?php

namespace App\Models;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Ejarnutowski\LaravelApiKey\Models\ApiKey as Api_Key;

class ApiKey extends Api_Key
{

     /**
     * Get the institution for the key.
     */
    public function institutions(){
        return $this->belongsToMany(Institution::class)
        ->withTimestamps();
    }
}
