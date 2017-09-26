<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class MealRecipe extends Model
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meal_recipes';

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
        'id',
        'type_id',
        'title',
        'prep_time',
        'portions',
        'difficulty',
        'prep_description',
        'ingredients',
        'kcal',
        'protein',
        'carbohydrate',
        'lipids',
        'fiber',
        'video_url',
        'is_published',
        'created_by_id',
        'created_by_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['ingredients' => 'json', 'is_published' => 'boolean'];

    /**
     * The accessors to append to the model's array.
     *
     * @var array
     */
    protected $appends = ['avatar', 'total_comments', 'current_rating', 'total_rating'];

    /*
   * Avatar
   */
    public function getAvatarAttribute()
    {
        $photo = MealRecipePhoto::where('meal_recipe_id', $this->id)->where('is_cover', true)->first();

        if (!$photo) {
            $photo = MealRecipePhoto::where('meal_recipe_id', $this->id)->first();
        }

        return $photo ? $photo->fresh()->photo_url : 'https://s3.amazonaws.com/isaudavel-assets/img/isaudavel_holder550.png';
    }

    /*
    * Comments count
    */
    public function getTotalCommentsAttribute()
    {
        return MealRecipeComment::where('meal_recipe_id', $this->id)->get()->count();
    }

    /*
    * Rating
    */
    public function getCurrentRatingAttribute()
    {
        $rating = MealRecipeRating::where('meal_recipe_id', $this->id)->get()->avg('rating');

        return round($rating, 1);
    }

    /*
    * TOTAL Rating
    */
    public function getTotalRatingAttribute()
    {
        $ratings = MealRecipeRating::where('meal_recipe_id', $this->id)->get()->count();

        // Round up or down Eg: ratings >= x.5 are rounded up and < x.5 are rounded down

        return $ratings;
    }



    /**
     * -------------------------------
     * Relationships
     * -------------------------------
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(MealType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function from()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public  function photos()
    {
        return $this->hasMany(MealRecipePhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public  function tags()
    {
        return $this->belongsToMany(MealRecipeTag::class, 'meal_recipe_tag');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public  function comments()
    {
        return $this->hasMany(MealRecipeComment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public  function ratings()
    {
        return $this->hasMany(MealRecipeRating::class);
    }
}
