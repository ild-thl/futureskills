<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    /**
     * Get the all roles
     */
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the permission by id.
     */
    public static function getById($id)
    {
        return self::where([
            'id'    => $id
        ])->first();
    }

    /**
     * Get the permission by name.
     */
    public static function getByName($name)
    {
        return self::where([
            'name'    => $name
        ])->first();
    }

}
