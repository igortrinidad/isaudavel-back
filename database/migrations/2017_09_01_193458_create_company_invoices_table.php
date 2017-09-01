<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_invoices', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->uuid('subscription_id')->index();
            $table->decimal('total', 15,2);
            $table->date('expire_at');
            $table->boolean('is_confirmed')->default(0);
            $table->dateTime('confirmed_at')->nullable();
            $table->boolean('is_canceled')->default(0);
            $table->dateTime('canceled_at')->nullable();
            $table->json('items');
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
        Schema::dropIfExists('company_invoices');
    }
}
