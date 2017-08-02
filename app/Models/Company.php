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
        'city',
        'state',
        'price',
        'is_pilates',
        'is_personal',
        'is_physio',
        'is_nutrition',
        'is_massage',
        'is_healthy',
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
        'address' => 'json',
        'address_is_available' => 'boolean',
        'price' => 'float',
        'is_pilates'  => 'boolean',
        'is_personal'  => 'boolean',
        'is_physio'  => 'boolean',
        'is_nutrition'  => 'boolean',
        'is_massage'  => 'boolean',
        'is_healthy' => 'boolean',
        'rating' => 'float',
        'informations' => 'json',
        'advance_schedule' => 'integer',
        'advance_reschedule'  => 'integer',
        'points_to_earn_bonus'  => 'integer'
    ];

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



}
