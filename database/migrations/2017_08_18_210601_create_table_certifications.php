<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCertifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('professional_id')->index();
            $table->string('name');
            $table->string('institution');
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('priority')->default(0);
            $table->string('path')->nullable();
            $table->string('filename')->nullable();
            $table->string('extension')->nullable();
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
        Schema::dropIfExists('certifications');
    }
}
