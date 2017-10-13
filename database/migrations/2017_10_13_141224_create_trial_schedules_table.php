<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrialSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trial_schedules', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->uuid('client_id')->index();
            $table->uuid('category_id')->index();
            $table->uuid('professional_id')->nullable();
            $table->date('date');
            $table->time('time');
            $table->text('observation')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->string('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->boolean('is_rescheduled')->default(false);
            $table->string('reschedule_by')->nullable();
            $table->dateTime('reschedule_at')->nullable();
            $table->boolean('is_canceled')->default(false);
            $table->string('canceled_by')->nullable();
            $table->dateTime('canceled_at')->nullable();
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
        Schema::dropIfExists('trial_schedules');
    }
}
