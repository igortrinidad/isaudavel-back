<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class MealRecipe extends Model
{
    use Uuids;

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
        'created_by_id',
        'created_by_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['ingredients' => 'json'];


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
    public function created_by()
    {
        $this->morphTo(null, 'created_by_type', 'created_by_id');
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
