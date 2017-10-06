<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('professional_id')->index();
            $table->integer('clients');
            $table->decimal('total', 15, 2);
            $table->date('start_at');
            $table->date('expire_at');
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('professional_subscriptions');
    }
}
