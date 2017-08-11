<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('client_id')->index();
            $table->uuid('created_by_id')->index();
            $table->string('created_by_type');
            $table->string('type');
            $table->text('observation');
            $table->string('path')->nullable();
            $table->string('filename')->nullable();
            $table->string('extension')->nullable();
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
        Schema::dropIfExists('exams');
    }
}
