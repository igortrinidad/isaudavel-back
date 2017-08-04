<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Company extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

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
        'owner_id',
        'is_active',
        'name',
        'website',
        'phone',
        'address_is_available',
        'address',
        'lat',
        'lng',
        'city',
        'state',
        'price',
        'rating',
        'informations',
        'advance_schedule',
        'advance_reschedule',
        'points_to_earn_bonus',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'address_is_available' => 'boolean',
        'address' => 'json',
        'lat' => 'float',
        'lng' => 'float',
        'price' => 'float',
        'rating' => 'float',
        'informations' => 'json',
        'advance_schedule' => 'integer',
        'advance_reschedule'  => 'integer',
        'points_to_earn_bonus'  => 'integer'
    ];


    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['avatar'];

    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /*
    * Avatar
    */
    public function getAvatarAttribute()
    {
        $photo = CompanyPhoto::where('company_id', $this->id)->where('is_profile', true)->first();

        return $photo->fresh()->photo_url;
    }


    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(CompanyPhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function professionals()
    {
        return $this->belongsToMany(Professional::class, 'company_professional');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendar_settings()
    {
        return $this->hasOne(CompanyCalendarSettings::class);
    }

}
