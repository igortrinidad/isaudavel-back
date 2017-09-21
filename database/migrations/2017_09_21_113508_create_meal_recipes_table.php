<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_recipes', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('type_id')->index();
            $table->string('title');
            $table->integer('prep_time');
            $table->integer('portions');
            $table->integer('difficulty');
            $table->text('prep_description');
            $table->json('ingredients');
            $table->integer('kcal')->default(0);
            $table->integer('protein')->default(0);
            $table->integer('carbohydrate')->default(0);
            $table->integer('lipids')->default(0);
            $table->integer('fiber')->default(0);
            $table->string('video_url')->nullable();
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
        Schema::dropIfExists('meal_recipes');
    }
}
