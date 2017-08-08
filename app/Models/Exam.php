<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Exam extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams';

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
        'from_id',
        'from_type',
        'type',
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
        if ($this->type == 'image') {
            return 'Imagem';
        }

        if ($this->type == 'blood') {
            return 'Sangue';
        }

        if ($this->type == 'physical') {
            return 'Físico';
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
        return $this->morphTo(null, 'from_type', 'from_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(ExamAttachment::class);
    }
}
