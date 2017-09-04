<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('schedules', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->after('points_earned');
            $table->boolean('is_rescheduled')->default(false)->after('confirmed_at');
            $table->boolean('is_canceled')->default(false)->after('reschedule_at');
            $table->string('canceled_by')->nullable()->after('is_canceled');
            $table->dateTime('canceled_at')->nullable()->after('canceled_by');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('is_confirmed');
            $table->dropColumn('is_rescheduled');
            $table->dropColumn('is_canceled');
            $table->dropColumn('canceled_by');
            $table->dropColumn('canceled_at');
        });
    }
}
