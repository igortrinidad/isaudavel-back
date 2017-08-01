<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_photos', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('client_id')->index();
            $table->string('path');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_profile')->default(false);
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
        Schema::dropIfExists('client_photos');
    }
}
