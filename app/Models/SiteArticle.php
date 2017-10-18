<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use App\Models\Traits\Sanitize;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class SiteArticle extends Model
{
    use GenerateUuid, Sanitize;

    /**
     * Sanitize this columns.
     *
     * @var array
     */
    protected $sanitize_columns = ['content'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_articles';


    protected $dates = ['created_at', 'updated_at'];

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
    protected $appends = ['avatar', 'short_desc'];
        
    /**
     * @return string
     */
    public function getAvatarAttribute()
    {
        if ($this->attributes['path']) {
            return $this->getFileUrl($this->attributes['path']);
        }

    }

    /**
     * @return string
     */
    public function getShortDescAttribute()
    {
        if ($this->attributes['content']) {
            return substr($this->attributes['content'],0,280) . '...';
        }

    }


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
