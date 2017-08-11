<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubject;

class Client extends Authenticatable implements JWTSubject
{
    use Notifiable, Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

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
        'name', 'last_name', 'email', 'password', 'bday', 'phone', 'target','current_xp', 'total_xp', 'level', 'remember_token'
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
    protected $appends = ['full_name', 'blank_password', 'role', 'avatar'];

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
        return 'client';
    }


    /*
    * Avatar
    */
    public function getAvatarAttribute()
    {
        $photo = ClientPhoto::where('client_id', $this->id)->where('is_profile', true)->first();

        return $photo ? $photo->fresh()->photo_url : null;
    }

    /*
     * Format to display
     */
    public function getBdayAttribute($bday)
    {
        return Carbon::parse($bday)->format('d/m/Y');
    }

    /*
     * Change bday to set on db
     */
    public function setBdayAttribute($value)
    {
        if(!isset($value)){
            $value = '00/00/0000';
        }

        $this->attributes['bday'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
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
        return $this->hasMany(ClientSocialProvider::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(ClientPhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'client_company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function observations()
    {
        return $this->hasMany(ClientCompanyObservation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainnings()
    {
        return $this->hasMany(Trainning::class);
    }


    /** Overide some attributes on update
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if(array_key_exists('bday', $attributes)){
            $attributes['bday'] = Carbon::createFromFormat('d/m/Y', $attributes['bday'])->toDateString();
        }
        return parent::update($attributes, $options);
    }
}
