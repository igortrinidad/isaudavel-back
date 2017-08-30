<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTableActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
   {
        Schema::dropIfExists('activities');

        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('client_id')->index();
            $table->integer('xp_earned')->index();
            $table->text('content');
            $table->boolean('is_public');
            $table->uuid('created_by_id')->index();
            $table->string('created_by_type')->index();
            $table->uuid('about_id')->index();
            $table->string('about_type')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
