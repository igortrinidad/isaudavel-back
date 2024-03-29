<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_company', function (Blueprint $table) {
            $table->uuid('company_id');
            $table->uuid('client_id');
            $table->boolean('requested_by_client')->default(0);
            $table->boolean('is_confirmed')->default(0);
            $table->boolean('trainnings_show')->default(0);
            $table->boolean('trainnings_edit')->default(0);
            $table->boolean('diets_show')->default(0);
            $table->boolean('diets_edit')->default(0);
            $table->boolean('evaluations_show')->default(0);
            $table->boolean('evaluations_edit')->default(0);
            $table->boolean('restrictions_show')->default(0);
            $table->boolean('restrictions_edit')->default(0);
            $table->boolean('exams_show')->default(0);
            $table->boolean('exams_edit')->default(0);
            $table->uuid('confirmed_by_id')->nullable();
            $table->string('confirmed_by_type')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->uuid('deleted_by_id')->nullable();
            $table->string('deleted_by_type')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index(['company_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_company');
    }
}
