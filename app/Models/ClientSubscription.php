<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class ClientSubscription extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_subscriptions';

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
        'client_id',
        'plan_id',
        'quantity',
        'value',
        'start_at',
        'expire_at',
        'auto_renew',
        'is_active',
        'workdays'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['workdays' => 'json', 'is_active' => 'boolean', 'auto_renew' => 'boolean'  ];


    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

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
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'subscription_id', 'id');
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