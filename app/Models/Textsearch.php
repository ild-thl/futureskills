<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Textsearch extends Model
{
    use HasFactory;

    protected $fillable = ['textsearch','count'];

    /**
     * Get the Textsearch by textsearch.
     */
    public static function getByTextsearch($textsearch)
    {
        return self::where([
            'textsearch'    => $textsearch
        ])->first();
    }
}
