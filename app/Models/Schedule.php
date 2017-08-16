<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


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
        'id',
        'company_id',
        'professional_id',
        'invoice_id',
        'date',
        'time',
        'confirmed_by',
        'confirmed_at',
        'reschedule_by',
        'reschedule_at',
        'created_at',
        'updated_at'
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
        return $this->belongsTo(Invoice::class, 'subscription_id');
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
    public function getRescheduleAtAttribute($expire)
    {
        return Carbon::parse($expire)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setRescheduleAtAttribute($value)
    {
        if(!isset($value)){
            $value = '00/00/0000';
        }

        $this->attributes['reschedule_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }

    /*
     * Format to display
     */
    public function getDateAttribute($start)
    {
        return Carbon::parse($start)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setDateAttribute($value)
    {
        if(!isset($value)){
            $value = '00/00/0000';
        }

        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
    }


}
