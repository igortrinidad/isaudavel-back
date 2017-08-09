<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCalendarSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_calendar_settings', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('company_id')->index();
            $table->boolean('calendar_is_public');
            $table->boolean('calendar_is_active');
            $table->boolean('workday_is_active');
            $table->integer('advance_schedule');
            $table->integer('advance_reschedule');
            $table->integer('points_to_earn_bonus');
            $table->json('available_dates_range');
            $table->json('available_days_config');
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
        Schema::dropIfExists('company_calendar_settings');
    }
}
