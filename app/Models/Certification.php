<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Certification extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'certifications';

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
        'professional_id',
        'name',
        'institution',
        'description',
        'granted_at',
        'priority',
        'path',
        'filename',
        'extension',
        'created_at',
        'updated_at'
        ];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['photo_url'];
        
    /**
     * @return string
     */
    public function getPhotoUrlAttribute()
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

    /*
    * Format to display
    */
    public function getGrantedAtAttribute($expire)
    {
        return Carbon::parse($expire)->format('d/m/Y');
    }

    /*
     * Change the Date attribute
     */
    public function setGrantedAtAttribute($value)
    {
        if (!isset($value)) {
            $value = '00/00/0000';
        }

        $this->attributes['granted_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();;
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
        return $this->belongsTo(Professional::class);
    }

}
