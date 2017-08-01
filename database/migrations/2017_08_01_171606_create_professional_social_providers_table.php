<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalSocialProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_social_providers', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('professional_id')->index();
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
        Schema::dropIfExists('professional_social_providers');
    }
}
