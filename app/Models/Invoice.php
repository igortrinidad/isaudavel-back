<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class Invoice extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

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
        'company_id',
        'subscription_id',
        'value',
        'expire_at',
        'is_confirmed',
        'confirmed_at',
        'is_canceled',
        'canceled_at',
        'history',
        'created_at',
        'updated_at'
    ];

    protected $casts = ['history' => 'json', 'is_canceled' => 'boolean', 'is_confirmed' => 'boolean'];

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(ClientSubscription::class, 'id', 'subscription_id');
    }

    /*
     * Format to display
     */
    public function getConfirmedAtAttribute($expire)
    {
        return Carbon::parse($expire)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setConfirmedAtAttribute($value)
    {
        if(!isset($value)){
            $value = '00/00/0000';
        }

        $this->attributes['confirmed_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }

    /*
     * Format to display
     */
    public function getCanceledAtAttribute($expire)
    {
        return Carbon::parse($expire)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setCanceledAtAttribute($value)
    {
        if(!isset($value)){
            $value = '00/00/0000';
        }

        $this->attributes['canceled_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }


}
