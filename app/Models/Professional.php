<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;


class Professional extends Authenticatable implements JWTSubject
{
    use Notifiable, Uuids, SoftDeletes, Sluggable;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'professionals';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'slug',
        'phone',
        'whatsapp',
        'password',
        'remember_token',
        'is_active',
        'is_paid',
        'email_confirmed',
        'is_delivery',
        'address_is_available',
        'address',
        'lat',
        'lng',
        'city',
        'state',
        'fcm_token_mobile',
        'fcm_token_browser',
        'terms_accepted',
        'terms_accepted_at'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name', 'last_name'],
                'separator' => '-'
            ]
        ];
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'terms_accepted' => 'boolean',
        'is_active' => 'boolean',
        'email_confirmed' => 'boolean',
        'address_is_available' => 'boolean',
        'is_delivery' => 'boolean',
        'address' => 'json',
        'lat' => 'float',
        'lng' => 'float',
        'is_paid' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['full_name', 'blank_password', 'role', 'avatar', 'current_rating', 'total_rating', 'plan'];



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
        return 'professional';
    }

    /*
    * Avatar
    */
    public function getAvatarAttribute()
    {
        $photo = ProfessionalPhoto::where('professional_id', $this->id)->where('is_profile', true)->first();

        if(!$photo){
            $photo = ProfessionalPhoto::where('professional_id', $this->id)->first();
        }

        return $photo ? $photo->fresh()->photo_url : 'https://s3.amazonaws.com/isaudavel-assets/img/isaudavel_holder550.png';
    }

    /*
   * Avatar
   */
    public function getCurrentRatingAttribute()
    {
        $rating = ProfessionalRating::where('professional_id', $this->id)->get()->avg('rating');

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down
        return round($rating,1);
    }

        /*
    * Rating
    */
    public function getTotalRatingAttribute()
    {
        $ratings = ProfessionalRating::where('professional_id', $this->id)->get()->count();

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down

        return $ratings;
    }

    /**
     * Plan attribute
     * @return string
     */
    public function getPlanAttribute()
    {
        if($this->is_paid){
            return 'paid';
        }

        if(!$this->is_paid){
            return 'free';
        }
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
        return $this->hasMany(ProfessionalSocialProvider::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(ProfessionalPhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certifications()
    {
        return $this->hasMany(Certification::class)->orderBy('priority', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_professional')->select('id', 'name', 'slug');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(ProfessionalRating::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_professional')
            ->select('id', 'name', 'slug', 'city', 'state', 'owner_id')
            ->with(['categories' => function ($query) {
                $query->select('id', 'name', 'slug');
            }])->withPivot('is_admin', 'is_confirmed', 'is_public', 'requested_by_professional');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function last_ratings()
    {
        return $this->hasMany(ProfessionalRating::class)
            ->orderBy('created_at', 'DESC')
            ->with(['client' => function ($query) {
                $query->select('id', 'name', 'last_name');
            }])->limit(5);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function recomendations_sent()
    {
        return $this->morphMany(Recomendation::class, 'from');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function recomendations_received()
    {
        return $this->morphMany(Recomendation::class, 'to');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function last_recomendations()
    {
        return $this->morphMany(Recomendation::class, 'to')
            ->orderBy('created_at', 'DESC')
            ->with(['from' => function ($query) {
                $query->select('id', 'name', 'last_name');
            }])->limit(5);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne(ProfessionalSubscription::class)->select('id', 'professional_id','clients', 'is_active');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_professional');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(ProfessionalNotification::class);
    }

}
