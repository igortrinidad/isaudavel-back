<?php

namespace App\Models\Traits;

use Mews\Purifier\Facades\Purifier;

trait Sanitize
{
    public static function bootSanitize()
    {
        //Sanitize defined columns on create
        static::creating(function ($model) {
            if (!empty($model->sanitize_columns)) {
                foreach ($model->sanitize_columns as $column) {
                    $model->{$column} = Purifier::clean($model->{$column});
                }
            }
        });

        //Sanitize defined columns on update
        static::updating(function ($model) {
            if (!empty($model->sanitize_columns)) {
                foreach ($model->sanitize_columns as $column) {
                    $model->{$column} = Purifier::clean($model->{$column});
                }
            }
        });
    }

}