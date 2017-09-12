<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class SubscriptionHistory extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_subscription_histories';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $dateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'subscription_id',
        'action',
        'description',
        'professionals_old_value',
        'professionals_new_value',
        'categories_old_value',
        'categories_new_value',
        'total_old_value',
        'total_new_value',
        'user_id',
        'user_type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'total_old_value' => 'double',
        'total_new_value' => 'double',
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
    public function subscription()
    {
        return $this->belongsTo(CompanySubscription::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->morphTo(null, 'user_type', 'user_id');
    }

    /*
     * Format to display
     */
    public function getCreatedAtAttribute($created_at)
    {
        return Carbon::parse($created_at)->format('d/m/Y H:i:s');
    }


}
