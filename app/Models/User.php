<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'phone', 'password',
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
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['full_name', 'blank_password', 'role', 'places_count'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['socialProviders'];


    /**
     * -------------------------------
     * JWT Auth
     * -------------------------------
     */

    /**
     * Get the identifier that will be stored in the subject claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /*
     * Full name attribute
     */
    public function getFullNameAttribute()
    {

        return $this->name . ' ' . $this->last_name;
    }

    /*
     * if user has blank password
     */
    public function getBlankPasswordAttribute()
    {

        return $this->password == '' || $this->password == null;
    }

    /*
     * Role attribute used in auth
     */
    public function getRoleAttribute()
    {

        return 'admin';
    }

    /**
     * @return mixed
     */
    public function getPlacesCountAttribute()
    {
        return $this->hasMany(Place::class)->count();
    }

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialProviders()
    {
        return $this->hasMany(UserSocialProvider::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function created_by()
    {
        return $this->hasMany(Place::class, 'created_by_id');
    }

}
