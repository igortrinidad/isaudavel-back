<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class CompanyCalendarSettings extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_calendar_settings';

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
        'calendar_is_public',
        'calendar_is_active',
        'workday_is_active',
        'advance_schedule',
        'advance_reschedule',
        'points_to_earn_bonus',
        'available_dates_range',
        'available_days_config'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'calendar_is_public' => 'boolean',
        'calendar_is_active' => 'boolean',
        'workday_is_active' =>  'boolean',
        'available_dates_range' => 'json',
        'available_days_config' => 'json'
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

}
