<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrColsConfirmRescedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_calendar_settings', function (Blueprint $table) {
            $table->boolean('should_manual_confirm')->default(0)->after('cancel_schedule');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('reschedule_confirmed_by')->nullable()->after('canceled_at');
            $table->dateTime('reschedule_confirmed_at')->nullable()->after('canceled_at');
        });

        Schema::table('single_schedules', function (Blueprint $table) {
            $table->string('reschedule_confirmed_by')->nullable()->after('canceled_at');
            $table->dateTime('reschedule_confirmed_at')->nullable()->after('canceled_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_calendar_settings', function (Blueprint $table) {
            $table->dropColumn('should_manual_confirm');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('reschedule_confirmed_by');
            $table->dropColumn('reschedule_confirmed_at');
        });

        Schema::table('single_schedules', function (Blueprint $table) {
            $table->dropColumn('reschedule_confirmed_by');
            $table->dropColumn('reschedule_confirmed_at');
        });
    }
}
