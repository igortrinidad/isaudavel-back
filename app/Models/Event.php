<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Event extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

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
        'name',
        'created_by_type',
        'created_by_id',
        'company_id',
        'is_free',
        'value',
        'date',
        'time',
        'description',
        'lat',
        'lng',
        'city',
        'state',
        'address',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'address_is_available' => 'boolean',
        'address' => 'json',
        'lat' => 'float',
        'lng' => 'float',
    ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['avatar', 'total_comments', 'total_participants'];


    /*
    * Avatar
    */
    public function getAvatarAttribute()
    {
        $photo = EventPhoto::where('event_id', $this->id)->where('is_profile', true)->first();

        if (!$photo) {
            $photo = EventPhoto::where('event_id', $this->id)->first();
        }

        return $photo ? $photo->fresh()->photo_url : 'https://s3.amazonaws.com/isaudavel-assets/img/isaudavel_holder550.png';
    }

    /*
    * Rating
    */
    public function getTotalCommentsAttribute()
    {
        $count = EventComment::where('event_id', $this->id)->get()->count();

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down

        return $count;
    }

    /*
    * Rating
    */
    public function getTotalParticipantsAttribute()
    {
        $count = EventParticipant::where('event_id', $this->id)->get()->count();

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down

        return $count;
    }

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function from()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_event');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(EventPhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(EventComment::class);
    }

}
