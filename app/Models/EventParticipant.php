<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class EventParticipant extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_participant';

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
        'event_id',
        'participant_id',
        'participant_type',
        'created_at',
    ];


    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function participant()
    {
        return $this->morphTo(null, 'participant_type', 'participant_id')->select('id', 'avatar', 'full_name', 'name', 'last_name', 'path');
    }


}
