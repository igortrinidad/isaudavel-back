<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Certification extends Model
{
    use Uudis;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'certifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'professional_id',
        'name',
        'institution',
        'date',
        'priority',
        'path',
        'filename',
        'extension',
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
    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }

}
