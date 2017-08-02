<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_professional', function (Blueprint $table) {
            $table->uuid('company_id');
            $table->uuid('professional_id');
            $table->boolean('is_admin')->default(false);
            $table->index(['company_id', 'professional_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_professional');
    }
}
