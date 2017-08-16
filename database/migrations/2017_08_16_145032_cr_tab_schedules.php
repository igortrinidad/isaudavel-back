<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTabSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->uuid('professional_id')->nullable();
            $table->uuid('invoice_id')->index();
            $table->date('date')->index();
            $table->string('time');
            $table->string('confirmed_by');
            $table->datetime('confirmed_at');
            $table->string('reschedule_by');
            $table->datetime('reschedule_at');
            $table->integer('points_earned')->default(0);
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
        Schema::dropIfExists('schedules');
    }
}
