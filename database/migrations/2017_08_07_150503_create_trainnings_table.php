<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainnings', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('client_id')->index();
            $table->uuid('created_by_id');
            $table->string('created_by_type');
            $table->integer('dow');
            $table->json('series');
            $table->text('observation')->nullable();
            $table->string('heart_rate')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('trainnings');
    }
}
