<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class ExamAttachment extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exam_attachments';

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
        'exam_id', 'path', 'filename', 'extension'
    ];


    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['attachment_url'];


    /**
     * -------------------------------
     * Custom fields
     * -------------------------------
     */

    /**
     * @return string
     */
    public function getAttachmentUrlAttribute()
    {
        if ($this->attributes['path']) {
            return $this->getFileUrl($this->attributes['path']);
        }

    }

    /**
     * @param $key
     * @return string
     */
    private function getFileUrl($key)
    {
        return (string)Storage::disk('media')->url($key);
    }

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

}
