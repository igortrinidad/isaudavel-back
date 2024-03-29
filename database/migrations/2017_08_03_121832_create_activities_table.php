<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('client_id')->index();
            $table->boolean('is_confirmed')->default(false);
            $table->integer('xp_earned');
            $table->uuid('confirmed_by_id')->nullable()->index();
            $table->string('confirmed_by_type')->nullable();
            $table->dateTime('confirmed_at')->nullable();
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
        Schema::dropIfExists('activities');
    }
}
