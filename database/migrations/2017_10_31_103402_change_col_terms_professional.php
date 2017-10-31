<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColTermsProfessional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('terms');
            $table->dateTime('terms_accepted_at')->nullable()->after('fcm_token_browser');
            $table->boolean('terms_accepted')->default(0)->after('fcm_token_browser');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('terms_accepted_at');
            $table->dropColumn('terms_accepted');
            $table->json('terms');
        });
    }
}
