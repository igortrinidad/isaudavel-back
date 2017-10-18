<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use App\Models\Traits\Sanitize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class EventComment extends Model
{
    use GenerateUuid, Sanitize;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_comments';

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
        'event_id',
        'created_by_id',
        'created_by_type',
        'content',
        'created_at',
    ];

    /**
     * Sanitize this columns.
     *
     * @var array
     */
    protected $sanitize_columns = ['content'];


    protected static function boot()
    {
        static::bootTraits();
    }

    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected static function bootTraits()
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {

            if (method_exists($class, $method = 'boot' . class_basename($trait))) {

                forward_static_call([$class, $method]);
            }
        }
    }


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
    public function from()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id')->select('id', 'avatar', 'full_name', 'name', 'last_name', 'path');
    }


}
