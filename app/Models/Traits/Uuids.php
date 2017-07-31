<?php
namespace App\Models\Traits;


use Webpatser\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });

        //Prevent attempts to change the UUID
        static::saving(function ($model) {

            $original_id = $model->getOriginal('id');

            if ($original_id !== $model->id) {
                $model->id = $original_id;
            }
        });
    }
}