<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class SiteArticle extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_articles';

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
        'slug', 'is_published','content', 'title', 'path', 'views', 'shares'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_published' => 'boolean'];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['photo_url'];

    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */


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

}
