<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOracleSocialProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oracle_social_providers', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('user_id')->index();
            $table->string('provider');
            $table->string('provider_id');
            $table->string('access_token');
            $table->string('photo_url')->nullable();
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
        Schema::dropIfExists('oracle_social_providers');
    }
}
