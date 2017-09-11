<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


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
        'history'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'double',
        'is_confirmed' => 'boolean',
        'is_canceled' => 'boolean',
        'history' => 'json'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['schedules'];

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
    public function subscription()
    {
        return $this->belongsTo(ClientSubscription::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('date', 'asc')->orderBy('time');
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

        $this->attributes['expire_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }


}
