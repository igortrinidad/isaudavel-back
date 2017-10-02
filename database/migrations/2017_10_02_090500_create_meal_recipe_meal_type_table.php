<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealRecipeMealTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_recipe_meal_type', function (Blueprint $table) {
            $table->uuid('meal_recipe_id');
            $table->uuid('meal_type_id');
            $table->index(['meal_recipe_id', 'meal_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_recipe_meal_type');
    }
}
