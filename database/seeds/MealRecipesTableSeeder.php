<?php

use App\Models\MealType;
use Illuminate\Database\Seeder;

class MealRecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create('pt_BR');

        /*
         * Meal types
         */
        MealType::create(['name' => 'Café da manhã', 'slug' => 'cafe-da-manha']);
        MealType::create(['name' => 'Almoço', 'slug' => 'almoco']);
        MealType::create(['name' => 'Jantar', 'slug' => 'jantar']);
        MealType::create(['name' => 'Ceia', 'slug' => 'ceia']);
        MealType::create(['name' => 'Lanche', 'slug' => 'lanche']);

        /*
         * Meal Recipes
         */

        factory(App\Models\MealRecipe::class, 10)->create();

        factory(App\Models\MealRecipeTag::class, 10)->create();

        $meal_recipes = \App\Models\MealRecipe::all();

        $tags = \App\Models\MealRecipeTag::all()->pluck('id')->flatten()->toArray();

        foreach ($meal_recipes as $meal_recipe)
        {
            //Photo
            \App\Models\MealRecipePhoto::create([
                'meal_recipe_id' => $meal_recipe->id,
                'path' => 'meal_recipes/photo/f2758e3d1b7db2ae08dfb3c372b3f330.png',
                'is_cover' => true
            ]);

            //types
            $types = \App\Models\MealType::all()->pluck('id')->flatten()->toArray();

            $meal_recipe->types()->attach($faker->randomElements($types,  rand(1, 3)));

            //Tags
            $meal_recipe->tags()->attach($faker->randomElements($tags,  rand(2, 5)));

            //comments
            foreach(range(1, rand(3,5)) as $comment)
            {
                $created_by_id = null;
                $created_by_type = null;

                $creator_type = $faker->randomElement(['professional', 'client']);

                if($creator_type == 'professional'){

                    $professional = \App\Models\Professional::inRandomOrder()->first();
                    $created_by_id = $professional->id;
                    $created_by_type = \App\Models\Professional::class;
                }else{
                    $client = \App\Models\Client::inRandomOrder()->first();
                    $created_by_id = $client->id;
                    $created_by_type = \App\Models\Client::class;
                }

                \App\Models\MealRecipeComment::create([
                    'meal_recipe_id' => $meal_recipe->id,
                    'content' => $faker->sentence(10),
                    'created_by_id' => $created_by_id,
                    'created_by_type' => $created_by_type
                ]);
            }

            //ratings
            foreach(range(1, rand(3,5)) as $rating)
            {
                $created_by_id = null;
                $created_by_type = null;

                $creator_type = $faker->randomElement(['professional', 'client']);

                if($creator_type == 'professional'){

                    $professional = \App\Models\Professional::inRandomOrder()->first();
                    $created_by_id = $professional->id;
                    $created_by_type = \App\Models\Professional::class;
                }else{
                    $client = \App\Models\Client::inRandomOrder()->first();
                    $created_by_id = $client->id;
                    $created_by_type = \App\Models\Client::class;
                }

                \App\Models\MealRecipeRating::create([
                    'meal_recipe_id' => $meal_recipe->id,
                    'rating' => rand(2,5),
                    'created_by_id' => $created_by_id,
                    'created_by_type' => $created_by_type
                ]);
            }


        }


    }
}
