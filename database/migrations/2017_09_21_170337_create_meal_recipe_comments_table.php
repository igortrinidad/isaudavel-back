<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealRecipeCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_recipe_comments', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('meal_recipe_id')->index();
            $table->text('content');
            $table->uuid('created_by_id');
            $table->string('created_by_type');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_recipe_comments');
    }
}
