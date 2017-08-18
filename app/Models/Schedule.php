<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schedules';

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
        'company_id',
        'professional_id',
        'invoice_id',
        'subscription_id',
        'date',
        'time',
        'points_earned',
        'confirmed_at',
        'confirmed_by',
        'reschedule_by',
        'reschedule_at',
    ];


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
    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(ClientSubscription::class);
    }


    /*
        * Format to display
        */
    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setDateAttribute($value)
    {
        if (!isset($value)) {
            $value = '00/00/0000';
        }

        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }

}
