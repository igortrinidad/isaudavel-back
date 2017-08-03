<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('owner_id')->index();
            $table->boolean('is_active');
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('phone');
            $table->boolean('address_is_available');
            $table->json('address');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->string('city');
            $table->string('state');
            $table->decimal('price', 15, 2);
            $table->float('rating');
            $table->json('informations');
            $table->integer('advance_schedule');
            $table->integer('advance_reschedule');
            $table->integer('points_to_earn_bonus');
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
        Schema::dropIfExists('companies');
    }
}
