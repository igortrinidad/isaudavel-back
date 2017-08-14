<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restriction extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restrictions';

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
        'created_by_id',
        'created_by_type',
        'type',
        'restriction',
        'observation'
    ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['type_label'];


    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return mixed
     */
    public function getTypeLabelAttribute()
    {
        if ($this->type == 'medication') {
            return 'Mediacamento';
        }

        if ($this->type == 'food') {
            return 'Comida';
        }

        if ($this->type == 'exercise') {
            return 'Exercício';
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
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function from()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

}
