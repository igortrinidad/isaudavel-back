<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use App\Models\Traits\Sanitize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class ProfessionalRating extends Model
{
    use GenerateUuid, Sanitize;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'professional_ratings';

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
        'client_id',
        'professional_id',
        'rating',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function professional()
    {
        return $this->belongsTo(Professional::class)->select('id', 'name', 'last_name');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
