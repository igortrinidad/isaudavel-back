<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTableDiets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diets', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('client_id')->index();
            $table->uuid('created_by_id')->index();
            $table->string('created_by_type')->index();
            $table->json('meals');
            $table->decimal('daily_total_kcal', 20,2)->default(0);
            $table->decimal('daily_total_protein', 20,2)->default(0);
            $table->decimal('daily_total_carb', 20,2)->default(0);
            $table->decimal('daily_total_fat', 20,2)->default(0);
            $table->decimal('daily_total_fiber', 20,2)->default(0);
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('diets');
    }

}
