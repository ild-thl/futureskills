<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'id', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The relationships that will be loaded with the model.
     *
     * @var array
     */
    protected $with = [
        'roles'
    ];

    /**
     * Get the offers for the user. (pivot)
     */
    public function offers()
    {
        return $this->belongsToMany('App\Models\Offer')->withPivot('active');
    }

    /**
     * Get the user by id.
     */
    public static function getById($id)
    {
        return self::where([
            'id'    => $id
        ]);
    }

     /**
     * Get the roles for the user. (pivot)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the User by name.
     */
    public static function getByName($name)
    {
        return self::where([
            'name'    => $name
        ])->first();
    }

}
