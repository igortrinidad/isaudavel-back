<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use App\Models\Traits\Sanitize;
use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Recomendation extends Model
{
    use GenerateUuid, Sanitize;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recomendations';

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
        'from_id',
        'from_type',
        'to_id',
        'to_type',
        'content'
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function from()
    {
        return $this->morphTo(null, 'from_type', 'from_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function to()
    {
        return $this->morphTo(null, 'to_type', 'to_id');
    }

}
