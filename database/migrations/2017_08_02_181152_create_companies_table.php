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
            $table->string('slug');
            $table->string('website')->nullable();
            $table->string('phone');
            $table->text('description');
            $table->boolean('address_is_available')->default(0);
            $table->boolean('is_delivery')->default(0);
            $table->json('address');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->string('city');
            $table->string('state');
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
