<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Company extends Model
{

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
        'id',
        'owner_id',
        'is_active',
        'name',
        'slug',
        'website',
        'phone',
        'description',
        'address_is_available',
        'is_delivery',
        'address',
        'lat',
        'lng',
        'city',
        'state',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'address_is_available' => 'boolean',
        'is_delivery' => 'boolean',
        'address' => 'json',
        'lat' => 'float',
        'lng' => 'float',
    ];


    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['avatar', 'current_rating', 'total_rating'];

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

        if(!$photo){
            $photo = CompanyPhoto::where('company_id', $this->id)->first();
        }

        return $photo ? $photo->fresh()->photo_url : 'https://s3.amazonaws.com/isaudavel-assets/img/isaudavel_holder550.png';
    }

    /*
    * Website
    */
    public function getWebsiteAttribute()
    {
        return 'http://' . $this->attributes['website'];
    }

    /*
    * Rating
    */
    public function getCurrentRatingAttribute()
    {
        $rating = CompanyRating::where('company_id', $this->id)->get()->avg('rating');

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down

        return round($rating,1);
    }

    /*
    * Rating
    */
    public function getTotalRatingAttribute()
    {
        $ratings = CompanyRating::where('company_id', $this->id)->get()->count();

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down

        return $ratings;
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
    public function plans()
    {
        return $this->hasMany(Plan::class);
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
        return $this->belongsToMany(Professional::class, 'company_professional')->withPivot(['is_admin', 'is_public']);
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function public_confirmed_professionals()
    {
        return $this->belongsToMany(Professional::class, 'company_professional')
            ->wherePivot('is_public', 1)
            ->wherePivot('is_confirmed', 1)
            ->withPivot(['is_admin', 'is_public']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ClientSubscription::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendar_settings()
    {
        return $this->hasOne(CompanyCalendarSettings::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(CompanyRating::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function last_ratings()
    {
        return $this->hasMany(CompanyRating::class)
            ->orderBy('created_at', 'DESC')
            ->with(['from' => function ($query) {
                $query->select('id', 'name', 'last_name');
            }])->limit(5);
    }

}
