<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTableEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('name');
            $table->string('created_by_type')->index();
            $table->uuid('created_by_id')->index();
            $table->uuid('company_id')->nullable();
            $table->boolean('is_free')->default(1);
            $table->decimal('value', 15,2)->default(0);
            $table->date('date');
            $table->time('time');
            $table->text('description');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->string('city');
            $table->string('state');
            $table->json('address');
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
        Schema::dropIfExists('events');
    }
}
