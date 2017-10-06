<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ProfessionalSubscription extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'professional_subscriptions';

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
        'processional_id',
        'clients',
        'total',
        'start_at',
        'expire_at',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_active' => 'boolean', 'total' => 'double' ];

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    /*
     * Format to display
     */
    public function getExpireAtAttribute($expire)
    {
        return Carbon::parse($expire)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setExpireAtAttribute($value)
    {
        if (!isset($value)) {
            $value = '00/00/0000';
        }

        $this->attributes['expire_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }

    /*
     * Format to display
     */
    public function getStartAtAttribute($start)
    {
        return Carbon::parse($start)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setStartAtAttribute($value)
    {
        if (!isset($value)) {
            $value = '00/00/0000';
        }

        $this->attributes['start_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }

}

