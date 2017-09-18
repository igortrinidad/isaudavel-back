<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCategoryCalendarSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_calendar_settings', function (Blueprint $table){
            $table->boolean('calendar_is_public')->default(true)->after('is_professional_scheduled');
            $table->boolean('calendar_is_active')->default(true)->after('calendar_is_public');
            $table->integer('advance_schedule')->default(0)->after('calendar_is_active');
            $table->integer('advance_reschedule')->default(0)->after('advance_schedule');
            $table->integer('cancel_schedule')->default(0)->after('advance_reschedule');
            $table->integer('points_to_earn_bonus')->default(0)->after('advance_reschedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
