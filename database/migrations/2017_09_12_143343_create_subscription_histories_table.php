<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_subscription_histories', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->uuid('subscription_id')->index();
            $table->string('action');
            $table->string('description');
            $table->integer('professionals_old_value');
            $table->integer('professionals_new_value');
            $table->integer('categories_old_value');
            $table->integer('categories_new_value');
            $table->decimal('total_old_value',15,2);
            $table->decimal('total_new_value',15,2);
            $table->uuid('user_id');
            $table->string('user_type');
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
        Schema::dropIfExists('company_subscription_histories');
    }
}
