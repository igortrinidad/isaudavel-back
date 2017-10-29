<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class SingleSchedule extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'single_schedules';

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
        'client_id',
        'category_id',
        'professional_id',
        'date',
        'time',
        'observation',
        'is_trial',
        'is_confirmed',
        'confirmed_by',
        'confirmed_at',
        'is_rescheduled',
        'reschedule_by',
        'reschedule_at',
        'is_canceled',
        'canceled_by',
        'canceled_at',
        'reschedule_confirmed_by',
        'reschedule_confirmed_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_trial' => 'boolean',
        'is_confirmed' => 'boolean',
        'is_rescheduled' => 'boolean',
        'is_canceled' => 'boolean',
    ];


    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['is_single'];

    /*
     * Role attribute used in auth
     */
    public function getIsSingleAttribute()
    {
        return true;
    }

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
    public function client()
    {
        return $this->belongsTo(Client::class);
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
    public function category()
    {
        return $this->belongsTo(Category::class);
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
