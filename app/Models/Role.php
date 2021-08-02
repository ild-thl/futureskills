<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const IS_ADMIN = 1;
    public const IS_USER = 2;
    /**
     * Get the all users with roles.
     */
    public function users(){
        return $this->belongsToMany(User::class);
    }


    /**
     * Get the all permissions for role.
     */
    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }


    /**
     * Get the role by id.
     */
    public static function getById($id)
    {
        return self::where([
            'id'    => $id
        ])->first();
    }

    /**
     * Get the role by name.
     */
    public static function getByName($name)
    {
        return self::where([
            'role'    => $name
        ])->first();
    }
}
