<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->uuid('subscription_id')->index();
            $table->decimal('value', 15,2);
            $table->date('expire_at');
            $table->boolean('is_confirmed')->default(0);
            $table->dateTime('confirmed_at')->nullable();
            $table->boolean('is_canceled')->default(0);
            $table->dateTime('canceled_at')->nullable();
            $table->json('history');
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
        Schema::dropIfExists('invoices');
    }
}
